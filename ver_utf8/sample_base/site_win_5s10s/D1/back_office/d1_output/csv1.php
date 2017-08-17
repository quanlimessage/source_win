<?
/*******************************************************************************

リスト出力

*******************************************************************************/

// 設定ファイル＆共通ライブラリの読み込み
require_once("../../common/config.php");		// 設定ファイル
require_once("dbOpe.php");			// ＤＢ操作クラスライブラリ
require_once("util_lib.php");			// 汎用処理クラスライブラリ

#=============================================================================================
# CSV形式のファイルに保存する
#
# 現在の時間を取得し、list-日時.csvというファイル名にして出力する
#=============================================================================================

header("Content-Type: text/plain; charset=Shift_JIS");//エンコードの指定
header("Content-Type: text/csv");//MIME-type（ファイルの形式指定）
header("Content-Disposition: attachment; filename=会員情報-".date("YmdHis").".csv");//出力するファイル名（全角を出す場合はこのファイル自体をSJISにエンコードしないと文字化けを起こす）

	//各項目の設定（項目名の一番最初の文字が【ID】と書いてしまうとofficeのエクセルでCSVではなく別の形式ファイルとご認識してしまう）
	$data = "更新日,タイトル,本文\r\n";

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

// データの数が多いので分割して出力をする
	//まず、出力する数を出す。
		$cnt_sql = "SELECT COUNT(*) AS CNT FROM D1_OUTPUT WHERE(DEL_FLG = '0')";
		$fetchCNT = dbOpe::fetch($cnt_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	//分割して出力する数を設定
		$division = 1000;//分割していく数
		$division_now = 0;//現在の分割の位置

	//全体のループ
	for($j=0;$j < $fetchCNT[0]['CNT'];$j+=$division){

		//必要なデータを取得する
			$sql = "
				SELECT
					RES_ID,
					TITLE,
					CONTENT,
					DISP_DATE
				FROM
					D1_OUTPUT
				WHERE
					(DEL_FLG = '0')
				ORDER BY
					VIEW_ORDER ASC
				LIMIT
					$j,$division
			";

			$fetchList = array();//初期化
			$fetchList = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

		//取得したデータを全てSJISにエンコードをさせる。
		mb_convert_variables('SJIS','UTF-8',$fetchList);//機種依存文字がある場合は【UTF-8win】【SJIS-win】を使用してみる

		for($i=0;$i<count($fetchList);$i++){

			//改行がある場合があるため【”】で囲む
				$data .= "\"".csv_conversion($fetchList[$i][DISP_DATE])."\",";//更新日
				$data .= "\"".csv_conversion($fetchList[$i][TITLE])."\",";//タイトル
				$data .= "\"".csv_conversion($fetchList[$i][CONTENT])."\"\r\n";//本文

		}

	}

//最後にデータを出力する
echo $data;

?>
