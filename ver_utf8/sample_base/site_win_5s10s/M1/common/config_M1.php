<?php
/*******************************************************************************
SiteWiN30 20 30（MySQL版）M1
設定ファイルの定義

*******************************************************************************/

// 設定ファイル＆共通ライブラリの読み込み
require_once("config.php");    // 共通設定情報

#=================================================================================
# 管理画面に共通して表示させる値
#=================================================================================
define('M1_TITLE', '会員ページのID/パスワードの管理');    // タイトル;

/**
 * ログイン状態によってリダイレクトを行うsession_startのラッパー関数
 * 初回時または失敗時にはヘッダを送信してexitする
 */
function require_unlogined_session()
{
    // セッション開始
    @session_start();
    // ログインしていれば / に遷移
    if ($_SESSION["M1_FLG"] == true) {
        header('Location: ./index.php');
        exit;
    }
}

function require_logined_session()
{
    // セッション開始
    @session_start();

    // ログインしていなければ /login.html に遷移
    if ($_SESSION["M1_FLG"] == false) {
        header('Location: ./login.html');
        exit;
    }
}

/**
 * CSRFトークンの生成
 *
 * @return string トークン
 */
function generate_token()
{
    // セッションIDからハッシュを生成
    return hash('sha256', session_id());
}

/**
 * CSRFトークンの検証
 *
 * @param string $token
 * @return bool 検証結果
 */
function validate_token($token)
{
    // 送信されてきた$tokenがこちらで生成したハッシュと一致するか検証
    return $token === generate_token();
}

/**
 * htmlspecialcharsのラッパー関数
 *
 * @param string $str
 * @return string
 */
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
