<?php
require_once('util_lib.php');

//リサイズのデフォルト設定
define("RESIZE_SIZE_X", 250);
//define("RESIZE_SIZE_Y", 300);

#=============================================================================
# 共通処理：GETデータの受け取りと共通な文字列処理
#=============================================================================
if ($_GET) {
    extract(utilLib::getRequestParams("get", array(8, 7, 1, 4, 5)));
}

// 送信される可能性のあるパラメーターはデコードする
$img_data = urldecode($img_data);
$width = urldecode($width);
$height = urldecode($height);

#####################################################
#スマートフォン用のリサイズ処理関数
#####################################################
function image_resize_out($img, $width = null, $height = null)
{
    $param = getimagesize($img);

    //画像の種類によって使用する関数が違うため
    //それぞれの関数名に使用する文字を$func_typeに入れる
    switch ($param[2]) {
        case IMAGETYPE_GIF:
            $func_type = "gif";
            break;
        case IMAGETYPE_JPEG:
            $func_type = "jpeg";
            break;
        case IMAGETYPE_PNG:
            $func_type = "png";
            break;
        default:
            exit();
    }

    $func_cre_name = "imagecreatefrom".$func_type;
    $srcImage = $func_cre_name($img); //画像の種類に適した関数を使用する

    if (isset($width) && is_numeric($width) && isset($height) && is_numeric($height)) {
        $size_x = $width;
        $size_y = $height;
    } elseif (isset($width) && is_numeric($width)) {
        $size_x = $width;
        $size_y = round($param[1] / ($param[0] / $size_x));
    } elseif (isset($height) && is_numeric($height)) {
        $size_y = $height;
        $size_x = round($param[0] / ($param[1] / $size_y));
    } else {
        $size_x = RESIZE_SIZE_X;
        $size_y = round($param[1] / ($param[0] / $size_x));
    }

    $newImage = ImageCreateTrueColor($size_x, $size_y);// 元の画像サイズ変更後の新規画像

    // リサイズ（画像を作成）
    ImageCopyResampled($newImage, $srcImage, 0, 0, 0, 0, $size_x, $size_y, $param[0], $param[1]);
    ImageDestroy($srcImage);

    //画像を出力
    header("Content-type: image/{$func_type}");
    $func_name = "image".$func_type;
    $func_name($newImage); //画像の種類に適した関数を使用する

    //データを削除する
    ImageDestroy($newImage);
}

if (file_exists($img_data)) {
    //画像の出力処理をさせる
    image_resize_out($img_data, $width, $height);
} else {
    exit;
}
