<?php

require_once '../vendor/autoload.php';

use Popcorn\Pop;

// STEP03 データベースを準備します
$pdo = new PDO('sqlite:example.db');
$sql = <<< "SQL"
  CREATE TABLE IF NOT EXISTS config (
    param_id INTEGER PRIMARY KEY,
    params TEXT
  );
SQL;
$pdo->exec($sql);

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

        // STEP04 データベースからパラメータを取得します
        $sql = <<< 'SQL'
          SELECT param_id, params
          FROM config
          WHERE (param_id = 1);
        SQL;

        $pdo = new PDO('sqlite:example.db');
        $sth = $pdo->prepare($sql);
        $sth->execute();
        $db_data = $sth->fetchColumn(1);

        // STEP04 取得したパラメータを返します
        // STEP04 取得した結果がnullのときは、デフォルトデータを返します
        $params = json_decode($db_data) ?? $params;

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

        // STEP03 pop-httpでリクエストデータを受け取ります
        $request = new \Pop\Http\Server\Request();
        $params = $request->getParsedData();
        $encoded_params = json_encode($params);

        // STEP03 リクエストデータを保存します
        $sql = <<< "SQL"
          INSERT OR REPLACE INTO
            config(param_id, params)
          VALUES(1, :params);
        SQL;

        $pdo = new PDO('sqlite:example.db');
        $sth = $pdo->prepare($sql);
        $sth->bindParam(':params', $encoded_params);
        $bool = $sth->execute();

        $message = $bool ? '保存しました' : '保存できませんでした';

        // STEP02 受信確認メッセージを返します
        // STEP03 実行結果をメッセージとして返します
        echo json_encode(['message' => $message]);
    }
);


$app->run();
