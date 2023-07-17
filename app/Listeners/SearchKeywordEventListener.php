<?php

namespace App\Listeners;

use App\Events\SearchKeywordEvent;
use App\Services\Signature;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use GuzzleHttp\Client;
use PhpParser\Node\Expr\Cast\Object_;

class SearchKeywordEventListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     */
    public function handle(SearchKeywordEvent $event): void
    {
        $token = env('ACCESS_TOKEN_1688');
        $client = new Client();
        $signature = new Signature();
        $api = 'com.alibaba.fenxiao/cross.keywords.search/';
        $abc = [
        'keyword' => $event->keyWord,
        'scenario' => 'all',
        'sortType' => 'price',
        'pageSize' => 20,
        'pageNum' => 1
        ];
        $param = [
            'access_token' => $token,
            'param' => (object)$abc


        ];
        $_aop_signature = $signature->genSignature($api, $param);
        dd($_aop_signature);
        if($_aop_signature)
        {
           $request =  $client->post(env('LINK_API_1688') .'/com.alibaba.fenxiao/cross.keywords.search/'.env('APP_KEY_1688'), [
                'json' => [
                    "_aop_signature" => $_aop_signature,
                    'access_token' => $token,
                    "keywords" => $event->keyWord,
                    "scenario" => 'all',
                    "sortType" => 'price',
                    "pageSize" => 20,
                    "pageNum" => 1


                ],
            ]);
           dd($request);
        }
    }
}
