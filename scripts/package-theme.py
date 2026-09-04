"""Create an installable theme ZIP without repository or runtime files."""
from pathlib import Path
from zipfile import ZipFile, ZIP_DEFLATED

root = Path(__file__).resolve().parent.parent
theme = root / 'themes' / 'phinix-media'
output = root / 'output' / 'phinix-media-0.1.0.zip'
output.parent.mkdir(exist_ok=True)
with ZipFile(output, 'w', ZIP_DEFLATED) as archive:
    for path in sorted(theme.rglob('*')):
        if path.is_file() and not path.name.startswith('.'):
            archive.write(path, path.relative_to(theme.parent))
with ZipFile(output) as archive:
    assert archive.testzip() is None
    assert 'phinix-media/style.css' in archive.namelist()
    assert 'phinix-media/templates/index.html' in archive.namelist()
print(output)
