"""Export the rendered WordPress theme as a static preview or production site.

The default is the private owner preview in ``dist``. Run with ``--production``
or ``PHINIX_EXPORT_MODE=production`` to create the public, indexable build in
``dist-production``. Start the documented local WordPress Playground first.
"""
import json
import os
import re
import sys
from pathlib import Path
from shutil import copyfile, copytree, rmtree
from urllib.parse import unquote, urlparse
from urllib.request import urlopen
from xml.etree import ElementTree

ROOT = Path(__file__).resolve().parent.parent
PRODUCTION = "--production" in sys.argv or os.environ.get("PHINIX_EXPORT_MODE", "preview").lower() == "production"
ORIGIN = os.environ.get("PHINIX_PREVIEW_ORIGIN", "http://127.0.0.1:9400").rstrip("/")
PREVIEW_URL = os.environ.get(
    "PHINIX_PREVIEW_URL",
    "https://phinix-media-vorschau.kevin1337pro.chatgpt.site",
).rstrip("/")
PUBLIC_URL = os.environ.get("PHINIX_PUBLIC_URL", "https://www.phinix.media").rstrip("/")
SITE_URL = PUBLIC_URL if PRODUCTION else PREVIEW_URL
DIST = ROOT / ("dist-production" if PRODUCTION else "dist")

if DIST.exists():
    rmtree(DIST)
DIST.mkdir(exist_ok=True)

assets = set()
service_slugs = list(json.loads((ROOT / "themes/phinix-media/content/seo-pages.json").read_text()))
article_slugs = [
    "digitale-aussenwerbung-gladbeck-phinix-screens",
    "impressum-pruefen-website-check",
]
legal_slugs = ["impressum", "datenschutz"]
public_paths = ["/"] + [f"/{slug}/" for slug in legal_slugs + service_slugs + article_slugs]


def fetch(path):
    try:
        return urlopen(ORIGIN + path, timeout=40).read()
    except Exception as error:
        if getattr(error, "code", None) == 404:
            return error.read()
        raise


def make_absolute_metadata(html):
    """Keep document links relative while metadata uses the selected public host."""
    html = re.sub(
        r'(<meta\s+(?:property|name)="(?:og:(?:url|image)|twitter:image)"\s+content=")(/[^\"]*)',
        lambda match: match[1] + SITE_URL + match[2],
        html,
        flags=re.I,
    )
    return re.sub(
        r'(<script[^>]*id="phinix-structured-data"[^>]*>)(.*?)(</script>)',
        lambda match: match[1]
        + re.sub(
            r'("(?:url|@id|item|image|logo)":")/',
            r"\1" + SITE_URL + "/",
            match[2],
        )
        + match[3],
        html,
        flags=re.S,
    )


def export_page(path, destination, canonical_path=None, is_404=False):
    html = fetch(path).decode("utf-8")
    assert "<html" in html and "<main" in html, "WordPress did not render a page"
    html = re.sub(
        r'<link\b[^>]*(?:rel=["\'](?:alternate|EditURI|https://api.w.org/|dns-prefetch)["\'])[^>]*>',
        "",
        html,
    )
    html = re.sub(r'<script\b[^>]*type="speculationrules"[^>]*>.*?</script>', "", html, flags=re.S)
    html = re.sub(
        r'<script\b[^>]*id="wp-emoji-settings"[^>]*>.*?</script>\s*<script\b[^>]*>.*?</script>',
        "",
        html,
        flags=re.S,
    )
    html = re.sub(
        r'<meta\b[^>]*name\s*=\s*(["\'])generator\1[^>]*>',
        "",
        html,
        flags=re.I,
    )
    # Download local public frontend dependencies only, never admin/API data.
    for match in re.findall(re.escape(ORIGIN) + r'/(?:wp-content|wp-includes)/[^\s"\'<>)]+', html):
        assets.add(match.replace("\\/", "/"))
    # JSON and inline CSS may use escaped forward slashes.
    html = html.replace(ORIGIN.replace("/", "\\/"), ORIGIN)
    html = html.replace(ORIGIN, "")
    html = re.sub(r'<meta\b[^>]*name=["\']robots["\'][^>]*>', "", html, flags=re.I)
    html = re.sub(r'<link\b[^>]*rel=["\']canonical["\'][^>]*>', "", html, flags=re.I)
    html = re.sub(
        r'(<html\b[^>]*\blang\s*=\s*)["\'][^"\']*["\']',
        r'\1"de-DE"',
        html,
        count=1,
        flags=re.I,
    )
    if not re.search(r'<html\b[^>]*\blang\s*=', html, flags=re.I):
        html = re.sub(r'<html\b', '<html lang="de-DE"', html, count=1, flags=re.I)
    html = make_absolute_metadata(html)
    head_parts = []
    if PRODUCTION and not is_404:
        assert canonical_path is not None
        head_parts.extend(
            [
                f'<link rel="canonical" href="{PUBLIC_URL}{canonical_path}">',
                '<meta name="robots" content="index,follow,max-image-preview:large">',
            ]
        )
    else:
        head_parts.append('<meta name="robots" content="noindex,follow">')
    html = html.replace("</head>", "\n".join(head_parts) + "\n</head>")
    html = re.sub(r"[ \t]+(?=\r?$)", "", html, flags=re.M)
    output = DIST / destination
    output.parent.mkdir(parents=True, exist_ok=True)
    output.write_text(html)


export_page("/", "index.html", "/")
for slug in legal_slugs:
    export_page(f"/{slug}/", f"{slug}/index.html", f"/{slug}/")
export_page("/?p=999999", "404.html", is_404=True)
for slug in service_slugs + article_slugs:
    export_page(f"/{slug}/", f"{slug}/index.html", f"/{slug}/")

if PRODUCTION:
    (DIST / "robots.txt").write_text(
        "User-agent: *\nAllow: /\n\n" f"Sitemap: {PUBLIC_URL}/sitemap.xml\n"
    )
    urlset = ElementTree.Element("urlset", xmlns="http://www.sitemaps.org/schemas/sitemap/0.9")
    for path in public_paths:
        url = ElementTree.SubElement(urlset, "url")
        ElementTree.SubElement(url, "loc").text = PUBLIC_URL + path
    ElementTree.ElementTree(urlset).write(DIST / "sitemap.xml", encoding="unicode", xml_declaration=True)
else:
    (DIST / "robots.txt").write_text("User-agent: *\nDisallow: /\n")

# Theme assets are original source files; retain all local fonts and their license.
copytree(
    ROOT / "themes/phinix-media/assets",
    DIST / "wp-content/themes/phinix-media/assets",
    dirs_exist_ok=True,
    copy_function=copyfile,
)
for url in sorted(assets):
    parsed = urlparse(url)
    target = DIST / unquote(parsed.path).lstrip("/")
    if target.exists():
        continue
    if ".." in Path(parsed.path).parts:
        raise ValueError("Unsafe path")
    target.parent.mkdir(parents=True, exist_ok=True)
    target.write_bytes(fetch(parsed.path + ("?" + parsed.query if parsed.query else "")))

# Safety: no local origins, PHP or personal planning documents are published.
for file in DIST.rglob("*.html"):
    source = file.read_text()
    assert ORIGIN not in source, f"Local URL remains: {file}"
    if PRODUCTION:
        assert PREVIEW_URL not in source, f"Preview URL remains: {file}"
assert not list(DIST.rglob("*.php"))
assert not [file for file in DIST.rglob("*") if file.is_file() and file.stat().st_size == 0], "Zero-byte file in export"
print(
    f"Exported {len(list(DIST.rglob('*')))} files/directories "
    f"to {DIST} ({'production' if PRODUCTION else 'preview'})"
)
