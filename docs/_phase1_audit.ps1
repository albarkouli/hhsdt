$ErrorActionPreference = 'Stop'
$root = 'C:\Users\HP\Herd\libya-marketplace'
Set-Location $root

$utf8NoBom = New-Object System.Text.UTF8Encoding($false)
$out = Join-Path $root 'docs\BRANDING_AUDIT.md'
$lines = New-Object System.Collections.Generic.List[string]

$lines.Add('# BRANDING AUDIT - Libya Market to WESALKOM | وصالكم')
$lines.Add('')
$lines.Add("**Generated:** $(Get-Date -Format 'yyyy-MM-dd HH:mm')")
$lines.Add('')
$lines.Add('---')
$lines.Add('')
$lines.Add('## 1. Old-brand occurrences')
$lines.Add('')

$pattern = 'Libya\s*Market|LibyaMarket|libya-market|libya_market|ليبيا\s*ماركت'
$searchPaths = @('app','resources','routes','config','database','lang','public','bootstrap')
$exts = @('.php','.js','.css','.json','.md','.webmanifest','.svg','.xml')

$results = @()
foreach ($p in $searchPaths) {
    $full = Join-Path $root $p
    if (Test-Path $full) {
        $results += Get-ChildItem $full -Recurse -File -ErrorAction SilentlyContinue |
            Where-Object { $exts -contains $_.Extension -or $_.Name -like 'site.webmanifest' } |
            Select-String -Pattern $pattern -AllMatches -ErrorAction SilentlyContinue
    }
}
foreach ($e in @('.env','.env.example')) {
    $f = Join-Path $root $e
    if (Test-Path $f) { $results += Select-String -Path $f -Pattern $pattern -AllMatches -ErrorAction SilentlyContinue }
}

$lines.Add("Total hits: **$($results.Count)**")
$lines.Add('')

$grouped = $results | Group-Object Path | Sort-Object Name
foreach ($g in $grouped) {
    $rel = $g.Name.Replace($root, '').TrimStart('\')
    $lines.Add("### $rel")
    $lines.Add('')
    foreach ($r in $g.Group) {
        $ln = "$($r.Line)".Trim()
        $lines.Add("- Line $($r.LineNumber): $ln")
    }
    $lines.Add('')
}

$lines.Add('---')
$lines.Add('')
$lines.Add('## 2. Current theme tokens (resources/css/app.css)')
$lines.Add('')

$cssPath = Join-Path $root 'resources\css\app.css'
if (Test-Path $cssPath) {
    $css = Get-Content $cssPath -Raw
    if ($css -match '(?s)@theme\s*\{(.*?)\}') {
        $lines.Add('~~~css')
        $lines.Add($Matches[1].Trim())
        $lines.Add('~~~')
    } else {
        $lines.Add('_No @theme block found._')
    }
} else {
    $lines.Add('_app.css not found._')
}
$lines.Add('')

$lines.Add('---')
$lines.Add('')
$lines.Add('## 3. Layouts inventory')
$lines.Add('')
$layDir = Join-Path $root 'resources\views\components\layouts'
if (Test-Path $layDir) {
    Get-ChildItem $layDir -Recurse -File | Sort-Object FullName | ForEach-Object {
        $rel = $_.FullName.Replace($root,'').TrimStart('\')
        $lines.Add("- $rel")
    }
} else { $lines.Add('_No layouts directory._') }
$lines.Add('')

$lines.Add('---')
$lines.Add('')
$lines.Add('## 4. Blade components inventory')
$lines.Add('')
$compDir = Join-Path $root 'resources\views\components'
if (Test-Path $compDir) {
    Get-ChildItem $compDir -Recurse -File | Where-Object { $_.FullName -notmatch 'layouts' } | Sort-Object FullName | ForEach-Object {
        $rel = $_.FullName.Replace($root,'').TrimStart('\')
        $lines.Add("- $rel")
    }
} else { $lines.Add('_No components directory._') }
$lines.Add('')

$lines.Add('---')
$lines.Add('')
$lines.Add('## 5. Package versions (composer.json / package.json)')
$lines.Add('')

$composer = Join-Path $root 'composer.json'
if (Test-Path $composer) {
    $lines.Add('### composer.json (require)')
    $lines.Add('~~~json')
    $json = Get-Content $composer -Raw | ConvertFrom-Json
    $json.require.PSObject.Properties | ForEach-Object { $lines.Add("  " + $_.Name + " : " + $_.Value) }
    $lines.Add('~~~')
    $lines.Add('')
}
$pkg = Join-Path $root 'package.json'
if (Test-Path $pkg) {
    $lines.Add('### package.json')
    $lines.Add('~~~json')
    $j = Get-Content $pkg -Raw | ConvertFrom-Json
    if ($j.dependencies) { $j.dependencies.PSObject.Properties | ForEach-Object { $lines.Add("  " + $_.Name + " : " + $_.Value) } }
    if ($j.devDependencies) { $j.devDependencies.PSObject.Properties | ForEach-Object { $lines.Add("  " + $_.Name + " : " + $_.Value) } }
    $lines.Add('~~~')
    $lines.Add('')
}

[System.IO.File]::WriteAllText($out, ($lines -join "`n"), $utf8NoBom)
Write-Host "OK $out : $((Get-Item $out).Length) bytes, $($results.Count) brand hits" -ForegroundColor Green