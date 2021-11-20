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
        $this->storageFilesUrl = config('app.url') . '/index.php/s/';
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
            $shareToken = $shareToken->value('share_token');
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
        $cloudFile = $this->cloud_db->table(CrmFileShareLog::CLOUD_TABLE_FILECACHE)
            ->where([
                ['name', '=', $file->file_original_name],
                ['path', '=', cloud_file_url($file->file_source)]
            ])->first();

        if (is_null($cloudFile)) {
            throw new CloudFileNotFoundException($file->file_original_name);
        }
        // share file url http://l-storage.standpoint.com.ua/index.php/s/rpabCTfH7EoENjW4P92QRGCnA
        $token = Str::random(25);
        $shareFileId = $this->cloud_db->table(CrmFileShareLog::CLOUD_TABLE_SHARE)->insertGetId(
            [
                'share_type' => CrmFileShareLog::TYPE_LINK,
                'share_with' => null,
                'uid_owner' => CrmFileShareLog::CLOUD_UID_OWNER,
                'uid_initiator' => CrmFileShareLog::CLOUD_UID_OWNER,
                'parent' => null,
                'item_type' => 'file',
                'item_source' => $cloudFile->fileid,
                'file_source' => $cloudFile->fileid,
                'file_target' => "/$cloudFile->name",
                'permissions' => CrmFileShareLog::CLOUD_FILE_PERMISSION,
                'stime' => time(),
                'token' => $token,
            ]
        );
        if ($shareFileId) {
            //        dd($shareFileId); //записать в базу id для дешарінгу
            $this->saveSharedFile($file, $user, $shareFileId, $token);
        }
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
