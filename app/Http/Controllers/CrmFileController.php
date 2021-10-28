<?php

namespace App\Http\Controllers;

use App\Exceptions\Handler;
use App\Models\CrmFile;
use App\Repositories\CrmFileRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\Statusable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
//use Modules\Files\Http\Resources\FileResource;
use App\Traits\ChunkFileUploadable;
use Pion\Laravel\ChunkUpload\Exceptions\UploadFailedException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File as FileFoundation;

use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

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
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse | $fileProgress
     */
    public function crmFileUpload(Request $request)
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




////dd($request->all());
//            $fileType = explode('/', $request->get('resumableType'));
//            $arrExtFile = explode('.', $request->get('resumableFilename'));
//            $extension = end($arrExtFile);
//            $tmpFileName = Str::random(5) . '.' . $extension;
//
//
//
//
//            Storage::disk('public')->put($tmpFileName, 'Contents');
//            //Convert file to request file input
//            $tmpFile = new FileFoundation(storage_path('app/public/' . $tmpFileName));
//            $fileSource = new UploadedFile(
//                $tmpFile->getPathname(),
//                $tmpFile->getFilename(),
//                $tmpFile->getMimeType(),
//                0,
//                true // Mark it as test, since the file isn't from real HTTP POST.
//            );
//            $request->request->set('file', $fileSource);
//
////            $file = $this->repository->storeCrmFile(
////                $fileSource,
////                collect($request->only([
////                    'fileable_id',
////                    'fileable_type',
////                    'file_position',
////                    'folder_name',
////                    'task_parameter_id'])),
////                $this->user
////            );
////            Storage::disk('public')->delete($imageName);
////            return $this->success(FileResource::make($file));
//
//
////            $file = new CrmFile();
////            $file->uuid = $request->get('uuid');
////            $file->user_id = $this->user->id;
////            $file->publication = true;
////            $file->file_name = $request->get('resumableFilename');
////            $file->file_type = (isset($fileType[0])) ? $fileType[0] : 'document';
////            $file->extension = $extension;
////            $file->file_source = 'document';
////            $file->file_share = 'document';
////            $file->save();
////            return $this->success($file);
//
//            $filePath = 'test';
//            $fileProgress = $this->chunkUpload($request);
//            if ($fileProgress instanceof UploadedFile) {
//                $file = $this->repository->uploadFileData(
//                    $filePath,
//                    $fileProgress,
//                    collect($request->only([
//                        'fileable_id',
//                        'fileable_type',
//                        'file_position',
//                        'folder_name',
//                        'task_parameter_id'])),
//                    $this->user
//                );
//
//                return $this->success($file);
//            }
//            return $fileProgress;
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
    protected function saveFile(UploadedFile $file, Request $request) {
        $user_obj = auth()->user();
        $fileName = $this->createFilename($file);

        // Get file mime type
        $mime_original = $file->getMimeType();
        $mime = str_replace('/', '-', $mime_original);

        $folderDATE = $request->dataDATE;

        $folder  = $folderDATE;
        $filePath = "public/upload/medialibrary/id/folder/";
        $finalPath = storage_path("app/".$filePath);

        $fileSize = $file->getSize();
        // move the file name
        $file->move($finalPath, $fileName);

        $url_base = 'storage/upload/medialibrary/$user_obj/folderDATE}/'.$fileName;

        return response()->json([
            'path' => $filePath,
            'name' => $fileName,
            'mime_type' => $mime
        ]);
    }

    /**
     * Create unique filename for uploaded file
     * @param UploadedFile $file
     * @return string
     */
    protected function createFilename(UploadedFile $file) {
        $extension = $file->getClientOriginalExtension();
        $filename = str_replace(".".$extension, "", $file->getClientOriginalName()); // Filename without extension

        //delete timestamp from file name
        $temp_arr = explode('_', $filename);
        if ( isset($temp_arr[0]) ) unset($temp_arr[0]);
        $filename = implode('_', $temp_arr);

        //here you can manipulate with file name e.g. HASHED
        return $filename.".".$extension;
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
