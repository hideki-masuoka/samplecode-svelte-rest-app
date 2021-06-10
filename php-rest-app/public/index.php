<?php

require_once '../vendor/autoload.php';

use Popcorn\Pop;

// STEP01 マイクロフレームワークの準備
$app = new Pop();

// STEP01 パラメータ取得に応答する機能を登録します
$app->get(
    '/params/',
    function () {
        // STEP01 ローカル環境間でのアクセスを許可します
        header('Access-Control-Allow-Origin: http://localhost:3000');
        header('Access-Control-Allow-Headers: Origin, Content-Type');

        $params = [
            'name' => 'name2',
            'lv'   => '12',
            'hp'   => '90',
            'mp'   => '10',
            'agi'  => '6',
            'tec'  => '3',
            'str'  => '10',
            'luc'  => '2',
            'text' => "I'm stronger2."
        ];
        echo json_encode($params);
    }
);


// STEP02 CORS対応処理
$app->options(
    '/saveparams/',
    function () {
        header('Access-Control-Allow-Origin: http://localhost:3000');
        header('Access-Control-Allow-Headers: Origin, Content-Type');
    }
);

// STEP02 パラメータ送信に応答する機能を登録します。
$app->post(
    '/saveparams/',
    function () {
        header('Access-Control-Allow-Origin: http://localhost:3000');
        header('Access-Control-Allow-Headers: Origin, Content-Type');

        // STEP02 受信確認メッセージを返します
        echo json_encode(['message' => '受信しました']);
    }
);


$app->run();
