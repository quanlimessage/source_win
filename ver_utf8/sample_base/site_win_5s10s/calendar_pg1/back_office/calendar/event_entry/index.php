<?php
/*******************************************************************************
スケジュール内容登録

*******************************************************************************/

session_start();

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}

// 設定ファイル＆共通ライブラリの読み込み
require_once("../../../common/config.php");	// 設定情報

require_once("dbOpe.php");					// DB操作クラスライブラリ
require_once("util_lib.php");				// 汎用処理クラスライブラリ
require_once("imgOpe.php");					// 画像アップロードクラスライブラリ

$weekday_list = array( "日", "月", "火", "水", "木", "金", "土" );

#--------------------------------------------------------------------------------
# GET受信データの処理
#--------------------------------------------------------------------------------
// GETデータの受け取りと共通な文字列処理
if($_POST)extract(utilLib::getRequestParams("post",array(8,7,1,4,5)));

// デコード
$ev   = urldecode($ev);
$scid = urldecode($scid);
$d    = urldecode($d);

if(!empty($_POST["action"])){

	// POSTデータの受取と文字列処理
	//extract(utilLib::getRequestParams("post",array(8,7,1,4),true));

	// 半角カナを全角かなに変換
	$title  = mb_convert_kana($title,"KV");
	$url  = mb_convert_kana($url,"KV");

}

#===============================================================================
# 更新ボタンが押されたら送信データをチェックし、DBの管理者情報を更新する
#===============================================================================
switch($_POST["action"]):

	case "update":

		// 入力値のエラーチェック
		$error_mes = "";
		$error_mes .= utilLib::strCheck($title,0,"タイトルを入力してください。\n");

		#--------------------------------------------------------------------
		# 入力内容に不備がある場合はエラー出力で強制終了し、
		# 正常処理が出来た場合は入力データを更新する
		#--------------------------------------------------------------------
		if($error_mes):

			utilLib::errorDisp($error_mes);exit();
		endif;

			$sql = "
			UPDATE
				".EVENT."
			SET
				TITLE  = '".utilLib::strRep($title,5)."',
				DETAIL_TITLE  = '".utilLib::strRep($detail_title,5)."',
				DETAIL_CONTENT  = '".utilLib::strRep($detail_content,5)."',
				UPD_DATE = NOW()
			WHERE
				(ID = '".$id."')
			";
			$db_result = dbOpe::regist($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
			if($db_result){die("データの更新に失敗しました<hr>{$db_result}");}

			//スケジュールマスタの更新
			$sql = "
					UPDATE
						".SCHEDULE."
					SET
						DAY_" . $d . " = '1',
						UPD_DATE = NOW()
					WHERE
						(SCHEDULE_ID = '".$scid."')
				";

			$db_result = dbOpe::regist($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
			if($db_result){die("データの更新に失敗しました<hr>{$db_result}");}else{$msg="スケジュールを登録しました。";}

	break;

	case "insert":

		// 入力値のエラーチェック
		$error_mes = "";
		$error_mes .= utilLib::strCheck($title,0,"タイトルを入力してください。\n");

		#--------------------------------------------------------------------
		# 入力内容に不備がある場合はエラー出力で強制終了し、
		# 正常処理が出来た場合は入力データを更新する
		#--------------------------------------------------------------------
		if($error_mes):

			utilLib::errorDisp($error_mes);exit();
		endif;

		$sql = "
		INSERT INTO ".EVENT."
		SET
			SCHEDULE_ID  = '".utilLib::strRep($scid,5)."',
			DAY          = '".utilLib::strRep($d,5)."',
			TITLE  = '".utilLib::strRep($title,5)."',
			DETAIL_TITLE  = '".utilLib::strRep($detail_title,5)."',
			DETAIL_CONTENT  = '".utilLib::strRep($detail_content,5)."',
			INS_DATE = NOW()
		";
		$db_result = dbOpe::regist($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
		if($db_result){die("データの更新に失敗しました<hr>{$db_result}");}

		//スケジュールマスタの更新
		$sql = "
				UPDATE
					".SCHEDULE."
				SET
					DAY_" . $d . " = '1',
					UPD_DATE = NOW()
				WHERE
					(SCHEDULE_ID = '".$scid."')
			";

		$db_result = dbOpe::regist($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
		if($db_result){die("データの更新に失敗しました<hr>{$db_result}");}else{$msg="スケジュールを更新しました。";}

	break;

	case "delete":

		//該当スケジュールの削除処理
		$sql = "
			DELETE FROM ".EVENT."
			WHERE
				(ID = '".$id."')
		";
		$db_result = dbOpe::regist($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
		if($db_result){die("データの削除に失敗しました<hr>{$db_result}");}

		//スケジュールマスタの更新
		$sql = "
				UPDATE
					".SCHEDULE."
				SET
					DAY_" . $d . " = '0',
					UPD_DATE = NOW()
				WHERE
					(SCHEDULE_ID = '".$scid."')
			";

		$db_result = dbOpe::regist($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
		if($db_result){die("データの更新に失敗しました<hr>{$db_result}");}else{$msg="スケジュールを削除しました。";}

		/*
		if(file_exists(IMG_PATH.$scid . "_" . $d.".jpg")){
			unlink(IMG_PATH.$scid . "_" . $d.".jpg") or die("画像の削除に失敗しました。");
		}
		*/

endswitch;

#==================================================================
# 共通処理：画像アップロード処理
#==================================================================
/*
if($_POST["action"] != "delete"){
	// 画像処理クラスimgOpeのインスタンス生成
	$imgObj = new imgOpe(IMG_PATH);

	// アップロードされた画像ファイルがあればアップロード処理
	if(is_uploaded_file($_FILES['up_img']['tmp_name'])){

		// 画像のアップロード：画像名は(記事ID_画像番号.jpg)
		//イメージの読み込み
		$param = GetImageSize($_FILES['up_img']['tmp_name']);
		//縦横を決定
		$FileWidth      = $param[0];	// 幅
		$FileHeight     = $param[1];	// 高さ

		$imgObj->setSize(IMGSIZE_MX, (IMGSIZE_MX /$FileWidth ) * $FileHeight);
		$imgObj->isFixed=false;
		if(!$imgObj->up($_FILES['up_img']['tmp_name'],$scid . "_" . $d)){
			exit("画像のアップロード処理に失敗しました。");
		}

	}
}
*/
// DBより全管理者情報を取得する
$sql = "
	SELECT

		ID,
		TITLE,
		DETAIL_TITLE,
		DETAIL_CONTENT
	FROM
		".EVENT."
	WHERE
		(SCHEDULE_ID = '".$scid."')
	AND
		(DAY = '".$d."')

	";

$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

// actionの指定
if(empty($fetch[0]["ID"])){
	$action = "insert";
}else{
	$action = "update";
}

//一覧に戻る様に年月を取得する
$sql = "
	SELECT

		ID,
		SCHEDULE_ID,
		YEAR,
		MONTH
	FROM
		".SCHEDULE."
	WHERE
		(SCHEDULE_ID = '".$scid."')
	";

$fetchBACK = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8",true,true,true,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<title>スケジュール内容登録</title>
<script type="text/javascript">
<!--
// 入力チェック
function inputChk(f){

	var flg = false;var error_mes = "恐れ入りますが、下記の内容を確認してください。\n\n";

	if(!f.title.value){error_mes += "・一覧用タイトルを入力してください。\n\n";flg = true;}
	//if(!f.detail_title.value){error_mes += "・詳細用タイトルを入力してください。\n\n";flg = true;}
	//if(!f.detail_content.value){error_mes += "・詳細用本文を入力してください。\n\n";flg = true;}

	if(flg){window.alert(error_mes);return false;}
	else{return confirm('入力いただいた内容で登録します。\nよろしいですか？');}
}
//-->
</script>
<link href="../../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body onload="focus()">

<?php if(!empty($msg)){?>
  <h3><font color="FF000000"><?php echo $msg;?></font></h3>
<?php }?>

<form name="new_regist" action="./" method="post" enctype="multipart/form-data" onSubmit="return inputChk(this);" style="margin:0px;">
<table width="510" border="1" cellpadding="2" cellspacing="0">
	<tr>
		<th colspan="2" nowrap class="tdcolored">■登録・更新（<?php echo $fetchBACK[0]['YEAR']."年 ".$fetchBACK[0]['MONTH']."月 ".$d."日";?> <?php echo $weekday_list[date('w',mktime(0, 0, 0, $fetchBACK[0]['MONTH'], $d, $fetchBACK[0]['YEAR']))]."曜日";?>）</th>
	</tr>
	<tr>
		<th nowrap class="tdcolored">一覧用タイトル：</th>
		<td class="other-td">
		<textarea name="title" cols="55" rows="5" style="ime-mode:active"><?php echo $fetch[0]["TITLE"];?></textarea>
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">詳細用タイトル：</th>
		<td class="other-td">
		<textarea name="detail_title" cols="55" rows="5" style="ime-mode:active"><?php echo $fetch[0]["DETAIL_TITLE"];?></textarea>
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">詳細用本文：</th>
		<td class="other-td">
		<textarea name="detail_content" cols="55" rows="5" style="ime-mode:active"><?php echo $fetch[0]["DETAIL_CONTENT"];?></textarea>
		</td>
	</tr>
	<?php /*
	<tr>
		<th nowrap class="tdcolored">表示／非表示：</th>
		<td class="other-td">
		<input name="display_flg" id="dispon" type="radio" value="1" checked><label for="dispon">表示</label>&nbsp;&nbsp;&nbsp;&nbsp;
		<input name="display_flg" id="dispoff" type="radio" value="0"><label for="dispoff">非表示</label>
		</td>
	</tr>
	*/?>
</table>
<br>
	  <input type="submit" value="上記の内容で更新" style="width:150px;">
	  <input type="hidden" name="action" value="<?php echo $action;?>">
	  <input type="hidden" name="id" value="<?php echo $fetch[0]["ID"];?>">
	  <input type="hidden" name="scid" value="<?php echo $scid;?>">
	  <input type="hidden" name="d" value="<?php echo $d;?>">
</form>
<br>
<form name="delete" action="./" method="post" style="margin:0px;" onSubmit="return confirm('このスケジュールを完全に削除します。\n記事データの復帰は出来ません。\nよろしいですか？');">
	  <input type="submit" value="削除する" style="width:150px;">
	  <input type="hidden" name="action" value="delete">
	  <input type="hidden" name="id" value="<?php echo $fetch[0]["ID"];?>">
	  <input type="hidden" name="scid" value="<?php echo $scid;?>">
	  <input type="hidden" name="d" value="<?php echo $d;?>">
</form>
<br>

<form name="back" action="../" method="post" style="margin:0px;">
	<input type="submit" name="submit" value="一覧に戻る">
	<input type="hidden" name="y" value="<?php echo $fetchBACK[0]['YEAR'];?>">
	<input type="hidden" name="m" value="<?php echo $fetchBACK[0]['MONTH'];?>">
</form>
</body>
</html>