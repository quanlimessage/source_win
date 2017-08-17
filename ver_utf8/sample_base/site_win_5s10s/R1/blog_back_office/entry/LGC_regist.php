<?php
/*******************************************************************************
ALL-INTERNET BLOG

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

#=================================================================================
# POSTデータの受取と文字列処理（共通処理）	※汎用処理クラスライブラリを使用
#=================================================================================
// タグ、空白の除去／危険文字無効化／“\”を取る／半角カナを全角に変換
extract(utilLib::getRequestParams("post", [8,7,1,4], true));

// 画像ファイル名の決定（POSTで渡された$res_idを使用）
$for_imgname = $res_id;

// MySQLにおいて危険文字をエスケープしておく
$title = utilLib::strRep($title, 5);
//$content = utilLib::strRep($content,5);

//ＨＴＭＬタグの有効化の処理（【utilLib::getRequestParams】の文字処理を行う前の情報を使用するためPOSTを使用する）
$content = html_tag($_POST['content']);

// 半角数字に統一（$y:年 $m:月 $d:日）
$y = mb_convert_kana($y, "n");
$m = mb_convert_kana($m, "n");
$d = mb_convert_kana($d, "n");

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

#=================================================================================
# 共通処理；画像アップロード処理
#=================================================================================
// 画像処理クラスimgOpeのインスタンス生成
$imgObj = new imgOpe(IMG_FILE_PATH);

// アップロードされた画像ファイルがあればアップロード処理
for ($i=1;$i<=IMG_COUNT;$i++) {
    if (is_uploaded_file($_FILES['up_img']['tmp_name'][$i])) {

        // Upload：File名
        $pathinfo = pathinfo($_FILES['up_img']['name'][$i]);

        // 拡張子を取得
        $extension[$i] = strtolower($pathinfo['extension']);

        if ($extension[$i] != "jpg" && $extension[$i] != "gif" && $extension[$i] != "png") {
            exit("ファイルの形式に誤りがあります。");
        }

        //古いファイルを削除する（ファイルタイプが変更された場合の対応）
        if (file_exists(IMG_FILE_PATH.$for_imgname."_".$i.".jpg")) {
            unlink(IMG_FILE_PATH.$for_imgname."_".$i.".jpg") or die("古い画像の削除に失敗しました。");
        }
        if (file_exists(IMG_FILE_PATH.$for_imgname."_".$i.".gif")) {
            unlink(IMG_FILE_PATH.$res_id."_".$i.".gif") or die("古い画像の削除に失敗しました。");
        }
        if (file_exists(IMG_FILE_PATH.$for_imgname."_".$i.".png")) {
            unlink(IMG_FILE_PATH.$for_imgname."_".$i.".png") or die("古い画像の削除に失敗しました。");
        }
        // 拡大用画像も削除する
        if (file_exists(IMG_FILE_PATH.$for_imgname."_L".$i.".jpg")) {
            unlink(IMG_FILE_PATH.$for_imgname."_L".$i.".jpg") or die("古い拡大用画像の削除に失敗しました。");
        }
        if (file_exists(IMG_FILE_PATH.$for_imgname."_L".$i.".gif")) {
            unlink(IMG_FILE_PATH.$for_imgname."_L".$i.".gif") or die("古い拡大用画像の削除に失敗しました。");
        }
        if (file_exists(IMG_FILE_PATH.$for_imgname."_L".$i.".png")) {
            unlink(IMG_FILE_PATH.$for_imgname."_L".$i.".png") or die("古い拡大用画像の削除に失敗しました。");
        }

        // アップされてきた画像のサイズを計る
        $size = getimagesize($_FILES['up_img']['tmp_name'][$i]);

        //画像サイズを調整
        $size_x = IMGSIZE_MX;//横の固定サイズ
        $size_y = $size[1]/($size[0]/$size_x);

        $imgObj->setSize($size_x, $size_y);
        if (!$imgObj->up($_FILES['up_img']['tmp_name'][$i], $for_imgname."_".$i)) {
            exit("詳細画像のアップロード処理に失敗しました。");
        }

        // 拡大用画像(基本は原寸大・最大横800px)をアップロード
        $size_x2 = $size[0];
        $size_y2 = $size[1];

        if ($size_x2 > 800) {
            $size_x2 = 800;//横の固定サイズ
            $size_y2 = $size[1]/($size[0]/$size_x2);
        }

        $imgObj->setSize($size_x2, $size_y2);
        if (!$imgObj->up($_FILES['up_img']['tmp_name'][$i], $for_imgname."_L".$i)) {
            exit("拡大用画像のアップロード処理に失敗しました。");
        }
    }
}

// ブログタイトル取得
$_sql_title = " SELECT TITLE,SENDPING_URL FROM BLOG_TITLE WHERE T_ID = '1'";
$fetchT = $PDO->fetch($_sql_title);

#=================================================================================
# 新規か更新かによって処理を分岐	※判断は$_POST["regist_type"]
#=================================================================================
switch ($_POST["regist_type"]):
case "new":
//////////////////////////////////////////////////////////////////
// 新規登録

        #-----------------------------------------------------------------
        #	VIEW_ORDER用の値を作成
        #		※現在登録されている記事データ中の最大VIEW_ORDER値を取得
        #		  それに1足したものを$view_orderに格納して使用
        #-----------------------------------------------------------------
        $vosql = "SELECT MAX(VIEW_ORDER) AS VO FROM BLOG_ENTRY_LST WHERE(DISPLAY_FLG = '1') AND (CATEGORY_CODE = '$category_code')";
        $fetchVO = $PDO->fetch($vosql);
        $view_order = ($fetchVO[0]["VO"] + 1);

        $sql = "
		INSERT INTO BLOG_ENTRY_LST(
			RES_ID,CATEGORY_CODE,TITLE,CONTENT,DISP_DATE,VIEW_ORDER,DISPLAY_FLG,TB_FLG
		)
		VALUES(
			'$res_id','$category_code','$title','$content','$disp_time','$view_order','$display_flg','$tb_flg'
		)";

        $sqlecnt = "
		SELECT
			COUNT(*) AS CNT
		FROM
			BLOG_ENTRY_LST
		WHERE
			(BLOG_ENTRY_LST.DEL_FLG = '0')
		";
        // ＳＱＬを実行
        $fetche_cnt_check = $PDO->fetch($sqlecnt);

        //最大登録件数以内の場合は登録をする
        if ($fetche_cnt_check[0]['CNT'] < ENTRY_MAXROW) {

            // ＳＱＬを実行
            if (!empty($sql)) {
                $PDO->regist($sql);
            }
        } else {
            exit("DB登録失敗しました。<br>エントリーの最大登録件数に達しています。");
        }

        // ==============================================================================
        // トラックバック送信
        // ==============================================================================
        $pm = new PingManager();
            // タイトル表示

        $ping_url = "http://".$_SERVER['SERVER_NAME']."/blog/u".$res_id."/";

        // トラックバックPingのルール設定
        // 「Movable Type」の仕様をそのまま使いました。
        // 配列の添え字に送信する項目名、値に内容を入れるだけです。
        $SendTrackBackPingRule = [
            "title" =>$title,
            "excerpt" => mb_strimwidth(strip_tags($content), 0, 200, "...", utf8),
            "url" => $ping_url,
            "blog_name" =>$fetchT[0]['TITLE']
        ];
        if ($trackback) {
            if (preg_match('/^(http|https):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}'.'((:[0-9]{1,5})?\/.*)?$/i', $trackback)) {
                // トラックバック送信先のアドレスを指定
                $result = $pm->SendTrackBackPing($trackback, $SendTrackBackPingRule);
            }
        }

        // 結果のチェック
        if ($result == true) {
        } else {
            // die("Track back エラー");
        }

    break;
case "update":
//////////////////////////////////////////////////////////
// 対象IDのデータ更新

    // 対象記事IDデータのチェック
    if (!preg_match("/^([0-9]{10,})-([0-9]{6})$/", $res_id)||empty($res_id)) {
        die("致命的エラー：不正な処理データが送信されましたので強制終了します！<br>{$res_id}");
    }

    // 削除指示がされていたら実行
    if ($_POST["regist_type"]=="update" and !empty($del_img)):
    foreach ($del_img as $k => $v) {
        if (file_exists(IMG_FILE_PATH.$res_id."_".$v.".jpg")):
            unlink(IMG_FILE_PATH.$res_id."_".$v.".jpg") or die("画像{$v}の削除に失敗しました。");
        endif;
        if (file_exists(IMG_FILE_PATH.$res_id."_".$v.".gif")):
            unlink(IMG_FILE_PATH.$res_id."_".$v.".gif") or die("画像{$v}の削除に失敗しました。");
        endif;
        if (file_exists(IMG_FILE_PATH.$res_id."_".$v.".png")):
            unlink(IMG_FILE_PATH.$res_id."_".$v.".png") or die("画像{$v}の削除に失敗しました。");
        endif;
        // 拡大用画像も削除する
        if (file_exists(IMG_FILE_PATH.$res_id."_L".$v.".jpg")):
            unlink(IMG_FILE_PATH.$res_id."_L".$v.".jpg") or die("拡大用画像{$v}の削除に失敗しました。");
        endif;
        if (file_exists(IMG_FILE_PATH.$res_id."_L".$v.".gif")):
            unlink(IMG_FILE_PATH.$res_id."_L".$v.".gif") or die("拡大用画像{$v}の削除に失敗しました。");
        endif;
        if (file_exists(IMG_FILE_PATH.$res_id."_L".$v.".png")):
            unlink(IMG_FILE_PATH.$res_id."_L".$v.".png") or die("拡大用画像{$v}の削除に失敗しました。");
        endif;
    }
    endif;

    // DB格納用のSQL文
    $sql = "
	UPDATE
		BLOG_ENTRY_LST
	SET
		CATEGORY_CODE 	 = '$category_code',
		TITLE      	 = '$title',
		CONTENT 	 = '$content',
		DISP_DATE 	 = '$disp_time',
		DISPLAY_FLG 	 = '$display_flg',
		TB_FLG 	 = '$tb_flg'
	WHERE
		(RES_ID = '$res_id')
	";

    // ＳＱＬを実行
    if (!empty($sql)) {
        $PDO->regist($sql);
    }

    $ping_url = "http://".$_SERVER['SERVER_NAME']."/blog/u".$res_id."/";

    break;

default:
    die("致命的エラー：登録フラグ（regist_type）が設定されていません");
endswitch;

// 設定ファイルの画像最大登録枚数分ループ
for ($i=1;$i<=IMG_COUNT;$i++):

// アップしたファイルの拡張子のデータを更新
if (is_uploaded_file($_FILES['up_img']['tmp_name'][$i])) {
    $sql = "
	UPDATE
		BLOG_ENTRY_LST
	SET
		EXTENTION".$i." = '".$extension[$i]."'
	WHERE
		(RES_ID = '$res_id')
	";
    if (!empty($sql)) {
        $PDO->regist($sql);
    }
}
endfor;

// ==============================================================================
// PING更新送信
// ==============================================================================
if ($display_flg) {
    $sp = new SendPing();
    /*
    // タイトル表示
    $_sql_title = " SELECT TITLE FROM BLOG_TITLE WHERE T_ID = '1'";
    $fetchT = $PDO->fetch($_sql_title);
    */

    // トラックバックPingのルール設定
    // 「Movable Type」の仕様をそのまま使いました。
    // 配列の添え字に送信する項目名、値に内容を入れるだけです。
    $SendUpdatePingRule = [
        "%%SITENAME%%" => $fetchT[0]['TITLE'],
        "%%SITEURL%%" => $ping_url,
    ];
    $str =

    $urls = explode("\r\n", $fetchT[0]['SENDPING_URL']);
    for ($i=0;$i < count($urls);$i++) {
        $urls[$i] = str_replace(["\r\n","\r","\n"], '', $urls[$i]);

        if (isset($urls[$i]) && preg_match("/^http:\/\/.+\./", $urls[$i])) {
            //$sp->SendUpdatePing($urls[$i], $SendUpdatePingRule);
        }
    }
}
