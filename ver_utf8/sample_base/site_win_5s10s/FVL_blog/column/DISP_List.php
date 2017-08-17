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
$totalpage = ceil($tcnt/DISP_MAXROW);

// カテゴリー別で表示していればページ遷移もカテゴリーパラメーターをつける
$cpram = "";
if ($ca) {
    $cpram = "&ca=".urlencode($ca);
} else if ($getDate) {
    $cpram = "&y=".urlencode($getDate->format('Y')).'&m='.urlencode($getDate->format('m'));
}

// 前ページへのリンク
if ($p > 1) {
    $link_prev = "<a href=\"./?p=".urlencode($prev).$cpram."\">前のページへ</a>";
}

//次ページリンク
if ($totalpage > $p) {
    $link_next = "<a href=\"./?p=".urlencode($next).$cpram."\">次のページへ</a>";
}

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
    <title>コラムテンプレート</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <link rel="canonical" href="<?=(empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] .'/'. basename(dirname($_SERVER['PHP_SELF']))?>">
    <!-- CSS -->
    <link rel="stylesheet" href="../css/base.css" type="text/css" media="all">
    <link rel="stylesheet" href="../css/lightbox.css" type="text/css" media="all">
    <!-- コラムテンプレート用CSS -->
    <link rel="stylesheet" href="../css/TMPL_column.css" type="text/css" media="all">
</head>
<body class="pages column">
    <!-- #header -->
    <header id="header">
        <h1>一覧ページ | コラムテンプレート</h1>
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
            <p>カテゴリー数は無限。</P>
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
            <!-- /#fvl_column_cate -->
            <?php /*アーカイブテンプレートここまで */?>
            <div id="fvl_column_list">
                <!-- .column_title_box -->
                <div>
                    <h2><?=$columnTitle?></h2>
                </div>
                <!-- .column_title_box -->
                <?php
                echo $disp_no_data;
                for ($i = 0;$i<count($fetch);$i++) {
                    //ID
                    $id = $fetch[$i]['RES_ID'];

                    //日付
                    $time = sprintf("%04d/%02d/%02d", $fetch[$i]['Y'], $fetch[$i]['M'], $fetch[$i]['D']);
                    $datetime = sprintf("%04d-%02d-%02d", $fetch[$i]['Y'], $fetch[$i]['M'], $fetch[$i]['D']);

                    //タイトル
                    $title = ($fetch[$i]['TITLE'])?"<a href=\"./?id=".urlencode($id)."\">".$fetch[$i]['TITLE']."</a>":"&nbsp;";
                    $ititle = ($fetch[$i]['TITLE'])?strip_tags($fetch[$i]['TITLE']):"";

                    //コメント
                    $content = ($fetch[$i]['CONTENT1'])?mb_strimwidth(strip_tags($fetch[$i]['CONTENT1']), 0, 420, "..."):"";
                    //image拡大有（１）・無（０）表示
                    $img_flg = $fetch[$i]['IMG_FLG'];

                    // 画像
                    if ($_POST['act']) {//プレビュー
                        $img = $prev_img[1];
                    } else {// 表示する画像を検索（拡張子の特定）
                        $img = search_file_disp("./up_img/", $id."_1.*", "", 2);
                    }
                    // 画像表示処理
                    if (!file_exists($img)) {
                        $image = "
                        <div class=\"fvl_column_img\">
                            <time datetime=\"{$datetime}\">{$time}</time>
                            <p><img src=\"./images/noimage.jpg\" alt=\"{$ititle}\"></p>
                        </div>
                        ";
                    } else {
                        //画像サイズが固定でない場合（サイズ自動調整、横固定縦可変など）
                        if ($img_flg==1) {
                            $image = "
                            <div class=\"fvl_column_img\">
                                <time datetime=\"{$datetime}\">{$time}</time>
                                <p>
                                    <a href=\"{$img}\" data-lightbox=\"lightbox\">
                                        <img src=\"{$img}\" alt=\"{$ititle}\">
                                    </a>
                                </p>
                            </div>
                            ";
                        } else {
                            $image = "
                            <div class=\"fvl_column_img\">
                                <time datetime=\"{$datetime}\">{$time}</time>
                                <p><img src=\"{$img}\" alt=\"{$ititle}\"></p>
                            </div>
                            ";
                        }
                    }
                    //表示内容を表示する
                    echo "
                    <!-- .fvl_column_box -->
                    <article class=\"fvl_column_box\">
                        <div class=\"fvl_column_inner clearfix\">
                            {$image}
                            <div class=\"fvl_column_txt\">
                                <header class=\"fvl_headline clearfix\">
                                    <h3><a href=\"./?id={$id}{$cpram}\">{$title}</a></h3>
                                </header>
                                {$content}
                            </div>
                        </div>
                    </article>
                    <!-- /.fvl_column_box -->
                    ";
                } ?>
            </div>
            <!-- .fvl_column_pager -->
            <div class="fvl_column_pager">
                <div class="clearfix">
                    <p class="fvl_pager_btn prev_btn"><?=$link_prev?></p>
                    <p class="fvl_pager_btn next_btn"><?=$link_next?></p>
                </div>
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
