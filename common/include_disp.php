<?php
#=================================================================================
# インクルード処理用関数
#=================================================================================

require_once(dirname(__FILE__)."/config.php");
require_once("util_lib.php");        // 汎用処理クラスライブラリ
require_once("dbOpe.php");        // ＤＢ操作クラスライブラリ

// SSLチェック
// SSLページならTRUEを返す
function sslchk()
{
    if ((false === empty($_SERVER['HTTPS'])) && ('off' !== $_SERVER['HTTPS'])) {
        return true;
    }
    return false;
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// $domain_name		SSLの場合このドメイン名を使用して絶対パスを作る。またはSSLへのパスを作る。wwwは無しで設定
// $demojp_name		demopage.jpでSSLページから簡単に戻れるようにする
// 新方式：$ssl_domain_name	SSLの【example-https.all-internet.jp】の部分を入れる。
// 旧方式：$ssl_domain_name	SSLの【mx00.all-internet.jp】の部分を入れる。
//
function path_maker()
{
    $domain_name = "example.com";//wwwは無しで設定
    $demojp_name = "example.demopage.jp";
    $ssl_domain_name = "example-https.all-internet.jp";
    // $ssl_domain_name = "mx00.all-internet.jp";

    $path_data = array();//最後にパスを返すための格納

    //このサイトのＴＯＰ階層を抽出する（裏側のファイルのパスなどに使用）
    $base_path = str_replace("/common", "", dirname(__FILE__));

    //ドキュメントルート ディレクトリのパスを除去する
    $dest_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $base_path) . '/';

    //通常ページへのリンク（０）、SSLへのリンク（１）、ファイルのパス（２）の３種類を設定
    if (strpos(__FILE__, "demopage.jp") !== false) {//デモページの場合　SSLが一切無い設定

        $path_data[0]     = $dest_path;
        $path_data[1]     = $dest_path;
        $path_data[2]     = $dest_path;
    } else if (sslchk()) {//sslページの場合

        //全て絶対パスにする
        $path_data[0]     = "http://www.".$domain_name.$dest_path;
        $path_data[1]     = "https://".$ssl_domain_name.$dest_path;
        // $path_data[1]	 = "https://".$ssl_domain_name."/".$domain_name.$dest_path;
        $path_data[2]     = "https://".$ssl_domain_name.$dest_path;
        // $path_data[2]	 = "https://".$ssl_domain_name."/".$domain_name.$dest_path;
    } else {

        //http抜きの全て絶対パスにする
        $path_data[0]     = $dest_path;
        $path_data[1]     = "https://".$ssl_domain_name.$dest_path;
        // $path_data[1]	 = "https://".$ssl_domain_name."/".$domain_name.$dest_path;
        $path_data[2]     = $dest_path;
    }

    return $path_data;
}

//リンク、パスのデータを受け取る 順番に通常リンクパス、SSL用リンクパス、ファイル用のパス
list($inc_nor_path, $inc_ssl_path, $inc_file_path) = path_maker();

// ディレクトリ名
//$plc = basename(dirname($_SERVER['PHP_SELF']));

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//　function で内容を出力　案件によって、左・右メニュー、フッターに動的カテゴリーなど
//　データベースへの接続が必要な場合がある。
//　変数名を多く使うと、同じ変数名を使用されてしまい誤動作を起こす可能性があるためfunctionを使用
//　（変数が初期化されていないと表示内容のデータが出てしまう、別なところで配列で使っているなどがある場合）
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 置換をすれば楽にパスが変更できるが、SSLを優先に置換をすること
// href="../ → href="{$inc_ssl_path}
// href="../ → href="{$inc_nor_path}
// src="../ → src="{$inc_file_path}
// ,'../ → ,'{$inc_file_path}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	ヘッダー
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function DispHeader()
{
    global $inc_nor_path,$inc_ssl_path,$inc_file_path;//ドメイン名をグローバル宣言

$html = <<<EDIT
EDIT;

//内容を返す。（ここで表示だとショッピングのテンプレートでは表示が難しい為、データを返す）
return $html;
}

function DispHeader2()
{
    global $inc_nor_path,$inc_ssl_path,$inc_file_path;//ドメイン名をグローバル宣言

    //表示ページによるヘッダーメニューの選択表示制御
        $dirname = dirname($_SERVER["PHP_SELF"]);//フォルダ名を取得
        $flist = array('about','service','jisseki','company','topics');//フォルダ名のリストを配列に入れる

        //ループで各フォルダを判定する
        for ($i=0;$i < count($flist);$i++) {
            ${'fdisp'.$i} = (strpos($dirname, $flist[$i]))?"on":"off";
        }

    $html = <<<EDIT
EDIT;

//内容を返す。（ここで表示だとショッピングのテンプレートでは表示が難しい為、データを返す）
return $html;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	サイド
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function DispSide()
{
    global $inc_nor_path,$inc_ssl_path,$inc_file_path;//ドメイン名をグローバル宣言

$html = <<<EDIT
EDIT;

//内容を返す。（ここで表示だとショッピングのテンプレートでは表示が難しい為、データを返す）
return $html;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	フッター
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function DispFooter()
{
    global $inc_nor_path,$inc_ssl_path,$inc_file_path;//ドメイン名をグローバル宣言

$html = <<<EDIT
EDIT;

//内容を返す。（ここで表示だとショッピングのテンプレートでは表示が難しい為、データを返す）
return $html;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Googleアナリティクス
//	head閉じタグの直前に挿入
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function DispAnalytics()
{
    global $inc_nor_path,$inc_ssl_path,$inc_file_path;//ドメイン名をグローバル宣言

$html = <<<EDIT

EDIT;

//内容を返す。（ここで表示だとショッピングのテンプレートでは表示が難しい為、データを返す）
return $html;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	全ページに追加するタグ
//	body閉じタグの直前に挿入
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function DispBeforeBodyEndTag()
{
    global $inc_nor_path,$inc_ssl_path,$inc_file_path;//ドメイン名をグローバル宣言

$html = <<<EDIT

EDIT;

//内容を返す。（ここで表示だとショッピングのテンプレートでは表示が難しい為、データを返す）
return $html;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	アクセス解析
//	body閉じタグの直前に挿入
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function DispAccesslog()
{
    global $inc_file_path;//リンクをグローバル宣言

$html = "";

// プレビューはログを取らない
if (!empty($_POST['act'])) {
    return $html;
}

/*
$ua = $_SERVER['HTTP_USER_AGENT'];
if ((strpos($ua, 'Android') !== false) && (strpos($ua, 'Mobile') !== false) || (strpos($ua, 'iPhone') !== false) || (strpos($ua, 'Windows Phone') !== false)) {
    // スマートフォンからアクセスされた場合
    $link_type = "log_sp.php";
} elseif ((strpos($ua, 'Android') !== false) || (strpos($ua, 'iPad') !== false) || strpos($ua,'Silk') !== false) {
    // タブレットからアクセスされた場合
    $link_type = "log_tb.php";
} else {
    // その他（PC）からアクセスされた場合
    $link_type = "log.php";
}
*/
$link_type = "log.php";

    //////////////////////////////////////////
    //ログファイルのパス設定
        $top_path = $inc_file_path;

    $html = <<<EDIT
<!-- ここから -->
<script type="text/javascript" src="https://www.google.com/jsapi?key="></script>
<script src="https://api.all-internet.jp/accesslog/access.js" language="javascript" type="text/javascript"></script>
<script language="JavaScript" type="text/javascript">
<!--
var s_obj = new _WebStateInvest();
document.write('<img src="{$top_path}{$link_type}?referrer='+escape(document.referrer)+'&st_id_obj='+encodeURI(String(s_obj._st_id_obj))+'" width="1" height="1" style="display:none">');
//-->
</script>
<!-- ここまで -->
EDIT;

//内容を返す。（ここで表示だとショッピングのテンプレートでは表示が難しい為、データを返す）
return $html;
}
