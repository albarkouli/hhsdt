$ErrorActionPreference = 'Stop'
$root = 'C:\Users\HP\Herd\libya-marketplace'
Set-Location $root
$utf8NoBom = New-Object System.Text.UTF8Encoding($false)
$timestamp = Get-Date -Format 'yyyyMMdd-HHmmss'
$backupDir = Join-Path $root ".backup\brand-$timestamp"
New-Item -ItemType Directory -Force -Path $backupDir | Out-Null

$files = @(
    '.env',
    '.env.example',
    'app/Notifications/OrderPlaced.php',
    'app/Notifications/OrderStatusChanged.php',
    'app/Notifications/VendorApproved.php',
    'lang/en/shop.php',
    'resources/views/components/layouts/dashboard.blade.php',
    'resources/views/components/layouts/shop.blade.php',
    'resources/views/customer/dashboard.blade.php',
    'resources/views/emails/order-placed-customer.blade.php',
    'resources/views/emails/order-placed-vendor.blade.php',
    'resources/views/errors/404.blade.php',
    'resources/views/errors/500.blade.php',
    'resources/views/errors/503.blade.php',
    'resources/views/invoices/pdf.blade.php',
    'resources/views/invoices/show.blade.php',
    'resources/views/layouts/auth/simple.blade.php',
    'resources/views/shop/about.blade.php',
    'resources/views/shop/home.blade.php',
    'resources/views/shop/products/show.blade.php',
    'resources/views/welcome.blade.php'
)

$replacements = @(
    @('Libya Marketplace', 'WESALKOM'),
    @('Libya Market', 'WESALKOM'),
    @('LibyaMarketplace', 'WESALKOM'),
    @('http://libya-marketplace.test', 'https://wesalkom.ly')
)

$report = New-Object System.Collections.Generic.List[string]
$report.Add('# BRAND REPLACEMENT MAP - Tier 3 Execution Report')
$report.Add('')
$report.Add("**Date:** $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')")
$report.Add("**Backup:** .backup/brand-$timestamp/")
$report.Add('')
$report.Add('## Protected (DO NOT TOUCH)')
$report.Add('- .env L62: DB_DATABASE=libya_marketplace')
$report.Add('')
$report.Add('## Tier 2 (pending owner)')
$report.Add('- .env L72: noreply@libyamarketplace.test')
$report.Add('- app/Notifications/VendorSuspended.php L31: hello@libyamarketplace.ly')
$report.Add('- resources/views/shop/contact.blade.php L19: hello@libyamarketplace.ly')
$report.Add('- resources/views/components/layouts/shop.blade.php L155-157: facebook/instagram/twitter libyamarketplace URLs')
$report.Add('')
$report.Add('## Geographic (kept)')
$report.Add('- resources/views/customer/dashboard.blade.php L85: "across Libya"')
$report.Add('- resources/views/shop/about.blade.php L11: "Libyan vendors"')
$report.Add('')
$report.Add('## Per-file results')
$report.Add('')

$totalHits = 0
foreach ($rel in $files) {
    $full = Join-Path $root $rel
    if (-not (Test-Path $full)) { $report.Add("- MISSING: $rel"); continue }
    $bak = Join-Path $backupDir ($rel -replace '/','\')
    New-Item -ItemType Directory -Force -Path (Split-Path $bak -Parent) | Out-Null
    Copy-Item $full $bak
    $content = Get-Content $full -Raw
    $fileHits = 0
    foreach ($r in $replacements) {
        $old = $r[0]; $new = $r[1]
        if ($content.Contains($old)) {
            $n = ([regex]::Matches($content, [regex]::Escape($old))).Count
            $fileHits += $n
            $content = $content.Replace($old, $new)
        }
    }
    if ($fileHits -gt 0) {
        [System.IO.File]::WriteAllText($full, $content, $utf8NoBom)
        $report.Add("- OK $rel : $fileHits replacement(s)")
        $totalHits += $fileHits
    } else {
        $report.Add("- SKIP $rel : no change")
    }
}
$report.Add('')
$report.Add("**Total replacements:** $totalHits")

$arPath = Join-Path $root 'lang\ar\shop.php'
if (Test-Path $arPath) {
    $arHits = Select-String -Path $arPath -Pattern ([char]0x0644+[char]0x064A+[char]0x0628+[char]0x064A+[char]0x0627) -AllMatches -ErrorAction SilentlyContinue
    if ($arHits) {
        $report.Add('')
        $report.Add('## WARN: lang/ar/shop.php - manual inspection')
        foreach ($h in $arHits) { $report.Add("- Line $($h.LineNumber): $($h.Line.Trim())") }
    }
}

$reportPath = Join-Path $root 'docs\BRAND_REPLACEMENT_MAP.md'
[System.IO.File]::WriteAllText($reportPath, ($report -join "`n"), $utf8NoBom)
Write-Host "OK Report: $reportPath : $((Get-Item $reportPath).Length) bytes" -ForegroundColor Green
Write-Host "OK Replaced: $totalHits hits" -ForegroundColor Green
Write-Host "OK Backup: $backupDir" -ForegroundColor Cyan