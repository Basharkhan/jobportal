php artisan db:seed --class=DatabaseSeeder
php artisan cache:clear
php artisan route:cache

working on ... Route::patch('/applications/{id}/status', [ApplicationController::class, 'updateApplicationStatus']); 