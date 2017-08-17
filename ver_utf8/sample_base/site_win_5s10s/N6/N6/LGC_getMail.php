<?php
/*****************************************************************************
 写メール日記用クラスライブラリ
 使用サンプル

 ライブラリファイル名：syameOpe.php
 クラス名：syameOpe		※直接クラスメソッドを呼び出す方式でも使用できます。

メソッド１：POP3サーバにアクセスし、生のメールデータを件数分取得

		メソッド名：getPOP3(host,user,pass)
		引数：		POP3鯖のホスト名、POP3鯖のユーザー名、POP3鯖のパスワード
		戻り値：	分解前の生のメールデータを配列で返す

メソッド２：件数分取得した生のメールデータを項目毎に分解加工して多次元配列で返す

		メソッド名：getQuery(array)
		引数：		getPOP3メソッドで戻ってきた配列データ
		戻り値：	メールデータを項目毎に分解加工して多次元配列で返す（2次元目の要素名は固定）

				※戻る多次元配列の内容（2次元目の要素名は固定です）
				$hoge[$i]["id"]			※ID（1次元目の要素と同じ値。特に使用しなくてもＯＫ）
				$hoge[$i]["now"]		※メール送信日時
				$hoge[$i]["subject"]	※メールの件名
				$hoge[$i]["from"]		※メールの“From”のメールアドレス
				$hoge[$i]["text"]		※メールの本文
				$hoge[$i]["attach"]		※添付ファイル名（ユニークな名前を自動割当）
				$hoge[$i]["tmp"]		※添付ファイル実体（格納方法はサンプル参照）

*****************************************************************************/

// 不正アクセスチェック
if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}

#=================================================================================
# 写メール日記用クラスライブラリを使用してPOP3鯖よりデータを取得して表示
#=================================================================================

// 設定ファイル＆写メライブラリの読み込み
require_once("syameOpe.php");		// 写メール日記用クラスライブラリ

// 写メオペのインスタンス作成
$syame = new syameOpe();

// 画像保存ディレクトリ（パーミッション777）
//$tmpdir = "/home/demo3/html/yossee/test/mail2/data/";
$tmpdir = $_SERVER['DOCUMENT_ROOT'].N6_1IMG_TMPPATH;

$img_path = $_SERVER['DOCUMENT_ROOT'].N6_1IMG_UPPATH;

// 投稿非許可件名を設定		※引数：配列
$subject_replace = array('未承諾','広告','未承認広告','未　承　認　広　告');

// 本文から削除する文字列（広告文など）を設定	※引数：配列
$text_replace = array('会員登録は無料','http://auction.msn.co.jp/');

// 禁止されている言葉チェック設定
$sql_words = "SELECT WORDS FROM NG_WORDS WHERE (KEY_ID = '9999')";
// ＳＱＬを実行
$fetch = dbOpe::fetch($sql_words,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

#=================================================================================
# Mofify KC

// Mail account info setting
$sql_ini = "SELECT EMAIL2,POP3,USER_NAME,PASS FROM APP_INIT_DATA WHERE (RES_ID = '1')";
$fetch_ini = dbOpe::fetch($sql_ini,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
#================================================================================

// POP3鯖から基本データを取得（POP3ホスト、ユーザー、パスワードの順で引数を指定する）
$result = $syame->getPOP3($fetch_ini[0]["POP3"],$fetch_ini[0]["USER_NAME"],$fetch_ini[0]["PASS"]);
//$result = syameOpe::getPOP3("mail.xxxxxx.xx","xxxxxxx%xxxx.xx","xxxxxxxx");

// 基本データを取得し、登録件数分のデータ（加工済みの多次元配列データ）を取り出して表示
if(!empty($result)):

		$line = $syame->getQuery($result);
		//$line = syameOpe::getQuery($result);
		for($i=0;$i<count($line);$i++):

			// SET ID (ATTACH FILE NAME)
			$diary_id = $makeID().$line[$i]["id"];
			// SET MAIL ADDRESS
			$email = $line[$i]["from"];
			// SET SUBJECT
			if($line[$i]["subject"]){
				$subject = str_replace($subject_replace,"",$line[$i]["subject"]);
			}else{
				$subject = "";
			}
			// SET COMMENT
			if($line[$i]["text"]){
				$comment = html_entity_decode(str_replace($text_replace,"",$line[$i]["text"]));
				$comment = utilLib::strRep($comment,8);
				$comment = utilLib::strRep($comment,4);
				$comment = utilLib::strRep($comment,1);
				$comment = utilLib::strRep($comment,7);
				$comment = str_replace("&amp;nbsp;","",$comment);
			}else{
				$comment = "";
			}
			// SET REG DATE
			//$reg_date = str_replace(".","-",$line[$i]["now"]);
			$reg_date = $line[$i]["now"];

			// 禁止されている言葉チェック
			$moji = $subject.$comment;
			$ng_word = array();
			$ng_word = explode(",",$fetch[0]["WORDS"]);
			$ngcount = 0;

			foreach($ng_word as $value){

			if($ngcount)break;

				$ngcount = @substr_count($moji,$value);

			}

			// 表示設定
			if($ngcount>0){
				$display_flg = 0;
			}else{
				$display_flg = 1;
			}

			// 画像処理クラスimgOpeのインスタンス生成
			//$imgObj = new imgOpe($img_path);

			if($line[$i]['tmp']){

				$fp = fopen($tmpdir.$line[$i]["attach"],"w") or die("File Not Open!!");
				fputs($fp,$line[$i]["tmp"]);
				fclose($fp);

				$uploaded_img_file = $tmpdir.$line[$i]["attach"];

				//画像のリサイズを行う
				$size = getimagesize($uploaded_img_file);

				//横固定縦可変型
				$ox = N6_1IMGSIZE_MX;//横の固定サイズ
				$oy = $size[1]/($size[0]/$ox);

					/*
					//指定範囲内に収まるように縮小
						//横幅のが長ければ横幅の比率で縦幅を決める
						if($size[0]>=$size[1]){

							$ox = N6_1IMGSIZE_MX;
							$oy = ($size[1] * N6_1IMGSIZE_MX) / $size[0];
						//縦幅のが長ければ縦幅の比率で横幅を決める

						}elseif($size[0]<$size[1]){
							$ox = ($size[0] * N6_1IMGSIZE_MY) / $size[1];
							$oy = N6_1IMGSIZE_MY;

						}
					*/

				$imgName = $img_path.$diary_id.'.jpg';

				// ファイルの形式チェック
				switch($size[2]):
				case IMAGETYPE_GIF:
					$upNewTmpImg  = ImageCreateFromGIF($uploaded_img_file);
				break;
				case IMAGETYPE_JPEG:
					$upNewTmpImg  = ImageCreateFromJPEG($uploaded_img_file);
				break;
				case IMAGETYPE_PNG:
					$upNewTmpImg  = ImageCreateFromPNG($uploaded_img_file);
				break;
				endswitch;

				$newImage = ImageCreateTrueColor($ox,$oy);
				$icr_result = ImageCopyResampled($newImage,$upNewTmpImg,0,0,0,0,$ox,$oy,$size[0],$size[1]);

				if($icr_result)$ij_result = imagejpeg($newImage,$imgName);

				ImageDestroy($upNewTmpImg);
				imageDestroy($newImage);

				if(!$icr_result||!$ij_result)die("{$i}番目商品画像のアップロード処理に失敗しました。");

				unlink($uploaded_img_file) or die("TMG画像の削除に失敗しました。");

			}

		//ＤＢへのデータ格納時に必要な文字をエスケープ。(addslashes)
		$comment = utilLib::strRep($comment,5);

		// INSERT TO DB
		#-----------------------------------------------------
		# 新規登録用SQLの組立
		#-----------------------------------------------------
		$sql = "
		INSERT INTO N6_1DIARY(
			DIARY_ID,
			EMAIL,
			SUBJECT,
			COMMENT,
			DISPLAY_FLG,
			REG_DATE
		)
		VALUES(
			'$diary_id',
			'$email',
			'$subject',
			'$comment',
			'$display_flg',
			'$reg_date'
		)";

		// ＳＱＬを実行
		if(!empty($sql)){
			$db_result = dbOpe::regist($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
			if($db_result)die("{$i}DB登録失敗しました<hr>{$db_result}");
		}

		endfor;
	/*
	else:
		echo "メールはないようだ。。。";
	*/
endif;

?>