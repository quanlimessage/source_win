<?php
session_start();
// 設定ファイル＆共通ライブラリの読み込み
require_once("../common/config_M1.php");

// POSTデータの受け取りと共通な文字列処理
if ($_POST) {
    extract(utilLib::getRequestParams("post", array(8,7,1,4,5), true));
}

//トークンが一致しなければ全てスルー
if (validate_token(filter_input(INPUT_POST, 'token'))) {
    if ($status == "login") {

        //使用するID PASSWORDの設定はこちらのＳＱＬ文を変更
        $sql = "
        SELECT
            PW
        FROM
            M1_INFO
        WHERE
            (ID = '{$id}')
        AND
            (DEL_FLG = '0')
        ";

        // ＳＱＬを実行
        $fetch = $PDO -> fetch($sql);

        if (password_verify($pass, isset($fetch[0]['PW'])? $fetch[0]['PW'] : '$2y$10$abcdefghijklmnopqrstuv' /* ユーザ名が存在しないときだけ極端に速くなるのを防ぐ */ ) ) {
            // 認証が成功したとき
            // セッションIDの追跡を防ぐ
            session_regenerate_id(true);
            // ログインフラグをセット
            $_SESSION["M1_FLG"] = true;
            exit();
        }
        // 認証が失敗したとき
        $error_mes = "ユーザー名またはパスワードが間違っています。";
    }
    echo $error_mes;
}
