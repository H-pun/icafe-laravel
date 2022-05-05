<?php

namespace App\Http\Controllers;

use App\Exceptions\LineBotException;
use App\Helpers\ApiResponse;
use App\LineBot\LineBotApi;
use App\Models\Billing;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;


class BillingController extends Controller
{
    public function verify(Request $request)
    {
        try {
            $data = Billing::where('token', $request->get('token'))->first();
            if ($data == null) {
                throw new LineBotException('Invalid Token', 404);
            }
            return ApiResponse::build(200, 'Success', $data);
        } catch (\Exception $e) {
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
            $billing->save();
            $json = $bot->prepareJson('receipt.json');
            $json['body']['contents'][6]['contents'][1]['text'] = '#' . $billing->token;
            $base_json = $json['body']['contents'][4]['contents'];
            $base_json[0]['contents'][1]['text'] = $paket;
            $base_json[1]['contents'][1]['text'] = $billing->timeStart;
            $base_json[2]['contents'][1]['text'] = $billing->timeEnd;
            $base_json[4]['contents'][1]['text'] = $billing->price;
            $base_json[5]['contents'][1]['text'] = 30000;
            $bot->pushFlexMessage($request->input('userId'), 'Receipt', $json);
            // $bot->pushMultipleMessage(env('GROUP_ID'), new TextMessageBuilder(
            //     'Ada tugas baru nich 〜(꒪꒳꒪)〜',
            //     $title . ', dikumpulkan jam: ' . $deadline,
            //     'Detail: ' . $detail,
            //     'Selamat Bersenang senang (~‾▿‾)~'
            // ));
            return ApiResponse::build(200, 'Success', $billing);
        } catch (\Exception $e) {
            return ApiResponse::build(500, 'Failed', $e->getMessage()); //'failed to insert to database');
        }
    }
}
