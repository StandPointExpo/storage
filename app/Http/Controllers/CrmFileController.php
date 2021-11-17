<?php

namespace App\Http\Controllers;

use App\Exceptions\FileExtException;
use App\Exceptions\Handler;
use App\Http\Requests\CrmFileRequest;
use App\Models\CrmFile;
use App\Repositories\CrmFileRepository;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\Statusable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use App\Traits\ChunkFileUploadable;
use Pion\Laravel\ChunkUpload\Exceptions\UploadFailedException;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CrmFileController extends Controller
{
    use Statusable, ChunkFileUploadable;

    private $repository;

    public function __construct()
    {
        $this->user = auth('crm-api')->user();
        $this->repository = new CrmFileRepository();
    }

    /**
     * Handles the file upload
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws UploadMissingFileException
     * @throws \Pion\Laravel\ChunkUpload\Exceptions\UploadFailedException
     */
    public function crmFileUpload(CrmFileRequest $request): JsonResponse
    {
        try {

            // create the file receiver
            $receiver = new FileReceiver("file", $request, HandlerFactory::classFromRequest($request));

            // check if the upload is success, throw exception or return response you need
            if ($receiver->isUploaded() === false) {
                throw new UploadMissingFileException();
            }

            // receive the file
            $save = $receiver->receive();

            // check if the upload has finished (in chunk mode it will send smaller files)
            if ($save->isFinished()) {
                // save the file and return any response you need, current example uses `move` function. If you are
                // not using move, you need to manually delete the file by unlink($save->getFile()->getPathname())
                return $this->saveFile($save->getFile(), $request);
            }

            // we are in chunk mode, lets send the current progress
            /** @var AbstractHandler $handler */
            $handler = $save->handler();

            return response()->json([
                "done" => $handler->getPercentageDone(),
                'status' => true
            ]);
        } catch (Handler $exception) {
            return $this->fail($exception);
        } catch (UploadFailedException $e) {
        }
    }


    /**
     * Saves the file
     *
     * @param UploadedFile $file
     *
     * @return JsonResponse
     */
    protected function saveFile(UploadedFile $file, CrmFileRequest $request): JsonResponse
    {
        try {
            $projectFolder = $request->get('project_name');
            $foldersThree = json_decode($request->get('folders_tree'));

            $filePath = "{$projectFolder}/{$this->foldersThreeString($foldersThree)}/";
            $fileName = $this->createFilename($file, $filePath);

            //Storage::disk('nextcloud')->makeDirectory($filePath);
            $finalPath = files_storage("projects/{$filePath}");

            $fileSize = $file->getSize();
            // move the file name
            $file->move($finalPath, $fileName);

            return $this->success($this->storeFileData($request, $fileName, $filePath));
        } catch (Handler $exception) {
            return $this->fail($exception);
        }

    }

    /**
     * Store uploaded file data
     * @param CrmFileRequest $request
     * @param $fileName
     * @param $finalPath
     * @return CrmFile $file
     * @throws FileExtException
     */

    public function storeFileData(CrmFileRequest $request, $fileName, $finalPath): CrmFile
    {
        // Get file mime type
        $type = (new CrmFile)->getType($request->file);
        $file = new CrmFile();
        $file->uuid = $request->get('uuid');
        $file->user_id = $this->user->id;
        $file->publication = true;
        $file->file_original_name = $fileName;
        $file->file_type = $type;
        $file->extension = isset($request->file) ? $request->file->getClientOriginalExtension() : 'file';
        $file->file_source = $finalPath . $fileName;
        $file->save();
        return $file->load('user');
    }


    /**
     * Create valid folders three for uploaded file
     * @param array $folderThree
     * @return string
     */
    public function foldersThreeString(array $folderThree): string
    {
        return implode('/', $folderThree);
    }

    /**
     * Create unique filename for uploaded file
     * @param UploadedFile $file
     * @param $filePath
     * @return string
     */
    protected function createFilename(UploadedFile $file, $filePath): string
    {
        return $this->checkExistFileName($file, $file->getClientOriginalName(), $filePath);
    }

    public function checkExistFileName(UploadedFile $file, $fileName, $filePath)
    {
        $extension = $file->getClientOriginalExtension();
        if (Storage::disk('nextcloud')->exists("{$filePath}/$fileName")) {
            $clearFilename = trim(str_replace("." . $extension, "", $fileName)); // Filename without extension
            $newFileName = "{$clearFilename}-copy." . $extension;
            $fileName = $this->checkExistFileName($file, $newFileName, $filePath);
        }
        return $fileName;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param string $fileUUID
     * @return StreamedResponse
     * @throws FileExtException
     * @throws FileNotFoundException
     */
    public function crmFileDownload(string $fileUUID): StreamedResponse
    {
        try {
            $file = $this->repository->getCrmFile($fileUUID);
            $disk = Storage::disk('nextcloud');

            if(!$disk->exists($file->file_source)) {
                throw new FileNotFoundException('Path not found', 404);
            }
            $mimeType = $disk->mimeType($file->file_source);
            $headers = array('Content-Type' => $mimeType);
            return $disk->download($file->file_source, $file->file_original_name, $headers);
        } catch (Handler $exception) {
            return $this->fail($exception);
        }
    }
}
