<?php

namespace App\Http\Controllers;

use App\Exceptions\LineBotException;
use App\Helpers\ApiResponse;
use App\LineBot\LineBotApi;
use App\Models\Billing;
use App\Models\LineAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;


class BillingController extends Controller
{
    public function verify(Request $request)
    {
        try {
            $data = Billing::join('line_accounts', 'billings.userId', '=', 'line_accounts.userId')
                ->where('token', $request->get('token'))->first();
            if ($data == null) {
                throw new LineBotException('Invalid Token');
            }
            return ApiResponse::build(200, 'Success', $data);
        } catch (\Exception $e) {
            // dd($e->getCode());
            return ApiResponse::build(500, 'Failed', $e->getMessage());
        }
    }
    public function start(LineBotApi $bot, Request $request)
    {
        try {
            $billing = new Billing($request->all());
            if ($billing->type == 0) {
                $billing->type = "regular";
            } else {
                $paket = 0;
                $billing->timeStart = Carbon::now();
                $billing->timeEnd = Carbon::now();
                switch ($billing->type) {
                    case 1:
                        $paket = "1 Jam";
                        $billing->price = 3000;
                        $billing->timeEnd->addHour();
                        break;
                    case 2:
                        $paket = "2 Jam";
                        $billing->price = 5000;
                        $billing->timeEnd->addHours(2);
                        break;
                    case 3:
                        $paket = "5 Jam";
                        $billing->price = 12000;
                        $billing->timeEnd->addHours(5);
                        break;
                    case 4:
                        $paket = "10 Jam";
                        $billing->price = 22000;
                        $billing->timeEnd->addHours(10);
                        break;
                }
                $billing->type = "plan";
            }
            $line_account = LineAccount::where('userId', $billing->userId)->first();
            if (!$line_account) {
                throw new LineBotException('Account not registered!');
            }
            if (Billing::where('token', $billing->token)->first()) {
                throw new LineBotException('Billing purchased!');
            }
            if ($line_account->balance < $billing->price) {
                throw new LineBotException('Insufficient balance!');
            }
            $line_account->balance -= $billing->price;
            $line_account->save();
            $billing->save();
            $json = $bot->prepareJson('receipt.json');
            $json['body']['contents'][6]['contents'][1]['text'] = '#' . $billing->token;
            $json['body']['contents'][4]['contents'][0]['contents'][1]['text'] = $paket;
            $json['body']['contents'][4]['contents'][1]['contents'][1]['text'] = $billing->timeStart->toDateTimeString();
            $json['body']['contents'][4]['contents'][2]['contents'][1]['text'] = $billing->timeEnd->toDateTimeString();
            $json['body']['contents'][4]['contents'][4]['contents'][1]['text'] = "Rp" . number_format($billing->price, 2, ",", ".");
            $json['body']['contents'][4]['contents'][5]['contents'][1]['text'] = "Rp" . number_format($line_account->balance, 2, ",", ".");
            $bot->pushFlexMessage($line_account->userId, 'Receipt', $json);
            $bot->pushMessage($line_account->userId, "Pembelian paket " . $paket . " berasil!");
            // $bot->pushMultipleMessage(env('GROUP_ID'), new TextMessageBuilder(
            //     'Ada tugas baru nich 〜(꒪꒳꒪)〜',
            //     $title . ', dikumpulkan jam: ' . $deadline,
            //     'Detail: ' . $detail,
            //     'Selamat Bersenang senang (~‾▿‾)~'
            // ));
            return ApiResponse::build(200, 'Success', $json);
        } catch (\Exception $e) {
            return ApiResponse::build(500, 'Failed', $e->getMessage()); //'failed to insert to database');
        }
    }
}
