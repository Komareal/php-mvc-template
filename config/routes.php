<?php
use Controllers\Auth\LoginControllerA;
use Controllers\Auth\RegisterControllerA;
use Controllers\HomeControllerA;
use Core\Route;

/**
 * Parametrized route example
 *
 * Route::get('product', '/product/{id}', ProductController::class, 'index');
 */
Route::get('home', '/', HomeControllerA::class, 'index');

Route::get('register', '/register', RegisterControllerA::class, 'index');
Route::post('register.submit', '/register/send', RegisterControllerA::class, 'register');

Route::get('login', '/login', LoginControllerA::class, 'index');
Route::post('login.submit', '/login/send', LoginControllerA::class, 'login');

Route::get('logout', '/logout', LoginControllerA::class, 'logout');

