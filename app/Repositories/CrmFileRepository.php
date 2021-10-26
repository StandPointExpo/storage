<?php
namespace App\Repositories;

use App\Models\User;
use App\Models\CrmFile;
/**
 * Crm File Repository
 */
class CrmFileRepository {

    public function getCrmFile ($fileName) {
        return CrmFile::where('file_name', $fileName)->firstOrFail();
    }
}
