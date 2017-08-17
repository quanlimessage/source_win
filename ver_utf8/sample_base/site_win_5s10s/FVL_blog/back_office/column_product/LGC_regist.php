<?php
/*******************************************************************************
Sx系プログラム バックオフィス（MySQL対応版）
Logic：DB登録・更新処理

*******************************************************************************/

#=================================================================================
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#=================================================================================
if (!$_SESSION['LOGIN']) {
    header("Location: ../err.php");
    exit();
}
// 不正アクセスチェック（直接このファイルにアクセスした場合）
if (!$accessChk) {
    header("Location: ../");
    exit();
}

//カテゴリー情報の取得
$sql = "
SELECT
    CATEGORY_CODE,CATEGORY_NAME,VIEW_ORDER
FROM
    ".COLUMN_CATEGORY_MST."
WHERE
    (DEL_FLG = '0')
ORDER BY
    VIEW_ORDER ASC
";
// ＳＱＬを実行
$fetchCA = $PDO -> fetch($sql);

#=================================================================================
# POSTデータの受取と文字列処理（共通処理）	※汎用処理クラスライブラリを使用
#=================================================================================
// タグ、空白の除去／危険文字無効化／“\”を取る／半角カナを全角に変換
extract(utilLib::getRequestParams("post", array(8,7,1,4), true));

// 半角数字に統一（$y:年 $m:月 $d:日）
$y = mb_convert_kana($y, "n");
$m = mb_convert_kana($m, "n");
$d = mb_convert_kana($d, "n");

// MySQLにおいて危険文字をエスケープしておく
$title = utilLib::strRep($title, 5);

//ＨＴＭＬタグの有効化の処理（【utilLib::getRequestParams】の文字処理を行う前の情報を使用するためPOSTを使用する）
$contentSql = '';
for ($i = 1; $i <= IMG_SET_CNT ; $i++) {
    $contentSql .= "CONTENT{$i} = '".html_tag($_POST['content'.$i])."',";
}

#==================================================================
# 表示日時の設定
# 表示日時のタイムスタンプ作成し、指定があれば指定日時をし
# 無ければ現在日時用文字列を使用する
#==================================================================
if (!empty($y) && !empty($m) && !empty($d)) {
    $disp_time = "{$y}-{$m}-{$d} ".date("H:i:s");
} else {
    $disp_time = date("Y-m-d H:i:s");
}

#==================================================================
# 更新する内容をここで記述をする
# フィールドの追加・変更はここで修正
#==================================================================
$sql_update_data = "
    CATEGORY_CODE = '$ca',
    TITLE = '$title',
    {$contentSql}
    IMG_FLG = '$img_flg',
    DISP_DATE = '$disp_time',
    DISPLAY_FLG = '$display_flg'
";

#=================================================================================
# 新規か更新かによって処理を分岐	※判断は$_POST["regist_type"]
#=================================================================================
switch ($_POST["regist_type"]) {
    case "update":
    //////////////////////////////////////////////////////////
    // 対象IDのデータ更新

        // 対象記事IDデータのチェック
        if (!preg_match("/^([0-9]{10,})-([0-9]{6})$/", $res_id)||empty($res_id)) {
            die("致命的エラー：不正な処理データが送信されましたので強制終了します！<br>{$res_id}");
        }

        // 削除指示がされていたら実行(複数)
        if ($_POST["regist_type"]=="update" && $del_img) {
            foreach ($del_img as $k => $v) {
                search_file_del(IMG_PATH, $res_id."_".$v.".*");
            }
        }

        // DB格納用のSQL文
        $sql = "
        UPDATE
            ".COLUMN_PRODUCT_LST."
        SET
            {$sql_update_data}
        WHERE
            (RES_ID = '$res_id')
        ";
        break;

    case "new":
    //////////////////////////////////////////////////////////////////
    // 新規登録

        // 画像ファイル名の決定（新しいIDを生成して使用。DB登録時のRES_IDにも使用）
        $res_id = $makeID();

        // 現在の登録件数が設定した件数未満の場合のみDBに格納
        $cnt_sql = "
        SELECT
            COUNT(*) AS CNT
        FROM
            ".COLUMN_PRODUCT_LST."
        LEFT JOIN
            ".COLUMN_CATEGORY_MST."
        ON
            (".COLUMN_PRODUCT_LST.".CATEGORY_CODE = ".COLUMN_CATEGORY_MST.".CATEGORY_CODE)
        WHERE
            (".COLUMN_PRODUCT_LST.".DEL_FLG = '0')
        AND
            (".COLUMN_CATEGORY_MST.".DEL_FLG = '0')
        ";
        $fetchCNT = $PDO -> fetch($cnt_sql);

        //最大登録件数に達していない、そして、カテゴリーが存在している場合登録をする
        if (($fetchCNT[0]["CNT"] < DBMAX_CNT) && count($fetchCA)) {
            $sql = "
            INSERT INTO ".COLUMN_PRODUCT_LST."
            SET
                RES_ID = '$res_id',
                {$sql_update_data}
            ";
        } else {
            header("Location: ./");
        }
        break;

    default:
        die("致命的エラー：登録フラグ（regist_type）が設定されていません");

}

// ＳＱＬを実行
$PDO -> regist($sql);

#=================================================================================
# 共通処理；画像アップロード処理
#=================================================================================
// 画像処理クラスimgOpeのインスタンス生成
$imgObj = new imgOpe(IMG_PATH);

// 画像ファイル名の決定(記事のIDを使用)
$for_imgname = $res_id;

// 設定ファイルの画像最大登録枚数分ループ
for ($i=1;$i<= IMG_CNT;$i++) {

    // アップロードされた画像ファイルがあればアップロード処理
    if (is_uploaded_file($_FILES['up_img']['tmp_name'][$i])) {

        //古いファイルは拡張子をワイルドカードにして削除（拡張子が違っている場合古いファイルは上書きで消えない為）
        search_file_del(IMG_PATH, $for_imgname."_".$i.".*");

        // アップされてきた画像のサイズを計る
        $size = getimagesize($_FILES['up_img']['tmp_name'][$i]);

        //画像サイズを調整
        $size_x = $ox[$i-1];//横の固定サイズ
        $size_y = $size[1]/($size[0]/$size_x);

        // 画像のアップロード：画像名は(記事ID_画像番号.jpg)
        $imgObj->setSize($size_x, $size_y);//横固定、縦可変型

        if (!$imgObj->up($_FILES['up_img']['tmp_name'][$i], $for_imgname."_".$i)) {
            exit("画像のアップロード処理に失敗しました。");
        }
    }
}
