$viewsDir = "c:\Users\ITU\p\ticket-manager\resources\views"

$files = Get-ChildItem -Path $viewsDir -Recurse -Filter "*.blade.php"

foreach ($file in $files) {
    $content = Get-Content -Path $file.FullName -Raw
    if ($content -match 'rose-') {
        # 1. Replace validation error rose border/ring with red
        $content = $content -replace 'border-rose-500', 'border-red-500'
        $content = $content -replace 'focus:border-rose-500', 'focus:border-red-500'
        $content = $content -replace 'focus:ring-rose-500', 'focus:ring-red-500'
        
        # 2. Replace all other rose- theme tokens with emerald-
        $content = $content -replace 'rose-', 'emerald-'
        
        [System.IO.File]::WriteAllText($file.FullName, $content, [System.Text.Encoding]::UTF8)
        Write-Host "Updated $($file.Name)"
    }
}

Write-Host "Emerald theme conversion complete on main!"
