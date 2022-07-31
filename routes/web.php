<?php

/*
|--------------------------------------------------------------------------
| Web Routesf
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/',function(){
return view('welcome');
});

// Route::view('/','landing-page');
Route::get('/','LandingPageController@index')->name('landing-page');
Route::get('/shop','ShopController@index')->name('shop.index');
Route::get('/shop/{product}','ShopController@show')->name('shop.show');
Route::get('/cart','CartController@index')->name('cart.index');
Route::post('/cart','CartController@store')->name('cart.store');
Route::delete('/cart/{product}','CartController@destroy')->name('cart.destroy');
Route::patch('/cart/{product}','CartController@update')->name('cart.update');

Route::post('/cart/switchToSaveForLater/{product}','CartController@switchToSaveForLater')->name('cart.switchToSaveForLater');


Route::delete('/saveForLater/{product}','saveForLaterController@destroy')->name('saveForLater.destroy');
Route::post('/saveForLater/switchToCart/{product}','saveForLaterController@switchToCart')->
name('saveForLater.switchToCart');



Route::post('/coupon','CouponsController@store')->name('coupon.store');
Route::delete('/coupon','CouponsController@destroy')->name('coupon.destroy');




Route::get('empty',function (){
    Cart::instance('SaveForLater')->destroy(); 
    });

    Route::get('/checkout','CheckoutController@index')->name('checkout.index')->middleware('auth');   
    Route::post('/checkout','CheckoutController@store')->name('checkout.store'); 


    Route::get('/guestcheckout','CheckoutController@index')->name('guestcheckout.index');   



Route::get('/thankyou','ConfirmationController@index')->name('Confirmation.index');


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/search','ShopController@search')->name('search');
