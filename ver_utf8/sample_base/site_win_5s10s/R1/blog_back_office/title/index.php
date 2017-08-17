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
require_once('imgOpe.php');                    // 画像アップロードクラスライブラリ

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

	if(!f.dir.value){error_mes += "・ブログデザインを入力してください。\n\n";flg = true;}

	if(!f.title.value){error_mes += "・ブログタイトルを入力してください。\n\n";flg = true;}

	if(flg){window.alert(error_mes);return false;}
	else{return confirm('入力いただいた内容で登録します。\nよろしいですか？');}
}
//-->
</script>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>

<?php
#=============================================================
# 送信ボタンが押された場合の処理
#	１．データチェック（不正ならエラー表示）
#	２．入力されたIDPW情報でデータを更新
#	３．ＯＫの場合メールを送信
#=============================================================
if ($_POST["action"] == "update"):

    // 	POSTデータの受取と文字列処理。
    extract(utilLib::getRequestParams("post", [8,7,1,4], true));

    // IDチェック
    $error_message = "";

    if (empty($title)) {
        $error_message.="タイトルが未入力です。<br>\n";
    }

    if (empty($dir)) {
        $error_message.="ブログデザインが未入力です。<br>\n";
    }

    /*
    else{
        $strlen_list = @file_get_contents(CSS_FILE_PATH.$dir."/styles-index.css");
        if(strlen($strlen_list) == 0){
            $error_message.="指定されたデザイン名(".CSS_FILE_PATH.$dir."/styles-index.css)はありません。<br>デザイン一覧に表示されているデザイン名をご登録してください。\n";
        }
        $strlen_list = "";
    }
    */

    // エラーがあればエラー表示して終了
    if ($error_message):

        utilLib::errorDisp($error_message);exit();
    else:

        #--------------------------------------------------------
        # データを更新し、管理者宛にメール通知をしてスルー
        #--------------------------------------------------------

        // 新ID／PWでデータ更新（DBに都合が悪い数字はエスケープ）
        $sql = "
		UPDATE
			BLOG_TITLE
		SET
			TITLE      = '$title',
			SUB_TITLE  = '$sub_title',
			SENDPING_URL  = '$sendping_url',
			DIR        = '$dir',
			IMG_SELECT = '$img_select',
			HEADER_IMG = '$header_img',
			LINK_TITLE1 = '$link_title1',
			LINK_URL1   = '$link_url1',
			LINK_TITLE2 = '$link_title2',
			LINK_URL2   = '$link_url2',
			LINK_TITLE3 = '$link_title3',
			LINK_URL3   = '$link_url3',
			LINK_TITLE4 = '$link_title4',
			LINK_URL4   = '$link_url4',
			LINK_TITLE5 = '$link_title5',
			LINK_URL5   = '$link_url5',
			LINK_TITLE6 = '$link_title6',
			LINK_URL6   = '$link_url6',
			LINK_TITLE7 = '$link_title7',
			LINK_URL7   = '$link_url7',
			LINK_TITLE8 = '$link_title8',
			LINK_URL8   = '$link_url8',
			LINK_TITLE9 = '$link_title9',
			LINK_URL9   = '$link_url9',
			LINK_TITLE10 = '$link_title10',
			LINK_URL10   = '$link_url10',
			IP_ADD     = '$ip_add',
			DISP_MAXROW = '$disp_maxrow'
		WHERE
			(T_ID = '1')
		";
        $PDO->regist($sql);

#=================================================================================
# 画像アップロード処理
#=================================================================================

        if (is_uploaded_file($_FILES['title_img']['tmp_name'])) {

        // 画像処理クラスimgOpeのインスタンス生成
        $imgObj = new imgOpe(IMG_FILE_PATH);

            $size = getimagesize($_FILES['title_img']['tmp_name']);
            $size_y = $size[1];
            // 画像のサイズは幅760、高さは自由
            // $imgObj->setSize(IMGSIZE_LX,IMGSIZE_LY);
            $imgObj->setSize(IMGSIZE_LX, $size_y);

            // 画像のアップロード：画像名は(記事ID_画像番号.jpg)
            if (!$imgObj->up($_FILES['title_img']['tmp_name'], title)) {
                exit("画像のアップロード処理に失敗しました。");
            }
        }

        // 完了画面を表示
        echo    "<p class=\"explanatio\"><strong>更新が完了しました</strong></p>\n",
                "<form action=\"{$_SERVER['PHP_SELF']}\" method=\"post\">\n",
                "<input type=\"submit\" value=\"タイトル管理トップへ戻る\">\n</form>\n";

    endif;

else:

    $sql = "
	SELECT

		TITLE,
		SUB_TITLE,
		DIR,
		IP_ADD,
		SENDPING_URL,
		LINK_TITLE1,
		LINK_URL1,
		LINK_TITLE2,
		LINK_URL2,
		LINK_TITLE3,
		LINK_URL3,
		LINK_TITLE4,
		LINK_URL4,
		LINK_TITLE5,
		LINK_URL5,
		LINK_TITLE6,
		LINK_URL6,
		LINK_TITLE7,
		LINK_URL7,
		LINK_TITLE8,
		LINK_URL8,
		LINK_TITLE9,
		LINK_URL9,
		LINK_TITLE10,
		LINK_URL10,
		IMG_SELECT,
		HEADER_IMG,
		DISP_MAXROW

	FROM

		BLOG_TITLE

	WHERE

		T_ID = '1'";

    $fetch = $PDO->fetch($sql);

    // header画像
    switch ($fetch[0]["IMG_SELECT"]):
        case "1":
            $title_img = CSS_FILE_PATH."/header_img/".$fetch[0]['HEADER_IMG'].".jpg";
            $size_y = "200";
        break;
        case "2":
            $title_img = IMG_FILE_PATH."title.jpg";
            $size = getimagesize($title_img);
            $size_y = $size[1];
        break;
    endswitch;

?>

<form action="../main.php" method="post" target="_self">
<input type="submit" value="管理画面トップへ" style="width:150px;">
</form>
<p class="page_title">ブログタイトル管理</p>
<p class="explanation">
▼ ブログ自体のタイトル及びヘッダー画像,ブログデザインの登録を行います。<br>
▼ デザイン一覧を開き「デザインタイトル」をブログデザイン入力フォームに入力するとデザインの変更を行えます。<br>
▼ コメント入力のアクセスを制限したいIPアドレスを「,」カンマ区切りでアクセス制限入力欄に入力するとコメントの入力を制限する事が出来ます。<br>
▼ リンク文字とリンクURLを入力すると１つだけ外部にリンクを貼る事が出来ます。
</p>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data" onSubmit="return inputChk(this);" style="margin:0px;">
<table width="550" border="1" cellpadding="2" cellspacing="0">
	<tr>
		<th colspan="3" class="tdcolored">
		■タイトル・ヘッダー画像の登録
		</th>
	</tr>
	<tr>
		<td width="170" align="left" nowrap class="tdcolored">ブログデザイン：<br><br>
		<a href="javascript:void(window.open('<?php echo CSS_FILE_PATH;?>back_office/style_list/','popup','width=670,height=600,scrollbars=yes,resizable=yes'));">デザイン一覧</a><br><br>
		<a href="../../blog/" target="_blank">サイトを確認</a>
		</td>
		<td class="other-td">ここにデザインタイトルを入力↓<br>※例 black , blue等<br>
		<input name="dir" type="text" size="40" value="<?php echo $fetch[0]["DIR"];?>" style="ime-mode:disabled;">
		</td>
	</tr>
	<tr>
		<td width="170" align="left" nowrap class="tdcolored">
		ヘッダー画像：
		<table width="100%" border="0">
		<tr>
				<td width="50%">
				<a href="javascript:void(window.open('<?php echo CSS_FILE_PATH;?>back_office/img_list/','popup','width=500,height=600,scrollbars=yes,resizable=yes'));">画像一覧</a>
				<br><br></td>
				<td width="50%">
				画像一覧より選択する<br>
				<input type="radio" name="img_select" value="1"<?php echo ($fetch[0]["IMG_SELECT"] == 1 || empty($fetch[0][""]))?" checked":"";?>>
				</td>
		</tr>
</table>
		</td>
		<td class="other-td">ここに画像名を入力↓<br>※例 black_img , blue_img等<br>
		<input name="header_img" type="text" size="40" value="<?php echo $fetch[0]["HEADER_IMG"];?>" style="ime-mode:disabled;">
		</td>
	</tr>
	<tr>
		<td width="170" align="left" nowrap class="tdcolored">
		ヘッダー画像：
		<table width="100%" border="0">
		<tr>
				<td width="50%">&nbsp;</td>
				<td width="50%">
				自分で画像をアップロードする<br>
				<input type="radio" name="img_select" value="2"<?php echo ($fetch[0]["IMG_SELECT"] == 2)?" checked":"";?>>
				</td>
		</tr>
</table>
		</td>
		<td class="other-td">
		アップロード後画像サイズ：<br><strong>横<?php echo IMGSIZE_LX;?>px×縦(FREE)</strong>
		<br>
		<input type="file" name="title_img" value="">
		</td>
	</tr>
	<tr>
		<td width="170" align="left" nowrap class="tdcolored">ブログタイトル：</td>
		<td class="other-td">
		<input name="title" type="text" size="40" value="<?php echo $fetch[0]["TITLE"];?>" style="ime-mode:active;">
		</td>
	</tr>
	<tr>
		<td width="170" align="left" nowrap class="tdcolored">ブログサブタイトル：</td>
		<td class="other-td"><textarea name="sub_title" cols="50" rows="3" style="ime-mode:active"><?php echo $fetch[0]["SUB_TITLE"];?></textarea></td>
	</tr>
	<tr>
		<td width="170" align="left" nowrap class="tdcolored">コメントのアクセス制限：<br>例 210.138.173.44,210.138.173.45</td>
		<td class="other-td">
		<textarea name="ip_add" cols="50" rows="2" style="ime-mode:disabled"><?php echo $fetch[0]["IP_ADD"];?></textarea>
		</td>
	</tr>
	<tr>
		<td width="170" align="left" nowrap class="tdcolored">更新pingURL：<br>（改行区切り）</td>
		<td class="other-td">
		<textarea name="sendping_url" cols="50" rows="2" style="ime-mode:disabled"><?php echo $fetch[0]["SENDPING_URL"];?></textarea>
		</td>
	</tr>
	<?php for ($i=1;$i<=10;$i++):?>
	<tr>
		<td width="170" align="left" nowrap class="tdcolored">リンク<?php echo $i?>：</td>
		<td class="other-td">
		<table>
		<tr>
		<td align="left" nowrap class="tdcolored">リンク文字： </td>
		<td class="other-td">
		<input name="link_title<?php echo $i;?>" type="text" size="60" value="<?php echo $fetch[0]["LINK_TITLE".$i];?>" style="ime-mode:active;">
		</td>
		</tr>
		<tr>
		<td align="left" nowrap class="tdcolored">URL： </td>
		<td class="other-td">
		<input name="link_url<?php echo $i;?>" type="text" size="60" value="<?php echo $fetch[0]["LINK_URL".$i];?>" style="ime-mode:disabled;">
		</td>
		</tr>
		</table>
		</td>
	</tr>
	<?php endfor;?>
	<tr>
		<td width="170" align="left" nowrap class="tdcolored">1ページあたりの表示件数：</td>
		<td class="other-td">
		<select name="disp_maxrow">
		<?php for ($i=1;$i<=10;$i++):?>
		<option value="<?php echo $i;?>"<?php echo ($i== $fetch[0]["DISP_MAXROW"])?" selected":"";?>><?php echo $i;?></option>
		<?php endfor;?>
		</select> 件
		<br>※カレンダーから日付を指定した際のみ、<br>　その日に登録された記事が全て表示されます。
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

<?php endif;?>

</body>
</html>
