<?php declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::post('/user/auth', 'User@authCredentials')->name('user.auth.credentials');
Route::post('/user', 'User@signup')->name('user.signup');
Route::post('/user/confirm', 'User@confirmStart')->name('user.confirm.start');
Route::post('/user/confirm/{hash}', 'User@confirmFinish')->name('user.confirm.finish');
Route::post('/user/password/reset', 'User@PasswordResetStart')->name('user.password.reset.start');
Route::post('/user/password/reset/{hash}', 'User@PasswordResetFinish')->name('user.password.reset.finish');

Route::get('/country', 'Country@index')->name('country.index');
Route::get('/state/{country_id}', 'State@index')->name('state.index');

Route::group(['middleware' => 'user.refresh'], static function () {
    Route::get('/user/auth/refresh', 'User@authRefresh')->name('user.auth.refresh');
});

Route::group(['middleware' => 'user'], static function () {
    Route::get('/user', 'User@detail')->name('user.detail');
    Route::get('/user/auth/logout', 'User@authLogout')->name('user.auth.logout');
});

Route::group(['middleware' => ['user', 'user.confirm']], static function () {
    Route::get('/company', 'Company@detail')->name('company.detail');
    Route::patch('/company', 'Company@update')->name('company.update');

    Route::patch('/user', 'User@updateProfile')->name('user.update.profile');
    Route::patch('/user/password', 'User@updatePassword')->name('user.update.password');
});

Route::group(['middleware' => ['user', 'user.confirm', 'user.company']], static function () {
    Route::get('/client', 'Client@index')->name('client.index');
    Route::get('/client/enabled', 'Client@enabled')->name('client.enabled');
    Route::get('/client/export', 'Client@export')->name('client.export');
    Route::get('/client/{id}', 'Client@detail')->name('client.detail');
    Route::post('/client', 'Client@create')->name('client.create');
    Route::patch('/client/{id}', 'Client@update')->name('client.update');

    Route::get('/client-address/enabled', 'ClientAddress@enabled')->name('client-address.enabled');
    Route::get('/client-address/{client_id}', 'ClientAddress@client')->name('client-address.client');
    Route::get('/client-address/{client_id}/enabled', 'ClientAddress@clientEnabled')->name('client-address.client-enabled');
    Route::post('/client-address/{client_id}', 'ClientAddress@clientCreate')->name('client-address.client-create');
    Route::patch('/client-address/{client_id}/{id}', 'ClientAddress@clientUpdate')->name('client-address.client-update');

    Route::get('/discount', 'Discount@index')->name('discount.index');
    Route::get('/discount/enabled', 'Discount@enabled')->name('discount.enabled');
    Route::get('/discount/{id}', 'Discount@detail')->name('discount.detail');
    Route::post('/discount', 'Discount@create')->name('discount.create');
    Route::patch('/discount/{id}', 'Discount@update')->name('discount.update');

    Route::get('/invoice', 'Invoice@index')->name('invoice.index');
    Route::get('/invoice/export', 'Invoice@export')->name('invoice.export');
    Route::get('/invoice/{id}', 'Invoice@detail')->name('invoice.detail');
    Route::post('/invoice', 'Invoice@create')->name('invoice.create');
    Route::patch('/invoice/{id}', 'Invoice@update')->name('invoice.update');

    Route::get('/invoice-file/{id}', 'InvoiceFile@detail')->name('invoice-file.detail');
    Route::get('/invoice-file/{id}/download', 'InvoiceFile@download')->name('invoice-file.download');
    Route::get('/invoice-file/invoice/{invoice_id}/main', 'InvoiceFile@main')->name('invoice-file.main');
    Route::post('/invoice-file/invoice/{invoice_id}', 'InvoiceFile@create')->name('invoice-file.create');
    Route::delete('/invoice-file/{id}', 'InvoiceFile@delete')->name('invoice-file.delete');

    Route::get('/invoice-serie', 'InvoiceSerie@index')->name('invoice-serie.index');
    Route::get('/invoice-serie/enabled', 'InvoiceSerie@enabled')->name('invoice-serie.enabled');
    Route::get('/invoice-serie/{id}', 'InvoiceSerie@detail')->name('invoice-serie.detail');
    Route::get('/invoice-serie/{id}/css', 'InvoiceSerie@css')->name('invoice-serie.css');
    Route::post('/invoice-serie', 'InvoiceSerie@create')->name('invoice-serie.create');
    Route::post('/invoice-serie/{id}/css', 'InvoiceSerie@cssPreview')->name('invoice-serie.css.preview');
    Route::patch('/invoice-serie/{id}', 'InvoiceSerie@update')->name('invoice-serie.update');
    Route::patch('/invoice-serie/{id}/css', 'InvoiceSerie@cssUpdate')->name('invoice-serie.css.update');

    Route::get('/invoice-status', 'InvoiceStatus@index')->name('invoice-status.index');
    Route::get('/invoice-status/enabled', 'InvoiceStatus@enabled')->name('invoice-status.enabled');
    Route::get('/invoice-status/{id}', 'InvoiceStatus@detail')->name('invoice-status.detail');
    Route::post('/invoice-status', 'InvoiceStatus@create')->name('invoice-status.create');
    Route::patch('/invoice-status/{id}', 'InvoiceStatus@update')->name('invoice-status.update');

    Route::get('/payment', 'Payment@index')->name('payment.index');
    Route::get('/payment/enabled', 'Payment@enabled')->name('payment.enabled');
    Route::get('/payment/{id}', 'Payment@detail')->name('payment.detail');
    Route::post('/payment', 'Payment@create')->name('payment.create');
    Route::patch('/payment/{id}', 'Payment@update')->name('payment.update');

    Route::get('/product', 'Product@index')->name('product.index');
    Route::get('/product/enabled', 'Product@enabled')->name('product.enabled');
    Route::get('/product/export', 'Product@export')->name('product.export');
    Route::get('/product/{id}', 'Product@detail')->name('product.detail');
    Route::post('/product', 'Product@create')->name('product.create');
    Route::patch('/product/{id}', 'Product@update')->name('product.update');

    Route::get('/shipping', 'Shipping@index')->name('shipping.index');
    Route::get('/shipping/enabled', 'Shipping@enabled')->name('shipping.enabled');
    Route::get('/shipping/{id}', 'Shipping@detail')->name('shipping.detail');
    Route::post('/shipping', 'Shipping@create')->name('shipping.create');
    Route::patch('/shipping/{id}', 'Shipping@update')->name('shipping.update');

    Route::get('/tax', 'Tax@index')->name('tax.index');
    Route::get('/tax/enabled', 'Tax@enabled')->name('tax.enabled');
    Route::get('/tax/{id}', 'Tax@detail')->name('tax.detail');
    Route::post('/tax', 'Tax@create')->name('tax.create');
    Route::patch('/tax/{id}', 'Tax@update')->name('tax.update');

    Route::get('/w/invoice', 'W@invoiceCreate')->name('w.invoice.create');
    Route::get('/w/invoice/{id}', 'W@invoiceUpdate')->name('w.invoice.update');
});
