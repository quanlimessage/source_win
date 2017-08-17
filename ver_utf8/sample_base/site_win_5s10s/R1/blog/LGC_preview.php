<?php
/*******************************************************************************
    プレビュー表示
*******************************************************************************/

// 不正アクセスチェック
if (!$injustice_access_chk) {
    header("HTTP/1.0 404 Not Found");
    exit();
}
#=============================================================================
# 共通処理：POSTデータの受け取りと共通な文字列処理
#=============================================================================
//extract(utilLib::getRequestParams("post",array(8,7,1,4)));

// タグ機能との兼ね合いにより、POSTは特殊な方法で処理
extract(utilLib::getRequestParams("post", [4]));

if ($_POST['regist_type']=="new" || $_POST['regist_type']=="update") {
    //////////////////////////////////////////////
// 新規登録・更新画面からのプレビュー

    #==================================================================
    # プレビュー画像に関する処理
    #==================================================================

    //画像枚数分ループ
    for ($i=1;$i<=IMG_COUNT;$i++) {

        // アクセスされる毎にプレビュー画像を削除する
        if(file_exists("../up_img_prev/".$res_id."_".$i.".jpg"))unlink("../up_img_prev/".$res_id."_".$i.".jpg") or die("プレビュー画像の削除に失敗しました。");
        //if(file_exists("../up_img_prev/prev_".$i.".gif"))unlink("../up_img_prev/prev_".$i.".gif") or die("プレビュー画像の削除に失敗しました。");
        //if(file_exists("../up_img_prev/prev_".$i.".png"))unlink("../up_img_prev/prev_".$i.".png") or die("プレビュー画像の削除に失敗しました。");

        //更新画面からのプレビューで、画像の新規アップがない場合
        if ($_POST['regist_type']=="update" && !is_uploaded_file($_FILES['up_img']['tmp_name'][$i])) {

            // プレビュー画像は現在登録されている画像とする //

            //該当の画像に削除指示が出ていないかを確認
            $del_img_flg = false; // 初期化
            for ($j=0;$j<=count($_POST['del_img']);$j++) {
                if ($_POST['del_img'][$j] == $i) {
                    $del_img_flg = true;
                }
            }

            if (!$del_img_flg) {
                // ファイル拡張子の情報を取得
                $sql = "
					SELECT
						RES_ID,
						EXTENTION".$i." AS EXT
					FROM
						".S7_PRODUCT_LST."
					WHERE
						(RES_ID = '".$res_id."')
				";
                // ＳＱＬを実行
                $fetchEXT = $PDO->fetch($sql);

                //プレビュー画像
                $prev_img[$i] = "../up_img_prev/".$res_id."_".$i.".".$fetchEXT[0]['EXT'];
            } else {
                $prev_img[$i] = ""; //削除指示に合わせ、プレビュー画像を表示しない
            }

        // 新規入力画面からのプレビュー・もしくは更新画面から画像アップがある場合
        } else {

            // プレビュー画像は新規アップされる画像とする //
            #-------------------------------------------------------------------------
            # プレビュー画像アップロード処理
            #-------------------------------------------------------------------------
            // 画像名(IDを使用)
            $for_imgname = $res_id;

            // 画像処理クラスimgOpeのインスタンス生成
            $imgObj = new imgOpe("../up_img_prev/");

            // アップロードされた画像ファイルがあればアップロード処理
            if (is_uploaded_file($_FILES['up_img']['tmp_name'][$i])) {

                // ファイル名を取得
                $pathinfo = pathinfo($_FILES['up_img']['name'][$i]);

                // 拡張子を取得
                $extension = strtolower($pathinfo['extension']);
                if ($extension=="jpeg") {
                    $extension = "jpg";
                }

                // アップされてきた画像のサイズを計る
                $size = getimagesize($_FILES['up_img']['tmp_name'][$i]);

                //画像サイズを調整
                $size_x = IMGSIZE_MX;//横の固定サイズ
                $size_y = $size[1]/($size[0]/$size_x);

                // 画像のアップロード：画像名は(記事ID_画像番号.jpg)
                $imgObj->setSize($size_x, $size_y);//横固定、縦可変型

                if (!$imgObj->up($_FILES['up_img']['tmp_name'][$i], $for_imgname."_".$i)) {
                    exit("画像のアップロード処理に失敗しました。");
                }
            }

            //プレビュー画像
            $prev_img[$i] = "./up_img_prev/".$res_id."_".$i.".".$extension;
        }
    }

    #==================================================================
    # 表示項目の設定 (POSTで送信された入力値を$fetch[0]に格納)
    #==================================================================
    //ID
    $fetchList[0]['RES_ID'] = $res_id;

    //タイトル
    $fetchList[0]['TITLE'] = $title;

    //コメント
    $fetchList[0]['CONTENT'] = $content;

    // 日付
    $fetchList[0]['Y'] = $y;
    $fetchList[0]['M'] = $m;
    $fetchList[0]['D'] = $d;
} else {
    ///////////////////////////////
//一覧画面からのプレビュー

    // 表示データは送信されたIDをもとに取得する
    // 一覧表示用データの取得（リスト順番は設定ファイルに従う）
    $sql = "
	SELECT
		BLOG_ENTRY_LST.RES_ID,
		BLOG_ENTRY_LST.TITLE,
		BLOG_ENTRY_LST.CONTENT,
		YEAR(BLOG_ENTRY_LST.DISP_DATE) AS Y,
		MONTH(BLOG_ENTRY_LST.DISP_DATE) AS M,
		DAYOFMONTH(BLOG_ENTRY_LST.DISP_DATE) AS D,
		BLOG_ENTRY_LST.VIEW_ORDER,
		BLOG_ENTRY_LST.DISPLAY_FLG,
		BLOG_CATEGORY_MST.CATEGORY_NAME
	FROM
			BLOG_ENTRY_LST
		INNER JOIN
			BLOG_CATEGORY_MST
		ON
			(BLOG_CATEGORY_MST.CATEGORY_CODE = BLOG_ENTRY_LST.CATEGORY_CODE)
	WHERE
		(BLOG_ENTRY_LST.DEL_FLG = '0')
	AND
		(BLOG_ENTRY_LST.DISPLAY_FLG = '1')
	AND
		(BLOG_ENTRY_LST.RES_ID = '".$res_id."')
	";

    // ＳＱＬを実行
    $fetchList = $PDO->fetch($sql);

    //画像
    for ($i=1;$i<=IMG_COUNT;$i++) {
        $prev_img[$i] = "up_img_prev/".$res_id."_".$i.".".$fetch[0]['EXTENTION'.$i];
    }
}
