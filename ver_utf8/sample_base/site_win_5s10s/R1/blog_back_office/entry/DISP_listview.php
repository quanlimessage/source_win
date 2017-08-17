<?php
/*******************************************************************************
ALL-INTERNET BLOG

View：エントリー一覧表示（最初に表示する）

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
// 不正アクセスチェック（直接このファイルにアクセスした場合）
if (!$_SESSION['LOGIN']) {
    header("Location: ../err.php");
    exit();
}
if (!$accessChk) {
    header("Location: ../");
    exit();
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
        $tcnt = count($fetchCNT);
        // 全ページ数
        $totalpage = ceil($tcnt/DISP_ENTRY_MAXROW);

        // カテゴリー別で表示していればページ遷移もカテゴリーパラメーターをつける
        if ($ca) {
            $cpram = "&ca=".urlencode($ca);
        }

        // 前ページへのリンク
        if ($p > 1) {
            $link_prev = "<a href=\"".$_SERVER['PHP_SELF']."?category_code=".urlencode($category_code)."&p=".urlencode($prev).$cpram."\">&lt;&lt;　前の頁へ</a>";
        }

        //次ページリンク
        if ($totalpage > $p) {
            $link_next = "<a href=\"".$_SERVER['PHP_SELF']."?category_code=".urlencode($category_code)."&p=".urlencode($next).$cpram."\">次の頁へ &gt;&gt;</a>";
            $disp_lcn = $p * DISP_ENTRY_MAXROW;
        } else {
            $disp_lcn = $tcnt;
        }

        //表示位置を取得
        $disp_cn = (($p-1) * DISP_ENTRY_MAXROW + 1)."～".$disp_lcn;

#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／有効期限の設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8", true, false, true, true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="header"></div>
<br>
<br>
<table width="400" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
		<form action="../main.php" method="post">
			<input type="submit" value="管理画面トップへ" style="width:150px;">
		</form>
		</td>
		<td>&nbsp;</td>
	</tr>
</table>

<p class="page_title">記事のエクスポート</p>

<form action="../output.php" method="post">
<input type="submit" value="エクスポート">
</form>

<p class="explanation">
▼新規登録を行う際は、<strong>「新規エントリー」</strong>をクリックしてください。<br>
▼既存記事データの修正は<strong>「編集」</strong>をクリックしてください<br>
▼<strong>「表示中」「現在非表示」</strong>をクリックで切替えると表示ページでの表示を制御します。<br>
▼<strong>「削除」</strong>をクリックすると登録されている記事データが削除されます。<br>
▼最大登録件数は<strong><?php echo ENTRY_MAXROW;?>件</strong>です。
</p>

<p class="page_title">新規エントリー</p>
<?php if ($fetche_cnt[0]['CNT'] < ENTRY_MAXROW):?>

<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
<input type="submit" value="新規エントリー" style="width:150px;">
<input type="hidden" name="act" value="new_entry">
</form>
<?php else:?>
<p class="err">最大登録件数<?php echo ENTRY_MAXROW;?>件に達しています。<br>
新規登録を行う場合は、いずれかの既存データを削除してください。</p>
<?php endif;?>
<p class="page_title">エントリー一覧</p>

<?php if (!$fetch):?>
<div>※登録エントリー件数：<strong><?php echo count($fetchCNT);?></strong>&nbsp;件（総合登録件数：<strong><?php echo count($fetchALL);//$fetche_cnt[0]['CNT'];?></strong>&nbsp;件）</div>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" style="margin:0;">
<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tr>
	<td align="right">
	<select name="category_code" onChange="return submit();">
		<option value="">カテゴリーごとにエントリーを表示</option>
		<?php for ($i=0;$i<count($fetch_Ca);$i++):?>
		<option value="<?php echo urlencode($fetch_Ca[$i]["CATEGORY_CODE"]);?>" <?php if($category_code == $fetch_Ca[$i]["CATEGORY_CODE"]){echo "selected";}?>>
		<?php echo $fetch_Ca[$i]["CATEGORY_NAME"];?>
		</option>
		<?php endfor;?>
	</select>
	</td>
  </tr>
</table>
</form>

<p><b>登録されている記事はありません。</b></p>
<?php else:?>
<div>※登録エントリー件数：<strong><?php echo count($fetchCNT);?></strong>&nbsp;件（総合登録件数：<strong><?php echo $fetche_cnt[0]['CNT'];?></strong>&nbsp;件）</div>
<?php echo $link_prev." ".$link_next;?> <br>
（現在<?php echo $disp_cn;?>件を表示中  ※<?php echo DISP_ENTRY_MAXROW;?>件ごとに表示）

<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" style="margin:0;">
<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tr>
	<td align="right">
	<select name="category_code" onChange="return submit();">
		<option value="">カテゴリーごとにエントリーを表示</option>
		<?php for ($i=0;$i<count($fetch_Ca);$i++):?>
		<option value="<?php echo urlencode($fetch_Ca[$i]["CATEGORY_CODE"]);?>" <?php if($category_code == $fetch_Ca[$i]["CATEGORY_CODE"]){echo "selected";}?>>
		<?php echo $fetch_Ca[$i]["CATEGORY_NAME"];?>
		</option>
		<?php endfor;?>
	</select>
	</td>
  </tr>
</table>
</form>
<br>
<table border="1" cellpadding="2" cellspacing="0" width="700">
	<tr class="tdcolored">
		<th nowrap>タイトル</th>
		<th width="15%" nowrap>カテゴリー</th>
		<th width="10%" nowrap>投稿日付</th>
		<th width="10%" nowrap>画像</th>
		<th width="5%" nowrap>編集</th>
		<th width="10%" nowrap>表示状態</th>
		<th width="5%" nowrap>削除</th>
		<th width="5%" nowrap>プレビュー</th>
		<th width="5%" nowrap>ツイート</th>

	</tr>
	<?php for ($i=0;$i<count($fetch);$i++):?>
	<tr class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">
		<td align="center">&nbsp;<?php echo (!empty($fetch[$i]['TITLE']))?$fetch[$i]['TITLE']:"No Title";?></td>
		<td align="center">&nbsp;<?php echo $fetch[$i]['CATEGORY_NAME'];?></td>
		<td align="center"><?php echo $fetch[$i]["Y"].".".$fetch[$i]["M"].".".$fetch[$i]["D"];?></td>

		<td align="center">
		<?php

        //画像の拡張子
        $img_ext = ($fetch[$i]['EXTENTION1'])?$fetch[$i]['EXTENTION1']:"jpg";

         if (file_exists(IMG_FILE_PATH.$fetch[$i]['RES_ID']."_1.".$img_ext)):?>
		<a href="<?php echo IMG_FILE_PATH.$fetch[$i]['RES_ID'];?>_1.<?php echo $img_ext;?>" target="_blank">
		<img src="<?php echo IMG_FILE_PATH.$fetch[$i]['RES_ID'];?>_1.<?php echo $img_ext;?>?r=<?php echo rand();?>" alt="画像" border="0" width="<?php echo IMGSIZE_SX;?>">
		</a>
		<?php else:
            echo '&nbsp;';
        endif;?>
		</td>
		<td align="center">
		<form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>" style="margin:0;">
		<input type="submit" name="reg" value="編集">
		<input type="hidden" name="act" value="update">
		<input type="hidden" name="res_id" value="<?php echo $fetch[$i]['RES_ID'];?>">
		<input type="hidden" name="p" value="<?php echo $p;?>">
		</form>
		</td>
		<td align="center">
		<form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>" style="margin:0;">
		<input type="submit" name="reg" value="<?php echo ($fetch[$i]["DISPLAY_FLG"] == 1)?"表示中":"現在非表示";?>" style="width:75px;">
		<input type="hidden" name="res_id" value="<?php echo $fetch[$i]['RES_ID'];?>">
		<input type="hidden" name="act" value="display_change">
		<input type="hidden" name="category_code" value="<?php echo $category_code;?>">
		<input type="hidden" name="display_change" value="<?php echo ($fetch[$i]["DISPLAY_FLG"] == 1)?"f":"t";?>">
		<input type="hidden" name="p" value="<?php echo $p;?>">
		</form>
		</td>
		<td align="center">
		<form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>" style="margin:0;" onSubmit="return confirm('この記事データを完全に削除します。\n記事データの復帰は出来ません。\nよろしいですか？');">
		<input type="submit" value="削除">
		<input type="hidden" name="res_id" value="<?php echo $fetch[$i]['RES_ID'];?>">
		<input type="hidden" name="act" value="del_data">
		<input type="hidden" name="category_code" value="<?php echo $category_code;?>">
		<input type="hidden" name="p" value="<?php echo $p;?>">
		</form>
		</td>
		<td align="center">
		<form method="post" action="../../blog/" target="_blank" style="margin:0;">
		<input type="submit" value="プレビュー">
		<input type="hidden" name="res_id" value="<?php echo $fetch[$i]['RES_ID'];?>">
		<input type="hidden" name="ca" value="<?php echo $category_code;?>">
		<input type="hidden" name="act" value="prev">
		</form>
		</td>
		<td align="center">
		<a href="http://twitter.com/?status=<?php echo (!empty($fetch[$i]['TITLE']))?urlencode(mb_convert_encoding($fetch[$i]['TITLE'], "UTF-8", "UTF-8")):"";?>%20<?php echo urlencode(BLOG_SITE_LINK."/blog/u".$fetch[$i]['RES_ID']."/");?>" target="_blank"><img title="この記事についてTwitterでつぶやく" src="../img/tweetn-ja.png" border="0"></a>
		</td>
	</tr>
	<?php endfor;?>
</table>
<?php endif;?>

</body>
</html>
