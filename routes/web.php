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

Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', 'HomeController@index')->name('dashboard');

    Route::group(['middleware' => ['role:admin'], 'prefix' => 'admin'], function () {
        Route::group(['prefix' => 'category'], function () {
            Route::get('/', 'CategoryController@index')->name('admin.category.index');
            Route::get('/create', 'CategoryController@create')->name('admin.category.create');
            Route::post('/', 'CategoryController@store')->name('admin.category.store');
        });
        // Preset Template Routes
        Route::group(['prefix' => 'preset'], function () {
            Route::get('/', 'PresetTemplateController@index')->name('admin.preset.index');
            Route::get('/create', 'PresetTemplateController@create')->name('admin.preset.create');
            Route::post('/', 'PresetTemplateController@store')->name('admin.preset.store');
            Route::get('{uuid}/', 'PresetTemplateController@show')->name('admin.preset.show');
            Route::put('{uuid}/', 'PresetTemplateController@update')->name('admin.preset.update');
            Route::get('{uuid}/design', 'PresetTemplateController@design')->name('admin.preset.design');
        });
    });

    // brand routes
    Route::get('brands/', 'BrandController@index')->name('brand.index');
    Route::get('brands/new-brand', 'BrandController@create')->name('brand.create');
    Route::post('brands/new-brand', 'BrandController@store')->name('brand.store');
    Route::get('brands/{slug}', 'BrandController@show')->name('brand.show');
    Route::get('brands/{slug}/edit', 'BrandController@edit')->name('brand.edit');
    Route::put('brands/{slug}', 'BrandController@update')->name('brand.update');
    Route::delete('brand/{slug}', 'BrandController@destroy')->name('brand.destroy');

    // Sequence routes
    Route::group(['prefix' => 'brands/{slug}/sequence'], function () {
        Route::get('/', 'SequenceController@create')->name('seq.create');
        Route::get('/{uuid}', 'SequenceController@show')->name('seq.show');
    });

    // campaigns routes
    Route::get('brands/{slug}/campaign/create', 'CampaignsController@create')->name('campaign.create'); // create campaign
    Route::post('brands/{slug}/campaign/create', 'CampaignsController@store')->name('campaign.store'); // store campaign

    Route::get('brands/{slug}/campaign/{uuid}/info', 'CampaignsController@storeInfo')->name('campaign.store.info'); // create Info
    Route::put('brands/{slug}/campaign/{uuid}/info', 'CampaignsController@updateInfo')->name('campaign.update.info'); // store Info

    Route::get('brands/{slug}/campaign/{uuid}/show', 'CampaignsController@show')->name('campaign.show'); // show campaign 

    Route::get('brands/{slug}/campaign/{uuid}/design', 'CampaignsController@designPage')->name('campaign.design.page'); // design campaign
    Route::put('brands/{slug}/campaign/{uuid}/design', 'CampaignsController@designUpdate')->name('campaign.design.update'); // design campaign

    Route::get('brands/{slug}/campaign/{uuid}/schedule', 'CampaignsController@schedulePage')->name('campaign.schedule.page'); // Schedule page
    Route::post('brands/{slug}/campaign/{uuid}/schedule', 'CampaignsController@scheduleUpdate')->name('campaign.schedule.update'); // Schedule page

    Route::get('campaign/{uuid}/email-sending', 'CampaignsController@sendCampaign')->name('campaign.send');

    // 

    // Route::get('brands/{slug}/campaign/{uuid}/content', 'CampaignsController@content')->name('campaign.content.create');
    // Route::post('brands/{slug}/campaign/{uuid}/update-content', 'CampaignsController@contentStore')->name('campaign.content.update_content');
    // Route::get('brands/{slug}/campaign/{uuid}/edit', 'CampaignsController@edit')->name('campaign.edit');
    // Route::put('brands/{slug}/campaign/{uuid}', 'CampaignsController@update')->name('campaign.update');

    Route::delete('brands/{slug}/campaign/{uuid}', 'CampaignsController@destroy')->name('campaign.destroy');

    Route::get('brands/{slug}/campaign/{uuid}/stats', 'CampaignsController@stats')->name('campaign.stats');
    Route::get('brands/{slug}/campaign/recent', 'CampaignsController@recentCampaign')->name('campaign.recent');
    Route::get('brands/{slug}/campaign/ongoing', 'CampaignsController@ongoingCampaign')->name('campaign.ongoing');
    Route::get('brands/{slug}/campaign/draft', 'CampaignsController@draftCampaign')->name('campaign.draft');
    Route::get('brands/{slug}/campaign/completed', 'CampaignsController@completedCampaign')->name('campaign.completed');

//    Scheduling a Camapign 
    Route::put('brands/{slug}/campaign/{uuid}/schedule', 'CampaignsController@storeSchedule')->name('campaign.schedule.store');

//    Adding and Removing list to Campaign
    Route::post('brands/{slug}/campaign/{uuid}/add-list', 'CampaignsController@addListsToCampaign')->name('campaign.add.lists');
    Route::post('brands/{slug}/campaign/{uuid}/remove-list', 'CampaignsController@removeListsToCampaign')->name('campaign.remove.lists');

//    SubsList Routes
    Route::get('subscribers', 'SubsListController@index')->name('subs.list.index');
    Route::post('subscribers', 'SubsListController@store')->name('subs.list.store');
    Route::get('subscribers/{uuid}', 'SubsListController@show')->name('subs.list.show');
    Route::get('subscribers/{uuid}/edit', 'SubsListController@edit')->name('subs.list.edit');
    Route::get('subscribers/{uuid}/upload', 'SubsListController@upload')->name('subs.list.upload');
    Route::post('subscribers/{uuid}/import', 'SubsListController@import')->name('subs.list.import');
    Route::put('subscribers/{uuid}', 'SubsListController@update')->name('subs.list.update');
    Route::delete('subscribers/{uuid}', 'SubsListController@destroy')->name('subs.list.destroy');

//    Subs Routes
    Route::get('subscribers/{uuid}/new-subscriber', 'SubsController@create')->name('subs.create');
    Route::post('subscribers/{uuid}/new-subscriber', 'SubsController@store')->name('subs.store');
    Route::get('subscribers/{uuid}/{email}', 'SubsController@show')->name('subs.show');
    Route::delete('subscribers/{uuid}/{email}', 'SubsController@destroy')->name('subs.destroy');

//    Email Routes
    Route::get('emails', 'EmailController@index')->name('email.index');
    Route::post('brands/{slug}/campaign/{uuid}/email-testing', 'EmailController@test')->name('email.test');
    
//    Email Templates Route
    Route::get('templates', 'TemplateController@index')->name('template.index');
    Route::get('templates/new', 'TemplateController@create')->name('template.create');
    Route::post('templates/new-template', 'TemplateController@store')->name('template.store');
    Route::get('templates/{id}', 'TemplateController@show')->name('template.show');
    Route::get('templates/{id}/edit', 'TemplateController@edit')->name('template.edit');
    Route::get('templates/{id}/get', 'TemplateController@getContent')->name('template.get.content');
    Route::put('templates/{uuid}', 'TemplateController@update')->name('template.update');
    Route::delete('templates/{uuid}', 'TemplateController@destroy')->name('template.destroy');

});


//  Tracking Routes

    Route::get('email/{uuid}/link/{url}', 'EmailController@linkTracker')->name('email.link.track');
    Route::get('email-open-tracking/{uuid}', 'EmailController@openTracking')->name('email.open.tracking');
    
    /**
     * @uuid Brand
     */
    Route::post('default/brand/mailing-list', 'BrandController@join')->name('subs.join');