@echo off
echo Starting DigiLoyalty Pro...
echo Server running at http://localhost:8000
php -d extension=pdo_sqlite -S 127.0.0.1:8000 -t public
pause
