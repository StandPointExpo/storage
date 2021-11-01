<?php

namespace App\Exceptions;

use Exception;

class FileExtException extends Exception
{
    public function __construct(string $path)
    {
        parent::__construct(sprintf('The file "%s" extension does not support', $path));
    }
}
