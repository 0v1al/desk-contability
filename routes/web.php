<?php

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChatController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
})->name('home');

//! auth routes
Route::group([], function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');

    Route::post('/login', [LoginController::class, 'login'])
        ->name('login.login');

    Route::get('/login/logout', [LoginController::class, 'logout'])
        ->name('login.logout');
});

//! normal user routes
Route::group(['prefix' => 'user'], function () {
    Route::post('/upload', [UserController::class, 'upload_file'])
        ->name('user.upload_file');

    Route::get('/files', [UserController::class, 'user_files'])
        ->name('user.user_files');

    Route::get('/files/admin', [UserController::class, 'admin_user_files'])
        ->name('user.admin_user_files');

    Route::get('/files/received', [UserController::class, 'received_files'])
        ->name('user.received_files');

    Route::delete('/file/delete', [UserController::class, 'delete_user_file'])
        ->name('user.delete_user_file');

    Route::get('/file/view', [UserController::class, 'view_user_file'])
        ->name('user.view_user_file');

    Route::get('/file/download', [UserController::class, 'download_user_file'])
        ->name('user.download_user_file');
});

//! admins user routes
Route::prefix('admin')->group(function () {

    //? show the page for registering a client
    Route::get('/register', [RegisterController::class, 'index'])
        ->name('admin.register');

    //? register the client
    Route::post('/register', [RegisterController::class, 'register'])
        ->name('admin.register_user');

    //? show the page that display the clients
    Route::get('/users', [AdminController::class, 'index'])
        ->name('admin.users');

    //? show the page with the files of a certain client
    Route::get('/user/files/{id}', [AdminController::class, 'user_files'])
        ->name('admin.user_files');

    //? delete the file of a certain user
    Route::delete('/user/file', [AdminController::class, 'delete_user_file'])
        ->name('admin.delete_user_file');

    Route::get('/user/file/view', [AdminController::class, 'view_user_file'])
        ->name('admin.view_user_file');

    Route::get('/user/file/download', [AdminController::class, 'download_user_file'])
        ->name('admin.download_user_file');

    Route::get('/user/edit', [AdminController::class, 'edit_user'])
        ->name('admin.edit_user');

    Route::post('/user/update', [AdminController::class, 'update_user'])
        ->name('admin.update_user');

    Route::delete('/user/delete/account', [AdminController::class, 'delete_user_account'])
        ->name('admin.delete_user_account');

    Route::get('/user/file/upload', [AdminController::class, 'index_upload_user_file'])
        ->name('admin.index_upload_user_file');

    Route::post('/user/file/upload', [AdminController::class, 'upload_user_file'])
        ->name('admin.upload_user_file');
});

//! chat routes
Route::group(['prefix' => 'chat'], function () {
    Route::get('/message', [ChatController::class, 'index'])
        ->name('chat.message');

    Route::get('/messages', [ChatController::class, 'get_messages'])
        ->name('chat.messages');

    Route::post('/messages', [ChatController::class, 'send_message']);
});
