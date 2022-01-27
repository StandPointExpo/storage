<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CrmToken;
use App\Models\CrmUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;
use Illuminate\Http\Client\RequestException;
use Exception;
use Illuminate\Support\Facades\Log;

class CrmUserController extends Controller
{

    /**
     * From request give token and get auth user from auth server
     * @param Request $request
     * @return String
     */
    public function getCrmToken(Request $request): ?string
    {
        $token = $request->bearerToken();
        Log::debug($request->all());
        if (!$token) {
            return null;
        }
        return $token;
    }

    /**
     * From request give token and get auth user from auth server
     * @param Request $request
     * @return String
     */
    public function getCrmUserToken(Request $request): ?string
    {
        $data = CrmToken::where('token', '=', $this->getCrmToken($request))->first();
        if (!$data) {
            return null;
        }
        return $data->token;
    }

    /**
     * From request give token and get auth user from auth server
     * @param $token String
     * @return Collection
     */
    public function getCrmUser(string $token): ?Collection
    {

        $response = Http::withToken($token)->get(config('app.auth_service_api') . '/api/auth/user');
        if ($response->ok()) {
            $result = $response->collect();
            return collect($result->get('data'));
        }
        return collect();
    }


    /**
     *
     * @param Request $request [$request description]
     * @return void [type]             [return description]
     */
    public function saveCrmUser(Request $request)
    {
        $token = $this->getCrmToken($request);
        if ($token) {
            $this->setCrmUser($this->getCrmUser($token), $token);
        }
    }

    /**
     *
     * @param Collection $user
     * @param $token
     */

    public function setCrmUser(Collection $user, $token)
    {
        if ($user->has('id')) {
            $crmUser = CrmUser::updateOrCreate(
                [
                    'id' => $user->get('id'),
                ],
                [
                    'id' => $user->get('id'),
                    'name' => $user->get('name'),
                    'email' => $user->get('email'),
                ]);
            $crmUser->crmToken()->updateOrCreate([
                'token' => $token
            ],
                [
                    'crm_user_id' => $crmUser->id,
                    'token' => $token,
                    'last_used_at'  => Carbon::now()
                ]
            );
        }
    }
}
