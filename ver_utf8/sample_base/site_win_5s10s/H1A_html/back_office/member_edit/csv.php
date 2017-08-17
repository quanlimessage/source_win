<?php
/*******************************************************************************

リスト出力

*******************************************************************************/
session_start();

// 設定ファイル＆共通ライブラリの読み込み
require_once("dbOpe.php");      // ＤＢ操作クラスライブラリ
require_once("util_lib.php");       // 汎用処理クラスライブラリ
require_once("../../common/config_H1A.php");  //設定ファイル

#=============================================================================================
# CSV形式のファイルに保存する
#
# 現在の時間を取得し、list-日時.csvというファイル名にして出力する
#=============================================================================================
$sql = "
	SELECT
		*
	FROM
		" . MEMBER_LST . "
	WHERE
		(DEL_FLG = '0')
	".$_SESSION["condition"]."
	ORDER BY
		INS_DATE ASC
";

$fetchAnswerList = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

//入力された危険文字が実態参照で表示されるため処理をする
function csv_conversion($str){
  $str = str_replace("&amp;","＆",$str);
  $str = str_replace("&quot;","”",$str);
  $str = str_replace("&lt;","＜",$str);
  $str = str_replace("&gt;","＞",$str);
  $str = str_replace("&#39","’",$str);
  $str = str_replace("'","’",$str);
  $str = str_replace("&","＆",$str);

  return $str;
}

function csv_format($str){
  $str = csv_conversion($str);
  $str = mb_convert_encoding($str,"Shift-JIS","UTF-8");
  //改行がある場合があるため【”】で囲む
  $str = "\"".$str."\"";
  return $str;
}

header("Content-Type: text/plain; charset=Shift_JIS");
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=list-".date("YmdHis").".csv");

//各項目の設定
$data = "";
$data .= csv_format("お名前").",";
$data .= csv_format("フリガナ").",";
$data .= csv_format("郵便番号").",";
$data .= csv_format("都道府県").",";
$data .= csv_format("市区町村").",";
$data .= csv_format("マンション名など").",";
$data .= csv_format("電話番号").",";
$data .= csv_format("FAX番号").",";
$data .= csv_format("メールアドレス").",";
$data .= csv_format("年代").",";
$data .= csv_format("職業").",";
$data .= csv_format("メール配信希望")."\r\n";

// データの数だけループする。
for($i=0,$cnt=count($fetchAnswerList);$i<$cnt;$i++):

  $data .= csv_format($fetchAnswerList[$i]['NAME']).",";
  $data .= csv_format($fetchAnswerList[$i]['KANA']).",";
  $data .= csv_format("〒".$fetchAnswerList[$i]['ZIP_CD1']."-".$fetchAnswerList[$i]['ZIP_CD2']).",";
  $data .= csv_format($state_list[$fetchAnswerList[$i]['STATE']]).",";
  $data .= csv_format($fetchAnswerList[$i]['ADDRESS1']).",";
  $data .= csv_format($fetchAnswerList[$i]['ADDRESS2']).",";
  $data .= csv_format($fetchAnswerList[$i]['TEL']).",";
  $data .= csv_format($fetchAnswerList[$i]['FAX']).",";
  $data .= csv_format($fetchAnswerList[$i]['EMAIL']).",";
  $data .= csv_format($generation_list[$fetchAnswerList[$i]['GENERATION_CD']]).",";
  $data .= csv_format($job_list[$fetchAnswerList[$i]['JOB_CD']]).",";
  $data .= csv_format(($fetchAnswerList[$i]['SENDMAIL'] == "1")?"希望する":"希望しない")."\r\n";

endfor;

echo $data;

?>
