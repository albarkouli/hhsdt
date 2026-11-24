$ErrorActionPreference = 'Stop'
$root = 'C:\Users\HP\Herd\libya-marketplace'
Set-Location $root
$utf8NoBom = New-Object System.Text.UTF8Encoding($false)

# STEP 1: Restore the 4 files from the pre-corruption backup
$backupRoot = Join-Path $root '.backup\tier2-20261122-191616'
$toRestore = @(
  '.env',
  'app\Notifications\VendorSuspended.php',
  'resources\views\shop\contact.blade.php',
  'resources\views\components\layouts\shop.blade.php'
)
Write-Host "`n=== STEP 1: Restore ===" -ForegroundColor Yellow
$restored = 0
foreach ($f in $toRestore) {
  $src = Join-Path $backupRoot $f
  $dst = Join-Path $root $f
  if (-not (Test-Path $src)) {
    Write-Host "MISSING BACKUP: $f" -ForegroundColor Red
    continue
  }
  Copy-Item $src $dst -Force
  Write-Host "RESTORED: $f" -ForegroundColor Green
  $restored++
}
Write-Host "Restored: $restored / $($toRestore.Count)" -ForegroundColor Cyan

# STEP 2: Backup current state, then apply Tier 2 correctly
$timestamp = Get-Date -Format 'yyyyMMdd-HHmmss'
$newBackup = Join-Path $root ".backup\tier2v2-$timestamp"
New-Item -ItemType Directory -Force -Path $newBackup | Out-Null

$pairs = @(
  [pscustomobject]@{ File='.env'; Old='noreply@libyamarketplace.test'; New='noreply@wesalkom.ly' },
  [pscustomobject]@{ File='app/Notifications/VendorSuspended.php'; Old='hello@libyamarketplace.ly'; New='hello@wesalkom.ly' },
  [pscustomobject]@{ File='resources/views/shop/contact.blade.php'; Old='hello@libyamarketplace.ly'; New='hello@wesalkom.ly' },
  [pscustomobject]@{ File='resources/views/components/layouts/shop.blade.php'; Old='https://facebook.com/libyamarketplace'; New='https://facebook.com/wesalkom' },
  [pscustomobject]@{ File='resources/views/components/layouts/shop.blade.php'; Old='https://instagram.com/libyamarketplace'; New='https://instagram.com/wesalkom' },
  [pscustomobject]@{ File='resources/views/components/layouts/shop.blade.php'; Old='https://twitter.com/libyamarket'; New='https://twitter.com/wesalkom' }
)

Write-Host "`n=== STEP 2: Apply Tier 2 (correct) ===" -ForegroundColor Yellow
$total = 0
$alreadyBackedUp = @{}
foreach ($p in $pairs) {
  $full = Join-Path $root $p.File
  if (-not (Test-Path $full)) {
    Write-Host "MISSING: $($p.File)" -ForegroundColor Red
    continue
  }
  if (-not $alreadyBackedUp.ContainsKey($p.File)) {
    $bak = Join-Path $newBackup ($p.File -replace '/','\')
    New-Item -ItemType Directory -Force -Path (Split-Path $bak -Parent) | Out-Null
    Copy-Item $full $bak
    $alreadyBackedUp[$p.File] = $true
  }
  $content = Get-Content $full -Raw
  if ($content.Contains($p.Old)) {
    $n = ([regex]::Matches($content, [regex]::Escape($p.Old))).Count
    $content = $content.Replace($p.Old, $p.New)
    [System.IO.File]::WriteAllText($full, $content, $utf8NoBom)
    Write-Host "OK $($p.File) : $n hit(s)" -ForegroundColor Green
    $total += $n
  } else {
    Write-Host "SKIP $($p.File) : pattern not found" -ForegroundColor DarkGray
  }
}
Write-Host "Total Tier 2 replacements: $total" -ForegroundColor Cyan
Write-Host "New backup: $newBackup" -ForegroundColor Cyan

# STEP 3: Verification
Write-Host "`n=== STEP 3: Verification ===" -ForegroundColor Yellow

Write-Host "`n--- .env core ---" -ForegroundColor Cyan
Select-String -Path '.env' -Pattern '^APP_NAME|^APP_URL|^DB_DATABASE|^MAIL_FROM_ADDRESS|^MAIL_FROM_NAME' | ForEach-Object { $_.Line }

Write-Host "`n--- Emails (VendorSuspended + contact) ---" -ForegroundColor Cyan
Select-String -Path 'app\Notifications\VendorSuspended.php','resources\views\shop\contact.blade.php' -Pattern 'hello@' | ForEach-Object { "$($_.Filename): $($_.Line.Trim())" }

Write-Host "`n--- Social links (shop layout) ---" -ForegroundColor Cyan
Select-String -Path 'resources\views\components\layouts\shop.blade.php' -Pattern 'facebook\.com|instagram\.com|twitter\.com' | ForEach-Object { $_.Line.Trim() }

Write-Host "`n--- Old brand remaining (should be NONE) ---" -ForegroundColor Cyan
$r = Get-ChildItem app,resources,routes,lang,config -Recurse -File -Include *.php,*.blade.php -ErrorAction SilentlyContinue |
     Select-String -Pattern 'Libya\s*Market|libyamarketplace|libya-marketplace\.test'
if ($r) {
  $r | ForEach-Object { "$($_.Path):$($_.LineNumber)  $($_.Line.Trim())" }
} else {
  Write-Host "NONE" -ForegroundColor Green
}

Write-Host "`n--- Geographic preserved (about.blade.php) ---" -ForegroundColor Cyan
if (Test-Path 'resources\views\shop\about.blade.php') {
  Select-String -Path 'resources\views\shop\about.blade.php' -Pattern 'WESALKOM|Libyan' | ForEach-Object { "L$($_.LineNumber): $($_.Line.Trim())" }
}

Write-Host "`n--- Lime baseline (Phase 2 target) ---" -ForegroundColor Cyan
$limeCount = (Get-ChildItem resources\views -Recurse -File -Include *.blade.php | Select-String -Pattern 'lime-400|lime-500' -AllMatches | Measure-Object).Count
Write-Host "Lime references: $limeCount" -ForegroundColor Magenta