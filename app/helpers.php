<?php
use App\Models\CrmUser;

if (!function_exists('files_storage')) {
    function files_storage($path): string
    {
        return storage_path(CrmUser::NEXTCLOUD_STORAGE . "/". CrmUser::USER_FOLDER ."/files/" . $path);
    }
}
