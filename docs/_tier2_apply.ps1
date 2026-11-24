$ErrorActionPreference = 'Stop'
$root = 'C:\Users\HP\Herd\libya-marketplace'
Set-Location $root
$utf8NoBom = New-Object System.Text.UTF8Encoding($false)
$timestamp = Get-Date -Format 'yyyyMMdd-HHmmss'
$backupDir = Join-Path $root ".backup\tier2-$timestamp"
New-Item -ItemType Directory -Force -Path $backupDir | Out-Null

$fileMap = @{
  '.env' = @(
    @('noreply@libyamarketplace.test', 'noreply@wesalkom.ly')
  )
  'app/Notifications/VendorSuspended.php' = @(
    @('hello@libyamarketplace.ly', 'hello@wesalkom.ly')
  )
  'resources/views/shop/contact.blade.php' = @(
    @('hello@libyamarketplace.ly', 'hello@wesalkom.ly')
  )
  'resources/views/components/layouts/shop.blade.php' = @(
    @('https://facebook.com/libyamarketplace', 'https://facebook.com/wesalkom')
    @('https://instagram.com/libyamarketplace', 'https://instagram.com/wesalkom')
    @('https://twitter.com/libyamarket', 'https://twitter.com/wesalkom')
  )
}

$total = 0
foreach ($rel in $fileMap.Keys) {
  $full = Join-Path $root $rel
  if (-not (Test-Path $full)) { Write-Host "MISSING $rel" -ForegroundColor Yellow; continue }
  $bak = Join-Path $backupDir ($rel -replace '/','\')
  New-Item -ItemType Directory -Force -Path (Split-Path $bak -Parent) | Out-Null
  Copy-Item $full $bak
  $content = Get-Content $full -Raw
  $hits = 0
  foreach ($pair in $fileMap[$rel]) {
    $old = $pair[0]; $new = $pair[1]
    if ($content.Contains($old)) {
      $n = ([regex]::Matches($content, [regex]::Escape($old))).Count
      $hits += $n
      $content = $content.Replace($old, $new)
    }
  }
  if ($hits -gt 0) {
    [System.IO.File]::WriteAllText($full, $content, $utf8NoBom)
    Write-Host "OK $rel : $hits hit(s)" -ForegroundColor Green
    $total += $hits
  } else { Write-Host "SKIP $rel" -ForegroundColor DarkGray }
}
Write-Host "TOTAL: $total" -ForegroundColor Cyan
Write-Host "BACKUP: $backupDir" -ForegroundColor Cyan