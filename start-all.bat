@echo off
start "Frontend :8000" cmd /k php artisan serve --port=8000
start "User Service :8001" cmd /k php artisan serve --port=8001
start "Product Service :8002" cmd /k php artisan serve --port=8002
start "Inventory Service :8003" cmd /k php artisan serve --port=8003
start "Order Service :8004" cmd /k php artisan serve --port=8004