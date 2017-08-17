<?php

require_once("../../common/config_column.php");    // 共通設定情報
//エラーメッセージの表示の時エンコードがsjisで文字化けをするのを回避
header("Content-Type: text/html; charset=UTF-8");
header("Content-Language: ja");

$errmsg = "";

//post_max_size（サーバー設定：POST送信可能なサイズ上限）を取得
$post_max = ini_get('post_max_size');
$post_max = str_replace("M", "", $post_max);
$post_max = $post_max  * 1024 * 1024;

// 送信しようとしたデータがpost_max_sizeよりも大きい場合
if ($_SERVER['CONTENT_LENGTH'] > $post_max) {
    $errmsg .= "送信可能なファイルのサイズを超えています。\n";
} else {
    foreach ($_FILES as $file) {
        if ($file) {
            if (is_array($file['size'])) {
                foreach ($file['size'] as $size) {
                    if ($size > MAX_MB * 1024 * 1024) {
                        $errmsg .= "容量が大きすぎるため、アップロードできません。\n(".MAX_MB."MB以下のファイルのみアップロード可能です。)\n";
                    }
                }

                foreach ($file['error'] as $error) {
                    if ($error) {
                        $errmsg .= "このファイルはアップロードすることができません。\n";
                    }
                }
            } else {
                if ($file['size'] > MAX_MB * 1024 * 1024) {
                    $errmsg .= "容量が大きいです。".MAX_MB."MB以下のファイルにしてください。\n";
                }
                if ($file['error']) {
                    $errmsg .= "このファイルはアップロードすることができません。\n";
                }
            }

            // ファイル型式チェック
            $errmsg = ($errmsg) ? $errmsg : 'success';
        }
    }
}
echo $errmsg;
