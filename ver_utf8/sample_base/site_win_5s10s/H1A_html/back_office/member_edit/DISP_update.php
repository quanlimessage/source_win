<?php
/*******************************************************************************
Sx系プログラム バックオフィス（MySQL対応版）
View：更新画面表示

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
if(!$accessChk){
	header("Location: ../");exit();
}

//最初に更新画面に入ってきた場合はデータベースからの情報を入れる
if(!$error_mes){
	$res_id = $fetch[0]['MEMBER_ID'];
	$name = $fetch[0]['NAME'];
	$kana = $fetch[0]['KANA'];
	$zip1 = $fetch[0]['ZIP_CD1'];
	$zip2 = $fetch[0]['ZIP_CD2'];
	$state = $fetch[0]['STATE'];
	$address1 = $fetch[0]['ADDRESS1'];
	$address2 = $fetch[0]['ADDRESS2'];
	$tel = $fetch[0]['TEL'];
	$fax = $fetch[0]['FAX'];
	$email = $fetch[0]['EMAIL'];
	$generation = $fetch[0]['GENERATION_CD'];
	$job = $fetch[0]['JOB_CD'];
	$mailmag = $fetch[0]['SENDMAIL'];
}

#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／有効期限の設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8",true,false,false,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
<script type="text/javascript" src="common.js"></script>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="header"></div>
<br><br>
<table width="400" border="0" cellpadding="0" cellspacing="0" style="margin-top:1em;">
	<tr>
		<td>
		<form action="../main.php" method="post">
			<input type="submit" value="管理画面トップへ" style="width:150px;">
		</form>
		</td>
	</tr>
</table>

<p class="page_title"><?php echo MEMBER_TITLE;?>：既存登録内容編集画面</p>
<p class="explanation">
▼現在の会員データ内容が初期表示されています。<br>
▼内容を編集したい場合は上書きをして「更新する」をクリックしてください。
</p>
<?php
//エラーが発生している場合エラー内容を表示する
if($error_mes){
	echo "<br><p class=\"explanation\">入力した内容に不備がございました。確認をお願いします。<br><br>\n{$error_mes}</p>\n";
	}
?>
<form action="./" method="post" enctype="multipart/form-data" onSubmit="return inputChk(this);" style="margin:0px;">
<table width="510" border="1" cellpadding="2" cellspacing="0">
	<tr>
		<th colspan="2" nowrap class="tdcolored">■登録内容の更新</th>
	</tr>
			<tr style="table-layout: fixed;">
				<td width="140">名前<span style="color:crimson;">（必須）</span></td>
				<td width="380">
					<input name="name" type="text" id="name" size="30" style="ime-mode:active;" maxlength="127" value="<?php echo $name;?>">
				</td>
			  </tr>
			  <tr bgcolor="#FFFFFF" align="left">
				<td>フリガナ<span style="color:crimson;">（必須）</span></td>
				<td>
					<input name="kana" type="text" id="kana" size="30" style="ime-mode:active;" maxlength="127" value="<?php echo $kana;?>">
					</td>
			  </tr>
			  <tr bgcolor="#FFFFFF" align="left">
				<td>郵便番号</td>
				<td>〒<input name="zip1" type="text" size="5" id="zip1" maxlength="3" style="ime-mode:disabled;" value="<?php echo $zip1;?>">
						-
					  <input name="zip2" type="text" size="7" id="zip2" maxlength="4" style="ime-mode:disabled;" value="<?php echo $zip2;?>">
                        </td>
			  </tr>
			  <tr bgcolor="#FFFFFF" align="left">
				<td>ご住所</td>
				<td>都道府県
                           <select name="state">
                              <option value="">▼選択してください▼</option>
                              <?php for($i=1;$i<count($state_list);$i++):?>
                              	<option value="<?php echo $i;?>" <?php echo ($state == $i)?"selected":"";?>><?php echo $state_list[$i];?></option>
                              <?php endfor;?>
                            </select><br>
				市区町村<br>
				<input name="address1" type="text" size="50" id="address1" maxlength="127" style="ime-mode:active;" value="<?php echo $address1;?>"><br>
				マンション名など<br>
				<input name="address2" type="text" size="50" id="address2" maxlength="127" style="ime-mode:active;" value="<?php echo $address2;?>">
                </td>
			  </tr>
			  <tr bgcolor="#FFFFFF" align="left">
				<td>電話番号</td>
				<td><input name="tel" type="text" id="tel" maxlength="30" size="20" style="ime-mode:disabled;" value="<?php echo $tel;?>"></td>
			  </tr>
			  <tr bgcolor="#FFFFFF" align="left">
				<td>FAX番号</td>
				<td><input name="fax" type="text" id="fax" maxlength="30" size="20" style="ime-mode:disabled;" value="<?php echo $fax;?>"></td>
			  </tr>
			  <tr bgcolor="#FFFFFF" align="left">
				<td>E-MAIL<span style="color:crimson;">（必須）</span></td>
				<td><input name="email" type="text" id="email" size="40" maxlength="127" style="ime-mode:disabled;" value="<?php echo $email;?>"></td>
			  </tr>

			  <tr bgcolor="#FFFFFF" align="left">
				<td>年代</td>
				<td>
					<?php for($i=1;$i<count($generation_list);$i++):?>
						<input type="radio" name="generation" value="<?php echo $i;?>" id="Generation<?php echo $i;?>"  <?php echo ($generation == $i)?"checked":"";?>><label for="Generation<?php echo $i;?>"><?php echo $generation_list[$i];?></label>　<br>
					<?php endfor;?>
				</td>
			  </tr>

			  <tr bgcolor="#FFFFFF" align="left">
				<td>職業</td>
				<td>
					<?php for($i=1;$i<count($job_list);$i++):?>
						<input type="radio" name="job" value="<?php echo $i;?>" id="job<?php echo $i;?>" <?php echo ($job == $i)?"checked":"";?>><label for="job<?php echo $i;?>"><?php echo $job_list[$i];?></label>　<br>
					<?php endfor;?>
				</td>
			  </tr>
			  <tr bgcolor="#FFFFFF" align="left">
				<td>メルマガ配信</td>
				<td>
						<input type="radio" name="mailmag" value="1" id="mailmag1" <?php echo ($mailmag != "0")?"checked":"";?>><label for="mailmag1">希望する</label><br>
						<input type="radio" name="mailmag" value="0" id="mailmag2" <?php echo ($mailmag == "0")?"checked":"";?>><label for="mailmag2">希望しない</label>
				</td>
			  </tr>
</table>
<input type="submit" value="更新する" style="width:150px;margin-top:1em;">
<input type="hidden" name="action" value="completion">
<input type="hidden" name="regist_type" value="update">
<input type="hidden" name="res_id" value="<?php echo $res_id;?>">
</form>

<form action="./" method="post">
	<input type="submit" value="会員リスト画面へ戻る" style="width:150px;">
	<input type="hidden" name="action" value="list">
</form>
</body>
</html>
