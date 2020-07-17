<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ .'/../config/defined.php';

use App\Core\Route;
use App\Controllers\FrontController;

session_start();

Route::add('/', static function () {
    (new FrontController())->show();
}, 'get');

Route::add('/', static function () {
    (new FrontController())->formProcess();
}, 'post');

Route::add('/ajax', static function () {
    (new FrontController())->ajaxProcess();
}, 'post');

Route::run('/');
