<?php
#=================================================================================
# スマホの表示切替処理
#=================================================================================
//この処理を行うのはスマホ限定
/*	if(
		(strpos($_SERVER['HTTP_USER_AGENT'],'iPhone')!==false)
		||
		(strpos($_SERVER['HTTP_USER_AGENT'],'iPod')!==false)
		||
		(strpos($_SERVER['HTTP_USER_AGENT'],'Android')!==false)
	){  */
		//カートの商品を削除する為セッションを削除（文字コードが違うデータが入っている為）
		session_start();
		session_destroy();

		if($_GET['mode']){//モードにデータが入っている場合

			//モードのデータが一致したらクッキーにデータを渡してリダイレクトを行う
			//クッキーの設定（有効時間を一時間に設定）
			switch($_GET['mode']):
				case 'pc'://ＰＣサイトを表示

					setcookie("mode", "pc",time()+3600, '/', '.'.$_SERVER['SERVER_NAME'],0);//クッキーを作成
					//setcookie("mode", "pc",mktime(0,0,0,date("n"),date("j")+7,(date("Y"))), '/', '.'.$_SERVER['SERVER_NAME'],0);//クッキーを作成
					break;

				case 'sp'://スマホサイトを表示

					setcookie("mode", "sp",time()+3600, '/', '.'.$_SERVER['SERVER_NAME'],0);//クッキーを作成
					//setcookie("mode", "sp",mktime(0,0,0,date("n"),date("j")+7,(date("Y"))), '/', '.'.$_SERVER['SERVER_NAME'],0);//クッキーを作成
					break;
			endswitch;

		}
	//}

	//トップに戻す
	if($_GET['rd']){
		//XSS対応の為文字処理を行う
		$rd_path = strip_tags($_GET['rd']);
		$rd_path = urlencode($rd_path);

		header("Location: ./".$rd_path, true, 302);
	}else{
		header("Location: ./", true, 302);
	}
	exit();

?>
