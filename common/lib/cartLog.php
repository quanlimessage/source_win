<?php
/******************************************************************************************************************************
■カートログ保存クラスライブラリ

  クレジットのログを残すためのプログラムです。

2005/10/7 Author : kinoi
******************************************************************************************************************************/

class cartLog{


//=============================================================================================================================
// ◆ログデータをファイルに格納するメソッド
// 
// 使用方法：regist("ファイルパス", "ファイル名", "データの項目名", "データ");
// 
//=============================================================================================================================
function regist( $path, $filename, $title, $log_data ){
	
	if ( !empty($path) && !empty($filename) ):
		
		$file_path = $path.$filename.".dat";
		
		if ( empty($title) ){ $title = "-------------------------------- ".date("Y/m/d H:i:s"); }
		else { $title = "// {$title} ----- ".date("Y/m/d H:i:s"); }
		
		$input_data = $title."\n\n";
		if ( is_array($log_data) ){
			$log_data = print_r($log_data, true);
		}
		$input_data .= $log_data."\n\n";
		
		// 環境情報の取得
		$env = "### 環境情報 ###\n";
		$input_data .= "USER_AGENT\t".$_SERVER['HTTP_USER_AGENT']."\n";
		$input_data .= "HTTP_REFERER\t".$_SERVER['HTTP_REFERER']."\n";
		$input_data .= "REMOTE_ADDR\t".$_SERVER['REMOTE_ADDR']."\n";
		
		// ファイル書き込み（追記型）
		$fp = @fopen($file_path, "a");
		@flock($fp, LOCK_EX);
		@fwrite($fp, $input_data);
		@fwrite($fp, "\n\n");
		@flock($fp, LOCK_UN);
		@fclose($fp);
	
	endif;
}

} // cartLogクラスの終了

?>
