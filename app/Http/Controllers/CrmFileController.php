<?php

namespace App\Http\Controllers;

use App\Models\CrmFile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CrmFileController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function crmFileUpload(Request $request): Response
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $fileName
     * @return Response
     */
    public function crmFileDownload($fileName): Response
    {
        $file = CrmFile::where('file_name', $fileName)->first();
        dd(files_storage($file->file_source));
    }
}


//# id, user_id, admin_id, fileable_type, fileable_id, publication, file_position, file_name, file_original_name, file_type, file_source, file_share, file_comment, extension, deleted_at, created_at, updated_at
//'1191', '6', NULL, 'projects', '338', '1', '1525243994774', 'attachment_1525243994774_6fb9c35d92a6c762994e2c4981efd9831558096086_.jpg', 'stand_front_left.jpg', 'image', 'public/repository/projects/338/Design/original/attachment_1525243994774_6fb9c35d92a6c762994e2c4981efd9831558096086_.jpg', '0', NULL, 'jpg', NULL, '2021-05-08 23:46:18', '2021-05-08 23:46:18'
