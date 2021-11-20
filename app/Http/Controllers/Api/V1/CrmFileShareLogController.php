<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CrmFile;
use App\Repositories\CrmFileShareLogRepository;
use App\Traits\Statusable;
use Illuminate\Http\Request;
use App\Exceptions\Handler;
use Illuminate\Support\Facades\DB;

class CrmFileShareLogController extends Controller
{
    use Statusable;

    /**
     * @var CrmFileShareLogRepository
     */
    private $repository;
    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable|null
     */
    private $user;

    public function __construct(CrmFileShareLogRepository $crmFileShareLogRepository)
    {
        $this->repository = $crmFileShareLogRepository;
        $this->user = auth('crm-api')->user();
    }

    public function crmShareFile(string $fileUUID, Request $request)
    {
        try {
            return $this->success($this->repository->getShare($fileUUID, $this->user));
        } catch (Handler $exception) {
            return $this->fail($exception);
        }
    }
}
