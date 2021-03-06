<?php declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::post('/form/contact', 'Form@contact')->name('form.contact');

Route::post('/user/auth', 'User@authCredentials')->name('user.auth.credentials');
Route::post('/user', 'User@signup')->name('user.signup');
Route::post('/user/confirm', 'User@confirmStart')->name('user.confirm.start');
Route::post('/user/confirm/{hash}', 'User@confirmFinish')->name('user.confirm.finish');
Route::post('/user/password/reset', 'User@passwordResetStart')->name('user.password.reset.start');
Route::post('/user/password/reset/{hash}', 'User@passwordResetFinish')->name('user.password.reset.finish');

Route::get('/cache/version', 'Cache@version')->name('cache.version');
Route::get('/configuration', 'Configuration@index')->name('configuration.index');

Route::get('/country', 'Country@index')->name('country.index');

Route::group(['middleware' => 'user.refresh'], static function () {
    Route::get('/user/auth/refresh', 'User@authRefresh')->name('user.auth.refresh');
});

Route::group(['middleware' => 'user'], static function () {
    Route::get('/notification/count', 'Notification@count')->name('notification.count');

    Route::get('/user', 'User@detail')->name('user.detail');
    Route::get('/user/auth/logout', 'User@authLogout')->name('user.auth.logout');
});

Route::group(['middleware' => ['user', 'user.confirm']], static function () {
    Route::get('/company', 'Company@detail')->name('company.detail');
    Route::post('/company', 'Company@create')->name('company.create');
    Route::patch('/company', 'Company@update')->name('company.update');

    Route::patch('/user', 'User@updateProfile')->name('user.update.profile');
    Route::patch('/user/password', 'User@updatePassword')->name('user.update.password');
});

Route::group(['middleware' => ['user', 'user.confirm', 'user.company']], static function () {
    Route::get('/client', 'Client@index')->name('client.index');
    Route::get('/client/export', 'Client@export')->name('client.export');
    Route::get('/client/{id}', 'Client@detail')->name('client.detail');
    Route::get('/client/w', 'Client@wCreate')->name('client.w.create');
    Route::get('/client/w/{id}', 'Client@wUpdate')->name('client.w.update');
    Route::post('/client', 'Client@create')->name('client.create');
    Route::patch('/client/{id}', 'Client@update')->name('client.update');
    Route::delete('/client/{id}', 'Client@delete')->name('client.delete');

    Route::get('/client-address/enabled', 'ClientAddress@enabled')->name('client-address.enabled');
    Route::get('/client-address/{client_id}', 'ClientAddress@client')->name('client-address.client');
    Route::get('/client-address/{client_id}/enabled', 'ClientAddress@clientEnabled')->name('client-address.client.enabled');
    Route::post('/client-address/{client_id}', 'ClientAddress@create')->name('client-address.create');
    Route::patch('/client-address/{id}', 'ClientAddress@update')->name('client-address.update');
    Route::delete('/client-address/{id}', 'ClientAddress@delete')->name('client-address.delete');

    Route::get('/discount', 'Discount@index')->name('discount.index');
    Route::get('/discount/enabled', 'Discount@enabled')->name('discount.enabled');
    Route::get('/discount/export', 'Discount@export')->name('discount.export');
    Route::get('/discount/{id}', 'Discount@detail')->name('discount.detail');
    Route::post('/discount', 'Discount@create')->name('discount.create');
    Route::patch('/discount/{id}', 'Discount@update')->name('discount.update');
    Route::delete('/discount/{id}', 'Discount@delete')->name('discount.delete');

    Route::get('/invoice', 'Invoice@index')->name('invoice.index');
    Route::get('/invoice/export', 'Invoice@export')->name('invoice.export');
    Route::get('/invoice/export/{format}/{filter}', 'Invoice@exportFormatFilter')->name('invoice.export.format.filter');
    Route::get('/invoice/{id}', 'Invoice@detail')->name('invoice.detail');
    Route::get('/invoice/w', 'Invoice@wIndex')->name('invoice.w.index');
    Route::get('/invoice/w/create', 'Invoice@wCreate')->name('invoice.w.create');
    Route::get('/invoice/w/{id}', 'Invoice@wUpdate')->name('invoice.w.update');
    Route::post('/invoice', 'Invoice@create')->name('invoice.create');
    Route::post('/invoice/{id}', 'Invoice@duplicate')->name('invoice.duplicate');
    Route::patch('/invoice/{id}', 'Invoice@update')->name('invoice.update');
    Route::patch('/invoice/{id}/paid', 'Invoice@paid')->name('invoice.paid');
    Route::delete('/invoice/{id}', 'Invoice@delete')->name('invoice.delete');

    Route::get('/invoice-file/{id}', 'InvoiceFile@detail')->name('invoice-file.detail');
    Route::get('/invoice-file/{id}/download', 'InvoiceFile@download')->name('invoice-file.download');
    Route::get('/invoice-file/invoice/{invoice_id}', 'InvoiceFile@invoice')->name('invoice-file.invoice');
    Route::get('/invoice-file/invoice/{invoice_id}/main', 'InvoiceFile@main')->name('invoice-file.main');
    Route::post('/invoice-file/invoice/{invoice_id}', 'InvoiceFile@create')->name('invoice-file.create');
    Route::delete('/invoice-file/{id}', 'InvoiceFile@delete')->name('invoice-file.delete');

    Route::get('/invoice-recurring', 'InvoiceRecurring@index')->name('invoice-recurring.index');
    Route::get('/invoice-recurring/enabled', 'InvoiceRecurring@enabled')->name('invoice-recurring.enabled');
    Route::get('/invoice-recurring/export', 'InvoiceRecurring@export')->name('invoice-recurring.export');
    Route::get('/invoice-recurring/{id}', 'InvoiceRecurring@detail')->name('invoice-recurring.detail');
    Route::post('/invoice-recurring', 'InvoiceRecurring@create')->name('invoice-recurring.create');
    Route::patch('/invoice-recurring/{id}', 'InvoiceRecurring@update')->name('invoice-recurring.update');
    Route::delete('/invoice-recurring/{id}', 'InvoiceRecurring@delete')->name('invoice-recurring.delete');

    Route::get('/invoice-serie', 'InvoiceSerie@index')->name('invoice-serie.index');
    Route::get('/invoice-serie/enabled', 'InvoiceSerie@enabled')->name('invoice-serie.enabled');
    Route::get('/invoice-serie/export', 'InvoiceSerie@export')->name('invoice-serie.export');
    Route::get('/invoice-serie/{id}', 'InvoiceSerie@detail')->name('invoice-serie.detail');
    Route::get('/invoice-serie/{id}/css', 'InvoiceSerie@css')->name('invoice-serie.css');
    Route::post('/invoice-serie', 'InvoiceSerie@create')->name('invoice-serie.create');
    Route::post('/invoice-serie/{id}/css', 'InvoiceSerie@cssPreview')->name('invoice-serie.css.preview');
    Route::patch('/invoice-serie/{id}', 'InvoiceSerie@update')->name('invoice-serie.update');
    Route::patch('/invoice-serie/{id}/css', 'InvoiceSerie@cssUpdate')->name('invoice-serie.css.update');
    Route::delete('/invoice-serie/{id}', 'InvoiceSerie@delete')->name('invoice-serie.delete');

    Route::get('/invoice-status', 'InvoiceStatus@index')->name('invoice-status.index');
    Route::get('/invoice-status/enabled', 'InvoiceStatus@enabled')->name('invoice-status.enabled');
    Route::get('/invoice-status/export', 'InvoiceStatus@export')->name('invoice-status.export');
    Route::get('/invoice-status/{id}', 'InvoiceStatus@detail')->name('invoice-status.detail');
    Route::post('/invoice-status', 'InvoiceStatus@create')->name('invoice-status.create');
    Route::patch('/invoice-status/{id}', 'InvoiceStatus@update')->name('invoice-status.update');
    Route::delete('/invoice-status/{id}', 'InvoiceStatus@delete')->name('invoice-status.delete');

    Route::get('/notification', 'Notification@index')->name('notification.index');
    Route::get('/notification/last', 'Notification@last')->name('notification.last');
    Route::patch('/notification', 'Notification@read')->name('notification.read');

    Route::get('/payment', 'Payment@index')->name('payment.index');
    Route::get('/payment/enabled', 'Payment@enabled')->name('payment.enabled');
    Route::get('/payment/export', 'Payment@export')->name('payment.export');
    Route::get('/payment/{id}', 'Payment@detail')->name('payment.detail');
    Route::post('/payment', 'Payment@create')->name('payment.create');
    Route::patch('/payment/{id}', 'Payment@update')->name('payment.update');
    Route::delete('/payment/{id}', 'Payment@delete')->name('payment.delete');

    Route::get('/product', 'Product@index')->name('product.index');
    Route::get('/product/enabled', 'Product@enabled')->name('product.enabled');
    Route::get('/product/export', 'Product@export')->name('product.export');
    Route::get('/product/{id}', 'Product@detail')->name('product.detail');
    Route::post('/product', 'Product@create')->name('product.create');
    Route::patch('/product/{id}', 'Product@update')->name('product.update');
    Route::delete('/product/{id}', 'Product@delete')->name('product.delete');

    Route::get('/shipping', 'Shipping@index')->name('shipping.index');
    Route::get('/shipping/enabled', 'Shipping@enabled')->name('shipping.enabled');
    Route::get('/shipping/export', 'Shipping@export')->name('shipping.export');
    Route::get('/shipping/{id}', 'Shipping@detail')->name('shipping.detail');
    Route::post('/shipping', 'Shipping@create')->name('shipping.create');
    Route::patch('/shipping/{id}', 'Shipping@update')->name('shipping.update');
    Route::delete('/shipping/{id}', 'Shipping@delete')->name('shipping.delete');

    Route::get('/tax', 'Tax@index')->name('tax.index');
    Route::get('/tax/enabled', 'Tax@enabled')->name('tax.enabled');
    Route::get('/tax/export', 'Tax@export')->name('tax.export');
    Route::get('/tax/{id}', 'Tax@detail')->name('tax.detail');
    Route::post('/tax', 'Tax@create')->name('tax.create');
    Route::patch('/tax/{id}', 'Tax@update')->name('tax.update');
    Route::delete('/tax/{id}', 'Tax@delete')->name('tax.delete');
});
