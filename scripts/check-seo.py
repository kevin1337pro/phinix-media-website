"""Integration checks against rendered WordPress or the static preview; no browser required."""
import json, os, sys
from pathlib import Path
from html.parser import HTMLParser
from urllib.request import urlopen
from urllib.error import HTTPError
ROOT=Path(__file__).resolve().parent.parent
ORIGIN=os.environ.get('PHINIX_PREVIEW_ORIGIN','http://127.0.0.1:9402').rstrip('/')
STATIC='--static' in sys.argv
PAGES=json.loads((ROOT/'themes/phinix-media/content/seo-pages.json').read_text())
ARTICLE='digitale-aussenwerbung-gladbeck-phinix-screens'
class Page(HTMLParser):
    def __init__(self):super().__init__();self.headings=[];self.meta=[];self.links=[];self.anchors=[];self.images=[];self.json=[];self.capture=None;self.buffer='';self.text=[]
    def handle_starttag(self,tag,attrs):
        a=dict(attrs)
        if tag=='meta':self.meta.append(a)
        if tag=='link':self.links.append(a)
        if tag=='a':self.anchors.append(a)
        if tag=='img':self.images.append(a)
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
for slug in ['']+list(PAGES)+[ARTICLE]:
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
    assert any('noindex' in x for x in robots),(path,robots)
    assert len(page.json)==1,(path,len(page.json))
    graph=page.json[0]['@graph']; types=[x['@type'] for x in graph]
    assert 'LocalBusiness' in types and 'WebPage' in types
    org=next(x for x in graph if x['@type']=='LocalBusiness')
    assert org['address']['addressLocality']=='Gelsenkirchen'
    assert org['telephone']=='+4917655376651'
    text=' '.join(page.text)
    assert 'Buerelterstraße 27' in text and 'kontakt@phinix.media' in text
    assert 'aggregateRating' not in raw and 'FAQPage' not in raw
    if slug and slug != ARTICLE:assert 'Service' in types and 'BreadcrumbList' in types
    if slug == ARTICLE:
        assert 'Article' in types and 'BreadcrumbList' in types and 'Service' not in types
        article=next(x for x in graph if x['@type']=='Article')
        assert article['image'].startswith('https://' if STATIC else ORIGIN)
        og_image=[x['content'] for x in page.meta if x.get('property')=='og:image']
        twitter_card=[x['content'] for x in page.meta if x.get('name')=='twitter:card']
        assert len(og_image)==1 and og_image[0].startswith('https://' if STATIC else ORIGIN)
        assert twitter_card==['summary_large_image']
        assert any(x.get('href')=='https://www.phinix-screens.com/' for x in page.anchors)
        assert all(term in text for term in ('Ratgeber','Aktualisiert am','digitale Außenwerbung','Digital Signage','DOOH','Gladbeck','Phinix Media gestaltet'))
    if not slug:
        assert all(term in text for term in ('Werbeagentur','WordPress','Corporate Design','Online-Marketing','Google-Unternehmensprofil','KI-Suche','PHINIX.SCREENS'))
        people=[x for x in page.images if '/people/' in x.get('src','')]
        assert len(people)>=6
        assert all(x.get('alt') and x.get('width') and x.get('height') for x in people)
    for entity in graph:
        if '@id' in entity:assert entity['@id'].startswith('https://' if STATIC else ORIGIN)
    assert 'Fatal error' not in raw and 'Parse error' not in raw
    results.append({'path':path,'h1':page.headings[0],'schema':types,'noindex':True})
print(json.dumps({'mode':'static' if STATIC else 'wordpress','pages':results},ensure_ascii=False,indent=2))
