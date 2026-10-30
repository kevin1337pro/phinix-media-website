"""Create an installable theme ZIP without repository or runtime files."""
from pathlib import Path
from zipfile import ZipFile, ZIP_DEFLATED

root = Path(__file__).resolve().parent.parent
theme = root / 'themes' / 'phinix-media'
output = root / 'output' / 'phinix-media-0.8.0.zip'
output.parent.mkdir(exist_ok=True)
images = [path for path in (theme / 'assets' / 'images').rglob('*') if path.is_file()]
assert images
assert all(path.suffix.lower() in {'.webp', '.svg'} for path in images), 'Theme images must be WebP or SVG'
assert all(path.stat().st_size < 500_000 for path in images), 'Theme image exceeds the 500 KB performance budget'
with ZipFile(output, 'w', ZIP_DEFLATED) as archive:
    for path in sorted(theme.rglob('*')):
        if path.is_file() and not path.name.startswith('.'):
            archive.write(path, path.relative_to(theme.parent))
with ZipFile(output) as archive:
    assert archive.testzip() is None
    assert 'phinix-media/style.css' in archive.namelist()
    assert 'phinix-media/templates/index.html' in archive.namelist()
    assert 'phinix-media/inc/articles.php' in archive.namelist()
    assert 'phinix-media/assets/images/people/phinix-brand-stage.webp' in archive.namelist()
    assert 'phinix-media/assets/images/brand-world/phinix-phoenix-wide.webp' in archive.namelist()
    assert 'phinix-media/assets/images/articles/website-pruefen-impressum-hero.webp' in archive.namelist()
print(output)
