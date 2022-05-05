<?php

namespace App\Http\Controllers;

use App\LineBot\LineBotApi;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WebhookController extends Controller
{
    public function webhook(LineBotApi $bot){
        return $bot->callback();
    }
}
