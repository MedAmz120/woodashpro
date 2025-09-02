# JavaScript Validation Script for WoodDash Pro
# This script checks for common JavaScript errors

Write-Host "=== WoodDash Pro JavaScript Validation ===" -ForegroundColor Green

$dashboardFile = ".\templates\dashboard.php"

if (Test-Path $dashboardFile) {
    Write-Host "✓ Dashboard file found" -ForegroundColor Green
    
    # Check for duplicate variable declarations
    Write-Host "`nChecking for duplicate variable declarations..." -ForegroundColor Yellow
    
    $content = Get-Content $dashboardFile -Raw
    
    # Count 'let notifications' declarations
    $notificationsCount = ([regex]::Matches($content, "let notifications\s*=")).Count
    if ($notificationsCount -gt 1) {
        Write-Host "✗ Found $notificationsCount 'let notifications' declarations - should be 1" -ForegroundColor Red
    } else {
        Write-Host "✓ 'let notifications' declarations: $notificationsCount" -ForegroundColor Green
    }
    
    # Count 'let notificationSettings' declarations
    $settingsCount = ([regex]::Matches($content, "let notificationSettings\s*=")).Count
    if ($settingsCount -gt 1) {
        Write-Host "✗ Found $settingsCount 'let notificationSettings' declarations - should be 1" -ForegroundColor Red
    } else {
        Write-Host "✓ 'let notificationSettings' declarations: $settingsCount" -ForegroundColor Green
    }
    
    # Check for jQuery availability
    Write-Host "`nChecking jQuery references..." -ForegroundColor Yellow
    $jqueryChecks = ([regex]::Matches($content, "typeof jQuery")).Count
    Write-Host "✓ jQuery availability checks: $jqueryChecks" -ForegroundColor Green
    
    Write-Host "`n=== Validation Complete ===" -ForegroundColor Green
    Write-Host "The duplicate variable declaration error should now be fixed." -ForegroundColor Cyan
    
} else {
    Write-Host "✗ Dashboard file not found at: $dashboardFile" -ForegroundColor Red
}
