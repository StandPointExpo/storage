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

    public function getCrmFile(string $fileUUID)
    {
        return CrmFile::where('uuid', $fileUUID)->firstOrFail();
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

    public function store(Collection $data, $fileName, $filePath, CrmUser $user )
    {
        //
    }
}
