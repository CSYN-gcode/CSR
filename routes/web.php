<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\CustomersController;
// use App\Http\Controllers\SituationsController;
// use App\Http\Controllers\PartsTroubleHistoryController;

use App\Http\Controllers\UserController;
// use App\Http\Controllers\DmrpqcTsController;
// use App\Http\Controllers\DiesetConditionController;
// use App\Http\Controllers\ExportPdfController;

use Illuminate\Support\Facades\Response;
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

// Route::get('/', function () {
//     return view('past_trouble_history_record');
// })->name('past_trouble_history_record');

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/users', function () {
    return view('users');
})->name('users');

Route::get('/customers', function () {
    return view('customers');
})->name('customers');

Route::get('/situations', function () {
    return view('situations');
})->name('situations');

Route::get('/csr_documents', function () {
    return view('csr_documents');
})->name('csr_documents');

// User CONTROLLER
Route::controller(UserController::class)->group(function () {
    Route::get('/view_users', 'viewUsersInfo')->name('view_users');
    Route::post('/add_users', 'addUsersInfo')->name('add_users');
    Route::get('/get_users_by_id', 'getUsersById')->name('get_users_by_id');
    Route::post('/update_users_status', 'updateUsersStatus')->name('update_users_status');
    Route::get('/get_rapidx_users', 'getRapidxUsers')->name('get_rapidx_users');
});

// Customers CONTROLLER
Route::controller(CustomersController::class)->group(function () {
    Route::get('/view_customers', 'viewCustomersInfo')->name('view_customers');
    Route::post('/add_customers', 'addCustomersInfo')->name('add_customers');
    Route::get('/get_customers_by_id', 'getCustomersById')->name('get_customers_by_id');
    Route::post('/update_customers_status', 'updateCustomersStatus')->name('update_customers_status');
    Route::get('/get_customers', 'getCustomers')->name('get_customers');
});

// CSR Document CONTROLLER
Route::controller(CsrDocumentController::class)->group(function () {
    Route::get('/view_parts_trouble_history', 'viewPartsTroubleHistoryInfo')->name('view_parts_trouble_history');
    Route::post('/add_parts_trouble_history', 'addPartsTroubleHistoryInfo')->name('add_parts_trouble_history');
    Route::get('/get_parts_trouble_history_by_id', 'getPartsTroubleHistoryById')->name('get_parts_trouble_history_by_id');
    Route::post('/update_parts_trouble_history_status', 'updatePartsTroubleHistoryStatus')->name('update_parts_trouble_history_status');
    Route::get('/download_file/{id}', 'downloadFile')->name('download_file');
    Route::get('/export_excel', 'exportExcel')->name('export_excel');
    Route::get('/get_count_no_of_occurrence', 'getCountOfNoOfOccurrence')->name('get_count_no_of_occurrence');
    Route::get('/get_device_name', 'getDeviceName')->name('get_device_name');
    Route::get('/get_users', 'getUsers')->name('get_users');
});

// // Situations CONTROLLER
// Route::controller(SituationsController::class)->group(function () {
//     Route::get('/view_situations', 'viewSituationsInfo')->name('view_situations');
//     Route::post('/add_situations', 'addSituationsInfo')->name('add_situations');
//     Route::get('/get_situations_by_id', 'getSituationsById')->name('get_situations_by_id');
//     Route::post('/update_situations_status', 'updateSituationsStatus')->name('update_situations_status');
//     Route::get('/get_situations', 'getSituations')->name('get_situations');
// });
