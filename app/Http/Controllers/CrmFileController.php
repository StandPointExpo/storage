<?php

namespace App\Http\Controllers;

use App\Exceptions\Handler;
use App\Models\CrmFile;
use App\Repositories\CrmFileRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\Statusable;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CrmFileController extends Controller
{
    use Statusable;

    private $repository;

    public function __construct() {
        $this->repository = new CrmFileRepository();
    }
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
     * @return BinaryFileResponse
     */
    public function crmFileDownload($fileName)
    {
        try {
            $file = $this->repository->getCrmFile($fileName);
            $headers = array('Content-Type' => mime_content_type(files_storage($file->file_source)));
            return response()->download(files_storage($file->file_source), $file->file_original_name, $headers);
        } catch (Handler $exception) {
            return $this->fail($exception);
        }
    }
}
