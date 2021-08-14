<?php

namespace App\Http\Controllers;

use Log;
use Illuminate\Http\Request;

class IbmWatsonAssistant extends Controller
{
    public function getSessionWt()
    {
        $url=config('watson.watson_ass_url').'?version='.config('watson.watson_api_ver');

        $curl=curl_init($url);
        curl_setopt($curl, CURLOPT_USERPWD, 'apikey:'.config('watson.watson_ass_key'));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result=curl_exec($curl);
        curl_close($curl);

        return json_decode($result);
    }

    public function deleteSessionWt($sessionId)
    {
        $url=config('watson.watson_ass_url').'/'.$sessionId.'?version='.config('watson.watson_api_ver');

        $curl=curl_init($url);
        curl_setopt($curl, CURLOPT_USERPWD, 'apikey:'.config('watson.watson_ass_key'));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result=curl_exec($curl);
        curl_close($curl);

        return json_decode($result, true);
    }

    public function sendMessagenWt($sessionId,$text)
    {

        // 送信のデータの作成
        $object=[
            'input'=>['text'=>$text]
        ];
        $json=json_encode($object);
        $url=  config('watson.watson_ass_url').'/'.$sessionId.'/message?version='.config('watson.watson_api_ver');

        // 送信の準備
        $curl=curl_init($url);
        curl_setopt($curl, CURLOPT_USERPWD, 'apikey:'.'uWGqxfx1HCrSna0Vuw_jNI84w-Tt66utLCGUhanpP9RL');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);

        $result=curl_exec($curl);
        curl_close($curl);

        return json_decode($result, true);
    }
}
