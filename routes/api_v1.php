<?php

use App\Http\Controllers\Api\V1\CrmFileController;
use App\Http\Controllers\Api\V1\CrmFileShareLogController;
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
    Route::group(['prefix' => '{uuid}'], function() {
        Route::get('/', [CrmFileController::class, 'crmFileDownload'])
            ->name('crm_file_download')->middleware('throttle:1000,1');

        Route::get('/share', [CrmFileShareLogController::class, 'crmShareFile'])
            ->name('crm_share_file');
        Route::get('/unshare', [CrmFileShareLogController::class, 'crmUnShareFile'])
            ->name('crm_unshare_file');

    });
    Route::post('/', [CrmFileController::class, 'crmFileUpload'])
        ->name('crm_file_upload');
});

Route::group(['prefix' => 'crm-files', 'middleware' => [
    'crm.service'
], 'as' => 'file_manager'], function () {
    Route::group(['prefix' => '{uuid}'], function() {
        Route::delete('/', [CrmFileController::class, 'deleteFile'])
            ->name('crm_file_delete');

    });
});
