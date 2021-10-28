<?php

namespace App\Repositories;

use App\Models\CrmUser;
use App\Models\CrmFile;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Crm File Repository
 */
class CrmFileRepository
{

    public function getCrmFile($fileName)
    {
        return CrmFile::where('file_name', $fileName)->firstOrFail();
    }

    public function uploadFileData($filePath,
                                   UploadedFile $file,
                                   Collection $data,
                                   CrmUser $user)
    {
        $fileName = 'fileName';
        $fileUpload = Storage::disk('public')->putFileAs($filePath, $file, $fileName);
        return null;
    }

    public function storeCrmFile(Request $request)
    {

        $file = new CrmFile();
        $file->uuid = $request->get('uuid');
        $file->user_id = $this->user->id;
        $file->publication = true;
        $file->file_name = $request->get('resumableFilename');
        $file->file_type = (isset($fileType[0])) ? $fileType[0] : 'document';
        $file->extension = $extension;
        $file->file_source = 'document';
        $file->file_share = 'document';
        $file->save();

        return $file;
    }
}
