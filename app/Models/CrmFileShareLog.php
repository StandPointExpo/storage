<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmFileShareLog extends Model
{
    use HasFactory;

    public const TYPE_USER = 0;
    public const TYPE_LINK = 3;
    public const CLOUD_TABLE_FILECACHE = 'oc_filecache';
    public const CLOUD_TABLE_SHARE = 'oc_share';
    public const CLOUD_UID_OWNER = 'admin';
    public const CLOUD_FILE_PERMISSION = 17;

    protected $fillable = [
        'crm_user_id',
        'crm_file_uuid',
        'share_id',
        'share_token'
    ];

}
