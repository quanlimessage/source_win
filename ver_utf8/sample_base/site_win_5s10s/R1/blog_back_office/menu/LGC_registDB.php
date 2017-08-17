<?php
/*******************************************************************************
ALL-INTERNET BLOG

Logic：入力情報をチェックし、ＤＢへ登録

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if (!$_SESSION['LOGIN']) {
    header("Location: ../err.php");
    exit();
}
// 不正アクセスチェック（直接このファイルにアクセスした場合）
if (!$injustice_access_chk) {
    header("HTTP/1.0 404 Not Found");
    exit();
}

#========================================================================
# POSTデータの受け取りと共通な文字列処理
#========================================================================
extract(utilLib::getRequestParams("post", [8,7,1,4,5]));

#=================================================================================
# 新規か更新かによってＳＱＬを分岐	※判断は$_POST["regist_type"]
#=================================================================================
switch ($_POST["regist_type"]):

case "delete":
/////////////////////////////////////////////////////////////////////////////////
// 更新

    #-----------------------------------------------------
    # SQL組立
    #-----------------------------------------------------
    $sql = "
	UPDATE
		BLOG_CATEGORY_MST
	SET
		DEL_FLG = '1',
		UPD_DATE = NOW()
	WHERE
		(CATEGORY_CODE = '$category_code')
	";

    //該当するカテゴリーの登録データを削除処理

    $sqlcnt = "
	SELECT
		RES_ID
	FROM
		BLOG_ENTRY_LST
	WHERE
		(DEL_FLG = '0')
		AND
	(CATEGORY_CODE = '$category_code')
	";

    // ＳＱＬを実行
    $fetchDEL = $PDO->fetch($sqlcnt);

    //登録された画像データを削除する
    for ($j=0;$j < count($fetchDEL);$j++):

        for ($i=1;$i<=IMG_COUNT;$i++) {

        // 記事画像の削除
            if (file_exists(IMG_FILE_PATH.$fetchDEL[$j]['RES_ID']."_".$i.".jpg")) {
                unlink(IMG_FILE_PATH.$fetchDEL[$j]['RES_ID']."_".$i.".jpg") or die("画像の削除に失敗しました。");
            }
            if (file_exists(IMG_FILE_PATH.$fetchDEL[$j]['RES_ID']."_L".$i.".jpg")) {
                unlink(IMG_FILE_PATH.$fetchDEL[$j]['RES_ID']."_L".$i.".jpg") or die("画像の削除に失敗しました。");
            }
        }

    endfor;

    //登録データを削除する
    //$PDO->regist("DELETE FROM ".S7_PRODUCT_LST." WHERE(CATEGORY_CODE = '$cate')");
    $PDO->regist("UPDATE BLOG_ENTRY_LST SET DEL_FLG = '1' WHERE(CATEGORY_CODE = '$category_code')");

    break;
case "update":
/////////////////////////////////////////////////////////////////////////////////
// 更新

    // 画像ファイル名の決定（POSTで渡された既存の記事ID（$category_code）を使用）
    $for_imgname = $category_code;

    // 削除指示がされていたら実行
    if ($_POST["regist_type"]=="update" and !empty($del_img)):
    foreach ($del_img as $k => $v) {
        if (file_exists(IMG_FILE_PATH.$category_code."_".$v.".jpg")):
            unlink(IMG_FILE_PATH.$category_code."_".$v.".jpg") or die("画像{$v}の削除に失敗しました。");
        endif;
    }
    endif;

    #-----------------------------------------------------
    # SQL組立
    #-----------------------------------------------------
    $sql = "
	UPDATE
		BLOG_CATEGORY_MST
	SET
		CATEGORY_NAME = '$category_name',
		UPD_DATE = NOW()
	WHERE
		(CATEGORY_CODE = '$category_code')
	AND
		(DEL_FLG = '0')
	";

    break;
case "new":
//////////////////////////////////////////////////////////////////////////////////
// 新規

    // 画像ファイル名の決定（新しいIDを生成して使用。DB登録時のRES_IDにも使用）
    $category_code = $makeID();
    $for_imgname = $category_code;

    // 並び順：現在のview_orderの一番最後に１を足したものを設定
    $vosql = "
		SELECT
			MAX(VIEW_ORDER) AS VO
		FROM
			BLOG_CATEGORY_MST
		WHERE
			(DEL_FLG = '0')
	";

    $fetchVO = $PDO->fetch($vosql);
    $view_order = ($fetchVO[0]["VO"] + 1);

    #-----------------------------------------------------
    # SQL組立
    #-----------------------------------------------------
    $sql = "
	INSERT INTO BLOG_CATEGORY_MST(
		CATEGORY_CODE,
		CATEGORY_NAME,
		UPD_DATE,
		VIEW_ORDER,
		DEL_FLG
	)
	VALUES(
		'".$category_code."',
		'".$category_name."',
		NOW(),
		'".$view_order."',
		'0'
	)";

    break;
default:
    die("致命的エラー：登録フラグ（regist_type）が設定されていません");
endswitch;

// ＳＱＬを実行
if (!empty($sql)) {
    $PDO->regist($sql);
}

#=================================================================================
# 共通処理；画像アップロード処理
#=================================================================================
// 画像処理クラスimgOpeのインスタンス生成
$imgObj = new imgOpe(IMG_FILE_PATH);

// アップロードされた画像ファイルがあればアップロード処理
    if (is_uploaded_file($_FILES['up_img']['tmp_name'])) {

        // 拡大画像のアップロード：画像名は(記事ID_画像番号.jpg)
        $imgObj->setSize(CA_IMGSIZE_SX, CA_IMGSIZE_SY);
        if (!$imgObj->up($_FILES['up_img']['tmp_name'], $for_imgname."_ca")) {
            exit("アイコン画像のアップロード処理に失敗しました。");
        }
    }
