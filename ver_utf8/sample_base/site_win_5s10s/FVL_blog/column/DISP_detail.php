<?php
/***********************************************************
SiteWin10 20 30（MySQL対応版）
S系表示用プログラム
View：取得したデータをHTML出力

***********************************************************/

// 不正アクセスチェック
if (!$injustice_access_chk) {
    header("HTTP/1.0 404 Not Found");
    exit();
}

//該当商品の並び位置の取得
for ($i=0;$i < count($fetchCNT);$i++) {
    if ($fetch[0]['RES_ID'] == $fetchCNT[$i]['RES_ID']) {
        $cpram = "";
        if ($ca) {
            $cpram = "&ca=".urlencode($ca);
        } else if ($getDate) {
            $cpram = "&y=".urlencode($getDate->format('Y')).'&m='.urlencode($getDate->format('m'));
        }
        $target = $i + 1;
        $next = urlencode($fetchCNT[$i+1]['RES_ID']);
        $prev = urlencode($fetchCNT[$i-1]['RES_ID']);
        if ($prev) {
            $link_prev = "<a href=\"./?id=".$prev.$cpram."\">前のページへ</a>";
        }
        if ($next) {
            $link_next = "<a href=\"./?id=".$next.$cpram."\">次のページへ</a>";
        }
        break;
    }
}

//ページ位置の取得
$p = ceil($target/DISP_MAXROW);

#-------------------------------------------------------------
# HTTPヘッダーを出力
#	１．文字コードと言語：utf8で日本語
#	２．ＪＳとＣＳＳの設定：する
#	３．有効期限：設定しない
#	４．キャッシュ拒否：設定する
#	５．ロボット拒否：設定しない
#-------------------------------------------------------------
utilLib::httpHeadersPrint("UTF-8", true, true, false, false);
?>
<!DOCTYPE html>
<html lang="ja" dir="ltr">
<head>
    <meta charset="utf-8">
    <title><?=$fetch[0]['TITLE']?>｜コラムテンプレート</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE">
    <meta name="description" content="<?=$fetch[0]['TITLE']?>｜ＳＥＯワード">
    <meta name="keywords" content="<?=$fetch[0]['TITLE']?>,キーワード">
    <link rel="canonical" href="<?=(empty($_SERVER["HTTPS"])?"http://":"https://").$_SERVER["HTTP_HOST"].'/'.basename(dirname($_SERVER['PHP_SELF'])).'/?id='.$id?>">
    <!-- CSS -->
    <link rel="stylesheet" href="../css/base.css" type="text/css" media="all">
    <link rel="stylesheet" href="../css/lightbox.css" type="text/css" media="all">
    <!-- コラムテンプレート用CSS -->
    <link rel="stylesheet" href="../css/TMPL_column.css" type="text/css" media="all">
</head>
<body class="pages column">
    <!-- #header -->
    <header id="header">
        <h1><?=$fetch[0]['TITLE']?>｜詳細ページ | コラムテンプレート</h1>
    </header>
    <!-- /#header -->
    <!-- #wrap -->
    <div id="wrap" class="clearfix">
        <!-- #main -->
        <div id="main">
            <p>記事の箇所はリキッドの為、親要素の横幅を指定すること。</p>
            <p>ブラウザ幅を小さくするとタブレット、スマホサイズも確認できます（＝レスポ対応可）</p>
            <p>一覧への表示件数は10件固定。</p>
            <p>掲載された日付の最新から表示。</p>
            <P>カテゴリー数は無限。</P>
            <!-- コピーここから -->
            <?php /* アーカイブテンプレートここから */?>
                <div id="fvl_column_cate">
                    <ul class="fvl_accordion clearfix">
                        <li>
                            <p class="fvl_acc_trigger">最新記事<span></span><span></span></p>
                            <ul class="fvl_acc_menu next">
                                <?php archiveLatest()?>
                            </ul>
                        </li>
                        <li>
                            <p class="fvl_acc_trigger">アーカイブ<span></span><span></span></p>
                            <ul class="fvl_acc_menu next">
                                <?php archiveMonth()?>
                            </ul>
                        </li>
                        <li>
                            <p class="fvl_acc_trigger">カテゴリー<span></span><span></span></p>
                            <ul class="fvl_acc_menu next">
                                <?php archiveCate()?>
                            </ul>
                        </li>
                    </ul>
                </div>
            <?php /*アーカイブテンプレートここまで */?>
            <div id="fvl_column_list">
                <article class="fvl_column_box">
                    <div class="fvl_column_inner clearfix">
                        <?php
                        //ID
                        $id = $fetch[0]['RES_ID'];

                        //日付
                        $time = sprintf("%04d/%02d/%02d", $fetch[0]['Y'], $fetch[0]['M'], $fetch[0]['D']);
                        $datetime = sprintf("%04d-%02d-%02d", $fetch[0]['Y'], $fetch[0]['M'], $fetch[0]['D']);

                        //タイトル
                        $title = ($fetch[0]['TITLE'])?$fetch[0]['TITLE']:"&nbsp;";
                        $ititle = ($fetch[0]['TITLE'])?strip_tags($fetch[0]['TITLE']):"";

                        //image拡大有（１）・無（０）表示
                        $img_flg = $fetch[0]['IMG_FLG'];

                        // 本文と画像のセット
                        $contents = '';
                        for ($i = 1; $i <= IMG_SET_CNT; $i++) {
                            $img_number = ($i + 1);
                            // 画像
                            if ($_POST['act']) {//プレビュー
                                $img = $prev_img[$img_number];
                            } else {// 表示する画像を検索（拡張子の特定）
                                $img = search_file_disp("./up_img/", $id."_{$img_number}.*", "", 2);
                            }
                            // 画像表示処理
                            if (!file_exists($img)) {
                                $contents .= "
                                <div class=\"fvl_column_img\">
                                    <img src=\"./images/noimage.jpg\" alt=\"{$ititle}\">
                                </div>
                                ";
                            } else {
                                if ($img_flg) {
                                    $contents .= "
                                    <div class=\"fvl_column_img\">
                                        <a href=\"{$img}\" data-lightbox=\"lightbox\">
                                            <img src=\"{$img}\" alt=\"{$ititle}\">
                                        </a>
                                    </div>
                                    ";
                                } else {
                                    $contents .= "
                                    <div class=\"fvl_column_img\">
                                        <img src=\"{$img}\" alt=\"{$ititle}\">
                                    </div>
                                    ";
                                }
                            }
                            $contents .= '<div class="fvl_column_txt">'.nl2br($fetch[0]['CONTENT'.$i]).'</div>';
                        }

                        //表示内容を表示する
                        echo "
                        <header class=\"fvl_headline clearfix\">
                            <time datetime=\"{$datetime}\">{$time}</time>
                            <h3>{$title}</h3>
                        </header>
                        {$contents}
                        ";
                        ?>

                    </div>
                </article>
                <!-- /.fvl_column_box -->
            </div>
            <!-- .fvl_column_pager -->
            <div class="fvl_column_pager">
                <div class="clearfix">
                    <p class="fvl_pager_btn prev_btn"><?=$link_prev?></p>
                    <p class="fvl_pager_btn next_btn"><?=$link_next?></p>
                </div>
                <p class="fvl_pager_btn back_list"><a href="./?p=<?=urlencode($p).$cpram?>">一覧へ戻る</a></p>
            </div>
            <!-- /.fvl_column_pager -->
            <!-- コピーここまで -->
        </div>
        <!-- /#main -->
    </div>
    <!-- /#wrap -->
    <script src="./js/jquery.js"></script>
    <script src="./js/lightbox.js"></script>
    <script>
        <!--
        /*** accordion (click) ***/
        $(function() {
            //menu hide
            var menu = $('.fvl_acc_menu');
            var trigger = $('.fvl_accordion > li p');
            menu.hide();
            trigger.click(function() {
                menu.slideUp();
                trigger.removeClass('open');
                if ( $(this).next(menu).is(':visible') ) {
                    $(this).next(menu).slideUp();
                    $(this).removeClass('open');
                } else {
                    $(this).next(menu).slideDown();
                    $(this).addClass('open');
                }
            });
        });
        // -->
    </script>
</body>
</html>
