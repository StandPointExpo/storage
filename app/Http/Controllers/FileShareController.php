<?php

namespace App\Http\Controllers;

use App\Models\CrmFile;
use App\Models\CrmFileShareLog;
use Illuminate\Http\Request;

class FileShareController extends Controller
{
    public function share(string $token)
    {
        $fileData = CrmFileShareLog::where('share_token', '=', $token)
            ->firstOrFail();
        $file = CrmFile::where('uuid', '=', $fileData->crm_file_uuid)
        ->firstOrFail();
        return view('share', ['file' => $file]);
    }
}
