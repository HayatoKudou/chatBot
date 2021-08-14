<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Aws\Comprehend\ComprehendClient;
use Log;

class Comprehend extends Controller
{
    public function getSentiment($text)
    {
        $client = new ComprehendClient([
            'region' => 'ap-northeast-1', // 東京リージョン
            'version' => '2017-11-27'
        ]);

        try {
            $comprehendResult = $client->detectSentiment([
                'LanguageCode' => 'ja',
                'Text' => $text // このテキストを感情分析する
            ]);
            $result['sentiment'] = $comprehendResult['Sentiment'];
            $result['sentimentScore'] = $comprehendResult['SentimentScore'];
            return $result;

        } catch (\Exception $e) {
            Log::debug($e->getMessage());
        }
    }
}
