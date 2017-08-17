<?php
/*******************************************************************************
Sx系プログラム バックオフィス（MySQL対応版）
View：新規登録画面表示

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#---------------------------------------------------------------
if (!$_SESSION['LOGIN']) {
    header("Location: ../err.php");
    exit();
}
if (!$accessChk) {
    header("Location: ../");
    exit();
}

$date = New Datetime();

#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／有効期限の設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8", true, false, false, true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title></title>
    <script type="text/javascript" src="inputcheck.js"></script>
    <link href="../for_bk.css" rel="stylesheet" type="text/css">
    <script src="../tag_pg/cms.js" type="text/javascript"></script>
    <script src="../actchange.js" type="text/javascript"></script>
    <script type="text/javascript" src="../jquery/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jquery/jquery.upload-1.0.2.js"></script>
    <script type="text/javascript" src="./uploadcheck.js"></script>
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
    <p class="page_title"><?=TITLE;?>：新規登録</p>
    <p class="explanation">
        ▼新規データの登録画面です。<br>
        ▼入力し終えたら<strong>「上記の内容で登録する」</strong>をクリックしてデータを登録してください。
    </p>
    <form name="new_regist" action="./" method="post" enctype="multipart/form-data" style="margin:0px;">
        <table width="510" border="1" cellpadding="2" cellspacing="0">
            <tr>
                <th colspan="2" nowrap class="tdcolored">■新規登録</th>
            </tr>
            <tr>
                <th width="15%" nowrap class="tdcolored">カテゴリー：</th>
                <td class="other-td">
                    <select name="ca">
                        <?php for ($i = 0;$i < count($fetchCA);$i++) { ?>
                            <option value="<?=$fetchCA[$i]['CATEGORY_CODE']; ?>"<?=($ca == $fetchCA[$i]['CATEGORY_CODE'])?' selected':''?>>
                                <?=$fetchCA[$i]['CATEGORY_NAME']; ?>
                            </option>
                        <?php }?>
                    </select>
                </td>
            </tr>
            <tr>
                <th width="15%" nowrap class="tdcolored">表示日付：</th>
                <td class="other-td">
                    表示する日付です。<br>
                    <select name="y">
                        <?php for ($i = 2010;$i <= ($date->format('Y')+10);$i++):?>
                            <option value="<?php printf("%04d", $i);?>"<?=($date->format('Y') == $i)?' selected':''?>><?=$i?></option>
                        <?php endfor;?>
                    </select>
                    年
                    <select name="m">
                        <?php for ($i=1;$i<=12;$i++):?>
                            <option value="<?php printf("%02d", $i);?>"<?=($date->format('m') == $i)?' selected':''?>><?=$i?></option>
                        <?php endfor;?>
                    </select>
                    月
                    <select name="d">
                        <?php for ($i=1;$i<=31;$i++):?>
                            <option value="<?php printf("%02d", $i);?>"<?=($date->format('d') == $i)?' selected':''?>><?=$i?></option>
                        <?php endfor;?>
                    </select>
                    日
                </td>
            </tr>
            <tr>
                <th width="15%" nowrap class="tdcolored">タイトル：</th>
                <td class="other-td">
                    <input name="title" type="text" value="<?=$title;?>" size="60" maxlength="127" style="ime-mode:active">
                </td>
            </tr>
            <tr>
                <th nowrap class="tdcolored">一覧用画像：</th>
                <td height="35" class="other-td">
                    アップロード後画像サイズ：<strong>横<?=IMGSIZE_MX1;?>px×縦<?php //echo IMGSIZE_MY2;echo "px";?> 自動算出</strong>
                    <br>
                    <input type="file" name="up_img[1]" value="" class="chkimg">
                </td>
            </tr>
            <?php for ($i = 1;$i <= IMG_SET_CNT;$i++):?>
                <tr>
                    <th nowrap class="tdcolored">本文<?=$i?>：</th>
                    <td class="other-td">
                        <select name="fontsize" onChange="CheckObj();addFontsSize(Temp.name,this); this.options.selectedIndex=0; return false;">
                            <option value="">サイズ</option>
                            <option value="x-small">極小</option>
                            <option value="small">小</option>
                            <option value="medium">小さめ</option>
                            <option value="large">中</option>
                            <option value="x-large">大きめ</option>
                            <option value="xx-large">大</option>
                        </select>
                        <a href="javascript:void(0)" onClick="addStyle(Temp.name,'p','text-align:left;margin-top:0px;margin-bottom:0px;'); return false;"><img src="../tag_pg/img/text_align_left.png" width="16" height="16" alt="テキストを左寄せ" border="0"></a>
                        <a href="javascript:void(0)" onClick="addStyle(Temp.name,'p','text-align:center;margin-top:0px;margin-bottom:0px;'); return false;"><img src="../tag_pg/img/text_align_center.png" width="16" height="16" alt="テキストを真中寄せ" border="0"></a>
                        <a href="javascript:void(0)" onClick="addStyle(Temp.name,'p','text-align:right;margin-top:0px;margin-bottom:0px;'); return false;"><img src="../tag_pg/img/text_align_right.png" width="16" height="16" alt="テキストを右寄せ" border="0"></a>
                        <a href="javascript:void(0)" onClick="CheckObj();addLink(Temp.name); return false;"><img src="../tag_pg/img/link.png" width="16" height="16" alt="リンク" border="0"></a>
                        <a href="javascript:void(0)" onClick="CheckObj();addTag(Temp.name,'strong'); return false;"><img src="../tag_pg/img/text_bold.png" width="16" height="16" alt="太字" border="0"></a>
                        <a href="javascript:void(0)" onClick="CheckObj();addTag(Temp.name,'u'); return false;"><img src="../tag_pg/img/text_underline.png" width="16" height="16" alt="下線" border="0"></a>
                        <a href="javascript:void(0)" onClick="CheckObj();obj=Temp.name;MM_showHideLayers('<?=$layer_free;?>',obj.name,'show');OnLink('<?=$layer_free;?>',event.x,event.y,event.pageX,event.pageY); return false;"><img src="../tag_pg/img/rainbow.png" alt="テキストカラー" border="0"></a>
                        <br>
                        <textarea name="content<?=$i?>" cols="85" rows="10" style="ime-mode:active" onFocus="SaveOBJ(this)"><?=${'content'.$i};?></textarea>
                    </td>
                </tr>
                <tr>
                    <th nowrap class="tdcolored"><?='詳細用画像'.$i;?>：</th>
                    <td height="35" class="other-td">
                        アップロード後画像サイズ：<strong>横<?=IMGSIZE_MX2?>px×縦<?php //echo IMGSIZE_MY2;echo "px";?> 自動算出</strong>
                        <br>
                        <input type="file" name="up_img[<?=($i+1)?>]" value="" class="chkimg">
                    </td>
                </tr>
            <?php endfor;?>
            <tr>
                <th nowrap class="tdcolored">画像の拡大有/無：</th>
                <td class="other-td">
                    <input name="img_flg" id="img_flg1" type="radio" value="1" checked>有
                    <input name="img_flg" id="img_flg2" type="radio" value="0">無
                </td>
            </tr>
            <tr>
                <th nowrap class="tdcolored">表示／非表示：</th>
                <td class="other-td">
                    <input name="display_flg" id="dispon" type="radio" value="1" checked><label for="dispon">表示</label>&nbsp;&nbsp;&nbsp;&nbsp;
                    <input name="display_flg" id="dispoff" type="radio" value="0"><label for="dispoff">非表示</label>
                </td>
            </tr>
        </table>
        <input type="submit" value="上記内容で登録する" style="width:150px;margin-top:1em;" onClick="chgsubmit();return confirm_message(this.form);">
        <input type="hidden" name="act" value="completion">
        <input type="hidden" name="regist_type" value="new">
        <input type="submit" value="プレビューを見る" style="width:150px;margin-top:1em;" onClick="chgpreview('<?=PREV_PATH;?>')"><br>
    </form>
    <br>
    <form action="./" method="post">
        <input type="submit" value="一覧画面へ戻る" style="width:150px;">
        <input type="hidden" name="ca" value="<?=$ca;?>">
    </form>
<?php
//ボタン付近に表示する
cp_disp($layer_free, "0", "0");
?>
</body>
</html>
