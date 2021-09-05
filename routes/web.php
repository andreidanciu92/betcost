<?php

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
});

Route::get('login', 'AuthController@index');
Route::post('post-login', 'AuthController@postLogin');
Route::get('register', 'AuthController@register');
Route::post('post-register', 'AuthController@postRegister');
Route::get('dashboard', 'AuthController@dashboard');
Route::get('logout', 'AuthController@logout');

// ROUTE TO ADD MATCH
Route::post('matches/create', 'MatchesController@store')->name('add-match');

// ROUTE TO ADD BETS
Route::post('bets/create', 'BetsController@store')->name('add-bets');

// ROUTE TO SEE ALL BETS
Route::post('bets', 'BetsController@store')->name('get-all-bets');

// ROUTE TO SEE ALL BETS
Route::post('ranking', 'BetsController@store')->name('get-ranking');

// LIST MATCHES THAT CAN BE ENDED
Route::post('matches/update', 'MatchesController@updateMatches')->name('set-match-result');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


