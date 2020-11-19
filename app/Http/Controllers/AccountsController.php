<?php

namespace App\Http\Controllers;

use App\Models\Account;
use GuzzleHttp\Utils;
use Illuminate\Support\Facades\Redis;

class AccountsController extends Controller
{
    /**
     * Handles /api/account/{accountId} route.
     *
     * @param string $accountId
     *   Account ID.
     *
     * @return string.
     *   Returns json encoded string.
     */
    public function index($accountId)
    {
        if (!$accountId) {
            return $this->setResponse(false, "Missing account ID.");
        }

        $account = Account::where("id", "=", $accountId)->first();
        if (!$account) {
            return $this->setResponse(false, "Account not found.");
        }

        if ($account->active) {
            $data = request()->get('data') ? request()->get('data') : "";

            Redis::publish("tracking-server", Utils::jsonEncode([
                "account_id" => $account->id,
                "account_name" => $account->name,
                "data" => $data,
                "timestamp" => time(),
            ]));

            return $this->setResponse(true, "Data broadcasted successfully.");
        }

        return $this->setResponse(true, "");
    }

    /**
     * Returns a JSON encoded response.
     *
     * @param bool $success
     *   Either true or false-
     * @param string $message
     *   Message to be passed.
     *
     * @return string.
     *   Returns json encoded string.
     */
    public function setResponse($success, $message)
    {
        return Utils::jsonEncode([
            'success' => $success,
            'error' => $message,
        ]);
    }
}
