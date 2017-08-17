<?php
/*******************************************************************************
ALL-INTERNETBLOG

 LOGIC:BLOG表示画面処理

*******************************************************************************/

// 不正アクセスチェック
if (!$injustice_access_chk) {
    header("HTTP/1.0 404 Not Found");
    exit();
}

#=============================================================
# ヘッダー調整
#=============================================================
utilLib::httpHeadersPrint("UTF-8", true, true, true, true);

#------------------------------------------------------------------------
#	該当商品リスト用情報の取得
#------------------------------------------------------------------------

// ページ番号の設定(GET受信データがなければ1をセット)
if (empty($p) or !is_numeric($p)) {
    $p=1;
}

// 抽出開始位置の指定
$st = ($p-1) * PAGE_MAX;

#----------------------------------------------------------------------------
# テンプレートクラスライブラリ読込みと出力用HTMLテンプレートをセット
# ※$category_codeの内容により分岐
#----------------------------------------------------------------------------
$tmpl_file = "TMPL_disp.html";

if (!file_exists($tmpl_file)) {
    die("Template File Is Not Found!!");
}
$tmpl = new Tmpl2($tmpl_file);

#------------------------------------------------------------------------------------------
# テンプレートを使用してHTML出力の設定
#------------------------------------------------------------------------------------------
// ヘッダー画像の分岐
switch ($fetch_title[0]["IMG_SELECT"]):
    case "1":
        $head_img = CSS_FILE_PATH."header_img/".$fetch_title[0]["HEADER_IMG"].".jpg";
    break;
    case "2":
        $head_img = RI_PATH."up_img/title.jpg";
        $size = @getimagesize($head_img);
        $size_y = $size[1];
    break;
endswitch;

// ヘッダー画像
$tmpl->assign("head_img", $head_img);

// ヘッダー画像高さ
$tmpl->assign("height", ($fetch_title[0]["IMG_SELECT"] == 2)?" height:".$size_y."px;":"");

// TITLE
$tmpl->assign("title", $fetch_title[0]['TITLE']);

// SUB_TITLE
$tmpl->assign("sub_title", ($fetch_title[0]['SUB_TITLE'])?nl2br($fetch_title[0]['SUB_TITLE']):"");

// 読み込みCSS
$tmpl->assign("dir", CSS_FILE_PATH.$fetch_title[0]['DIR']);

//SEO
$tmpl->assign("ca_title", ($fetchList[0]['CATEGORY_NAME'])?$fetchList[0]['CATEGORY_NAME']:"");
$tmpl->assign("e_title", ($fetchList[0]['TITLE'])?$fetchList[0]['TITLE']:"");

// リンク設定
$link = "
<div class=\"sidetitle\">リンク</div>
	<div class=\"side\">";

for ($i=1;$i<=10;$i++):
// リンクURL
$link_url[$i] = $fetch_title[0]["LINK_URL".$i];

// リンク文字
if ($link_url[$i]) {
    $link_title[$i] = "<a href=\"".$link_url[$i]."\">".$fetch_title[0]["LINK_TITLE".$i]."</a>";
} else {
    $link_title[$i] = $fetch_title[0]["LINK_TITLE".$i];
}
if (strlen($link_title[$i])!=0) {
    $link .= $link_title[$i]."</a><br>\n";
}
endfor;

$link .= "</div>";
$tmpl->assign("link", ($link)?$link:"");
$tmpl->assign("site_url", BLOG_SITE_LINK);

//RSS
$tmpl->assign("rss_blog", "<link rel=\"alternate\" type=\"application/rss+xml\" title=\"".$fetchList[0]['TITLE']." RSS\" href=\"".BLOG_SITE_LINK."/blog_rss.php\" />");

// PHP_SELF
$tmpl->assign("php_self", RL_PATH);//現在の階層用
$tmpl->assign("php_self2", RI_PATH);//一つ上の階層用

// デフォルト値以外が送信されたらmainへのリンクを出す
if (!empty($act)) {
    $tmpl->assign("main", "<a href=\"".RL_PATH."\">Main</a>");
} else {
    $tmpl->assign("main", "");
}

// キーワード検索された場合のコメント
if (!empty($query)) {
    $q_comment = "検索キーワード : ".$query."<br>検索ヒット件数".$fetchCnt[0]["CNT"];
}

// 検索コメント
$tmpl->assign("query_comment", ($q_comment)?$q_comment:"");

#--------------------------------------------------------------------------------
# ページ右リンク部分表示
# ・カレンダー
# ・カテゴリー一覧
# ・新着情報(コンテンツの新しい順に5件)
# ・過去ログ過去の記事を年月でリンク
#--------------------------------------------------------------------------------

#################################################################################
# カレンダー

// カレンダー表示
require_once 'Calendar/Month/Weekdays.php';

// 年月が６桁以上の数字でなければ今年の今月を表示
if ((!strlen($log) >= 6) || (!is_numeric($log))) {
    $log = "";
    $y = "";
    $m = "";
}

$y = substr($log, 0, 4);
$m = substr($log, 4, 2);

// 初期値年は今年月は今月を基準にプラス$i個
// 年月のデータがなければ今年の今月
if (empty($y)) {
    $y = date('Y');
}

if (empty($m)) {
    $m = date('n');
}

// カレンダーの次へ前へ処理
// 次へ(１２月が表示されていたら年をプラス１して月を１月にする)
if ($m == 12) {
    $year1 = sprintf("%04d", ($y+1));
    $mon1  = sprintf("%02d", 1);
} else {
    $year1 = sprintf("%04d", $y);
    $mon1  = sprintf("%02d", ($m+1));
}
// 前へ(１月が表示されていたら年をマイナス１にして月を１２月にする)
if ($m == 1) {
    $year2 = sprintf("%04d", ($y-1));
    $mon2  = sprintf("%02d", 12);
} else {
    $year2 = sprintf("%04d", $y);
    $mon2  = sprintf("%02d", ($m-1));
}

// インスタンスを生成（第3引数に“0”を指定すると日曜始まり）
$Month = new Calendar_Month_Weekdays($y, $m, 0);
$Month->build();

$cal = "
<div class=\"sidetitle\">カレンダー</div>
<div id=\"calendar\">
<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" summary=\"calendar\">
<caption class=\"calendarhead\">
<a href=\"".RL_PATH."l".urlencode($year2.$mon2)."/\">&lt;&lt;</a>
".$y."年".$m."月
<a href=\"".RL_PATH."l".urlencode($year1.$mon1)."/\">&gt;&gt;</a>
</caption>
<tr>
<th abbr=\"sunday\" align=\"center\" class=\"calendarday\">日</th>
<th abbr=\"monday\" align=\"center\" class=\"calendarday\">月</th>
<th abbr=\"tuesday\" align=\"center\" class=\"calendarday\">火</th>
<th abbr=\"wednesday\" align=\"center\" class=\"calendarday\">水</th>
<th abbr=\"thursday\" align=\"center\" class=\"calendarday\">木</th>
<th abbr=\"friday\" align=\"center\" class=\"calendarday\">金</th>
<th abbr=\"saturday\" align=\"center\" class=\"calendarday\">土</th>
</tr>
";

while ($Day = $Month->fetch()):

    if ($Day->isFirst()) {
        $calt .= "<tr>\n";
    }
    if ($Day->isEmpty()) {
        $calt .= "<td align=\"center\" class=\"calendarday\">&nbsp;</td>\n";
    } else {

        // 本日データの日付整形
        $link_d = sprintf("%04d", $y).sprintf("%02d", $m).sprintf("%02d", $Day->thisDay());

        $sql =
        "SELECT E_ID FROM BLOG_ENTRY_LST
		WHERE
			(DATE_FORMAT(DISP_DATE, '%Y%m%d') = '".$link_d."')
		AND (DEL_FLG = '0') AND (DISPLAY_FLG = '1')
		";
        $fetchD = $PDO->fetch($sql);

        // エントリーがある場合記事へのリンク
        if (!empty($fetchD)) {
            $dlink = "<td align=\"center\" class=\"calendarday\"><a href=\"".RL_PATH."d".urlencode($link_d)."/\">".$Day->thisDay()."</a></td>\n";

        // エントリーがない場合日付のみ
        } else {
            $dlink = "<td align=\"center\" class=\"calendarday\">".$Day->thisDay()."</td>\n";
        }

        $calt .= $dlink;
    }

    if ($Day->isLast()) {
        $calt .= "</tr>\n";
    }

endwhile;

$cal .= $calt;

$cal .= "
</table>
</div>
";

$tmpl->assign("calendar", ($cal)?$cal:"");

#################################################################################
# カテゴリー
for ($i=0;$i<count($fetchCategory);$i++):
// カテゴリー画像の表示
if (file_exists("../up_img/".$fetchCategory[$i]['CATEGORY_CODE']."_ca.jpg")) {
    $ca_img = "<img src=\"".RI_PATH."up_img/".$fetchCategory[$i]['CATEGORY_CODE']."_ca.jpg\" width=\"16\" height=\"12\">";
} else {
    $ca_img = "・";
}

$cate_link .= "
".$ca_img."
<a href=\"".RL_PATH."c".urlencode($fetchCategory[$i]['CATEGORY_CODE'])."/\">".$fetchCategory[$i]['CATEGORY_NAME']."</a>(".$fetchCategory[$i]['C_CNT'].")<br />\n
";

// カテゴリー別ページ遷移
if ($ca == $fetchCategory[$i]['CATEGORY_CODE']) {
    if (!empty($fetchCategory[$i+1]['CATEGORY_CODE'])) {
        $ne_ca = " | <a href=\"".RL_PATH."c".urlencode($fetchCategory[$i+1]['CATEGORY_CODE'])."/\">".$fetchCategory[$i+1]['CATEGORY_NAME']." &gt;&gt; </a>";
    } else {
        $ne_ca = "";
    }

    if (!empty($fetchCategory[$i-1]['CATEGORY_CODE'])) {
        $pr_ca = "<a href=\"".RL_PATH."c".urlencode($fetchCategory[$i-1]['CATEGORY_CODE'])."/\"> &lt;&lt; ".$fetchCategory[$i-1]['CATEGORY_NAME']."</a> | ";
    } else {
        $pr_ca = "";
    }
}
// ページ遷移表示
if (!empty($ca)) {
    $tmpl->assign("main", "".$pr_ca."<a href=\"".RL_PATH."\">Main</a>".$ne_ca."");
}

endfor;
// カテゴリー別リンク表示
$tmpl->assign("category", ($cate_link)?$cate_link:"");
#################################################################################

#################################################################################
# 新着情報
for ($i=0;$i<count($fetchNew);$i++):

    $new_entry .= "(".$fetchNew[$i]["M"]."/".$fetchNew[$i]["D"].")<a href=\"".RL_PATH."#".urlencode($fetchNew[$i]["RES_ID"])."\">".$fetchNew[$i]["TITLE"]."</a><br />\n";

endfor;
// 新着情報表示
$tmpl->assign("new_entry", ($new_entry)?$new_entry:"");
##################################################################################

##################################################################################
# 過去ログ
for ($i=0;$i<count($fetchKako);$i++):

    $kako_log .= "<a href=\"".RL_PATH."l".urlencode(sprintf("%04d", $fetchKako[$i]["Y"]).sprintf("%02d", $fetchKako[$i]["M"]))."/\">".$fetchKako[$i]["Y"]."年".$fetchKako[$i]["M"]."月</a>(".$fetchKako[$i]["KAKO_CNT"].")<br />\n";

// 過去ログ別ページ遷移
if ($log == sprintf("%04d", $fetchKako[$i]["Y"]).sprintf("%02d", $fetchKako[$i]["M"])) {
    if (!empty($fetchKako[$i+1]['KAKO_CNT'])) {
        $ne_log = "<a href=\"".RL_PATH."l".urlencode(sprintf("%04d", $fetchKako[$i+1]["Y"]).sprintf("%02d", $fetchKako[$i+1]["M"]))."/\"> &lt;&lt; ".$fetchKako[$i+1]["Y"]."年".$fetchKako[$i+1]["M"]."月</a> | ";
    } else {
        $ne_log = "";
    }

    if (!empty($fetchKako[$i-1]['KAKO_CNT'])) {
        $pr_log = " | <a href=\"".RL_PATH."l".urlencode(sprintf("%04d", $fetchKako[$i-1]["Y"]).sprintf("%02d", $fetchKako[$i-1]["M"]))."/\">".$fetchKako[$i-1]["Y"]."年".$fetchKako[$i-1]["M"]."月</a> &gt;&gt; ";
    } else {
        $pr_log = "";
    }
}
// 過去ログ遷移表示
if (!empty($log)) {
    $tmpl->assign("main", "".$ne_log."<a href=\"".RL_PATH."\">Main</a>".$pr_log."");
}

endfor;
// 過去ログ表示
$tmpl->assign("kako_log", ($kako_log)?$kako_log:"");

##################################################################################

#--------------------------------------------------------------------------------
# エントリー表示テーブルHTML出力
#--------------------------------------------------------------------------------

    // 何も登録されていない場合に表示
    if (count($fetchList) == 0):
        // なにも登録がなかったらassign_defのno_data_flgを定義する
        $tmpl->assign("disp_no_data", "ご登録がありません。<br />");
    else:
        $tmpl->assign("disp_no_data", "");
    endif;

    // ループセットと取得レコード分のHTML出力設定
    $tmpl->loopset("entry_list");
    for ($i=0;$i<count($fetchList);$i++) {

        // aタグ
        $tmpl->assign("res", $fetchList[$i]['RES_ID']);
        $tmpl->assign("e_id", $fetchList[$i]['E_ID']);

        // エントリータイトル
        $tmpl->assign("entry_title", $fetchList[$i]['TITLE']);

        // エントリーコメント
        $content = $fetchList[$i]['CONTENT'];

        /*
        /////////////////////////////////////////////////////////
        // プレビューの場合、up_imgのパスをup_img_prevに変更する
        /////////////////////////////////////////////////////////
        if($_POST['act']){
            $content = str_replace("up_img","up_img_prev",$content);
        }
        */
        //$content = ereg_replace("(<img src=\")(" . RI_PATH . "up_img/{$fetchList[$i]['RES_ID']}_)([[:digit:]])(.jpg)(\">)","<a href=\"\\2L\\3\\4\" target=\"_blank\">\\1\\2\\3\\4\" border=\"0\\5</a>",$content);

        // $tmpl->assign("entry_comment",nl2br($fetchList[$i]['CONTENT']));
        $tmpl->assign("entry_comment", nl2br($content));

        // 日付
        $tmpl->assign("date", $fetchList[$i]['Y']."年".$fetchList[$i]['M']."月".$fetchList[$i]['D']."日");

        // 画像を変数に格納
        /*
        $image = $fetchList[$i]["RES_ID"]."_1.jpg";

        if(file_exists("../up_img/{$image}")){

        // 画像情報（商品画像は商品ID+“_s.jpg”となる)
            $tmpl->assign("entry_img","<img src=\"".RI_PATH."up_img/{$image}\" border=\"0\">");

        }else{
            $tmpl->assign("entry_img","");
        }
        */
            $tmpl->assign("entry_img", "");

        #------------------------------------------------------------------------
        # コメントがあればコメントも表示
        #------------------------------------------------------------------------
        // 初期化
        $t = "";
        $c = "";

        $sql_com = "
			SELECT
				TITLE,NAME,CONTENT,EMAIL,DISP_DATE FROM BLOG_COMMENT_LST
			WHERE
				(RES_ID = '".$fetchList[$i]["RES_ID"]."') AND (DEL_FLG = '0') AND (DISPLAY_FLG = '1')
			ORDER BY DISP_DATE DESC";
        $fetchCom = $PDO->fetch($sql_com);

        for ($j=0;$j<count($fetchCom);$j++) {
            $t = "<div class=\"comments-head\">この記事へのコメント<br /></div>\n";
            $m = "";
            if (!empty($fetchCom[$j]["EMAIL"])) {
                $m = "<a href=\"mailto:".$fetchCom[$j]["EMAIL"]."\">E-mail</a>";
            }

            $c .= "
			<div class=\"comments-body\">
			<div class=\"title\">".$fetchCom[$j]["TITLE"]."</div>
			<div class=\"text\">".nl2br($fetchCom[$j]["CONTENT"])."</div>
			<div class=\"comments-post\">".$fetchCom[$j]["NAME"]." ".$m." ".$fetchCom[$j]["DISP_DATE"]."<br /></div>
			</div>
			";
        }

        $comment_data = $t.$c;

        // コメント表示
        $tmpl->assign("comment_data", ($comment_data)?$comment_data:"");

        // コメントへのリンク
        $tmpl->assign("comment", "<a href=\"".RL_PATH."?act=com&res=".urlencode($fetchList[$i]["RES_ID"])."&log=".$log."\">この記事へのコメント</a>");

        $tmpl->assign("cm_num", count($fetchCom));

        #--------------------------------------------------------
        # トラックバック処理
        #--------------------------------------------------------

        $tb_area = "";
        if ($fetchList[$i]['TB_FLG'] == 1) {
            $tb_area = "<p id=\"trackback\">";
            $tb_area .= "ﾄﾗｯｸﾊﾞｯｸURL : ".BLOG_SITE_LINK."/blog_trackback/".$fetchList[$i]['E_ID']."/ <br>";
            for ($j=0;$j<count($fetchList[$i]['__TB']);$j++) {
                $tb_title = $fetchList[$i]['__TB'][$j]['ENTRY_TITLE'];
                $tb_btitle = $fetchList[$i]['__TB'][$j]['BLOG_TITLE'];
                $tb_detail = $fetchList[$i]['__TB'][$j]['BLOG_DETAIL'];
                $tb_url = $fetchList[$i]['__TB'][$j]['BLOG_URL'];
                $tb_date = $fetchList[$i]['__TB'][$j]['Y']."/".$fetchList[$i]['__TB'][$j]['M']."/".$fetchList[$i]['__TB'][$j]['D'];
                $tb_area .= "
				<br>
				<a href=\"{$tb_url}\">{$tb_title}</a>({$tb_btitle}) {$tb_date}<br>
				{$tb_detail}
				<br>

				";
            }
            $tb_area .= "</p>";
        }

        $tmpl->assign("tb_num", count($fetchList[$i]['__TB']));
        $tmpl->assign("trackback_area", ($tb_area)?$tb_area:"");

        $tmpl->loopnext("entry_list");
    }

    $tmpl->loopend("entry_list");

#------------------------------------------------------------
# 記事へのコメントをリンクされた場合はコメントフォームを表示
#------------------------------------------------------------
if (($act == "com") && (empty($regist_type))) {

    // actがcomならコメントフォームを表示
    $com_form = "
	<div id=\"comments\">

	<a name=\"comment\"></a>

	<div class=\"comments-head\">この記事へのコメント</div>

	<div class=\"comments-head\">コメントを書く</div>
	<form action=\"".RL_PATH."\" name=\"commentForm\" method=\"post\" onSubmit=\"return inputChk(this);\">
	<div class=\"comments-body\">
	お名前: <br />
	<input type=\"text\" name=\"name\" size=\"50\" value=\"\" style=\"ime-mode:active\" /><br />
	メールアドレス: <br />
	<input type=\"text\" name=\"e_mail\" size=\"50\" value=\"\" style=\"ime-mode:disabled\" /><br />
	タイトル: <br />
	<input type=\"text\" name=\"title\" size=\"50\" value=\"\" style=\"ime-mode:active\" onkeydown=\"keyDownFun()\" /><br />
	コメント: <br />
	<textarea name=\"content\" rows=\"10\" cols=\"45\" style=\"ime-mode:active\"></textarea><br />
	<input name=\"submit\" type=\"submit\" class=\"input-submit\"  value=\"送信する\" />
	<input type=\"hidden\" name=\"act\" value=\"com\">
	<input type=\"hidden\" name=\"regist_type\" value=\"input_data\">
	<input type=\"hidden\" name=\"res\" value=\"".$fetchList[0]['RES_ID']."\">
	<input type=\"hidden\" name=\"randdata\" value=\"123\">
	<input type=\"hidden\" name=\"w\" value=\"1\">
	</div>
	</form>
	</div>
	";

        // 記事がなかったら(不正パラメーターが送信されたら)コメント入力フォーム
        if (count($fetchList) == 0) {
            $com_form = "";
        }

    $tmpl->assign("com_form", ($com_form)?$com_form:"");
} else {
    $tmpl->assign("com_form", "");
}

#--------------------------------------------------------
# プロフィール処理
#--------------------------------------------------------

//データベースから情報を取得する

    $p_sql="
	SELECT
		*
	FROM
		PROFILE_DATA
	WHERE
		(DEL_FLG = '0')
	AND
		(RES_ID = '1')
	";

    $fetchPD = $PDO->fetch($p_sql);

    if ($fetchPD[0]['DISPLAY_FLG'] == '1') {//プロフィールを表示する場合

        //画像の有無
            if (file_exists("../up_img/p_img.jpg")) {
                $p_img = "<img src=\"".RI_PATH."up_img/p_img.jpg?r=".rand()."\"  width=\"170\" style=\"margin:5px;\">";
            } else {
                $p_img = "";
            }

        //お名前
            $p_name = ($fetchPD[0]['NAME'])?"<h3 class=\"title\">ニックネーム：".$fetchPD[0]['NAME']."</h3>":"";

        //プロフィール
            $prof = str_replace("<br />", "<br>", nl2br($fetchPD[0]['PROFILE']));

        $prof_data = <<<EDIT
		<div class="sidetitle">プロフィール</div>
				<div class="side">
				{$p_img}
				<br>
				{$p_name}
				{$prof}
				</div>
EDIT;
    } else {//プロフィールを表示しない場合
        $prof_data = "";
    }

    //内容を表示する
    $tmpl->assign("prof_data", $prof_data);

#--------------------------------------------------------
# ページング用リンク文字列処理
#--------------------------------------------------------

// 次ページ番号
$next = $p + 1;
// 前ページ番号
$prev = $p - 1;
// 記事全件数
$tcnt = $fetchCnt[0]["CNT"];
// 全ページ数
$totalpage = ceil($tcnt/$fetch_title[0]['DISP_MAXROW']);

// 前ページへのリンク
$pr = "<a href=\"".RL_PATH."?p=".urlencode($prev)."&ca=".urlencode($ca)."&log=".urlencode($log)."&query=".urlencode($query)."\">&lt;&lt; Prev</a>";
if ($p <= 1) {
    $pr = "";
}

//次ページリンク
$nx = "<a href=\"".RL_PATH."?p=".urlencode($next)."&ca=".urlencode($ca)."&log=".urlencode($log)."&query=".urlencode($query)."\">Next &gt;&gt;</a>";
if ($totalpage <= $p) {
    $nx = "";
}

$page = $pr." &nbsp; ".$nx;

$tmpl->assign("page", $page);

if ($_POST['act'] != "prev") {
    $access_log =<<<JAVASCRIPT
<script language="JavaScript" type="text/javascript">
<!--
document.write('<img src="../log.php?referrer='+escape(document.referrer)+'" width="1" height="1">');
//-->
</script>
JAVASCRIPT;
} else {
    $access_log = "";
}

$tmpl->assign("access_log", $access_log);

#------------------------------------------------------------
# 設定した内容を一括出力
#------------------------------------------------------------
$tmpl->flush();
