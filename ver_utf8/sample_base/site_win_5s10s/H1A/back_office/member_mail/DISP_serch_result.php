<?php
/*******************************************************************************
会員メール配信　 バックオフィス（MySQL対応版）

View：検索結果の表示画面

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}

if( !$injustice_access_chk){
	header("Location: ../"); exit();
}

	#--------------------------------------------------------
	# ページング用リンク文字列処理
	#--------------------------------------------------------

	//ページリンクの初期化
	$link_prev = "";
	$link_next = "";

	// 次ページ番号
	$next = $p + 1;
	// 前ページ番号
	$prev = $p - 1;

	// 商品全件数
	$tcnt = $fetchCNT[0]['CNT'];

	// 全ページ数
	$totalpage = ceil($tcnt/MEMBER_DISP_MAXROW);

	// 前ページへのリンク
	if($p > 1){
		$link_prev = "<a href=\"javascript:void(0);\" onclick=\"n_data(".($p-1).");\">&lt;&lt; 前のページへ</a>";
	}

	//次ページリンク
	if($totalpage > $p){
		$link_next = "<a href=\"javascript:void(0);\" onclick=\"n_data(".($p+1).");\">次のページへ &gt;&gt;</a>";
	}

#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／有効期限の設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8",true,false,true,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>

<link href="../for_bk.css" rel="stylesheet" type="text/css" media="all">
<link href="../for_bk_print.css" rel="stylesheet" type="text/css" media="print">

<script type="text/javascript">

//選択
function AllCheck(truth){
	var me = document.send_mail_base;
	var i;
	for(i=0;i<me.length;i++){
		if(me.elements[i].name.substring(0,10) == "send_check"){
				me.elements[i].checked = truth;
		}
	}
}

function n_data(mp){//ページネーション処理をする

	//チェックされた箇所を調べる
		//formタグの入れ子は出来ない為、全inputタグでの全体で調べる
		for(var i = 0; i < document.getElementsByTagName("input").length; i ++){//全項目数を数える

			if((document.getElementsByTagName("input")[i].name == 'send_check[]') && (document.getElementsByTagName("input")[i].checked)){//条件に一致するnameを探す、そしてチェックされているかも確かめる
				document.getElementById("nd_id_ok_stock").value += document.getElementsByTagName("input")[i].value + ",";//チェックされたIDを入れる
			}else if((document.getElementsByTagName("input")[i].name == 'send_check[]') && (!document.getElementsByTagName("input")[i].checked)){
				document.getElementById("nd_id_ng_stock").value += document.getElementsByTagName("input")[i].value + ",";//チェックされたIDを入れる
			}
		}

	document.getElementById("p").value = mp;//移動先のページ指定

	//データを入れ終わったらsubmit処理を実行
	document.npage.submit();

}

function sendm_data(){//決定の場合の処理

	if(confirm('この送信チェック内容を保存し、\nメルマガの内容作成をします。\nよろしいですか？')){
		//チェックされた箇所を調べる
		for(var i = 0; i < document.getElementsByTagName("input").length; i ++){//全項目数を数える

			if((document.getElementsByTagName("input")[i].name == 'send_check[]') && (document.getElementsByTagName("input")[i].checked)){//条件に一致するnameを探す、そしてチェックされているかも確かめる
				document.getElementById("sm_nd_id_ok_stock").value += document.getElementsByTagName("input")[i].value + ",";//チェックされたIDを入れる
			}else if((document.getElementsByTagName("input")[i].name == 'send_check[]') && (!document.getElementsByTagName("input")[i].checked)){
				document.getElementById("sm_nd_id_ng_stock").value += document.getElementsByTagName("input")[i].value + ",";//チェックされたIDを入れる
			}
		}

		//データを入れ終わったらsubmit処理を実行
		//document.frm.submit();
		return true;

	}else{
		return false;
	}

}

</script>

</head>
<body>
<table border="0" cellpadding="0" cellspacing="0" style="margin-top:1em;">
	<tr>
		<td>
		<form action="../main.php" method="post">
			<input type="submit" value="管理画面トップへ" style="width:150px;">
		</form>
		</td>
	</tr>
</table>
<p class="page_title"><?php echo MEMBER_TITLE;?>：メルマガ送信先検索結果一覧</p>
<p class="explanation">▼<strong>「配信」</strong>のチェックが送信対象になります。<br></p>
<?php if(!$fetchCustList):?>
	<p>該当するお客様は登録されておりません。</p>
<?php else:?>
  <div>※検索結果：<strong><?php echo $fetchCNT[0]['CNT'];?></strong>&nbsp;件</div>

  <input type="button" value="全てにチェック" onclick="AllCheck(1);">
  &nbsp;&nbsp;&nbsp;
  <input type="button" value="全て未チェック" onclick="AllCheck(0);">

<br><br>
<table width="500" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="50" align="left">
		<?php echo $link_prev;?>
		</td>
		<td width="50" align="right">
		<?php echo $link_next;?>
		</td>
	</tr>
</table>
<br>
<?php //今現在の表示しているページの件数を表示
	$now_fnum = (MEMBER_DISP_MAXROW * ($p - 1)) + 1;//開始位置
	$now_lnum = ($totalpage > $p)?(MEMBER_DISP_MAXROW * $p):$fetchCNT[0]['CNT'];
	echo $now_fnum."～".$now_lnum."件を表示";?>
<br>

	<form action="./" method="post" name="send_mail_base" >
	<table width="800" border="0" cellpadding="0" cellspacing="0">
	<tr>
	<td>

	<table width="650" border="0" cellpadding="5" cellspacing="2" style="margin:0px;">
	<tr class="tdcolored">
	<th width="10%" nowrap>お名前</th>
	<th width="15%" nowrap>メールアドレス</th>
	<th width="5%" nowrap>登録日</th>
	<th width="5%" nowrap>メルマガ</th>
	<th width="5%" nowrap>配信</th>
	</tr>
	<?php for($i=0;$i<count($fetchCustList);$i++):?>
	<tr class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>" height="30px">
	<td align="center" nowrap class="other-td"><?php echo ($fetchCustList[$i]["NAME"])?$fetchCustList[$i]["NAME"]:"&nbsp;";?></td>
	<td align="center" class="other-td"><?php echo ($fetchCustList[$i]["EMAIL"])?$fetchCustList[$i]["EMAIL"]:"&nbsp;";?></td>
	<td align="center" class="other-td"><?php echo $fetchCustList[$i]["Y"]."/".$fetchCustList[$i]["M"]."/".$fetchCustList[$i]["D"];?></td>
	<td align="center" class="other-td"><?php echo ($fetchCustList[$i]["SENDMAIL"] == "1")?"希望する":"希望しない";?></td>
	<td align="center" class="other-td">
	<input name="send_check[]" type="checkbox" value="<?php echo $fetchCustList[$i]['MEMBER_ID'];?>" id="send_check[]" <?php echo ($fetchCustList[$i]['SENDMAIL_FLG'] == "1")?"checked":"";?>>
	<input type="hidden" name="member_id[<?php echo $i;?>]" value="<?php echo $fetchCustList[$i]['MEMBER_ID'];?>">
	</td>
	</tr>
	<?php endfor;?>
	</table>

	<?php if(count($fetchMISS) > 0):?>
	<br>
	<h3>　以下の会員はメールアドレスの形式が不正のため、メール送信が行えません。 </h3>
	<table width="650" border="0" cellpadding="5" cellspacing="2" style="float:left;margin:0px;">
	<tr class="tdcolored">
	<th width="10%" nowrap>お名前</th>
	<th width="15%" nowrap>メールアドレス</th>
	<th width="5%" nowrap>登録日</th>
	<th width="5%" nowrap>メルマガ</th>
	<th width="5%" nowrap>配信</th>
	</tr>

	<?php for($i=0;$i<count($fetchMISS);$i++):?>
	<tr class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>" height="30px">
	<td align="center" nowrap class="other-td"><?php echo $fetchMISS[$i]["REP_LAST_NAME"]." ".$fetchMISS[$i]["NAME"];?></td>
	<td align="center" class="other-td"><?php echo ($fetchMISS[$i]["EMAIL"])?$fetchMISS[$i]["EMAIL"]:"&nbsp;";?></td>
	<td align="center" class="other-td"><?php echo $fetchMISS[$i]["Y"]."/".$fetchMISS[$i]["M"]."/".$fetchMISS[$i]["D"];?></td>
	<td align="center" class="other-td">
	<?php echo ($fetchMISS[$i]["SENDMAIL"] == "1")?"希望する":"希望しない";?></td>
	<td align="center" class="other-td">×</td>
	</tr>
	<?php endfor;?>

	</table>
	<?php endif;?>

	</td>
	</tr>
	</table>
	</form>
	<br>

	<table width="500" border="0" cellpadding="0" cellspacing="0">
		<tr>
		<td width="50" align="left">
		<?php echo $link_prev;?>
		</td>
		<td width="50" align="right">
		<?php echo $link_next;?>
		</td>
		</tr>
	</table>
	<br>

	<br>
	<form action="./" method="post" style="margin:0px;" onSubmit="return sendm_data();" name="frm">
		<input type="submit" value="メルマガの内容作成へ" style="width:150px;">
		<input name="status" type="hidden" id="status" value="insert_comment">
		<input type="hidden" name="sm_nd_id_ok_stock" id="sm_nd_id_ok_stock" value="">
		<input type="hidden" name="sm_nd_id_ng_stock" id="sm_nd_id_ng_stock" value="">
	</form>

	<div style="display:none;">
	<form action="./" method="post" style="margin:0px;" name="npage">
		<input name="status" type="hidden" id="status" value="pagen">
		<input type="hidden" name="nd_id_ok_stock" id="nd_id_ok_stock" value="">
		<input type="hidden" name="nd_id_ng_stock" id="nd_id_ng_stock" value="">
		<input type="hiddem" name="p" id="p" value="">
	</form>
	</div>

	<?php endif;?>

	<br>
	<form action="./" method="post" style="margin:0px;">
		<input type="submit" value="ユーザー検索画面へ" style="width:150px;">
	</form>
	<div class="footer"></div>
</body>
</html>
