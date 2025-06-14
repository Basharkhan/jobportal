php artisan db:seed --class=DatabaseSeeder
php artisan cache:clear
php artisan route:cache
I was testing this api :
 Route::get('/users', [UserController::class, 'getAllUsers']);