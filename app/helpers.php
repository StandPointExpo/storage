<?php

if (!function_exists('files_storage')) {
    function files_storage($path): string
    {
        return storage_path('data/admin/' . $path);
    }
}
