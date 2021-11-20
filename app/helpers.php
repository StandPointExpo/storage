<?php

use App\Models\CrmUser;

if (!function_exists('cloud_storage_url')) {
    function cloud_storage_url($path): string
    {
        return storage_path(CrmUser::NEXTCLOUD_STORAGE . "/" . CrmUser::USER_FOLDER . "/files/" . $path);
    }
}

if (!function_exists('cloud_file_url')) {
    /**
     * Make valid file url from file file_source to cloud oc_filecache table
     * @param string $path
     * @return string
     * */
    function cloud_file_url(string $path): string
    {
        return "files/projects/" . $path;
    }
}
