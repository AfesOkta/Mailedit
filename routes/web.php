<?php

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth']], function () {
//    brand routes
    Route::get('brands/new-brand', 'BrandController@create')->name('brand.create');
    Route::post('brands/new-brand', 'BrandController@store')->name('brand.store');
    Route::get('brands/{slug}', 'BrandController@show')->name('brand.show');
    Route::get('brands/{slug}/edit', 'BrandController@edit')->name('brand.edit');
    Route::put('brands/{slug}', 'BrandController@update')->name('brand.update');

//    campaigns routes
    Route::get('brands/{slug}/new-campaign', 'CampaignsController@create')->name('campaign.create');
    Route::post('brands/{slug}/new-campaign', 'CampaignsController@store')->name('campaign.store');
    Route::get('brands/{slug}/campaign/{uuid}', 'CampaignsController@show')->name('campaign.show');
    Route::get('brands/{slug}/campaign/{uuid}/edit', 'CampaignsController@edit')->name('campaign.edit');
    Route::put('brands/{slug}/campaign/{uuid}', 'CampaignsController@update')->name('campaign.update');

//    SubsList Routes
    Route::get('subscribers', 'SubsListController@index')->name('subs.list.index');
    Route::post('subscribers', 'SubsListController@store')->name('subs.list.store');
    Route::get('subscribers/{uuid}', 'SubsListController@show')->name('subs.list.show');
    Route::get('subscribers/{uuid}/edit', 'SubsListController@edit')->name('subs.list.edit');

//    Subs Routes
    Route::get('subscribers/{uuid}/new-subscriber', 'SubsController@create')->name('subs.create');
    Route::post('subscribers/{uuid}/new-subscriber', 'SubsController@store')->name('subs.store');
    Route::get('subscribers/{uuid}/{email}', 'SubsController@show')->name('subs.show');
});