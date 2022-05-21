<?php

namespace App\Http\Controllers;

use App\Exceptions\LineBotException;
use App\Helpers\ApiResponse;
use App\LineBot\LineBotApi;
use App\Models\Billing;
use App\Models\LineAccount;
use App\Models\Redeem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class RedeemController extends Controller
{
    public function verify(LineBotApi $bot, Request $request)
    {
        try {
            $code = Redeem::where('code', $request->get('code'))->first();
            if ($code->isUsed) {
                throw new LineBotException('Code has been used!');
            }
            $line_account = LineAccount::where('userId', $request->get('userId'))->first();
            if (!$line_account) {
                $line_account =  new LineAccount($request->all());
            }
            $line_account->balance += $code->amount;
            $code->isUsed = true;
            $line_account->save();
            $code->save();
            $bot->pushMessage($line_account->userId, "Top Up " . "Rp" . number_format($code->amount, 2, ",", ".")  . " berasil!");
            return ApiResponse::build(200, 'Success', $code);
        } catch (\Exception $e) {
            return ApiResponse::build(500, 'Failed', $e->getMessage());
        }
    }

    public function generate(Request $request)
    {
        try {
            $redeem = new Redeem($request->all());
            $redeem->save();
            return ApiResponse::build(200, 'Success', $redeem);
        } catch (\Exception $e) {
            return ApiResponse::build(500, 'Failed', $e->getMessage()); //'failed to insert to database');
        }
    }
}
