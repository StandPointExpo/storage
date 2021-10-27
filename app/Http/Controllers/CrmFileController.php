<?php

namespace App\Http\Controllers;

use App\Exceptions\Handler;
use App\Models\CrmFile;
use App\Repositories\CrmFileRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\Statusable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CrmFileController extends Controller
{
    use Statusable;

    private $repository;

    public function __construct()
    {
        dd(auth('crm.user')->user()); //TODO додати guard, отрмати активного юзера
        $this->user = auth('api')->user();

        $this->repository = new CrmFileRepository();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function crmFileUpload(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $file = new CrmFile();
            $file->uuid = $request->get('uuid');
            $file->publication = true;
            $file->file_name = $request->get('resumableFilename');
            $file->save();
            return $this->success($file);
        } catch (Handler $exception) {
            return $this->fail($exception);
        }
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
