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
        $totalpage = ceil($tcnt/DISP_COMMENT_MAXROW);

        // カテゴリー別で表示していればページ遷移もカテゴリーパラメーターをつける
        if ($ca) {
            $cpram = "&ca=".urlencode($ca);
        }

        // 前ページへのリンク
        if ($p > 1) {
            $link_prev = "<a href=\"".$_SERVER['PHP_SELF']."?p=".urlencode($prev).$cpram."\">&lt;&lt;　前の頁へ</a>";
        }

        //次ページリンク
        if ($totalpage > $p) {
            $link_next = "<a href=\"".$_SERVER['PHP_SELF']."?p=".urlencode($next).$cpram."\">次の頁へ &gt;&gt;</a>";
            $disp_lcn = $p * DISP_COMMENT_MAXROW;
        } else {
            $disp_lcn = $tcnt;
        }

        //表示位置を取得
        $disp_cn = (($p-1) * DISP_COMMENT_MAXROW + 1)."～".$disp_lcn;

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
		<td>&nbsp;
		</td>
	</tr>
</table>

<p class="page_title">コメント投稿一覧</p>
<p class="explanation">
▼既存記事データの修正は<strong>「編集」</strong>をクリックしてください<br>
▼<strong>「表示中」「現在非表示」</strong>をクリックで切替えると表示ページでの表示を制御します。<br>
▼<strong>「削除」</strong>をクリックすると登録されている記事データが削除されます。
</p>
<?php if (!$fetch):?>
<p><b>登録されている記事はありません。</b></p>
<?php else:?>
<div>※登録コメント件数：<strong><?php echo $tcnt;?></strong>&nbsp;件</div>
<?php echo $link_prev." ".$link_next;?> <br>
（現在<?php echo $disp_cn;?>件を表示中  ※<?php echo DISP_COMMENT_MAXROW;?>件ごとに表示）

<table border="1" cellpadding="2" cellspacing="0" width="700">
	<tr class="tdcolored">
		<th width="20%">コメントタイトル</th>
		<th nowrap >コメント内容</th>
		<th width="10%" nowrap>投稿日付</th>
		<th width="10%" nowrap>エントリータイトル</th>
		<th width="5%" nowrap>編集</th>
		<th width="10%" nowrap>表示状態</th>
	    <th width="5%" nowrap>削除</th>
	</tr>
	<?php for ($i=0;$i<count($fetch);$i++):?>
	<tr class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">
		<td align="center">&nbsp;<?php echo (!empty($fetch[$i]['TITLE']))?$fetch[$i]['TITLE']:"No Title";?></td>
		<td align="center">&nbsp;<?php echo (!empty($fetch[$i]['CONTENT']))?mb_strimwidth($fetch[$i]['CONTENT'], 0, 160, "...", utf8):"No Comment";?></td>
		<td align="center"><?php echo $fetch[$i]["Y"].".".$fetch[$i]["M"].".".$fetch[$i]["D"];?></td>

		<td align="center"><?php echo $fetch[$i]["ENTRY_TITLE"];?></td>
		<td align="center">
		<form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>" style="margin:0;">
		<input type="submit" name="reg" value="編集">
		<input type="hidden" name="action" value="update">
		<input type="hidden" name="comment_id" value="<?php echo $fetch[$i]['COMMENT_ID'];?>">
		<input type="hidden" name="p" value="<?php echo $p;?>">
		</form>
		</td>
		<td align="center">
		<form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>" style="margin:0;">
		<input type="submit" name="reg" value="<?php echo ($fetch[$i]["DISPLAY_FLG"] == 1)?"表示中":"現在非表示";?>" style="width:75px;">
		<input type="hidden" name="comment_id" value="<?php echo $fetch[$i]['COMMENT_ID'];?>">
		<input type="hidden" name="action" value="display_change">
		<input type="hidden" name="display_change" value="<?php echo ($fetch[$i]["DISPLAY_FLG"] == 1)?"f":"t";?>">
		<input type="hidden" name="p" value="<?php echo $p;?>">
		</form>
		</td>
		<td align="center">
		<form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>" style="margin:0;" onSubmit="return confirm('この記事データを完全に削除します。\n記事データの復帰は出来ません。\nよろしいですか？');">
		<input type="submit" value="削除">
		<input type="hidden" name="comment_id" value="<?php echo $fetch[$i]['COMMENT_ID'];?>">
		<input type="hidden" name="action" value="del_data">
		<input type="hidden" name="p" value="<?php echo $p;?>">
		</form>
		</td>
	</tr>
	<?php endfor;?>
</table>
<?php endif;?>

</body>
</html>
