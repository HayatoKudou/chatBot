<?php

namespace App\Http\Controllers;

use Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Comprehend;
use App\Http\Controllers\IbmWatsonAssistant;
use SebastianBergmann\Environment\Console;

class AppController extends Controller
{
    public function app()
    {
        return view('app');
    }

    public function bot(Request $request)
    {
        $text = $request->message;

        $Comprehend = new Comprehend;
        $IbmWatsonAssistant = new IbmWatsonAssistant;

        $session = $IbmWatsonAssistant->getSessionWt(); // Watson session idを取得
        $sessionId = $session->session_id;
        $watsonResult = $IbmWatsonAssistant->sendMessagenWt($sessionId,$text);
        $IbmWatsonAssistant->deleteSessionWt($sessionId); // Watsonセッションを破棄

        Log::debug($watsonResult);
        $watsonTextCount = count($watsonResult['output']['generic']) - 1;
        $watsonTexts = $watsonResult['output']['generic'];
        if(isset($watsonResult['output']['generic'][$watsonTextCount]['text'])){
            $sentimentResult = $Comprehend->getSentiment($watsonResult['output']['generic'][$watsonTextCount]['text']); // 最後の言葉を分析
        } else {
            $sentimentResult = $Comprehend->getSentiment($text);
        }

        Log::debug($watsonTexts);

        $result = [
            'watsonTexts' => $watsonTexts,
            'sentimentObj' => $sentimentResult,
        ];
        
        return json_encode($result, true);;
    }
}
