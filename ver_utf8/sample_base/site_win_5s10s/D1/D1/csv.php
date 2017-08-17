<?
/*******************************************************************************

リスト出力

*******************************************************************************/

// 設定ファイル＆共通ライブラリの読み込み
require_once("../common/config_D1.php");		// 設定ファイル
require_once("dbOpe.php");			// ＤＢ操作クラスライブラリ

#=============================================================================================
# CSV形式のファイルに保存する
#
# 現在の時間を取得し、list-日時.csvというファイル名にして出力する
#=============================================================================================
$sql = "
	SELECT
		RES_ID,TITLE,CONTENT
	FROM
		".D1_OUTPUT."
	WHERE
		(DISPLAY_FLG = '1')
	AND
		(DEL_FLG = '0')
	ORDER BY
		VIEW_ORDER ASC
";

$fetchAnswerList = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

header("Content-Type: text/plain; charset=Shift_JIS");
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=list-".date("YmdHis").".csv");

	//各項目の設定
	$data .= "RES_ID,"."_TITLE,"."CONTENT"."\r\n";

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

// データの数だけループする。

for($i=0;$i<count($fetchAnswerList);$i++):

  $list = "";

   //改行がある場合があるため【”】で囲む
  $data .= "\"".csv_conversion($fetchAnswerList[$i][RES_ID])."\",";
  $data .= "\"".mb_convert_encoding(csv_conversion($fetchAnswerList[$i][TITLE]),"Shift-JIS","UTF-8")."\",";
  $data .= "\"".mb_convert_encoding(csv_conversion($fetchAnswerList[$i][CONTENT]),"Shift-JIS","UTF-8")."\"\r\n";

endfor;

echo $data;

?>
