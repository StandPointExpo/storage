<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class CloudFileNotFoundException extends Exception
{
    public function __construct(string $path)
    {
        parent::__construct(sprintf('The file "%s" not found or not indexed on storage', $path));
    }

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function render()
    {
        return response([
            'error' => [
                'status' => 404,
                'message' => $this->message
            ]
        ], 404);
    }
}
