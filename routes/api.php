<?php

use App\Http\Controllers\CrmFileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\CrmFile;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'crm-files', 'middleware' => [
    'throttle:1000,1',
    'crm.user'
], 'as' => 'file_manager'], function () {
    Route::get('/{uuid}', [CrmFileController::class, 'crmFileDownload'])
        ->name('crm_file_download')->middleware('throttle:1000,1');
    Route::post('/', [CrmFileController::class, 'crmFileUpload'])
        ->name('crm_file_upload')
        ->middleware('throttle:1000,1');
});
