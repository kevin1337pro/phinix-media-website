"""Integration checks against rendered WordPress or either static export."""
import json
import os
import sys
from html.parser import HTMLParser
from pathlib import Path
from urllib.parse import urlparse
from urllib.request import urlopen
from xml.etree import ElementTree

ROOT = Path(__file__).resolve().parent.parent
ORIGIN = os.environ.get("PHINIX_PREVIEW_ORIGIN", "http://127.0.0.1:9402").rstrip("/")
PREVIEW_URL = os.environ.get(
    "PHINIX_PREVIEW_URL",
    "https://phinix-media-vorschau.kevin1337pro.chatgpt.site",
).rstrip("/")
PUBLIC_URL = os.environ.get("PHINIX_PUBLIC_URL", "https://www.phinix.media").rstrip("/")
PRODUCTION = "--production" in sys.argv or "--static-production" in sys.argv
STATIC = "--static" in sys.argv or PRODUCTION
STATIC_ROOT = ROOT / ("dist-production" if PRODUCTION else "dist")
EXPECT_NOINDEX = not PRODUCTION and (STATIC or os.environ.get("PHINIX_EXPECT_NOINDEX", "1") != "0")
EXPECTED_BASE = PUBLIC_URL if PRODUCTION else (PREVIEW_URL if STATIC else ORIGIN)

PAGES = json.loads((ROOT / "themes/phinix-media/content/seo-pages.json").read_text())
ARTICLES = {
    "digitale-aussenwerbung-gladbeck-phinix-screens": "Article",
    "impressum-pruefen-website-check": "BlogPosting",
}
LEGAL = ["impressum", "datenschutz"]
SLUGS = [""] + LEGAL + list(PAGES) + list(ARTICLES)
PUBLIC_PATHS = ["/" + slug + ("/" if slug else "") for slug in SLUGS]


class Page(HTMLParser):
    def __init__(self):
        super().__init__()
        self.html = {}
        self.headings = []
        self.meta = []
        self.links = []
        self.anchors = []
        self.images = []
        self.sources = []
        self.pictures = []
        self.spans = []
        self.json = []
        self.capture = None
        self.buffer = ""
        self.text = []

    def handle_starttag(self, tag, attrs):
        attributes = dict(attrs)
        if tag == "html":
            self.html = attributes
        if tag == "meta":
            self.meta.append(attributes)
        if tag == "link":
            self.links.append(attributes)
        if tag == "a":
            self.anchors.append(attributes)
        if tag == "img":
            self.images.append(attributes)
        if tag == "source":
            self.sources.append(attributes)
        if tag == "picture":
            self.pictures.append(attributes)
        if tag == "span":
            self.spans.append(attributes)
        if tag == "script" and attributes.get("type") == "application/ld+json":
            self.capture = "json"
            self.buffer = ""
        if tag == "h1":
            self.capture = "h1"
            self.buffer = ""

    def handle_endtag(self, tag):
        if tag == "script" and self.capture == "json":
            self.json.append(json.loads(self.buffer))
            self.capture = None
        if tag == "h1" and self.capture == "h1":
            self.headings.append(self.buffer)
            self.capture = None

    def handle_data(self, text):
        self.text.append(text)
        if self.capture:
            self.buffer += text


def static_file(path):
    if path == "/":
        return STATIC_ROOT / "index.html"
    return STATIC_ROOT / path.strip("/") / "index.html"


def get(path):
    if STATIC:
        return static_file(path).read_text()
    with urlopen(ORIGIN + path, timeout=45) as response:
        assert response.status == 200
        return response.read().decode()


def meta_values(page, attribute, value):
    return [item.get("content", "") for item in page.meta if item.get(attribute, "").lower() == value]


def assert_clean_public_source(path, raw, page):
    assert page.html.get("lang") == "de-DE", (path, page.html)
    assert not meta_values(page, "name", "generator"), path
    if not PRODUCTION:
        return
    lowered = raw.lower()
    for forbidden in (
        PREVIEW_URL.lower(),
        "chatgpt.site",
        ">chatgpt<",
        ">openai<",
        ">codex<",
        "entwurfsseite",
        "wird vor veröffentlichung ergänzt",
        "hier zeigen wir künftig",
        "ergänzen wir nach freigabe",
        "lorem ipsum",
        "skip to content",
        'aria-label="open menu"',
        'aria-label="close menu"',
    ):
        assert forbidden not in lowered, (path, forbidden)


seen_descriptions = set()
results = []
for slug in SLUGS:
    path = "/" + slug + ("/" if slug else "")
    raw = get(path)
    page = Page()
    page.feed(raw)
    assert_clean_public_source(path, raw, page)
    assert len(page.headings) == 1, (path, page.headings)
    descriptions = meta_values(page, "name", "description")
    assert len(descriptions) == 1 and len(descriptions[0]) > 40, (path, descriptions)
    assert descriptions[0] not in seen_descriptions, (path, descriptions[0])
    seen_descriptions.add(descriptions[0])

    canonicals = [item["href"] for item in page.links if item.get("rel") == "canonical"]
    if STATIC and not PRODUCTION:
        assert not canonicals, (path, canonicals)
    else:
        assert canonicals == [EXPECTED_BASE + path], (path, canonicals)

    robots = meta_values(page, "name", "robots")
    if EXPECT_NOINDEX:
        assert any("noindex" in value for value in robots), (path, robots)
    else:
        assert robots and all("noindex" not in value for value in robots), (path, robots)
        if PRODUCTION:
            assert any("max-image-preview:large" in value for value in robots), (path, robots)

    assert len(page.json) == 1, (path, len(page.json))
    graph = page.json[0]["@graph"]
    types = [entity["@type"] for entity in graph]
    assert "LocalBusiness" in types and "WebPage" in types
    organization = next(entity for entity in graph if entity["@type"] == "LocalBusiness")
    assert organization["address"]["addressLocality"] == "Gelsenkirchen"
    assert organization["telephone"] == "+4917655376651"
    text = " ".join(page.text)
    assert "Buerelterstraße 27" in text and "kontakt@phinix.media" in text
    assert "aggregateRating" not in raw and "FAQPage" not in raw

    if slug in PAGES:
        assert "Service" in types and "BreadcrumbList" in types
    elif slug in LEGAL:
        assert "Service" not in types and "BreadcrumbList" in types
    if slug in ARTICLES:
        schema_type = ARTICLES[slug]
        assert schema_type in types and "BreadcrumbList" in types and "Service" not in types
        article = next(entity for entity in graph if entity["@type"] == schema_type)
        assert article["image"].startswith(EXPECTED_BASE + "/"), (path, article["image"])
        assert article.get("about")
        if slug == "digitale-aussenwerbung-gladbeck-phinix-screens":
            assert any(item.get("href") == "https://www.phinix-screens.com/" for item in page.anchors)
            assert all(
                term in text
                for term in (
                    "Ratgeber",
                    "Aktualisiert am",
                    "digitale Außenwerbung",
                    "Digital Signage",
                    "DOOH",
                    "Gladbeck",
                    "Phinix Media gestaltet",
                )
            )
        if slug == "impressum-pruefen-website-check":
            assert any(item.get("href") == "https://complioneer.eu/" for item in page.anchors)
            assert any(item.get("href", "").startswith("https://wa.me/4917655376651") for item in page.anchors)
            assert all(
                term in text
                for term in (
                    "2.500 bis 200.000 Euro",
                    "nicht belastbar",
                    "50.000 Euro",
                    "fiktives, typisches Beispiel",
                    "§ 5 DDG",
                    "keine Rechtsberatung",
                    "Complioneer",
                    "+49 176 5537 6651",
                )
            )

    if PRODUCTION:
        og_images = meta_values(page, "property", "og:image")
        og_alts = meta_values(page, "property", "og:image:alt")
        twitter_images = meta_values(page, "name", "twitter:image")
        twitter_cards = meta_values(page, "name", "twitter:card")
        assert len(og_images) == 1 and og_images[0].startswith(PUBLIC_URL + "/"), (path, og_images)
        assert len(og_alts) == 1 and len(og_alts[0]) > 20, (path, og_alts)
        assert len(twitter_images) == 1 and twitter_images[0] == og_images[0], (path, twitter_images)
        assert twitter_cards == ["summary_large_image"], (path, twitter_cards)

    if not slug:
        assert page.headings[0] == "DEINUNTERNEHMEN.UNÜBERSEHBAR."
        assert all(
            term in text
            for term in (
                "Werbeagentur",
                "WordPress",
                "Corporate Design",
                "Online-Marketing",
                "Google-Unternehmensprofil",
                "KI-Suche",
                "PHINIX.SCREENS",
                "Website prüfen",
                "Ruhrpott",
                "Ruhrgebiet",
                "Wir bringen Ideen in die Realität",
                "Umsatzsteigernde Maßnahmen",
            )
        )
        wordmark_symbols = [image for image in page.images if "wordmark-symbol" in image.get("class", "").split()]
        assert len(wordmark_symbols) == 1
        assert wordmark_symbols[0].get("alt") == "" and wordmark_symbols[0].get("width") and wordmark_symbols[0].get("height")
        people = [image for image in page.images if "/people/" in image.get("src", "")]
        assert len(people) >= 6
        assert all(image.get("alt") and image.get("width") and image.get("height") for image in people)
        assert all(image.get("src", "").endswith(".webp") for image in people)
        wallpaper_pictures = [picture for picture in page.pictures if "scene-wallpaper" in picture.get("class", "").split()]
        assert len(wallpaper_pictures) == 3 and all(picture.get("aria-hidden") == "true" for picture in wallpaper_pictures)
        brand_world = [image for image in page.images if "/brand-world/" in image.get("src", "")]
        assert len(brand_world) == 3 and all(image.get("alt") == "" and image.get("width") and image.get("height") for image in brand_world)
        assert len({image.get("src") for image in brand_world}) == 3
        mobile_wallpapers = [source.get("srcset", "").split()[0] for source in page.sources if "/brand-world/" in source.get("srcset", "")]
        assert len(mobile_wallpapers) == 3
        assert "brand-world__hero" not in raw and "brand-world__gallery" not in raw and '<section class="brand-world"' not in raw
        expected_icons = {
            "service-icon--websites",
            "service-icon--branding",
            "service-icon--print",
            "service-icon--marketing",
            "service-icon--seo",
            "service-icon--screens",
        }
        rendered_icons = {
            name
            for span in page.spans
            for name in span.get("class", "").split()
            if name.startswith("service-icon--")
        }
        assert rendered_icons == expected_icons, (rendered_icons, expected_icons)
        for asset in [image.get("src") for image in brand_world] + mobile_wallpapers:
            asset_path = urlparse(asset).path
            if STATIC:
                assert (STATIC_ROOT / asset_path.lstrip("/")).is_file(), asset_path
            else:
                with urlopen(ORIGIN + asset_path, timeout=20) as response:
                    assert response.status == 200

    for entity in graph:
        if "@id" in entity:
            assert entity["@id"].startswith(EXPECTED_BASE + "/"), (path, entity["@id"])
    assert "Fatal error" not in raw and "Parse error" not in raw
    results.append(
        {
            "path": path,
            "h1": page.headings[0],
            "schema": types,
            "noindex": any("noindex" in value for value in robots),
        }
    )

if STATIC:
    robots_source = (STATIC_ROOT / "robots.txt").read_text()
    if PRODUCTION:
        assert "Disallow: /" not in robots_source
        assert "Allow: /" in robots_source
        assert f"Sitemap: {PUBLIC_URL}/sitemap.xml" in robots_source
        sitemap = ElementTree.parse(STATIC_ROOT / "sitemap.xml").getroot()
        sitemap_locations = {element.text for element in sitemap.findall("{*}url/{*}loc")}
        expected_locations = {PUBLIC_URL + path for path in PUBLIC_PATHS}
        assert sitemap_locations == expected_locations, (sitemap_locations, expected_locations)

        not_found_source = (STATIC_ROOT / "404.html").read_text()
        not_found = Page()
        not_found.feed(not_found_source)
        assert_clean_public_source("/404.html", not_found_source, not_found)
        assert any("noindex" in value for value in meta_values(not_found, "name", "robots"))
        assert not [item for item in not_found.links if item.get("rel") == "canonical"]
        assert "Seite nicht gefunden" in " ".join(not_found.text)
    else:
        assert "Disallow: /" in robots_source and "Sitemap:" not in robots_source

print(
    json.dumps(
        {
            "mode": "static-production" if PRODUCTION else ("static-preview" if STATIC else "wordpress"),
            "pages": results,
        },
        ensure_ascii=False,
        indent=2,
    )
)
