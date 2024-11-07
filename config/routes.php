<?php
use Controllers\Auth\LoginController;
use Controllers\Auth\RegisterController;
use Controllers\HomeController;
use Core\Route;

/**
 * Parametrized route example
 *
 * Route::get('product', '/product/{id}', ProductController::class, 'index');
 */
Route::get('home', '/', HomeController::class, 'index');

Route::get('register', '/register', RegisterController::class, 'index');
Route::post('register.submit', '/register/send', RegisterController::class, 'register');

Route::get('login', '/login', LoginController::class, 'index');
Route::post('login.submit', '/login/send', LoginController::class, 'login');

Route::get('logout', '/logout', LoginController::class, 'logout');

