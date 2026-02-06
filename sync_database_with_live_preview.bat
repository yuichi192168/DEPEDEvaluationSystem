@echo off
REM Batch script to run the live preview calculation sync
REM This applies the working live preview functions to the database

cd /d "%~dp0"

echo.
echo ╔══════════════════════════════════════════════════════════════╗
echo ║  Apply Live Preview Calculation to Database                 ║
echo ║  Syncing all records to match live preview formula          ║
echo ╚══════════════════════════════════════════════════════════════╝
echo.

php apply_live_preview_to_database.php

pause
