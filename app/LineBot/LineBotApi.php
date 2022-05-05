<?php

namespace App\LineBot;

use App\Helpers\ApiResponse;
use App\Exceptions\LineBotException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use LINE\LINEBot\SignatureValidator;

class LineBotApi
{
    private $bot;
    private $body;
    private $httpClient;
    public function __construct($request)
    {
        $this->httpClient = new CurlHTTPClient(env('CHANNEL_ACCESS_TOKEN'));
        $this->bot = new LINEBot($this->httpClient, ['channelSecret' => env('CHANNEL_SECRET')]);
        $this->body = $request->getContent();
        if (App::environment('Production')) {
            $signature = $request->header('X-Line-Signature');

            // log body and signature
            file_put_contents('php://stderr', 'Body: ' . $this->body);

            // is LINE_SIGNATURE exists in request header?
            if (empty($signature)) {
                throw new LineBotException('Signature not set', 400);
            }

            // is this request comes from LINE?
            if (!SignatureValidator::validateSignature($this->body, env('CHANNEL_SECRET'), $signature)) {
                throw new LineBotException('Invalid signature', 400);
            }
        }
    }

    public function callback()
    {
        $data = json_decode($this->body, true);
        if (is_array($data['events'])) {
            foreach ($data['events'] as $event) {
                if ($event['type'] == 'message') {
                    //reply message
                    if ($event['message']['type'] == 'text') {
                        if (strtolower($event['message']['text']) == 'user id') {
                            $result = $this->bot->replyText($event['replyToken'], $event['source']['userId']);
                        }

                        elseif (strtolower($event['message']['text']) == 'test') {
                            $result = $this->bot->replyText($event['replyToken'], "Ok dah bisa beb");
                        } else {
                            // send same message as reply to user
                            $result = $this->bot->replyText($event['replyToken'], $event['message']['text']);
                        }

                        return ApiResponse::build($result->getHTTPStatus(), $this->botResponseMessage($result));
                    }
                    elseif (
                        $event['message']['type'] == 'image' or
                        $event['message']['type'] == 'video' or
                        $event['message']['type'] == 'audio' or
                        $event['message']['type'] == 'file'
                    ) {
                        $contentURL = " https://example.herokuapp.com/public/content/" . $event['message']['id'];
                        $contentType = ucfirst($event['message']['type']);
                        $result = $this->bot->replyText(
                            $event['replyToken'],
                            $contentType . " yang Anda kirim bisa diakses dari link:\n " . $contentURL
                        );
                        return ApiResponse::build($result->getHTTPStatus(), $this->botResponseMessage($result));
                    } //group room
                    elseif (
                        $event['source']['type'] == 'group' or
                        $event['source']['type'] == 'room'
                    ) {
                        //message from group / room
                        if ($event['source']['userId']) {

                            $userId = $event['source']['userId'];
                            $getprofile = $this->bot->getProfile($userId);
                            $profile = $getprofile->getJSONDecodedBody();
                            $greetings = new TextMessageBuilder("Halo, " . $profile['displayName']);

                            $result = $this->bot->replyMessage($event['replyToken'], $greetings);
                            return ApiResponse::build($result->getHTTPStatus(), $this->botResponseMessage($result));
                        }
                    }
                }
            }
        }
        return ApiResponse::build(400, 'No event sent!');
    }

    public function pushFlexMessage($userId, $altText, $json)
    {
        $result = $this->httpClient->post(LINEBot::DEFAULT_ENDPOINT_BASE . '/v2/bot/message/push', [
            'to' => $userId,
            'messages'   => [['type' => 'flex', 'altText'  => $altText, 'contents' => $json]],
        ]);
        return ApiResponse::build($result->getHTTPStatus(), $this->botResponseMessage($result));
    }

    public function pushMessage($to, $message)
    {
        $textMessageBuilder = new TextMessageBuilder($message);
        $result = $this->bot->pushMessage($to, $textMessageBuilder);
        return ApiResponse::build($result->getHTTPStatus(), $this->botResponseMessage($result));
    }

    public function pushMultipleMessage($to, TextMessageBuilder $message)
    {
        $result = $this->bot->pushMessage($to, $message);
        return ApiResponse::build($result->getHTTPStatus(), $this->botResponseMessage($result));
    }

    public function prepareJson($fileName)
    {
        return json_decode(File::get(public_path('src/json/' . $fileName)), true);
    }

    private function botResponseMessage($result)
    {
        return array_key_exists('message', $result->getJSONDecodedBody()) ?
            $result->getJSONDecodedBody()['message'] : 'Success';
    }
}
