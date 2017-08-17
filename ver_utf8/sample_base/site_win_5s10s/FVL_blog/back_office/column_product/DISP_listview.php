<?php
/*******************************************************************************
Sx系プログラム バックオフィス（MySQL対応版）
View：登録内容一覧表示（最初に表示する）

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
$tcnt = count($fetchCNT_CA);
// 全ページ数
$totalpage = ceil($tcnt/DISP_MAXROW_BACK);

// カテゴリー別で表示していればページ遷移もカテゴリーパラメーターをつける
if ($ca) {
    $cpram = "&ca=".urlencode($ca);
}

// 前ページへのリンク
if ($p > 1) {
    $link_prev = "<a href=\"./?p=".urlencode($prev).$cpram."\">&lt;&lt; Prev</a>";
}

//次ページリンク
if ($totalpage > $p) {
    $link_next = "<a href=\"./?p=".urlencode($next).$cpram."\">Next &gt;&gt;</a>";
}

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
    <table width="400" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <form action="../main.php" method="post">
                    <input type="submit" value="管理画面トップへ" style="width:150px;">
                </form>
            </td>
        </tr>
    </table>
    <p class="page_title">
        <?= TITLE."【{$ca_name}】";//タイトルとカテゴリーを表示?>：新規登録
    </p>
    <p class="explanation">
        ▼新規データの登録を行う際は、<strong>「新規追加」</strong>をクリックしてください。<br>
        ▼最大登録件数は<strong><?=DBMAX_CNT;?>件</strong>です。
    </p>
    <?php
    #-----------------------------------------------------
    # 書込許可（最大登録件数に達していない）の場合に表示
    #-----------------------------------------------------
    //最大件数を超えてない、またはカテゴリーが存在している場合新規登録が出来るようにする
    //基本的にカテゴリーが無ければそのカテゴリーに登録されているデータが表示または存在が出来ない為、編集ボタン側の表示は制限をしない
    if ((count($fetchCNT) < DBMAX_CNT) && count($fetchCA)):?>
        <form action="./" method="post">
            <input type="submit" value="新規追加" style="width:150px;">
            <input type="hidden" name="act" value="new_entry">
            <input type="hidden" name="ca" value="<?=$ca?>">
        </form>
    <?php else:?>
        <p class="err">
            <?php if (count($fetchCA)) { ?>
                最大登録件数<?=DBMAX_CNT?>件に達しています。<br>
                新規登録を行う場合は、いずれかの既存データを削除してください。
            <?php } else { //カテゴリーが存在しない為新規ボタンを非表示にした場合の文言?>
                カテゴリーの登録がございません。<br>
                新規登録を行う場合はカテゴリーの登録を行ってください。
            <?php }?>
        </p>
    <?php endif;?>
    <form name="frms" action="./" method="post" style="margin:0;">
        <select name="ca" onChange="JavaScript:document.frms.submit();">
            <?php for ($i = 0;$i<count($fetchCA);$i++) { ?>
                <option value="<?=$fetchCA[$i]['CATEGORY_CODE']; ?>"<?=($ca == $fetchCA[$i]['CATEGORY_CODE'])?' selected':''?>>
                    <?=$fetchCA[$i]['CATEGORY_NAME']; ?>(<?=count(${'fetchCA_ca'.$i})?>)
                </option>
            <?php }?>
        </select>←表示したいカテゴリーを選択してください
    </form>
    <?php if ($ca) { ?>
        <p class="page_title">
            <?= TITLE."【{$ca_name}】";//タイトルとカテゴリーを表示?>：登録一覧
        </p>
        <p class="explanation">
            ▼既存データの修正は<strong>「編集」</strong>をクリックしてください<br>
            ▼<strong>「表示中」「現在非表示」</strong>をクリックで切替えると表示ページでの表示を制御します。<br>
            ▼<strong>「削除」</strong>をクリックすると登録されているデータが削除されます。<br>
            ▼<strong>削除したデータは復帰できません。</strong>十分に注意して処理を行ってください。<br>
            ▼<strong>「登録データ件数」</strong>は選択されたカテゴリーの登録データの合計件数です。<br>
            ▼<strong>「総合件数」</strong>は全てのカテゴリーの登録データを合計した合計件数です。
        </p>
        <?php if (!$fetch):?>
            <p><b>登録されているデータはありません。</b></p>
            <div>※登録データ件数：<strong><?=count($fetchCNT_CA)?></strong>&nbsp;件（総合件数：<strong><?=count($fetchCNT)?></strong>&nbsp;件）</div>
        <?php else:?>
            <div>※登録データ件数：<strong><?=count($fetchCNT_CA)?></strong>&nbsp;件（総合件数：<strong><?=count($fetchCNT)?></strong>&nbsp;件）</div>
            <table width="510" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="50" align="left">
                        <?=$link_prev?>
                    </td>
                    <td width="50" align="right">
                        <?=$link_next?>
                    </td>
                </tr>
            </table>
            <table width="510" border="1" cellpadding="2" cellspacing="0">
                <tr class="tdcolored">
                    <th width="15%" nowrap>表示日付</th>
                    <th width="10%" nowrap>画像</th>
                    <th nowrap>タイトル</th>
                    <th width="5%" nowrap>編集</th>
                    <th width="10%" nowrap>表示状態</th>
                    <th width="5%" nowrap>削除</th>
                    <th width="10%">プレビュー</th>
                </tr>
                <?php for ($i=0;$i<count($fetch);$i++):?>
                    <tr class="<?=(($i % 2)==0)?'other-td':'otherColTd'?>">
                        <td align="center"><?=$fetch[$i]['DISP_DATE']; ?></td>
                        <td align="center">
                            <?php if (search_file_flg(IMG_PATH, $fetch[$i]['RES_ID']."_1.*")):?>
                                <a href="<?=search_file_disp(IMG_PATH, $fetch[$i]['RES_ID']."_1.*", "", 2); ?>" target="_blank">
                                    <?=search_file_disp(IMG_PATH, $fetch[$i]['RES_ID']."_1.*", "border=\"0\" width=\"".IMGSIZE_SX."\""); ?>
                                </a>
                            <?php else:
                                echo '&nbsp;';
                            endif; ?>
                        </td>
                        <td align="center">&nbsp;<?php echo ($fetch[$i]['TITLE'])?mb_strimwidth($fetch[$i]['TITLE'], 0, 80, "...", utf8):"No Title"; ?></td>
                        <td align="center">
                            <form method="post" action="./" style="margin:0;">
                                <input type="submit" name="reg" value="編集">
                                <input type="hidden" name="act" value="update">
                                <input type="hidden" name="res_id" value="<?=$fetch[$i]['RES_ID']?>">
                                <input type="hidden" name="ca" value="<?=$ca?>">
                                <input type="hidden" name="p" value="<?=$p?>">
                            </form>
                        </td>
                        <td align="center">
                            <form method="post" action="./" style="margin:0;">
                                <input type="submit" name="reg" value="<?=($fetch[$i]['DISPLAY_FLG'] == 1)?'表示中':'現在非表示'?>" style="width:75px;">
                                <input type="hidden" name="res_id" value="<?=$fetch[$i]['RES_ID']?>">
                                <input type="hidden" name="act" value="display_change">
                                <input type="hidden" name="display_change" value="<?=($fetch[$i]['DISPLAY_FLG'] == 1)?'f':'t'?>">
                                <input type="hidden" name="ca" value="<?=$ca?>">
                                <input type="hidden" name="p" value="<?=$p?>">
                            </form>
                        </td>
                        <td align="center">
                            <form method="post" action="./" style="margin:0;" onSubmit="return confirm('このデータを完全に削除します。\nデータの復帰は出来ません。\nよろしいですか？');">
                                <input type="submit" value="削除">
                                <input type="hidden" name="res_id" value="<?=$fetch[$i]['RES_ID']?>">
                                <input type="hidden" name="act" value="del_data">
                                <input type="hidden" name="ca" value="<?=$ca?>">
                                <input type="hidden" name="p" value="<?=$p?>">
                            </form>
                        </td>
                        <td align="center">
                            <form method="post" action="<?=PREV_PATH?>" target="_blank" style="margin:0;">
                                <input type="submit" value="プレビュー">
                                <input type="hidden" name="res_id" value="<?=$fetch[$i]['RES_ID']?>">
                                <input type="hidden" name="ca" value="<?=$ca?>">
                                <input type="hidden" name="act" value="prev_d">
                            </form>
                        </td>
                    </tr>
                <?php endfor;?>
            </table>
            <table width="510" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="50" align="left">
                        <?=$link_prev?>
                    </td>
                    <td width="50" align="right">
                        <?=$link_next?>
                    </td>
                </tr>
            </table>
        <?php endif; ?>
    <?php } ?>
</body>
</html>
