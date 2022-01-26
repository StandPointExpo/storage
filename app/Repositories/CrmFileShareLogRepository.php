<?php

namespace App\Repositories;

use App\Exceptions\CloudFileNotFoundException;
use App\Exceptions\FileExtException;
use App\Exceptions\Handler;
use App\Models\CrmFileShareLog;
use App\Models\CrmUser;
use App\Models\CrmFile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Crm File Repository
 */
class CrmFileShareLogRepository
{
    /**
     * @var \Illuminate\Database\ConnectionInterface
     */
    private $cloud_db;
    /**
     * @var string
     */
    private $storageFilesUrl;

    public function __construct()
    {
        $this->cloud_db = DB::connection('cloud_mysql');
        $this->storageFilesUrl = config('app.url') . '/share/';
    }


    /**
     * @return string
     */
    public function getShare(string $fileUUID, CrmUser $user): string
    {
        $shareToken = CrmFileShareLog::where([
            ['crm_user_id', '=', $user->id],
            ['crm_file_uuid', '=', $fileUUID]
        ])
            ->first();

        if (is_null($shareToken)) {
            $shareToken = $this->makeShare($fileUUID, $user);
        } else {
            $shareToken = $shareToken->share_token;
        }
        return $this->storageFilesUrl . $shareToken;
    }

    /**
     * @throws FileExtException
     * @throws CloudFileNotFoundException
     */
    public function makeShare(string $fileUUID, CrmUser $user)
    {
        $file = CrmFile::where('uuid', '=', $fileUUID)->first();
        if (is_null($file)) {
            throw new \Illuminate\Contracts\Filesystem\FileNotFoundException('File not found!', 404);
        }

        // share file url http://l-storage.standpoint.com.ua/index.php/s/rpabCTfH7EoENjW4P92QRGCnA
        $token = Str::random(25);

        $this->saveSharedFile($file, $user, 1, $token);
        return $token;
    }

    public function saveSharedFile(CrmFile $file, CrmUser $user, int $shareFileId, string $token)
    {
        return CrmFileShareLog::create([
            'crm_user_id' => $user->id,
            'crm_file_uuid' => $file->uuid,
            'share_id' => $shareFileId,
            'share_token' => $token
        ]);
    }

}
