<?php
/*******************************************************************************
BLOGタイトルの更新

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
session_start();
if (!$_SESSION['LOGIN']) {
    header("Location: ../err.php");
    exit();
}

// 設定ファイル＆共通ライブラリの読み込み
require_once("../../common/blog_config.php");    // 設定情報
require_once("../tag_pg/LGC_color_table.php");    // タグ処理のプログラム

require_once('imgOpe.php');                    // 画像アップロードクラスライブラリ

if ($_POST["action"] == "update"):

    // 	POSTデータの受取と文字列処理。
    extract(utilLib::getRequestParams("post", [8,7,1,4], true));

    /*
    if(empty($name)){
        $error_message.="お名前が未入力です。<br>\n";
    }

    if(empty($profile)){
        $error_message.="プロフィールが未入力です。<br>\n";
    }
    */

    // エラーがあればエラー表示して終了
    if ($error_message):

        utilLib::errorDisp($error_message);exit();
    else:

        #--------------------------------------------------------
        # データを更新し、管理者宛にメール通知をしてスルー
        #--------------------------------------------------------

            $name = utilLib::strRep($name, 5);
            $profile = html_tag($_POST['profile']);

        $sql = "
		UPDATE
			PROFILE_DATA
		SET
			NAME		 = '$name',
			PROFILE		 = '$profile',

			DISPLAY_FLG	 = '$display_flg',
			UPD_DATE = NOW()
		WHERE
			(RES_ID = '1')
		";
        $PDO->regist($sql);

#=================================================================================
# 画像アップロード処理
#=================================================================================

        if (is_uploaded_file($_FILES['p_img']['tmp_name'])) {

        // 画像処理クラスimgOpeのインスタンス生成
        $imgObj = new imgOpe(IMG_FILE_PATH);

            $size = getimagesize($_FILES['p_img']['tmp_name']);

            //画像サイズを調整
                $size_x = P_IMGSIZE_X;//横の固定サイズ
                $size_y = $size[1]/($size[0]/$size_x);

            // 画像のサイズは幅760、高さは自由
            $imgObj->setSize($size_x, $size_y);

            // 画像のアップロード：画像名は(記事ID_画像番号.jpg)
            if (!$imgObj->up($_FILES['p_img']['tmp_name'], p_img)) {
                exit("画像のアップロード処理に失敗しました。");
            }
        }

        // 完了画面を表示
        $mes = "<p class=\"explanatio\"><strong>更新が完了しました</strong></p>\n";

    endif;

endif;

    //データを取得する
    $sql = "
	SELECT

		*
	FROM

		PROFILE_DATA

	WHERE

		RES_ID = '1'";

    $fetch = $PDO->fetch($sql);

#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8", true, true, true, true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>

<script type="text/javascript">
<!--
// 入力チェック
function inputChk(f){

	var flg = false;var error_mes = "";
	/*
	if(!f.name.value){error_mes += "・お名前を入力してください。\n\n";flg = true;}

	if(!f.profile.value){error_mes += "・プロフィールを入力してください。\n\n";flg = true;}
	*/

	if(flg){window.alert(error_mes);return false;}
	else{return confirm('入力いただいた内容で登録します。\nよろしいですか？');}
}
//-->
</script>
<script src="../tag_pg/cms.js" type="text/javascript"></script>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>

	<form action="../main.php" method="post" target="_self">
		<input type="submit" value="管理画面トップへ" style="width:150px;">
	</form>

	<p class="page_title">プロフィール管理</p>
	<p class="explanation">
	▼ ブログのプロフィールの登録を行います。
	</p>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data" onSubmit="return inputChk(this);" style="margin:0px;" name="p_update">

<?php echo $mes;?>

<table width="550" border="1" cellpadding="2" cellspacing="0">
	<tr>
		<th colspan="3" class="tdcolored">
		■プロフィールの登録
		</th>
	</tr>
	<tr>
		<td width="170" align="left" nowrap class="tdcolored">
			プロフィール画像：
		</td>
		<td class="other-td">
			<?php if(file_exists(IMG_FILE_PATH."p_img.jpg")){?>
				<img src="<?php echo IMG_FILE_PATH."p_img.jpg"; ?>?r=<?php echo rand(); ?>">
				<br>
				<input type="checkbox" name="del_pimg" value="1" id="del_pimg"><label for="del_pimg">プロフィール画像を削除する</label>
			<?php }?>
			アップロード後画像サイズ：<br><strong>横<?php echo P_IMGSIZE_X;?>px×縦 自動算出</strong>
			<br>
			<input type="file" name="p_img" value="">
		</td>
	</tr>
	<tr>
		<td width="170" align="left" nowrap class="tdcolored">
			ニックネーム：
		</td>
		<td class="other-td">
			<input type="text" name="name" value="<?php echo $fetch[0]["NAME"];?>" size="60">
		</td>
	</tr>
	<tr>
		<td width="170" align="left" nowrap class="tdcolored">
			プロフィール：
		</td>
		<td class="other-td">
			趣味・特技・目標・好きな言葉など自己紹介を記入する欄です。<br>

			<a href="javascript:void(0)" onClick="addStyle(document.p_update.profile,'p','text-align:left;margin-top:0px;margin-bottom:0px;'); return false;"><img src="../tag_pg/img/text_align_left.png" width="16" height="16" alt="テキストを左寄せ" border="0"></a>
			<a href="javascript:void(0)" onClick="addStyle(document.p_update.profile,'p','text-align:center;margin-top:0px;margin-bottom:0px;'); return false;"><img src="../tag_pg/img/text_align_center.png" width="16" height="16" alt="テキストを真中寄せ" border="0"></a>
			<a href="javascript:void(0)" onClick="addStyle(document.p_update.profile,'p','text-align:right;margin-top:0px;margin-bottom:0px;'); return false;"><img src="../tag_pg/img/text_align_right.png" width="16" height="16" alt="テキストを右寄せ" border="0"></a>
			<a href="javascript:void(0)" onClick="CheckObj();addLink(Temp.name); return false;"><img src="../tag_pg/img/link.png" width="16" height="16" alt="リンク" border="0"></a>
			<a href="javascript:void(0)" onClick="CheckObj();addTag(Temp.name,'strong'); return false;"><img src="../tag_pg/img/text_bold.png" width="16" height="16" alt="太字" border="0"></a>
			<a href="javascript:void(0)" onClick="CheckObj();addTag(Temp.name,'u'); return false;"><img src="../tag_pg/img/text_underline.png" width="16" height="16" alt="下線" border="0"></a>
			<a href="javascript:void(0)" onClick="CheckObj();obj=Temp.name;MM_showHideLayers('<?php echo $layer_free;?>',obj.name,'show');OnLink('<?php echo $layer_free;?>',event.x,event.y,event.pageX,event.pageY); return false;"><img src="../tag_pg/img/rainbow.png" alt="テキストカラー" border="0"></a>
			<br>

			<textarea name="profile" cols="30" rows="20" onFocus="SaveOBJ(this)" ><?php echo $fetch[0]["PROFILE"];?></textarea>
		</td>
	</tr>
	<tr>
		<td width="170" align="left" nowrap class="tdcolored">プロフィールの表示：</td>
		<td class="other-td">
			プロフィールは不必要な場合は非表示にしてください。<br>

			<input type="radio" name="display_flg" value="1" id="disp_on" <?php echo ($fetch[0]["DISPLAY_FLG"] == "1")?"checked":"";?>><label for="disp_on">プロフィールを表示</label>
			<br>
			<input type="radio" name="display_flg" value="" id="disp_off" <?php echo ($fetch[0]["DISPLAY_FLG"] != "1")?"checked":"";?>><label for="disp_off">プロフィールを非表示</label>
		</td>
	</tr>

</table>

<br><br>
<input type="submit" value="内容を更新する" style="width:150px;">
<input type="hidden" name="action" value="update">
</form>
<br>
<br>
<table width="750" border="0" cellspacing="0" cellpadding="5">
  <tr>
	<td style="background-image:url(<?php echo $title_img."?r=".rand();?>)" width="750" height="<?php echo $size_y;?>" valign="bottom">
	<h1><?php echo $fetch[0]["TITLE"];?></h1>
	<h3><?php echo nl2br($fetch[0]["SUB_TITLE"]);?></h3>
	</td>
  </tr>
</table>

<?php

//ボタン付近に表示する
cp_disp($layer_free, "0", "0");

?>

</body>
</html>
