<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

trait ChunkFileUploadable
{

    /**
     * @param Request $request
     * @param $fileFolder
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\UploadedFile
     */
    public function chunkUpload(Request $request)
    {
        try {
            $receiver = new FileReceiver("file", $request, HandlerFactory::classFromRequest($request));
            if ($receiver->isUploaded() === false) {
                throw new UploadMissingFileException();
            }

            $save = $receiver->receive();

            if ($save->isFinished()) {
                return $save->getFile();
            }

            $handler = $save->handler();

            return response()->json([
                "done" => $handler->getPercentageDone(),
                'status' => true
            ]);

        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            report($exception);
        }
    }
}
