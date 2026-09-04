"""Export the locally rendered WordPress theme as an owner-only design preview.
The export retains WordPress frontend assets, not its PHP/admin runtime.
Run the documented local WordPress Playground first.
"""
import re
from pathlib import Path
from urllib.request import urlopen
from urllib.parse import urlparse, unquote

ORIGIN = 'http://127.0.0.1:9400'
ROOT = Path(__file__).resolve().parent.parent
DIST = ROOT / 'dist'
DIST.mkdir(exist_ok=True)
assets = set()

def fetch(path):
    try:
        return urlopen(ORIGIN + path, timeout=40).read()
    except Exception as error:
        if getattr(error, 'code', None) == 404:
            return error.read()
        raise

def export_page(path, destination):
    html = fetch(path).decode('utf-8')
    assert '<html' in html and '<main' in html, 'WordPress did not render a page'
    html = re.sub(r'<link\b[^>]*(?:rel=[\"\'](?:alternate|EditURI|https://api.w.org/|dns-prefetch)[\"\'])[^>]*>', '', html)
    html = re.sub(r'<script\b[^>]*type="speculationrules"[^>]*>.*?</script>', '', html, flags=re.S)
    html = re.sub(r'<script\b[^>]*id="wp-emoji-settings"[^>]*>.*?</script>\s*<script\b[^>]*>.*?</script>', '', html, flags=re.S)
    # Download only local public frontend dependencies, never administrative/API data.
    for match in re.findall(r'http://127\.0\.0\.1:9400/(?:wp-content|wp-includes)/[^\s\"\'<>\)]+', html):
        assets.add(match.replace('\\/', '/'))
    html = html.replace(ORIGIN, '')
    html = html.replace('</head>', '<meta name="robots" content="noindex,nofollow"><meta name="description" content="Phinix Media – Websites, Branding, Print, Marketing und Google-SEO. Designvorschau des eigenen WordPress-Themes."></head>')
    output = DIST / destination
    output.parent.mkdir(parents=True, exist_ok=True)
    output.write_text(html)

export_page('/', 'index.html')
export_page('/impressum/', 'impressum/index.html')
export_page('/datenschutz/', 'datenschutz/index.html')
export_page('/?p=999999', '404.html')
# Theme assets are original source files; retain all local fonts/license.
from shutil import copytree
copytree(ROOT / 'themes/phinix-media/assets', DIST / 'wp-content/themes/phinix-media/assets', dirs_exist_ok=True)
for url in sorted(assets):
    parsed = urlparse(url)
    target = DIST / unquote(parsed.path).lstrip('/')
    if target.exists():
        continue
    if '..' in Path(parsed.path).parts:
        raise ValueError('Unsafe path')
    target.parent.mkdir(parents=True, exist_ok=True)
    target.write_bytes(fetch(parsed.path + ('?' + parsed.query if parsed.query else '')))
# Safety: no local origins, PHP or personal planning docs are published.
for f in DIST.rglob('*.html'):
    assert ORIGIN not in f.read_text(), f'Local URL remains: {f}'
assert not list(DIST.rglob('*.php'))
print(f'Exported {len(list(DIST.rglob("*")))} files/directories to {DIST}')
