"""Integration checks against rendered WordPress or the static preview; no browser required."""
import json, os, sys
from pathlib import Path
from html.parser import HTMLParser
from urllib.request import urlopen
from urllib.parse import urlparse
from urllib.error import HTTPError
ROOT=Path(__file__).resolve().parent.parent
ORIGIN=os.environ.get('PHINIX_PREVIEW_ORIGIN','http://127.0.0.1:9402').rstrip('/')
STATIC='--static' in sys.argv
EXPECT_NOINDEX=STATIC or os.environ.get('PHINIX_EXPECT_NOINDEX','1')!='0'
PAGES=json.loads((ROOT/'themes/phinix-media/content/seo-pages.json').read_text())
ARTICLES={
    'digitale-aussenwerbung-gladbeck-phinix-screens': 'Article',
    'impressum-pruefen-website-check': 'BlogPosting',
}
class Page(HTMLParser):
    def __init__(self):super().__init__();self.headings=[];self.meta=[];self.links=[];self.anchors=[];self.images=[];self.sources=[];self.pictures=[];self.spans=[];self.json=[];self.capture=None;self.buffer='';self.text=[]
    def handle_starttag(self,tag,attrs):
        a=dict(attrs)
        if tag=='meta':self.meta.append(a)
        if tag=='link':self.links.append(a)
        if tag=='a':self.anchors.append(a)
        if tag=='img':self.images.append(a)
        if tag=='source':self.sources.append(a)
        if tag=='picture':self.pictures.append(a)
        if tag=='span':self.spans.append(a)
        if tag=='script' and a.get('type')=='application/ld+json':self.capture='json';self.buffer=''
        if tag=='h1':self.capture='h1';self.buffer=''
    def handle_endtag(self,tag):
        if tag=='script' and self.capture=='json':self.json.append(json.loads(self.buffer));self.capture=None
        if tag=='h1' and self.capture=='h1':self.headings.append(self.buffer);self.capture=None
    def handle_data(self,text):
        self.text.append(text)
        if self.capture:self.buffer+=text

def get(path):
    if STATIC:return (ROOT/'dist'/path.lstrip('/')/'index.html').read_text()
    with urlopen(ORIGIN+path,timeout=45) as r:
        assert r.status==200
        return r.read().decode()
seen=set(); results=[]
for slug in ['']+list(PAGES)+list(ARTICLES):
    path='/'+slug+('/' if slug else '')
    raw=get(path); page=Page();page.feed(raw)
    assert len(page.headings)==1,(path,page.headings)
    desc=[x['content'] for x in page.meta if x.get('name')=='description']
    assert len(desc)==1 and len(desc[0])>40,(path,desc)
    assert desc[0] not in seen;seen.add(desc[0])
    canon=[x['href'] for x in page.links if x.get('rel')=='canonical']
    assert len(canon)==(0 if STATIC else 1),(path,canon)
    if not STATIC:assert canon[0]==ORIGIN+path,(path,canon)
    robots=[x['content'] for x in page.meta if x.get('name')=='robots']
    if EXPECT_NOINDEX:assert any('noindex' in x for x in robots),(path,robots)
    else:assert all('noindex' not in x for x in robots),(path,robots)
    assert len(page.json)==1,(path,len(page.json))
    graph=page.json[0]['@graph']; types=[x['@type'] for x in graph]
    assert 'LocalBusiness' in types and 'WebPage' in types
    org=next(x for x in graph if x['@type']=='LocalBusiness')
    assert org['address']['addressLocality']=='Gelsenkirchen'
    assert org['telephone']=='+4917655376651'
    text=' '.join(page.text)
    assert 'Buerelterstraße 27' in text and 'kontakt@phinix.media' in text
    assert 'aggregateRating' not in raw and 'FAQPage' not in raw
    if slug and slug not in ARTICLES:assert 'Service' in types and 'BreadcrumbList' in types
    if slug in ARTICLES:
        schema_type=ARTICLES[slug]
        assert schema_type in types and 'BreadcrumbList' in types and 'Service' not in types
        article=next(x for x in graph if x['@type']==schema_type)
        assert article['image'].startswith('https://' if STATIC else ORIGIN)
        assert article.get('about')
        og_image=[x['content'] for x in page.meta if x.get('property')=='og:image']
        og_alt=[x['content'] for x in page.meta if x.get('property')=='og:image:alt']
        twitter_card=[x['content'] for x in page.meta if x.get('name')=='twitter:card']
        assert len(og_image)==1 and og_image[0].startswith('https://' if STATIC else ORIGIN)
        assert len(og_alt)==1 and len(og_alt[0])>20
        assert twitter_card==['summary_large_image']
        if slug == 'digitale-aussenwerbung-gladbeck-phinix-screens':
            assert any(x.get('href')=='https://www.phinix-screens.com/' for x in page.anchors)
            assert all(term in text for term in ('Ratgeber','Aktualisiert am','digitale Außenwerbung','Digital Signage','DOOH','Gladbeck','Phinix Media gestaltet'))
        if slug == 'impressum-pruefen-website-check':
            assert any(x.get('href')=='https://complioneer.eu/' for x in page.anchors)
            assert any(x.get('href','').startswith('https://wa.me/4917655376651') for x in page.anchors)
            assert all(term in text for term in ('2.500 bis 200.000 Euro','nicht belastbar','50.000 Euro','fiktives, typisches Beispiel','§ 5 DDG','keine Rechtsberatung','Complioneer','+49 176 5537 6651'))
    if not slug:
        assert page.headings[0] == 'DEINUNTERNEHMEN.UNÜBERSEHBAR.'
        assert all(term in text for term in ('Werbeagentur','WordPress','Corporate Design','Online-Marketing','Google-Unternehmensprofil','KI-Suche','PHINIX.SCREENS','Website prüfen','Ruhrpott','Ruhrgebiet','Wir bringen Ideen in die Realität','Umsatzsteigernde Maßnahmen'))
        wordmark_symbols=[x for x in page.images if 'wordmark-symbol' in x.get('class','').split()]
        assert len(wordmark_symbols)==1
        assert wordmark_symbols[0].get('alt')=='' and wordmark_symbols[0].get('width') and wordmark_symbols[0].get('height')
        people=[x for x in page.images if '/people/' in x.get('src','')]
        assert len(people)>=6
        assert all(x.get('alt') and x.get('width') and x.get('height') for x in people)
        wallpaper_pictures=[x for x in page.pictures if 'scene-wallpaper' in x.get('class','').split()]
        assert len(wallpaper_pictures)==3 and all(x.get('aria-hidden')=='true' for x in wallpaper_pictures)
        brand_world=[x for x in page.images if '/brand-world/' in x.get('src','')]
        assert len(brand_world)==3 and all(x.get('alt')=='' and x.get('width') and x.get('height') for x in brand_world)
        assert len({x.get('src') for x in brand_world})==3
        mobile_wallpapers=[x.get('srcset','').split()[0] for x in page.sources if '/brand-world/' in x.get('srcset','')]
        assert len(mobile_wallpapers)==3
        assert 'brand-world__hero' not in raw and 'brand-world__gallery' not in raw and '<section class="brand-world"' not in raw
        expected_icons={
            'service-icon--websites','service-icon--branding','service-icon--print',
            'service-icon--marketing','service-icon--seo','service-icon--screens',
        }
        rendered_icons={name for span in page.spans for name in span.get('class','').split() if name.startswith('service-icon--')}
        assert rendered_icons==expected_icons,(rendered_icons,expected_icons)
        for asset in [x.get('src') for x in brand_world]+mobile_wallpapers:
            asset_path=urlparse(asset).path
            if STATIC:
                assert (ROOT/'dist'/asset_path.lstrip('/')).is_file(),asset_path
            else:
                with urlopen((asset if asset.startswith('http') else ORIGIN+asset_path),timeout=20) as response:assert response.status==200
    for entity in graph:
        if '@id' in entity:assert entity['@id'].startswith('https://' if STATIC else ORIGIN)
    assert 'Fatal error' not in raw and 'Parse error' not in raw
    results.append({'path':path,'h1':page.headings[0],'schema':types,'noindex':True})
print(json.dumps({'mode':'static' if STATIC else 'wordpress','pages':results},ensure_ascii=False,indent=2))
