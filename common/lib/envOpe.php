<?php
/***********************************************************************************************************
 環境変数操作クラスライブラリ
 	※環境変数情報の判別、取得、ファイル書き出し等を行う

 2004/8/11 Yossee
***********************************************************************************************************/

############################################################################################################
#
# ユーザーエージェント情報操作クラス	UA_Info("HTTP_USER_AGENTの情報");
#	※パラメーター省略可。省略した場合は$_SERVER['HTTP_USER_AGENT']を設定して情報を取得する
#
############################################################################################################
class UA_Info{


var $ua;	# HTTP_USER_AGENT

// コンストラクタ
function UA_Info($ev = ""){
	$this->ua = (empty($ev))?$_SERVER['HTTP_USER_AGENT']:$ev;
}

#------------------------------------------------------------------------------------------------
# 使用ＯＳ／ブラウザ判定メソッド
#
#	メソッド：getNavInfo()			
#	引数：	なし（コンストラクタで設定した情報を使用）
#	戻り値：結果情報を配列で返す
#	
#	※戻り値の内容（番号は要素番号）
#		0:主要モダンブラウザの判定(bool) ※WinIE5～6 MacIE5／NN6～7／Gecko系／Opera6～7／Safari
#		1:総合判定したUA情報(String)
#		2:ＯＳ名(String)
#		3:ＯＳのバージョン(String)
#		4:ブラウザ名(String)
#		5:ブラウザのバージョン(String)
#		6:文字列処理してUAを格納
#
#------------------------------------------------------------------------------------------------
function getNavInfo(){

	///////////////////////////////////////////
	// 結果格納の配列を初期化
	$result[0] = false;
	$result[1] = "";
	$result[2] = "";
	$result[3] = "";
	$result[4] = "";
	$result[5] = "";
	$result[6] = "";

	///////////////////////////////////////////
	// 文字列処理（危険文字回避）の匿名関数
	//$strSyori = create_function('$str','$str = strip_tags($str);$str = htmlspecialchars($str);'.$str = str_replace(array("\t","/etc/passwd","sendmail","\\","|"),"",$str).';return $str;');
    function strSyori($str){
    	$str = strip_tags($str);
    	$str = htmlspecialchars($str);
    	$str = str_replace(array("\t","/etc/passwd","sendmail","\\","|"),"",$str);
    	return $str;
    }

	///////////////////////////////////////////
	// Windows95
	if(stristr($this->ua,"MSIE 4.0")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows 95")&&!stristr($this->ua,"Opera")){
		$result[1] = "Windows95 IE4.0";
		$result[2] = "Windows";
		$result[3] = "95";
		$result[4] = "Internet Explorer";
		$result[5] = "4.0";
	}
	elseif(stristr($this->ua,"MSIE 5.0")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows 95")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows95 IE5.0";
		$result[2] = "Windows";
		$result[3] = "95";
		$result[4] = "Internet Explorer";
		$result[5] = "5.0";
	}
	elseif(stristr($this->ua,"MSIE 5.5;")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows 95")&& !stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows95 IE5.5";
		$result[2] = "Windows";
		$result[3] = "95";
		$result[4] = "Internet Explorer";
		$result[5] = "5.5";
	}
	elseif(stristr($this->ua,"MSIE 6.0")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows 95")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows95 IE6.0";
		$result[2] = "Windows";
		$result[3] = "95";
		$result[4] = "Internet Explorer";
		$result[5] = "6.0";
	}
	// Firefox
	elseif(stristr($this->ua,"Firefox/1")&&stristr($this->ua,"Win95")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows95 Firefox1.x";
		$result[2] = "Windows";
		$result[3] = "95";
		$result[4] = "Firefox";
		$result[5] = "1.x";
	}
	elseif(stristr($this->ua,"Firefox/2")&&stristr($this->ua,"Win95")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows95 Firefox2.x";
		$result[2] = "Windows";
		$result[3] = "95";
		$result[4] = "Firefox";
		$result[5] = "2.x";
	}
	elseif(stristr($this->ua,"Firefox/3")&&stristr($this->ua,"Win95")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows95 Firefox3.x";
		$result[2] = "Windows";
		$result[3] = "95";
		$result[4] = "Firefox";
		$result[5] = "3.x";
	}
	elseif(stristr($this->ua,"Netscape6")&&stristr($this->ua,"Win95")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows95 NN6.x";
		$result[2] = "Windows";
		$result[3] = "95";
		$result[4] = "Netscape";
		$result[5] = "6.x";
	}
	elseif(stristr($this->ua,"Netscape/7")&&stristr($this->ua,"Win95")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows95 NN7.x";
		$result[2] = "Windows";
		$result[3] = "95";
		$result[4] = "Netscape";
		$result[5] = "7.x";
	}
	elseif(stristr($this->ua,"Gecko/")&&stristr($this->ua,"Win95")&&!stristr($this->ua,"Netscape/7")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows95 Mozilla.org";
		$result[2] = "Windows";
		$result[3] = "95";
		$result[4] = "Mozilla.org";
		$result[5] = "Unknown";
	}
	elseif((stristr($this->ua,"Opera 6")||stristr($this->ua,"Opera/6"))&&stristr($this->ua,"Windows 95")){
		$result[0] = true;
		$result[1] = "Windows95 Opera6.x";
		$result[2] = "Windows";
		$result[3] = "95";
		$result[4] = "Opera";
		$result[5] = "6.x";
	}
	elseif((stristr($this->ua,"Opera 7")||stristr($this->ua,"Opera/7"))&&stristr($this->ua,"Windows 95")){
		$result[0] = true;
		$result[1] = "Windows95 Opera7.x";
		$result[2] = "Windows";
		$result[3] = "95";
		$result[4] = "Opera";
		$result[5] = "7.x";
	}


	///////////////////////////////////////////
	// Windows98
	elseif(stristr($this->ua,"MSIE 4.0")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows 98")&&!stristr($this->ua,"Win 9x")&&!stristr($this->ua,"Opera")){
		$result[1] = "Windows98 IE4.0";
		$result[2] = "Windows";
		$result[3] = "98";
		$result[4] = "Internet Explorer";
		$result[5] = "4.0";
	}
	elseif(stristr($this->ua,"MSIE 5.0")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows 98")&&!stristr($this->ua,"Win 9x")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows98 IE5.0";
		$result[2] = "Windows";
		$result[3] = "98";
		$result[4] = "Internet Explorer";
		$result[5] = "5.0";
	}
	elseif(stristr($this->ua,"MSIE 5.5;")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows 98")&&!stristr($this->ua,"Win 9x")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows98 IE5.5";
		$result[2] = "Windows";
		$result[3] = "98";
		$result[4] = "Internet Explorer";
		$result[5] = "5.5";
	}
	elseif(stristr($this->ua,"MSIE 6.0")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows 98")&&!stristr($this->ua,"Win 9x")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows98 IE6.0";
		$result[2] = "Windows";
		$result[3] = "98";
		$result[4] = "Internet Explorer";
		$result[5] = "6.0";
	}
	// Firefox
	elseif(stristr($this->ua,"Firefox/1")&&stristr($this->ua,"Windows 98")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows98 Firefox1.x";
		$result[2] = "Windows";
		$result[3] = "98";
		$result[4] = "Firefox";
		$result[5] = "1.x";
	}
	elseif(stristr($this->ua,"Firefox/2")&&stristr($this->ua,"Windows 98")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows98 Firefox2.x";
		$result[2] = "Windows";
		$result[3] = "98";
		$result[4] = "Firefox";
		$result[5] = "2.x";
	}
	elseif(stristr($this->ua,"Firefox/3")&&stristr($this->ua,"Windows 98")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows98 Firefox3.x";
		$result[2] = "Windows";
		$result[3] = "98";
		$result[4] = "Firefox";
		$result[5] = "3.x";
	}
	elseif(!stristr($this->ua,"compatible;")&&stristr($this->ua,"Mozilla/4.")&&stristr($this->ua,"Win98")&&!stristr($this->ua,"Opera")){
		$result[1] = "Windows98 NN4.x";
		$result[2] = "Windows";
		$result[3] = "98";
		$result[4] = "Netscape";
		$result[5] = "4.x";
	}
	elseif(stristr($this->ua,"Netscape6")&&stristr($this->ua,"Win98")&&!stristr($this->ua,"Win 9x")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows98 NN6.x";
		$result[2] = "Windows";
		$result[3] = "98";
		$result[4] = "Netscape";
		$result[5] = "6.x";
	}
	elseif(stristr($this->ua,"Netscape/7")&&stristr($this->ua,"Win98")&&!stristr($this->ua,"Win 9x")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows98 NN7.x";
		$result[2] = "Windows";
		$result[3] = "98";
		$result[4] = "Netscape";
		$result[5] = "7.x";
	}
	elseif(stristr($this->ua,"Gecko/")&&stristr($this->ua,"Win98")&&!stristr($this->ua,"Win 9x")&&!stristr($this->ua,"Netscape/7")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows98 Mozilla.org";
		$result[2] = "Windows";
		$result[3] = "98";
		$result[4] = "Mozilla.org";
		$result[5] = "Unknown";
	}
	elseif((stristr($this->ua,"Opera 6")||stristr($this->ua,"Opera/6"))&&stristr($this->ua,"Windows 98")&&!stristr($this->ua,"Win 9x")){
		$result[0] = true;
		$result[1] = "Windows98 Opera6.x";
		$result[2] = "Windows";
		$result[3] = "98";
		$result[4] = "Opera";
		$result[5] = "6.x";
	}
	elseif((stristr($this->ua,"Opera 7")||stristr($this->ua,"Opera/7"))&&stristr($this->ua,"Windows 98")&&!stristr($this->ua,"Win 9x")){
		$result[0] = true;
		$result[1] = "Windows98 Opera7.x";
		$result[2] = "Windows";
		$result[3] = "98";
		$result[4] = "Opera";
		$result[5] = "7.x";
	}


	///////////////////////////////////////////
	// WindowsMe
	elseif(stristr($this->ua,"MSIE 5.0;")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows 98; Win 9x")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsMe IE5.0";
		$result[2] = "Windows";
		$result[3] = "Me";
		$result[4] = "Internet Explorer";
		$result[5] = "5.0";
	}
	elseif(stristr($this->ua,"MSIE 5.5;")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows 98; Win 9x")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsMe IE5.5";
		$result[2] = "Windows";
		$result[3] = "Me";
		$result[4] = "Internet Explorer";
		$result[5] = "5.5";
	}
	elseif(stristr($this->ua,"MSIE 6.0")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows 98; Win 9x")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsMe IE6.0";
		$result[2] = "Windows";
		$result[3] = "Me";
		$result[4] = "Internet Explorer";
		$result[5] = "6.0";
	}
	
	// WindowsMe : IE7
	elseif(stristr($this->ua,"MSIE 7.0")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows 98; Win 9x")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsMe IE7.0";
		$result[2] = "Windows";
		$result[3] = "Me";
		$result[4] = "Internet Explorer";
		$result[5] = "7.0";
	}
	// Firefox
	elseif(stristr($this->ua,"Firefox/1")&&stristr($this->ua,"Windows 98; Win 9x")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsMe Firefox1.x";
		$result[2] = "Windows";
		$result[3] = "Me";
		$result[4] = "Firefox";
		$result[5] = "1.x";
	}	
	elseif(stristr($this->ua,"Firefox/2")&&stristr($this->ua,"Windows 98; Win 9x")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsMe Firefox2.x";
		$result[2] = "Windows";
		$result[3] = "Me";
		$result[4] = "Firefox";
		$result[5] = "2.x";
	}
	elseif(stristr($this->ua,"Firefox/3")&&stristr($this->ua,"Windows 98; Win 9x")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsMe Firefox3.x";
		$result[2] = "Windows";
		$result[3] = "Me";
		$result[4] = "Firefox";
		$result[5] = "3.x";
	}
	elseif(!stristr($this->ua,"compatible;")&&stristr($this->ua,"Mozilla/4.")&&stristr($this->ua,"Win95")&&!stristr($this->ua,"Opera")){
		$result[1] = "WindowsMe or 95 NN4.x";
		$result[2] = "Windows";
		$result[3] = "Me or 95";
		$result[4] = "Netscape";
		$result[5] = "4.x";
	}
	elseif(stristr($this->ua,"Netscape6")&&stristr($this->ua,"Win 9x")&& !stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsMe NN6.x";
		$result[2] = "Windows";
		$result[3] = "Me";
		$result[4] = "Netscape";
		$result[5] = "6.x";
	}
	elseif(stristr($this->ua,"Netscape/7")&&stristr($this->ua,"Win 9x")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsMe NN7.x";
		$result[2] = "Windows";
		$result[3] = "Me";
		$result[4] = "Netscape";
		$result[5] = "7.x";
	}
	elseif(stristr($this->ua,"Gecko/")&&stristr($this->ua,"Win 9x")&&!stristr($this->ua,"Netscape/7")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsMe Mozilla.org";
		$result[2] = "Windows";
		$result[3] = "Me";
		$result[4] = "Mozilla.org";
		$result[5] = "Unknown";
	}
	elseif((stristr($this->ua,"Opera 6")||stristr($this->ua,"Opera/6"))&&stristr($this->ua,"Windows ME")){
		$result[0] = true;
		$result[1] = "WindowsMe Opera6.x";
		$result[2] = "Windows";
		$result[3] = "Me";
		$result[4] = "Opera";
		$result[5] = "6.x";
	}
	elseif((stristr($this->ua,"Opera 7")||stristr($this->ua,"Opera/7"))&&stristr($this->ua,"Windows ME")){
		$result[0] = true;
		$result[1] = "WindowsMe Opera7.x";
		$result[2] = "Windows";
		$result[3] = "Me";
		$result[4] = "Opera";
		$result[5] = "7.x";
	}


	///////////////////////////////////////////	
	// Windows 2000 
	elseif(stristr($this->ua,"MSIE 5.0")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows NT 5.0")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows2000 IE5.0";
		$result[2] = "Windows";
		$result[3] = "2000";
		$result[4] = "Internet Explorer";
		$result[5] = "5.0";
	}
	elseif(stristr($this->ua,"MSIE 5.5;")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows NT 5.0")&& !stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows2000 IE5.5";
		$result[2] = "Windows";
		$result[3] = "2000";
		$result[4] = "Internet Explorer";
		$result[5] = "5.5";
	}
	
	// Windows2000 : IE7
	elseif(stristr($this->ua,"MSIE 7.0")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows NT 5.0")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsXP IE7.0";
		$result[2] = "Windows";
		$result[3] = "2000";
		$result[4] = "Internet Explorer";
		$result[5] = "7.0";
	}
	elseif(stristr($this->ua,"MSIE 6.0")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows NT 5.0")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows2000 IE6.0";
		$result[2] = "Windows";
		$result[3] = "2000";
		$result[4] = "Internet Explorer";
		$result[5] = "6.0";
	}
	elseif(stristr($this->ua,"MSIE 7.0")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows NT 5.2")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows2003 IE7.0";
		$result[2] = "Windows";
		$result[3] = "2003";
		$result[4] = "Internet Explorer";
		$result[5] = "7.0";
	}
	elseif(stristr($this->ua,"MSIE 8.0")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows NT 5.2")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows2003 IE8.0";
		$result[2] = "Windows";
		$result[3] = "2003";
		$result[4] = "Internet Explorer";
		$result[5] = "8.0";
	}
	elseif(stristr($this->ua,"MSIE 9.0")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows NT 5.2")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows2003 IE9.0";
		$result[2] = "Windows";
		$result[3] = "2003";
		$result[4] = "Internet Explorer";
		$result[5] = "9.0";
	}
	elseif(stristr($this->ua,"Windows NT 5.2")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows2003";
		$result[2] = "Windows";
		$result[3] = "2003";
		$result[4] = "Unknown";
		$result[5] = " Unknown";
	}
	
	// Firefox
	elseif(stristr($this->ua,"Firefox/1")&&stristr($this->ua,"Windows NT 5.0")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsMe Firefox1.x";
		$result[2] = "Windows";
		$result[3] = "2000";
		$result[4] = "Firefox";
		$result[5] = "1.x";
	}
	elseif(stristr($this->ua,"Firefox/2")&&stristr($this->ua,"Windows NT 5.0")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsMe Firefox2.x";
		$result[2] = "Windows";
		$result[3] = "2000";
		$result[4] = "Firefox";
		$result[5] = "2.x";
	}
	elseif(stristr($this->ua,"Firefox/3")&&stristr($this->ua,"Windows NT 5.0")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsMe Firefox3.x";
		$result[2] = "Windows";
		$result[3] = "2000";
		$result[4] = "Firefox";
		$result[5] = "3.x";
	}
	elseif(stristr($this->ua,"Firefox/4")&&stristr($this->ua,"Windows NT 5.0")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsMe Firefox4.x";
		$result[2] = "Windows";
		$result[3] = "2000";
		$result[4] = "Firefox";
		$result[5] = "4.x";
	}
	elseif(stristr($this->ua,"Firefox")&&stristr($this->ua,"Windows NT 5.0")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsMe Firefox Unknown";
		$result[2] = "Windows";
		$result[3] = "2000";
		$result[4] = "Firefox";
		$result[5] = " Unknown";
	}
	elseif(!stristr($this->ua,"compatible;")&&stristr($this->ua,"Mozilla/4.")&&(stristr($this->ua,"WinNT;")||stristr($this->ua,"Windows NT 5.0"))&&!stristr($this->ua,"Opera")){
		$result[1] = "Windows2000 or XP NN4.x";
		$result[2] = "Windows";
		$result[3] = "2000 or XP";
		$result[4] = "Netscape";
		$result[5] = "4.x";
	}
	elseif(stristr($this->ua,"Netscape6")&&stristr($this->ua,"NT 5.0")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows2000 NN6.x";
		$result[2] = "Windows";
		$result[3] = "2000";
		$result[4] = "Netscape";
		$result[5] = "6.x";
	}
	elseif(stristr($this->ua,"Netscape/7")&&stristr($this->ua,"NT 5.0")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows2000 NN7.x";
		$result[2] = "Windows";
		$result[3] = "2000";
		$result[4] = "Netscape";
		$result[5] = "7.x";
	}
	elseif(stristr($this->ua,"Gecko/")&&stristr($this->ua,"NT 5.0")&&!stristr($this->ua,"Netscape/7")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows2000 Mozilla.org";
		$result[2] = "Windows";
		$result[3] = "2000";
		$result[4] = "Mozilla.org";
		$result[5] = "Unknown";
	}
	elseif(stristr($this->ua,"Opera 6")||stristr($this->ua,"Opera/6")&&stristr($this->ua,"Windows 2000")){
		$result[0] = true;
		$result[1] = "Windows2000 Opera6.x";
		$result[2] = "Windows";
		$result[3] = "2000";
		$result[4] = "Opera";
		$result[5] = "6.x";
	}
	elseif(stristr($this->ua,"Opera 7")||stristr($this->ua,"Opera/7")&&stristr($this->ua,"NT 5.0")){
		$result[0] = true;
		$result[1] = "Windows2000 Opera7.x";
		$result[2] = "Windows";
		$result[3] = "2000";
		$result[4] = "Opera";
		$result[5] = "7.x";
	}
	
	// Opera 対応外バージョン
	elseif(stristr($this->ua,"Opera")||stristr($this->ua,"Opera")&&stristr($this->ua,"NT 5.0")){
		$result[0] = true;
		$result[1] = "Windows2000 Opera";
		$result[2] = "Windows";
		$result[3] = "2000";
		$result[4] = "Opera";
		$result[5] = " Unknown";
	}

	///////////////////////////////////////////	
	// Windows XP
	elseif(stristr($this->ua,"MSIE 5.0")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows NT 5.1")&&stristr($this->ua,"Windows NT 5.1")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsXP IE5.0";
		$result[2] = "Windows";
		$result[3] = "XP";
		$result[4] = "Internet Explorer";
		$result[5] = "5.0";
	}
	elseif(stristr($this->ua,"MSIE 5.5;")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows NT 5.1")&& !stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsXP IE5.5";
		$result[2] = "Windows";
		$result[3] = "XP";
		$result[4] = "Internet Explorer";
		$result[5] = "5.5";
	}
	elseif(stristr($this->ua,"MSIE 6.0")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows NT 5.1")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsXP IE6.0";
		$result[2] = "Windows";
		$result[3] = "XP";
		$result[4] = "Internet Explorer";
		$result[5] = "6.0";
	}
	// WindowsXP : IE7
	elseif(stristr($this->ua,"MSIE 7.0")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows NT 5.1")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsXP IE7.0";
		$result[2] = "Windows";
		$result[3] = "XP";
		$result[4] = "Internet Explorer";
		$result[5] = "7.0";
	}
	// WindowsXP : IE8
	elseif(stristr($this->ua,"MSIE 8.0")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows NT 5.1")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsXP IE8.0";
		$result[2] = "Windows";
		$result[3] = "XP";
		$result[4] = "Internet Explorer";
		$result[5] = "8.0";
	}
	// WindowsXP : IE9
	elseif(stristr($this->ua,"MSIE 9.0")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows NT 5.1")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsXP IE9.0";
		$result[2] = "Windows";
		$result[3] = "XP";
		$result[4] = "Internet Explorer";
		$result[5] = "9.0";
	}
	// IE 対応外バージョン
	elseif(stristr($this->ua,"MSIE;")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows NT 5.1")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsXP IE";
		$result[2] = "Windows";
		$result[3] = "XP";
		$result[4] = "Internet Explorer";
		$result[5] = " Unknown";
	}

	// WindowsXP : Firefox
	elseif(stristr($this->ua,"Firefox/1")&&stristr($this->ua,"Windows NT 5.1")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsXP Firefox1.x";
		$result[2] = "Windows";
		$result[3] = "XP";
		$result[4] = "Firefox";
		$result[5] = "1.x";
	}
	elseif(stristr($this->ua,"Firefox/2")&&stristr($this->ua,"Windows NT 5.1")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsXP Firefox2.x";
		$result[2] = "Windows";
		$result[3] = "XP";
		$result[4] = "Firefox";
		$result[5] = "2.x";
	}
	elseif(stristr($this->ua,"Firefox/3")&&stristr($this->ua,"Windows NT 5.1")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsXP Firefox3.x";
		$result[2] = "Windows";
		$result[3] = "XP";
		$result[4] = "Firefox";
		$result[5] = "3.x";
	}
	elseif(stristr($this->ua,"Firefox/4")&&stristr($this->ua,"Windows NT 5.1")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsXP Firefox4.x";
		$result[2] = "Windows";
		$result[3] = "XP";
		$result[4] = "Firefox";
		$result[5] = "4.x";
	}
	
	
	// Firefox 対応外バージョン
	elseif(stristr($this->ua,"Firefox")&&stristr($this->ua,"Windows NT 5.1")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsXP Firefox";
		$result[2] = "Windows";
		$result[3] = "XP";
		$result[4] = "Firefox";
		$result[5] = " Unknown";
	}
	
	elseif(stristr($this->ua,"Netscape6")&&stristr($this->ua,"NT 5.1")&& !stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsXP NN6.x";
		$result[2] = "Windows";
		$result[3] = "XP";
		$result[4] = "Netscape";
		$result[5] = "6.x";
	}
	elseif(stristr($this->ua,"Netscape/7")&&stristr($this->ua,"NT 5.1")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsXP NN7.x";
		$result[2] = "Windows";
		$result[3] = "XP";
		$result[4] = "Netscape";
		$result[5] = "7.x";
	}
	elseif(stristr($this->ua,"Gecko/")&&stristr($this->ua,"NT 5.1")&&!stristr($this->ua,"Netscape/7")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsXP Mozilla.org";
		$result[2] = "Windows";
		$result[3] = "XP";
		$result[4] = "Mozilla.org";
		$result[5] = "Unknown";
	}
	elseif((stristr($this->ua,"Opera 6")||stristr($this->ua,"Opera/6"))&&stristr($this->ua,"Windows NT 5.1")){
		$result[0] = true;
		$result[1] = "WindowsXP Opera6.x";
		$result[2] = "Windows";
		$result[3] = "XP";
		$result[4] = "Opera";
		$result[5] = "6.x";
	}
	elseif((stristr($this->ua,"Opera 7")||stristr($this->ua,"Opera/7"))&&stristr($this->ua,"Windows NT 5.1")){
		$result[0] = true;
		$result[1] = "WindowsXP Opera7.x";
		$result[2] = "Windows";
		$result[3] = "XP";
		$result[4] = "Opera";
		$result[5] = "7.x";
	}
	elseif((stristr($this->ua,"Opera 8")||stristr($this->ua,"Opera/8"))&&stristr($this->ua,"Windows NT 5.1")){
		$result[0] = true;
		$result[1] = "WindowsXP Opera8.x";
		$result[2] = "Windows";
		$result[3] = "XP";
		$result[4] = "Opera";
		$result[5] = "8.x";
	}
	elseif((stristr($this->ua,"Opera 9")||stristr($this->ua,"Opera/9"))&&stristr($this->ua,"Windows NT 5.1")){
		$result[0] = true;
		$result[1] = "WindowsXP Opera9.x";
		$result[2] = "Windows";
		$result[3] = "XP";
		$result[4] = "Opera";
		$result[5] = "9.x";
	}
	elseif((stristr($this->ua,"Opera 10")||stristr($this->ua,"Opera/10"))&&stristr($this->ua,"Windows NT 5.1")){
		$result[0] = true;
		$result[1] = "WindowsXP Opera10.x";
		$result[2] = "Windows";
		$result[3] = "XP";
		$result[4] = "Opera";
		$result[5] = "10.x";
	}
	
	// Opera 対応外バージョン
	elseif((stristr($this->ua,"Opera")||stristr($this->ua,"Opera"))&&stristr($this->ua,"Windows NT 5.1")){
		$result[0] = true;
		$result[1] = "WindowsXP Opera";
		$result[2] = "Windows";
		$result[3] = "XP";
		$result[4] = "Opera";
		$result[5] = " Unknown";
	}
	
	elseif(stristr($this->ua,"Chrome/2")&&stristr($this->ua,"Windows NT 5.1")){
		$result[0] = true;
		$result[1] = "WindowsXP Chrome2.x";
		$result[2] = "Windows";
		$result[3] = "XP";
		$result[4] = "Chrome";
		$result[5] = "2.x";
	}
	elseif(stristr($this->ua,"Chrome/3")&&stristr($this->ua,"Windows NT 5.1")){
		$result[0] = true;
		$result[1] = "WindowsXP Chrome3.x";
		$result[2] = "Windows";
		$result[3] = "XP";
		$result[4] = "Chrome";
		$result[5] = "3.x";
	}
	elseif(stristr($this->ua,"Chrome/4")&&stristr($this->ua,"Windows NT 5.1")){
		$result[0] = true;
		$result[1] = "WindowsXP Chrome4.x";
		$result[2] = "Windows";
		$result[3] = "XP";
		$result[4] = "Chrome";
		$result[5] = "4.x";
	}
	elseif(stristr($this->ua,"Chrome/5")&&stristr($this->ua,"Windows NT 5.1")){
		$result[0] = true;
		$result[1] = "WindowsXP Chrome5.x";
		$result[2] = "Windows";
		$result[3] = "XP";
		$result[4] = "Chrome";
		$result[5] = "5.x";
	}
	elseif(stristr($this->ua,"Chrome/6")&&stristr($this->ua,"Windows NT 5.1")){
		$result[0] = true;
		$result[1] = "WindowsXP Chrome6.x";
		$result[2] = "Windows";
		$result[3] = "XP";
		$result[4] = "Chrome";
		$result[5] = "6.x";
	}
	
	elseif(stristr($this->ua,"Chrome/7")&&stristr($this->ua,"Windows NT 5.1")){
		$result[0] = true;
		$result[1] = "WindowsXP Chrome7.x";
		$result[2] = "Windows";
		$result[3] = "XP";
		$result[4] = "Chrome";
		$result[5] = "7.x";
	}
	
	elseif(stristr($this->ua,"Chrome/8")&&stristr($this->ua,"Windows NT 5.1")){
		$result[0] = true;
		$result[1] = "WindowsXP Chrome8.x";
		$result[2] = "Windows";
		$result[3] = "XP";
		$result[4] = "Chrome";
		$result[5] = "8.x";
	}
	elseif(stristr($this->ua,"Chrome/9")&&stristr($this->ua,"Windows NT 5.1")){
		$result[0] = true;
		$result[1] = "WindowsXP Chrome9.x";
		$result[2] = "Windows";
		$result[3] = "XP";
		$result[4] = "Chrome";
		$result[5] = "9.x";
	}
	elseif(stristr($this->ua,"Chrome/10")&&stristr($this->ua,"Windows NT 5.1")){
		$result[0] = true;
		$result[1] = "WindowsXP Chrome10.x";
		$result[2] = "Windows";
		$result[3] = "XP";
		$result[4] = "Chrome";
		$result[5] = "10.x";
	}
	elseif(stristr($this->ua,"Chrome/11")&&stristr($this->ua,"Windows NT 5.1")){
		$result[0] = true;
		$result[1] = "WindowsXP Chrome11.x";
		$result[2] = "Windows";
		$result[3] = "XP";
		$result[4] = "Chrome";
		$result[5] = "11.x";
	}
	elseif(stristr($this->ua,"Chrome/12")&&stristr($this->ua,"Windows NT 5.1")){
		$result[0] = true;
		$result[1] = "WindowsXP Chrome12.x";
		$result[2] = "Windows";
		$result[3] = "XP";
		$result[4] = "Chrome";
		$result[5] = "12.x";
	}
	
	elseif(stristr($this->ua,"Chrome")&&stristr($this->ua,"Windows NT 5.1")){
		$result[0] = true;
		$result[1] = "WindowsXP Chrome";
		$result[2] = "Windows";
		$result[3] = "XP";
		$result[4] = "Chrome";
		$result[5] = " Unknown";
	}
	
	elseif(stristr($this->ua,"Safari")&&stristr($this->ua,"Windows NT 5.1")&& !stristr($this->ua,"Chrome")){
		$safari_version = 'Unknown';
		$safari_version_array = explode('Version/',$this->ua);
		if(is_array($safari_version_array) && count($safari_version_array) == 2) {
			$safari_version = substr($safari_version_array[1], 0, strpos($safari_version_array[1], '.')).'.x';
		}
		$result[0] = true;
		$result[1] = "WindowsXP Safari".$safari_version;
		$result[2] = "Windows";
		$result[3] = "XP";
		$result[4] = "Safari";
		$result[5] = $safari_version;
	}

	///////////////////////////////////////////	
	// WindowsVista : IE7
	elseif(stristr($this->ua,"MSIE 7.0")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows NT 6.0")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsVista IE7.0";
		$result[2] = "Windows";
		$result[3] = "Vista";
		$result[4] = "Internet Explorer";
		$result[5] = "7.0";
	}
	// WindowsVista : IE8
	elseif(stristr($this->ua,"MSIE 8.0")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows NT 6.0")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsVista IE8.0";
		$result[2] = "Windows";
		$result[3] = "Vista";
		$result[4] = "Internet Explorer";
		$result[5] = "8.0";
	}
	// WindowsVista : IE9
	elseif(stristr($this->ua,"MSIE 9.0")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows NT 6.0")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsVista IE9.0";
		$result[2] = "Windows";
		$result[3] = "Vista";
		$result[4] = "Internet Explorer";
		$result[5] = "9.0";
	}
	// IE 対応外バージョン
	elseif(stristr($this->ua,"MSIE")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows NT 6.0")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsVista IE";
		$result[2] = "Windows";
		$result[3] = "Vista";
		$result[4] = "Internet Explorer";
		$result[5] = " Unknown";
	}
	
	// WindowsVista : Firefox
	elseif(stristr($this->ua,"Firefox/1")&&stristr($this->ua,"Windows NT 6.0")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsVista Firefox1.x";
		$result[2] = "Windows";
		$result[3] = "Vista";
		$result[4] = "Firefox";
		$result[5] = "1.x";
	}
	elseif(stristr($this->ua,"Firefox/2")&&stristr($this->ua,"Windows NT 6.0")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsVista Firefox2.x";
		$result[2] = "Windows";
		$result[3] = "Vista";
		$result[4] = "Firefox";
		$result[5] = "2.x";
	}
	elseif(stristr($this->ua,"Firefox/3")&&stristr($this->ua,"Windows NT 6.0")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsVista Firefox3.x";
		$result[2] = "Windows";
		$result[3] = "Vista";
		$result[4] = "Firefox";
		$result[5] = "3.x";
	}
	elseif(stristr($this->ua,"Firefox/4")&&stristr($this->ua,"Windows NT 6.0")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsVista Firefox4.x";
		$result[2] = "Windows";
		$result[3] = "Vista";
		$result[4] = "Firefox";
		$result[5] = "4.x";
	}
	
	// Firefox 対応外バージョン
	elseif(stristr($this->ua,"Firefox")&&stristr($this->ua,"Windows NT 6.0")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "WindowsVista Firefox";
		$result[2] = "Windows";
		$result[3] = "Vista";
		$result[4] = "Firefox";
		$result[5] = " Unknown";
	}
	elseif((stristr($this->ua,"Opera 8")||stristr($this->ua,"Opera/8"))&&stristr($this->ua,"Windows NT 6.0")){
		$result[0] = true;
		$result[1] = "WindowsVista Opera8.x";
		$result[2] = "Windows";
		$result[3] = "Vista";
		$result[4] = "Opera";
		$result[5] = "8.x";
	}
	elseif((stristr($this->ua,"Opera 9")||stristr($this->ua,"Opera/9"))&&stristr($this->ua,"Windows NT 6.0")){
		$result[0] = true;
		$result[1] = "WindowsVista Opera9.x";
		$result[2] = "Windows";
		$result[3] = "Vista";
		$result[4] = "Opera";
		$result[5] = "9.x";
	}
	elseif((stristr($this->ua,"Opera 10")||stristr($this->ua,"Opera/10"))&&stristr($this->ua,"Windows NT 6.0")){
		$result[0] = true;
		$result[1] = "WindowsVista Opera10.x";
		$result[2] = "Windows";
		$result[3] = "Vista";
		$result[4] = "Opera";
		$result[5] = "10.x";
	}
	elseif(stristr($this->ua,"Chrome/2")&&stristr($this->ua,"Windows NT 6.0")){
		$result[0] = true;
		$result[1] = "WindowsVista Chrome2.x";
		$result[2] = "Windows";
		$result[3] = "Vista";
		$result[4] = "Chrome";
		$result[5] = "2.x";
	}
	elseif(stristr($this->ua,"Chrome/3")&&stristr($this->ua,"Windows NT 6.0")){
		$result[0] = true;
		$result[1] = "WindowsVista Chrome3.x";
		$result[2] = "Windows";
		$result[3] = "Vista";
		$result[4] = "Chrome";
		$result[5] = "3.x";
	}
	elseif(stristr($this->ua,"Chrome/4")&&stristr($this->ua,"Windows NT 6.0")){
		$result[0] = true;
		$result[1] = "WindowsVista Chrome4.x";
		$result[2] = "Windows";
		$result[3] = "Vista";
		$result[4] = "Chrome";
		$result[5] = "4.x";
	}
	elseif(stristr($this->ua,"Chrome/5")&&stristr($this->ua,"Windows NT 6.0")){
		$result[0] = true;
		$result[1] = "WindowsVista Chrome5.x";
		$result[2] = "Windows";
		$result[3] = "Vista";
		$result[4] = "Chrome";
		$result[5] = "5.x";
	}
	elseif(stristr($this->ua,"Chrome/6")&&stristr($this->ua,"Windows NT 6.0")){
		$result[0] = true;
		$result[1] = "WindowsVista Chrome6.x";
		$result[2] = "Windows";
		$result[3] = "Vista";
		$result[4] = "Chrome";
		$result[5] = "6.x";
	}
	
	elseif(stristr($this->ua,"Chrome/7")&&stristr($this->ua,"Windows NT 6.0")){
		$result[0] = true;
		$result[1] = "WindowsVista Chrome7.x";
		$result[2] = "Windows";
		$result[3] = "Vista";
		$result[4] = "Chrome";
		$result[5] = "7.x";
	}
	
	elseif(stristr($this->ua,"Chrome/8")&&stristr($this->ua,"Windows NT 6.0")){
		$result[0] = true;
		$result[1] = "WindowsVista Chrome8.x";
		$result[2] = "Windows";
		$result[3] = "Vista";
		$result[4] = "Chrome";
		$result[5] = "8.x";
	}
	
	elseif(stristr($this->ua,"Chrome/9")&&stristr($this->ua,"Windows NT 6.0")){
		$result[0] = true;
		$result[1] = "WindowsVista Chrome9.x";
		$result[2] = "Windows";
		$result[3] = "Vista";
		$result[4] = "Chrome";
		$result[5] = "9.x";
	}
	
	elseif(stristr($this->ua,"Chrome/10")&&stristr($this->ua,"Windows NT 6.0")){
		$result[0] = true;
		$result[1] = "WindowsVista Chrome10.x";
		$result[2] = "Windows";
		$result[3] = "Vista";
		$result[4] = "Chrome";
		$result[5] = "10.x";
	}
	
	elseif(stristr($this->ua,"Chrome/11")&&stristr($this->ua,"Windows NT 6.0")){
		$result[0] = true;
		$result[1] = "WindowsVista Chrome11.x";
		$result[2] = "Windows";
		$result[3] = "Vista";
		$result[4] = "Chrome";
		$result[5] = "11.x";
	}
	elseif(stristr($this->ua,"Chrome/12")&&stristr($this->ua,"Windows NT 6.0")){
		$result[0] = true;
		$result[1] = "WindowsVista Chrome12.x";
		$result[2] = "Windows";
		$result[3] = "Vista";
		$result[4] = "Chrome";
		$result[5] = "12.x";
	}
	
	elseif(stristr($this->ua,"Chrome")&&stristr($this->ua,"Windows NT 6.0")){
		$result[0] = true;
		$result[1] = "WindowsVista Chrome.x";
		$result[2] = "Windows";
		$result[3] = "Vista";
		$result[4] = "Chrome";
		$result[5] = " Unknown";
	}
	
	elseif(stristr($this->ua,"Safari")&&stristr($this->ua,"Windows NT 6.0")&& !stristr($this->ua,"Chrome")){
		$safari_version = 'Unknown';
		$safari_version_array = explode('Version/',$this->ua);
		if(is_array($safari_version_array) && count($safari_version_array) == 2) {
			$safari_version = substr($safari_version_array[1], 0, strpos($safari_version_array[1], '.')).'.x';
		}
		$result[0] = true;
		$result[1] = "WindowsVista Safari".$safari_version;
		$result[2] = "Windows";
		$result[3] = "Vista";
		$result[4] = "Safari";
		$result[5] = $safari_version;
	}


	///////////////////////////////////////////	
	// Windows7 : IE7
	elseif(stristr($this->ua,"MSIE 7.0")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows NT 6.1")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows 7 IE7.0";
		$result[2] = "Windows";
		$result[3] = "7";
		$result[4] = "Internet Explorer";
		$result[5] = "7.0";
	}
	// Windows7 : IE8
	elseif(stristr($this->ua,"MSIE 8.0")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows NT 6.1")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows 7 IE8.0";
		$result[2] = "Windows";
		$result[3] = "7";
		$result[4] = "Internet Explorer";
		$result[5] = "8.0";
	}
	// Windows7 : IE9
	elseif(stristr($this->ua,"MSIE 9.0")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows NT 6.1")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows 7 IE9.0";
		$result[2] = "Windows";
		$result[3] = "7";
		$result[4] = "Internet Explorer";
		$result[5] = "9.0";
	}
	
	// Windows7 : IE10
	elseif(stristr($this->ua,"MSIE 10.0")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows NT 6.1")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows 7 IE10.0";
		$result[2] = "Windows";
		$result[3] = "7";
		$result[4] = "Internet Explorer";
		$result[5] = "10.0";
	}
	
	// Windows7 : IE11
	elseif(stristr($this->ua,"Trident")&&stristr($this->ua," rv:11")&&stristr($this->ua,"Windows NT 6.1")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows 7 IE11.0";
		$result[2] = "Windows";
		$result[3] = "7";
		$result[4] = "Internet Explorer";
		$result[5] = "11.0";
	}
	
	elseif(stristr($this->ua,"Trident")&&stristr($this->ua," rv:12")&&stristr($this->ua,"Windows NT 6.1")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows 7 IE12.0";
		$result[2] = "Windows";
		$result[3] = "7";
		$result[4] = "Internet Explorer";
		$result[5] = "12.0";
	}
	// IE 対応外バージョン
	elseif(stristr($this->ua,"MSIE")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows NT 6.1")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows 7 IE";
		$result[2] = "Windows";
		$result[3] = "7";
		$result[4] = "Internet Explorer";
		$result[5] = " Unknown";
	}
	/*
	// Windows7 : Firefox
	elseif(stristr($this->ua,"Firefox/1")&&stristr($this->ua,"Windows NT 6.1")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows 7 Firefox1.x";
		$result[2] = "Windows";
		$result[3] = "7";
		$result[4] = "Firefox";
		$result[5] = "1.x";
	}
	elseif(stristr($this->ua,"Firefox/2")&&stristr($this->ua,"Windows NT 6.1")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows 7 Firefox2.x";
		$result[2] = "Windows";
		$result[3] = "7";
		$result[4] = "Firefox";
		$result[5] = "2.x";
	}
	elseif(stristr($this->ua,"Firefox/3")&&stristr($this->ua,"Windows NT 6.1")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows 7 Firefox3.x";
		$result[2] = "Windows";
		$result[3] = "7";
		$result[4] = "Firefox";
		$result[5] = "3.x";
	}
	elseif(stristr($this->ua,"Firefox/4")&&stristr($this->ua,"Windows NT 6.1")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows 7 Firefox4.x";
		$result[2] = "Windows";
		$result[3] = "7";
		$result[4] = "Firefox";
		$result[5] = "4.x";
	}
	*/
	// Firefox 対応外バージョン
	elseif(stristr($this->ua,"Firefox")&&stristr($this->ua,"Windows NT 6.1")&&!stristr($this->ua,"Opera")){
		$ver = $this->ua;
		$ver = preg_replace("/.*Firefox\//","",$ver);
		$ver = preg_replace("/\..*/","",$ver);
		if(!is_numeric($ver)) $ver = " Unknown";
		else $ver .= ".x";

		$result[0] = true;
		$result[1] = "Windows 7 Firefox" . ( $ver);
		$result[2] = "Windows";
		$result[3] = "7";
		$result[4] = "Firefox";
		$result[5] = "{$ver}";
	}
	
	elseif((stristr($this->ua,"Opera 8")||stristr($this->ua,"Opera/8"))&&stristr($this->ua,"Windows NT 6.1")){
		$result[0] = true;
		$result[1] = "Windows 7 Opera8.x";
		$result[2] = "Windows";
		$result[3] = "7";
		$result[4] = "Opera";
		$result[5] = "8.x";
	}
	elseif((stristr($this->ua,"Opera 9")||stristr($this->ua,"Opera/9"))&&stristr($this->ua,"Windows NT 6.1")){
		$result[0] = true;
		$result[1] = "Windows 7 Opera9.x";
		$result[2] = "Windows";
		$result[3] = "7";
		$result[4] = "Opera";
		$result[5] = "9.x";
	}
	elseif((stristr($this->ua,"Opera 10")||stristr($this->ua,"Opera/10"))&&stristr($this->ua,"Windows NT 6.1")){
		$result[0] = true;
		$result[1] = "Windows 7 Opera10.x";
		$result[2] = "Windows";
		$result[3] = "7";
		$result[4] = "Opera";
		$result[5] = "10.x";
	}
	/*
	elseif(stristr($this->ua,"Chrome/2")&&stristr($this->ua,"Windows NT 6.1")){
		$result[0] = true;
		$result[1] = "Windows 7 Chrome2.x";
		$result[2] = "Windows";
		$result[3] = "7";
		$result[4] = "Chrome";
		$result[5] = "2.x";
	}
	elseif(stristr($this->ua,"Chrome/3")&&stristr($this->ua,"Windows NT 6.1")){
		$result[0] = true;
		$result[1] = "Windows 7 Chrome3.x";
		$result[2] = "Windows";
		$result[3] = "7";
		$result[4] = "Chrome";
		$result[5] = "3.x";
	}
	elseif(stristr($this->ua,"Chrome/4")&&stristr($this->ua,"Windows NT 6.1")){
		$result[0] = true;
		$result[1] = "Windows 7 Chrome4.x";
		$result[2] = "Windows";
		$result[3] = "7";
		$result[4] = "Chrome";
		$result[5] = "4.x";
	}
	elseif(stristr($this->ua,"Chrome/5")&&stristr($this->ua,"Windows NT 6.1")){
		$result[0] = true;
		$result[1] = "Windows 7 Chrome5.x";
		$result[2] = "Windows";
		$result[3] = "7";
		$result[4] = "Chrome";
		$result[5] = "5.x";
	}
	elseif(stristr($this->ua,"Chrome/6")&&stristr($this->ua,"Windows NT 6.1")){
		$result[0] = true;
		$result[1] = "Windows 7 Chrome6.x";
		$result[2] = "Windows";
		$result[3] = "7";
		$result[4] = "Chrome";
		$result[5] = "6.x";
	}
	elseif(stristr($this->ua,"Chrome/7")&&stristr($this->ua,"Windows NT 6.1")){
		$result[0] = true;
		$result[1] = "Windows 7 Chrome7.x";
		$result[2] = "Windows";
		$result[3] = "7";
		$result[4] = "Chrome";
		$result[5] = "7.x";
	}
	
	elseif(stristr($this->ua,"Chrome/8")&&stristr($this->ua,"Windows NT 6.1")){
		$result[0] = true;
		$result[1] = "Windows 7 Chrome8.x";
		$result[2] = "Windows";
		$result[3] = "7";
		$result[4] = "Chrome";
		$result[5] = "8.x";
	}
	
	elseif(stristr($this->ua,"Chrome/9")&&stristr($this->ua,"Windows NT 6.1")){
		$result[0] = true;
		$result[1] = "Windows 7 Chrome9.x";
		$result[2] = "Windows";
		$result[3] = "7";
		$result[4] = "Chrome";
		$result[5] = "9.x";
	}
	
	elseif(stristr($this->ua,"Chrome/10")&&stristr($this->ua,"Windows NT 6.1")){
		$result[0] = true;
		$result[1] = "Windows 7 Chrome10.x";
		$result[2] = "Windows";
		$result[3] = "7";
		$result[4] = "Chrome";
		$result[5] = "10.x";
	}
	
	elseif(stristr($this->ua,"Chrome/11")&&stristr($this->ua,"Windows NT 6.1")){
		$result[0] = true;
		$result[1] = "Windows 7 Chrome11.x";
		$result[2] = "Windows";
		$result[3] = "7";
		$result[4] = "Chrome";
		$result[5] = "11.x";
	}
	elseif(stristr($this->ua,"Chrome/12")&&stristr($this->ua,"Windows NT 6.1")){
		$result[0] = true;
		$result[1] = "Windows 7 Chrome12.x";
		$result[2] = "Windows";
		$result[3] = "7";
		$result[4] = "Chrome";
		$result[5] = "12.x";
	}
	*/
	elseif(stristr($this->ua,"Chrome")&&stristr($this->ua,"Windows NT 6.1")){
		$ver = $this->ua;
		$ver = preg_replace("/.*Chrome\//","",$ver);
		$ver = preg_replace("/\..*/","",$ver);
		if(!is_numeric($ver)) $ver = " Unknown";
		else $ver .= ".x";

		$result[0] = true;
		$result[1] = "Windows 7 Chrome".$ver;
		$result[2] = "Windows";
		$result[3] = "7";
		$result[4] = "Chrome";
		$result[5] = "$ver";
	}
	
	elseif(stristr($this->ua,"Safari")&&stristr($this->ua,"Windows NT 6.1")&& !stristr($this->ua,"Chrome")){
		$safari_version = 'Unknown';
		$safari_version_array = explode('Version/',$this->ua);
		if(is_array($safari_version_array) && count($safari_version_array) == 2) {
			$safari_version = substr($safari_version_array[1], 0, strpos($safari_version_array[1], '.')).'.x';
		}
		$result[0] = true;
		$result[1] = "Windows 7 Safari".$safari_version;
		$result[2] = "Windows";
		$result[3] = "7";
		$result[4] = "Safari";
		$result[5] = $safari_version;
	}



	///////////////////////////////////////////	
	// Windows8 : IE10
	elseif(stristr($this->ua,"MSIE 10.0")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows NT 6.2")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows 8 IE10.0";
		$result[2] = "Windows";
		$result[3] = "8";
		$result[4] = "Internet Explorer";
		$result[5] = "10.0";
	}
	
	// Windows8 : IE11
	elseif(stristr($this->ua,"Trident")&&stristr($this->ua," rv:11")&&stristr($this->ua,"Windows NT 6.2")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows 8 IE11.0";
		$result[2] = "Windows";
		$result[3] = "8";
		$result[4] = "Internet Explorer";
		$result[5] = "11.0";
	}
	
	// Windows8 : IE11
	elseif(stristr($this->ua,"Trident")&&stristr($this->ua," rv:12")&&stristr($this->ua,"Windows NT 6.2")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows 8 IE12.0";
		$result[2] = "Windows";
		$result[3] = "8";
		$result[4] = "Internet Explorer";
		$result[5] = "12.0";
	}
	
	// IE 対応外バージョン
	elseif(stristr($this->ua,"MSIE")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows NT 6.2")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows 8 IE";
		$result[2] = "Windows";
		$result[3] = "8";
		$result[4] = "Internet Explorer";
		$result[5] = " Unknown";
	}
	/*
	// Windows8 : Firefox
	elseif(stristr($this->ua,"Firefox/1")&&stristr($this->ua,"Windows NT 6.2")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows 8 Firefox1.x";
		$result[2] = "Windows";
		$result[3] = "8";
		$result[4] = "Firefox";
		$result[5] = "1.x";
	}
	elseif(stristr($this->ua,"Firefox/2")&&stristr($this->ua,"Windows NT 6.2")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows 8 Firefox2.x";
		$result[2] = "Windows";
		$result[3] = "8";
		$result[4] = "Firefox";
		$result[5] = "2.x";
	}
	elseif(stristr($this->ua,"Firefox/3")&&stristr($this->ua,"Windows NT 6.2")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows 8 Firefox3.x";
		$result[2] = "Windows";
		$result[3] = "8";
		$result[4] = "Firefox";
		$result[5] = "3.x";
	}
	elseif(stristr($this->ua,"Firefox/4")&&stristr($this->ua,"Windows NT 6.2")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows 8 Firefox4.x";
		$result[2] = "Windows";
		$result[3] = "8";
		$result[4] = "Firefox";
		$result[5] = "4.x";
	}
	*/
	// Firefox 対応外バージョン
	elseif(stristr($this->ua,"Firefox")&&stristr($this->ua,"Windows NT 6.2")&&!stristr($this->ua,"Opera")){
		$ver = $this->ua;
		$ver = preg_replace("/.*Firefox\//","",$ver);
		$ver = preg_replace("/\..*/","",$ver);
		if(!is_numeric($ver)) $ver = " Unknown";
		else $ver .= ".x";
		$result[0] = true;
		$result[1] = "Windows 8 Firefox" . ($ver);
		$result[2] = "Windows";
		$result[3] = "8";
		$result[4] = "Firefox";
		$result[5] = " {$ver}";
	}
	
	elseif((stristr($this->ua,"Opera 8")||stristr($this->ua,"Opera/8"))&&stristr($this->ua,"Windows NT 6.2")){
		$result[0] = true;
		$result[1] = "Windows 8 Opera8.x";
		$result[2] = "Windows";
		$result[3] = "8";
		$result[4] = "Opera";
		$result[5] = "8.x";
	}
	elseif((stristr($this->ua,"Opera 9")||stristr($this->ua,"Opera/9"))&&stristr($this->ua,"Windows NT 6.2")){
		$result[0] = true;
		$result[1] = "Windows 8 Opera9.x";
		$result[2] = "Windows";
		$result[3] = "8";
		$result[4] = "Opera";
		$result[5] = "9.x";
	}
	elseif((stristr($this->ua,"Opera 10")||stristr($this->ua,"Opera/10"))&&stristr($this->ua,"Windows NT 6.2")){
		$result[0] = true;
		$result[1] = "Windows 8 Opera10.x";
		$result[2] = "Windows";
		$result[3] = "8";
		$result[4] = "Opera";
		$result[5] = "10.x";
	}
	/*
	elseif(stristr($this->ua,"Chrome/2")&&stristr($this->ua,"Windows NT 6.2")){
		$result[0] = true;
		$result[1] = "Windows 8 Chrome2.x";
		$result[2] = "Windows";
		$result[3] = "8";
		$result[4] = "Chrome";
		$result[5] = "2.x";
	}
	elseif(stristr($this->ua,"Chrome/3")&&stristr($this->ua,"Windows NT 6.2")){
		$result[0] = true;
		$result[1] = "Windows 8 Chrome3.x";
		$result[2] = "Windows";
		$result[3] = "8";
		$result[4] = "Chrome";
		$result[5] = "3.x";
	}
	elseif(stristr($this->ua,"Chrome/4")&&stristr($this->ua,"Windows NT 6.2")){
		$result[0] = true;
		$result[1] = "Windows 8 Chrome4.x";
		$result[2] = "Windows";
		$result[3] = "8";
		$result[4] = "Chrome";
		$result[5] = "4.x";
	}
	elseif(stristr($this->ua,"Chrome/5")&&stristr($this->ua,"Windows NT 6.2")){
		$result[0] = true;
		$result[1] = "Windows 8 Chrome5.x";
		$result[2] = "Windows";
		$result[3] = "8";
		$result[4] = "Chrome";
		$result[5] = "5.x";
	}
	elseif(stristr($this->ua,"Chrome/6")&&stristr($this->ua,"Windows NT 6.2")){
		$result[0] = true;
		$result[1] = "Windows 8 Chrome6.x";
		$result[2] = "Windows";
		$result[3] = "8";
		$result[4] = "Chrome";
		$result[5] = "6.x";
	}
	elseif(stristr($this->ua,"Chrome/7")&&stristr($this->ua,"Windows NT 6.2")){
		$result[0] = true;
		$result[1] = "Windows 8 Chrome7.x";
		$result[2] = "Windows";
		$result[3] = "8";
		$result[4] = "Chrome";
		$result[5] = "7.x";
	}
	
	elseif(stristr($this->ua,"Chrome/8")&&stristr($this->ua,"Windows NT 6.2")){
		$result[0] = true;
		$result[1] = "Windows 8 Chrome8.x";
		$result[2] = "Windows";
		$result[3] = "8";
		$result[4] = "Chrome";
		$result[5] = "8.x";
	}
	
	elseif(stristr($this->ua,"Chrome/9")&&stristr($this->ua,"Windows NT 6.2")){
		$result[0] = true;
		$result[1] = "Windows 8 Chrome9.x";
		$result[2] = "Windows";
		$result[3] = "8";
		$result[4] = "Chrome";
		$result[5] = "9.x";
	}
	
	elseif(stristr($this->ua,"Chrome/10")&&stristr($this->ua,"Windows NT 6.2")){
		$result[0] = true;
		$result[1] = "Windows 8 Chrome10.x";
		$result[2] = "Windows";
		$result[3] = "8";
		$result[4] = "Chrome";
		$result[5] = "10.x";
	}
	
	elseif(stristr($this->ua,"Chrome/11")&&stristr($this->ua,"Windows NT 6.2")){
		$result[0] = true;
		$result[1] = "Windows 8 Chrome11.x";
		$result[2] = "Windows";
		$result[3] = "8";
		$result[4] = "Chrome";
		$result[5] = "11.x";
	}
	elseif(stristr($this->ua,"Chrome/12")&&stristr($this->ua,"Windows NT 6.2")){
		$result[0] = true;
		$result[1] = "Windows 8 Chrome12.x";
		$result[2] = "Windows";
		$result[3] = "8";
		$result[4] = "Chrome";
		$result[5] = "12.x";
	}
	*/
	elseif(stristr($this->ua,"Chrome")&&stristr($this->ua,"Windows NT 6.2")){
		$ver = $this->ua;
		$ver = preg_replace("/.*Chrome\//","",$ver);
		$ver = preg_replace("/\..*/","",$ver);
		if(!is_numeric($ver)) $ver = " Unknown";
		else $ver .= ".x";

		$result[0] = true;
		$result[1] = "Windows 8 Chrome".$ver;
		$result[2] = "Windows";
		$result[3] = "8";
		$result[4] = "Chrome";
		$result[5] = "$ver";
	}
	
	elseif(stristr($this->ua,"Safari")&&stristr($this->ua,"Windows NT 6.2")&& !stristr($this->ua,"Chrome")){
		$safari_version = 'Unknown';
		$safari_version_array = explode('Version/',$this->ua);
		if(is_array($safari_version_array) && count($safari_version_array) == 2) {
			$safari_version = substr($safari_version_array[1], 0, strpos($safari_version_array[1], '.')).'.x';
		}
		$result[0] = true;
		$result[1] = "Windows 8 Safari".$safari_version;
		$result[2] = "Windows";
		$result[3] = "8";
		$result[4] = "Safari";
		$result[5] = $safari_version;
	}



	///////////////////////////////////////////	
	// Windows8.1 : IE10
	elseif(stristr($this->ua,"MSIE 10.0")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows NT 6.3")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows 8.1 IE10.0";
		$result[2] = "Windows";
		$result[3] = "8.1";
		$result[4] = "Internet Explorer";
		$result[5] = "10.0";
	}
	
	// Windows8.1 : IE11
	elseif(stristr($this->ua,"Trident")&&stristr($this->ua," rv:11")&&stristr($this->ua,"Windows NT 6.3")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows 8.1 IE11.0";
		$result[2] = "Windows";
		$result[3] = "8.1";
		$result[4] = "Internet Explorer";
		$result[5] = "11.0";
	}
	
	// Windows8.1 : IE11
	elseif(stristr($this->ua,"Trident")&&stristr($this->ua," rv:12")&&stristr($this->ua,"Windows NT 6.3")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows 8.1 IE12.0";
		$result[2] = "Windows";
		$result[3] = "8.1";
		$result[4] = "Internet Explorer";
		$result[5] = "12.0";
	}
	
	// IE 対応外バージョン
	elseif(stristr($this->ua,"MSIE")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Windows NT 6.3")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows 8.1 IE";
		$result[2] = "Windows";
		$result[3] = "8.1";
		$result[4] = "Internet Explorer";
		$result[5] = " Unknown";
	}
	
	// Windows8.1 : Firefox
	/*
	elseif(stristr($this->ua,"Firefox/1")&&stristr($this->ua,"Windows NT 6.3")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows 8.1 Firefox1.x";
		$result[2] = "Windows";
		$result[3] = "8.1";
		$result[4] = "Firefox";
		$result[5] = "1.x";
	}
	elseif(stristr($this->ua,"Firefox/2")&&stristr($this->ua,"Windows NT 6.3")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows 8.1 Firefox2.x";
		$result[2] = "Windows";
		$result[3] = "8.1";
		$result[4] = "Firefox";
		$result[5] = "2.x";
	}
	elseif(stristr($this->ua,"Firefox/3")&&stristr($this->ua,"Windows NT 6.3")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows 8.1 Firefox3.x";
		$result[2] = "Windows";
		$result[3] = "8.1";
		$result[4] = "Firefox";
		$result[5] = "3.x";
	}
	elseif(stristr($this->ua,"Firefox/4")&&stristr($this->ua,"Windows NT 6.3")&&!stristr($this->ua,"Opera")){
		$result[0] = true;
		$result[1] = "Windows 8.1 Firefox4.x";
		$result[2] = "Windows";
		$result[3] = "8.1";
		$result[4] = "Firefox";
		$result[5] = "4.x";
	}
	*/
	// Firefox 対応外バージョン
	elseif(stristr($this->ua,"Firefox")&&stristr($this->ua,"Windows NT 6.3")&&!stristr($this->ua,"Opera")){
		$ver = $this->ua;
		$ver = preg_replace("/.*Firefox\//","",$ver);
		$ver = preg_replace("/\..*/","",$ver);
		if(!is_numeric($ver)) $ver = " Unknown";
		else $ver .= ".x";
		$result[0] = true;
		$result[1] = "Windows 8.1 Firefox".$ver;
		$result[2] = "Windows";
		$result[3] = "8.1";
		$result[4] = "Firefox";
		$result[5] = "$ver";
	}
	
	elseif((stristr($this->ua,"Opera 8")||stristr($this->ua,"Opera/8"))&&stristr($this->ua,"Windows NT 6.3")){
		$result[0] = true;
		$result[1] = "Windows 8.1 Opera8.x";
		$result[2] = "Windows";
		$result[3] = "8.1";
		$result[4] = "Opera";
		$result[5] = "8.x";
	}
	elseif((stristr($this->ua,"Opera 9")||stristr($this->ua,"Opera/9"))&&stristr($this->ua,"Windows NT 6.3")){
		$result[0] = true;
		$result[1] = "Windows 8.1 Opera9.x";
		$result[2] = "Windows";
		$result[3] = "8.1";
		$result[4] = "Opera";
		$result[5] = "9.x";
	}
	elseif((stristr($this->ua,"Opera 10")||stristr($this->ua,"Opera/10"))&&stristr($this->ua,"Windows NT 6.3")){
		$result[0] = true;
		$result[1] = "Windows 8.1 Opera10.x";
		$result[2] = "Windows";
		$result[3] = "8.1";
		$result[4] = "Opera";
		$result[5] = "10.x";
	}
	/*
	elseif(stristr($this->ua,"Chrome/2")&&stristr($this->ua,"Windows NT 6.3")){
		$result[0] = true;
		$result[1] = "Windows 8.1 Chrome2.x";
		$result[2] = "Windows";
		$result[3] = "8.1";
		$result[4] = "Chrome";
		$result[5] = "2.x";
	}
	elseif(stristr($this->ua,"Chrome/3")&&stristr($this->ua,"Windows NT 6.3")){
		$result[0] = true;
		$result[1] = "Windows 8.1 Chrome3.x";
		$result[2] = "Windows";
		$result[3] = "8.1";
		$result[4] = "Chrome";
		$result[5] = "3.x";
	}
	elseif(stristr($this->ua,"Chrome/4")&&stristr($this->ua,"Windows NT 6.3")){
		$result[0] = true;
		$result[1] = "Windows 8.1 Chrome4.x";
		$result[2] = "Windows";
		$result[3] = "8.1";
		$result[4] = "Chrome";
		$result[5] = "4.x";
	}
	elseif(stristr($this->ua,"Chrome/5")&&stristr($this->ua,"Windows NT 6.3")){
		$result[0] = true;
		$result[1] = "Windows 8.1 Chrome5.x";
		$result[2] = "Windows";
		$result[3] = "8.1";
		$result[4] = "Chrome";
		$result[5] = "5.x";
	}
	elseif(stristr($this->ua,"Chrome/6")&&stristr($this->ua,"Windows NT 6.3")){
		$result[0] = true;
		$result[1] = "Windows 8.1 Chrome6.x";
		$result[2] = "Windows";
		$result[3] = "8.1";
		$result[4] = "Chrome";
		$result[5] = "6.x";
	}
	elseif(stristr($this->ua,"Chrome/7")&&stristr($this->ua,"Windows NT 6.3")){
		$result[0] = true;
		$result[1] = "Windows 8.1 Chrome7.x";
		$result[2] = "Windows";
		$result[3] = "8.1";
		$result[4] = "Chrome";
		$result[5] = "7.x";
	}
	
	elseif(stristr($this->ua,"Chrome/8")&&stristr($this->ua,"Windows NT 6.3")){
		$result[0] = true;
		$result[1] = "Windows 8.1 Chrome8.x";
		$result[2] = "Windows";
		$result[3] = "8.1";
		$result[4] = "Chrome";
		$result[5] = "8.x";
	}
	
	elseif(stristr($this->ua,"Chrome/9")&&stristr($this->ua,"Windows NT 6.3")){
		$result[0] = true;
		$result[1] = "Windows 8.1 Chrome9.x";
		$result[2] = "Windows";
		$result[3] = "8.1";
		$result[4] = "Chrome";
		$result[5] = "9.x";
	}
	
	elseif(stristr($this->ua,"Chrome/10")&&stristr($this->ua,"Windows NT 6.3")){
		$result[0] = true;
		$result[1] = "Windows 8.1 Chrome10.x";
		$result[2] = "Windows";
		$result[3] = "8.1";
		$result[4] = "Chrome";
		$result[5] = "10.x";
	}
	
	elseif(stristr($this->ua,"Chrome/11")&&stristr($this->ua,"Windows NT 6.3")){
		$result[0] = true;
		$result[1] = "Windows 8.1 Chrome11.x";
		$result[2] = "Windows";
		$result[3] = "8.1";
		$result[4] = "Chrome";
		$result[5] = "11.x";
	}
	elseif(stristr($this->ua,"Chrome/12")&&stristr($this->ua,"Windows NT 6.3")){
		$result[0] = true;
		$result[1] = "Windows 8.1 Chrome12.x";
		$result[2] = "Windows";
		$result[3] = "8.1";
		$result[4] = "Chrome";
		$result[5] = "12.x";
	}
	*/
	elseif(stristr($this->ua,"Chrome")&&stristr($this->ua,"Windows NT 6.3")){
		$ver = $this->ua;
		$ver = preg_replace("/.*Chrome\//","",$ver);
		$ver = preg_replace("/\..*/","",$ver);
		if(!is_numeric($ver)) $ver = " Unknown";
		else $ver .= ".x";

		$result[0] = true;
		$result[1] = "Windows 8.1 Chrome".$ver;
		$result[2] = "Windows";
		$result[3] = "8.1";
		$result[4] = "Chrome";
		$result[5] = "$ver";
	}
	
	elseif(stristr($this->ua,"Safari")&&stristr($this->ua,"Windows NT 6.3")&& !stristr($this->ua,"Chrome")){
		$safari_version = 'Unknown';
		$safari_version_array = explode('Version/',$this->ua);
		if(is_array($safari_version_array) && count($safari_version_array) == 2) {
			$safari_version = substr($safari_version_array[1], 0, strpos($safari_version_array[1], '.')).'.x';
		}
		$result[0] = true;
		$result[1] = "Windows 8.1 Safari".$safari_version;
		$result[2] = "Windows";
		$result[3] = "8.1";
		$result[4] = "Safari";
		$result[5] = $safari_version;
	}



	///////////////////////////////////////////	
	// MacClassic
	elseif(stristr($this->ua,"MSIE 4.5")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Mac_PowerPC")){
		$result[1] = "Macintosh PPC MSIE 4.5";
		$result[2] = "Macintosh";
		$result[3] = "PPC";
		$result[4] = "Internet Explorer";
		$result[5] = "4.5";
	}
	elseif(stristr($this->ua,"MSIE 5.")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Mac_PowerPC")){
		$result[0] = true;
		$result[1] = "Macintosh PPC MSIE 5.x";
		$result[2] = "Macintosh";
		$result[3] = "PPC";
		$result[4] = "Internet Explorer";
		$result[5] = "5.x";
	}
	// MaxClassic : Firefox
	/*
	elseif(stristr($this->ua,"Firefox/1")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Mac_PowerPC")){
		$result[0] = true;
		$result[1] = "Macintosh Firefox1.x";
		$result[2] = "Macintosh";
		$result[3] = "PPC";
		$result[4] = "Firefox";
		$result[5] = "1.x";
	}
	elseif(stristr($this->ua,"Firefox/2")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Mac_PowerPC")){
		$result[0] = true;
		$result[1] = "Macintosh Firefox2.x";
		$result[2] = "Macintosh";
		$result[3] = "PPC";
		$result[4] = "Firefox";
		$result[5] = "2.x";
	}
	elseif(stristr($this->ua,"Firefox3")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Mac_PowerPC")){
		$result[0] = true;
		$result[1] = "Macintosh Firefox3.x";
		$result[2] = "Macintosh";
		$result[3] = "PPC";
		$result[4] = "Firefox";
		$result[5] = "3.x";
	}
	*/
	elseif(stristr($this->ua,"Firefox")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Mac_PowerPC")){
		$ver = $this->ua;
		$ver = preg_replace("/.*Firefox\//","",$ver);
		$ver = preg_replace("/\..*/","",$ver);
		if(!is_numeric($ver)) $ver = " Unknown";
		else $ver .= ".x";
		$result[0] = true;
		$result[1] = "Macintosh Firefox".$ver;
		$result[2] = "Macintosh";
		$result[3] = "PPC";
		$result[4] = "Firefox";
		$result[5] = "$ver";
	}
	elseif(stristr($this->ua,"Mozilla/4.")&&stristr($this->ua,"PPC")&&stristr($this->ua,"Macintosh")){
		$result[1] = "Macintosh PPC NN4.x";
		$result[2] = "Macintosh";
		$result[3] = "PPC";
		$result[4] = "Netscape";
		$result[5] = "4.x";
	}
	elseif(stristr($this->ua,"Netscape6/")&&stristr($this->ua,"Macintosh")&&!stristr($this->ua,"Mac OS X")){
		$result[0] = true;
		$result[1] = "Macintosh PPC NN6.x";
		$result[2] = "Macintosh";
		$result[3] = "PPC";
		$result[4] = "Netscape";
		$result[5] = "6.x";
	}
	elseif(stristr($this->ua,"Netscape/7")&&stristr($this->ua,"Macintosh")&&!stristr($this->ua,"Mac OS X")){
		$result[0] = true;
		$result[1] = "Macintosh PPC NN7.x";
		$result[2] = "Macintosh";
		$result[3] = "PPC";
		$result[4] = "Netscape";
		$result[5] = "7.x";
	}
	elseif(stristr($this->ua,"Gecko/")&&stristr($this->ua,"Macintosh")&&!stristr($this->ua,"Netscape")&&!stristr($this->ua,"Mac OS X")){
		$result[0] = true;
		$result[1] = "Macintosh PPC Mozilla.org";
		$result[2] = "Macintosh";
		$result[3] = "PPC";
		$result[4] = "Mozilla.org";
		$result[5] = "Unknown";
	}
	elseif(stristr($this->ua,"Opera")&&stristr($this->ua,"Mac_PowerPC")){
		$result[0] = true;
		$result[1] = "Macintosh PPC Opera";
		$result[2] = "Macintosh";
		$result[3] = "PPC";
		$result[4] = "Opera";
		$result[5] = "Unknown";
	}
	elseif(stristr($this->ua,"iCab")&&stristr($this->ua,"Macintosh")&&stristr($this->ua,"PPC")&&!stristr($this->ua,"Mac OS X")){
		$result[1] = "Macintosh PPC iCab";
		$result[2] = "Macintosh";
		$result[3] = "PPC";
		$result[4] = "iCab";
		$result[5] = "Unknown";
	}


	///////////////////////////////////////////	
	// MacOS X
	elseif(stristr($this->ua,"MSIE 5.2")&&stristr($this->ua,"compatible;")&&stristr($this->ua,"Mac_PowerPC")){
		$result[0] = true;
		$result[1] = "Macintosh OS X MSIE 5.2x";
		$result[2] = "Macintosh";
		$result[3] = "OS X";
		$result[4] = "Internet Explorer";
		$result[5] = "5.2x";
	}
	elseif(stristr($this->ua,"Netscape6/")&& stristr($this->ua,"Mac OS X")){
		$result[0] = true;
		$result[1] = "Macintosh OS X NN6.x";
		$result[2] = "Macintosh";
		$result[3] = "OS X";
		$result[4] = "Netscape";
		$result[5] = "6.x";
	}
	elseif(stristr($this->ua,"Netscape/7")&& stristr($this->ua,"Mac OS X")){
		$result[0] = true;
		$result[1] = "Macintosh OS X NN7.x";
		$result[2] = "Macintosh";
		$result[3] = "OS X";
		$result[4] = "Netscape";
		$result[5] = "7.x";
	}
	/*
	elseif(stristr($this->ua,"Firefox/2")&& stristr($this->ua,"Mac OS X")){
		$result[0] = true;
		$result[1] = "Macintosh OS X Firefox2.x";
		$result[2] = "Macintosh";
		$result[3] = "OS X";
		$result[4] = "Firefox";
		$result[5] = "2.x";
	}
	elseif(stristr($this->ua,"Firefox/3")&& stristr($this->ua,"Mac OS X")){
		$result[0] = true;
		$result[1] = "Macintosh OS X Firefox3.x";
		$result[2] = "Macintosh";
		$result[3] = "OS X";
		$result[4] = "Firefox";
		$result[5] = "3.x";
	}
	elseif(stristr($this->ua,"Firefox/4")&& stristr($this->ua,"Mac OS X")){
		$result[0] = true;
		$result[1] = "Macintosh OS X Firefox4.x";
		$result[2] = "Macintosh";
		$result[3] = "OS X";
		$result[4] = "Firefox";
		$result[5] = "4.x";
	}
	*/
	// FireFox 対応外バージョン
	elseif(stristr($this->ua,"Firefox")&& stristr($this->ua,"Mac OS X")){
		$ver = $this->ua;
		$ver = preg_replace("/.*Firefox\//","",$ver);
		$ver = preg_replace("/\..*/","",$ver);
		if(!is_numeric($ver)) $ver = " Unknown";
		else $ver .= ".x";

		$result[0] = true;
		$result[1] = "Macintosh OS X Firefox".$ver;
		$result[2] = "Macintosh";
		$result[3] = "OS X";
		$result[4] = "Firefox";
		$result[5] = "$ver";
	}
	
	
	elseif(stristr($this->ua,"Gecko/") && stristr($this->ua,"Mac OS X") && !stristr($this->ua,"Netscape")){
		$result[0] = true;
		$result[1] = "Macintosh OS X Mozilla.org";
		$result[2] = "Macintosh";
		$result[3] = "OS X";
		$result[4] = "Mozilla.org";
		$result[5] = "Unknown";
	}
	elseif(stristr($this->ua,"Opera")&&stristr($this->ua,"Mac OS X")){
		$result[0] = true;
		$result[1] = "Macintosh OS X Opera";
		$result[2] = "Macintosh";
		$result[3] = "OS X";
		$result[4] = "Opera";
		$result[5] = "Unknown";
	}
	elseif(stristr($this->ua,"Mac OS X")&&stristr($this->ua,"Safari")&& !stristr($this->ua,"Chrome")&& !stristr($this->ua,"iPad")&& !stristr($this->ua,"iPhone")){
		$safari_version = 'Unknown';
		$safari_version_array = explode('Version/',$this->ua);
		if(is_array($safari_version_array) && count($safari_version_array) == 2) {
			$safari_version = substr($safari_version_array[1], 0, strpos($safari_version_array[1], '.')).'.x';
		}
		$result[0] = true;
		$result[1] = "Macintosh OS X Safari".$safari_version;
		$result[2] = "Macintosh";
		$result[3] = "OS X";
		$result[4] = "Safari";
		$result[5] = $safari_version;
	}
	elseif(stristr($this->ua,"iCab")&&stristr($this->ua,"OS X")&&!stristr($this->ua,"68")){
		$result[1] = "Macintosh OS X iCab";
		$result[2] = "Macintosh";
		$result[3] = "OS X";
		$result[4] = "iCab";
		$result[5] = "Unknown";
	}
	/*
	elseif(stristr($this->ua,"Firefox/1")&&stristr($this->ua,"OS X")){
		$result[0] = true;
		$result[1] = "Macintosh OS X Firefox1.x";
		$result[2] = "Macintosh";
		$result[3] = "OS X";
		$result[4] = "Firefox";
		$result[5] = "1.x";
	}
	elseif(stristr($this->ua,"Firefox/2")&&stristr($this->ua,"OS X")){
		$result[0] = true;
		$result[1] = "Macintosh OS X Firefox2.x";
		$result[2] = "Macintosh";
		$result[3] = "OS X";
		$result[4] = "Firefox";
		$result[5] = "2.x";
	}
	elseif(stristr($this->ua,"Firefox/3")&&stristr($this->ua,"OS X")){
		$result[0] = true;
		$result[1] = "Macintosh OS X Firefox3.x";
		$result[2] = "Macintosh";
		$result[3] = "OS X";
		$result[4] = "Firefox";
		$result[5] = "3.x";
	}
	elseif(stristr($this->ua,"Firefox/4")&&stristr($this->ua,"OS X")){
		$result[0] = true;
		$result[1] = "Macintosh OS X Firefox4.x";
		$result[2] = "Macintosh";
		$result[3] = "OS X";
		$result[4] = "Firefox";
		$result[5] = "4.x";
	}
	*/
	// FireFox 対応外バージョン
	elseif(stristr($this->ua,"Firefox")&&stristr($this->ua,"OS X")){
		$ver = $this->ua;
		$ver = preg_replace("/.*Firefox\//","",$ver);
		$ver = preg_replace("/\..*/","",$ver);
		if(!is_numeric($ver)) $ver = " Unknown";
		else $ver .= ".x";

		$result[0] = true;
		$result[1] = "Macintosh OS X Firefox".$ver;
		$result[2] = "Macintosh";
		$result[3] = "OS X";
		$result[4] = "Firefox";
		$result[5] = "$ver";

	}
	/*
	elseif(stristr($this->ua,"Chrome/4")&&stristr($this->ua,"OS X")){
		$result[0] = true;
		$result[1] = "Macintosh OS X Chrome4.x";
		$result[2] = "Macintosh";
		$result[3] = "OS X";
		$result[4] = "Chrome";
		$result[5] = "4.x";
	}
	elseif(stristr($this->ua,"Chrome/5")&&stristr($this->ua,"OS X")){
		$result[0] = true;
		$result[1] = "Macintosh OS X Chrome5.x";
		$result[2] = "Macintosh";
		$result[3] = "OS X";
		$result[4] = "Chrome";
		$result[5] = "5.x";
	}
	elseif(stristr($this->ua,"Chrome/6")&&stristr($this->ua,"OS X")){
		$result[0] = true;
		$result[1] = "Macintosh OS X Chrome6.x";
		$result[2] = "Macintosh";
		$result[3] = "OS X";
		$result[4] = "Chrome";
		$result[5] = "6.x";
	}
	
	
	elseif(stristr($this->ua,"Chrome/7")&&stristr($this->ua,"OS X")){
		$result[0] = true;
		$result[1] = "Macintosh OS X Chrome7.x";
		$result[2] = "Macintosh";
		$result[3] = "OS X";
		$result[4] = "Chrome";
		$result[5] = "7.x";
	}
	elseif(stristr($this->ua,"Chrome/8")&&stristr($this->ua,"OS X")){
		$result[0] = true;
		$result[1] = "Macintosh OS X Chrome8.x";
		$result[2] = "Macintosh";
		$result[3] = "OS X";
		$result[4] = "Chrome";
		$result[5] = "8.x";
	}
	elseif(stristr($this->ua,"Chrome/9")&&stristr($this->ua,"OS X")){
		$result[0] = true;
		$result[1] = "Macintosh OS X Chrome9.x";
		$result[2] = "Macintosh";
		$result[3] = "OS X";
		$result[4] = "Chrome";
		$result[5] = "9.x";
	}
	elseif(stristr($this->ua,"Chrome/10")&&stristr($this->ua,"OS X")){
		$result[0] = true;
		$result[1] = "Macintosh OS X Chrome10.x";
		$result[2] = "Macintosh";
		$result[3] = "OS X";
		$result[4] = "Chrome";
		$result[5] = "10.x";
	}
	elseif(stristr($this->ua,"Chrome/11")&&stristr($this->ua,"OS X")){
		$result[0] = true;
		$result[1] = "Macintosh OS X Chrome11.x";
		$result[2] = "Macintosh";
		$result[3] = "OS X";
		$result[4] = "Chrome";
		$result[5] = "11.x";
	}
	elseif(stristr($this->ua,"Chrome/12")&&stristr($this->ua,"OS X")){
		$result[0] = true;
		$result[1] = "Macintosh OS X Chrome12.x";
		$result[2] = "Macintosh";
		$result[3] = "OS X";
		$result[4] = "Chrome";
		$result[5] = "12.x";
	}
	*/
	elseif(stristr($this->ua,"Chrome")&&stristr($this->ua,"OS X")){
		$ver = $this->ua;
		$ver = preg_replace("/.*Chrome\//","",$ver);
		$ver = preg_replace("/\..*/","",$ver);
		if(!is_numeric($ver)) $ver = " Unknown";
		else $ver .= ".x";
		$result[0] = true;
		$result[1] = "Macintosh OS X Chrome".$ver;
		$result[2] = "Macintosh";
		$result[3] = "OS X";
		$result[4] = "Chrome";
		$result[5] = "$ver";
	}
	
	
	///////////////////////////////////////////////////////	
	// 携帯ブラウザ
	// docomo
	elseif(preg_match("|^DoCoMo/1\.0/(\w+)/?|", $this->ua, $matches)){
		$result[0] = true;
		$result[1] = "DoCoMo 1.0 ".$matches[1];
		$result[2] = "DoCoMo";
		$result[3] = $matches[1];
		$result[4] = "DoCoMo";
		$result[5] = "1.0";
	}
	elseif(preg_match("|^DoCoMo/2\.0 (\w+)\(|", $this->ua, $matches)){
		$result[0] = true;
		$result[1] = "DoCoMo 2.0 ".$matches[1];
		$result[2] = "DoCoMo";
		$result[3] = $matches[1];
		$result[4] = "DoCoMo";
		$result[5] = "2.0";
	}
	// au
	elseif(preg_match("|^KDDI-|", $this->ua) || preg_match("|^UP.Browser|", $this->ua)){
		preg_match("|.+-(\w+) .+|", $this->ua, $matches);
		$result[0] = true;
		$result[1] = "au UP.Browser ".$matches[1];
		$result[2] = "au";
		$result[3] = $matches[1];
		$result[4] = "UP.Browser";
		$result[5] = "";
	}
	// Softbank
	elseif(preg_match("|^J-PHONE|", $this->ua) || preg_match("|^Vodafone|", $this->ua) || preg_match("|^SoftBank|", $this->ua) || preg_match("|^MOT-|", $this->ua)){
		$all_headers = getallheaders();
		$result[0] = true;
		$result[1] = "SoftBank ".$all_headers["x-jphone-msname"];
		$result[2] = "SoftBank";
		$result[3] = $all_headers["x-jphone-msname"];
		$result[4] = "SoftBank";
		$result[5] = "";
	}
	
	///////////////////////////////////////////////////////	
	// スマホ系
	
	elseif (strpos($this->ua, 'iPhone') !== false) {
		$result[0] = true;
		$result[1] = "SoftBank iPhone";
		$result[2] = "SoftBank ";
		$result[3] = "iPhone";
		$result[4] = "iPhone";
		$result[5] = "";
	}

	elseif (strpos($this->ua, 'iPad') !== false) {
		$result[0] = true;
		$result[1] = "Mac OS iPad";
		$result[2] = "Mac OS ";
		$result[3] = "iPad";
		$result[4] = "iPad";
		$result[5] = "";
	}

	elseif(stristr($this->ua,"Linux") || stristr($this->ua,"Android") || stristr($this->ua,"Mobile Safari")){
		$result[1] = true;
		$result[2] = "Linux OS ";
		$result[3] = "Android";
		$result[4] = "Linux OS Android Mobile Safari";
		$result[5] = "";
	}
	
	elseif(stristr($this->ua,"Linux") || stristr($this->ua,"Android") || !stristr($this->ua,"Mobile Safari")){
		$result[1] = true;
		$result[2] = "Linux OS ";
		$result[3] = "Android";
		$result[4] = "Linux OS Android";
		$result[5] = "";
	}



	///////////////////////////////////////////////////////	
	// Unix系OS（Linux含む）	※ＵＡを$result[6]に格納
	elseif(stristr($this->ua,"Linux") || stristr($this->ua,"SunOS") || stristr($this->ua,"BSD") || stristr($this->ua,"Fedora")){
		$result[1] = "UNIX OS";
		$result[2] = "UNIX OS";
		$result[3] = "Unknown";
		$result[4] = "Unknown";
		$result[5] = "Unknown";
	}


	//////////////////////////////////////////////////////////	
	// インターネット対応ゲーム機等	※ＵＡを$result[6]に格納
	elseif(stristr($this->ua,"Mozilla/3.0 (DreamPassport/3.")){
		$result[1] = "Multimedia Apparatus(DreamPassport)";
		$result[2] = "Multimedia Apparatus(DreamPassport)";
		$result[3] = "Multimedia Apparatus(DreamPassport)";
		$result[4] = "Multimedia Apparatus(DreamPassport)";
		$result[5] = "Unknown";		
	}
	elseif(stristr($this->ua,"Mozilla/3.0")&&(stristr($this->ua,"BrowserInfo")||stristr($this->ua,"Screen=")||stristr($this->ua,"InputMethod=")||stristr($this->ua,"Product="))){
		$result[1] = "Other Multimedia Apparatus";
		$result[2] = "Other Multimedia Apparatus";
		$result[3] = "Other Multimedia Apparatus";
		$result[4] = "Other Multimedia Apparatus";
		$result[5] = "Unknown";			
	}
	
	
	///////////////////////////////////////////////////////////////////////////////
	// その他不明	※ＵＡを$result[6]に格納
	else{

		// Windows系その他（タブブラウザ系もしくは環境変数偽造野郎の疑い）
		if(stristr($this->ua,"Sleipnir")||stristr($this->ua,"MSIE")||stristr($this->ua,"Win")||stristr($this->ua,"Cuam")||stristr($this->ua,"compatible;")){	
			$result[1] = "Windows IE";
			$result[2] = "Windows";
			$result[3] = "Unknown";
			$result[4] = "Internet Explorer";
			$result[5] = "Unknown";
		}
		else{
			// 超極少数または環境変数偽造野郎の疑い。
			$result[1] = "Unknown";
			$result[2] = "Unknown";
			$result[3] = "Unknown";
			$result[4] = "Unknown";
			$result[5] = "Unknown";
		}
	
	}


	///////////////////////////////////////////////////////////////////////////
	// 最後に文字列処理をした元データを、$result[6]に格納して判定結果を返す
	$result[6] = strSyori($this->ua);
	return $result;


/* メソッド“getNavInfo”の終了 */	
}






} /* クラス“UA_Info”の終了 */

############################################################################################################
#
# 環境変数のファイル出力／調査クラス 	ENV_Info("配列で格納された環境変数");
#	※１部パラメーター省略可。
#
############################################################################################################

class ENV_Info{


var $env;	# ENV

// コンストラクタ
function ENV_Info($ev = array()){

	if(empty($ev)):
		$this->env[0] = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
		$this->env[1] = $_SERVER["HTTP_USER_AGENT"];
		$this->env[2] = $_SERVER["HTTP_REFERER"];
	else:
		foreach($ev as $e)$this->env[] = $e; 
	endif;
	
}

#--------------------------------------------------------------------------------------------------------
# 環境変数を抜き取りファイルに格納するメソッド
#
#	メソッド：fileOutput("ファイルパス",["ファイル名"],["串情報取得の設定"]);
#	引	数：１：必須。２：省略したら年月日がファイル名。３：true = 取得する falseまたは省略 = 取得しない
#	戻り値：なし（環境変数を指定したファイルに出力して終了）
#
#	注1：ファイルパスを設定しないと強制終了。書き込みエラーは、エラー非表示で実行されないので注意する事
#	注2：不正な環境変数対策にRep()を使用しているので、必ず“PDC_function.php”を読み込んでおく事！
#---------------------------------------------------------------------------------------------------------
function fileOutput($path,$file_name = "",$getProxy = false){

	if(!$path)die("ファイルパスが未指定です！ ※fileOutput実行時");

	/// 取得するファイル名（引数２）の処理	※デフォルトはファイル名が年月日
	$getFileName = (empty($file_name))?date("Ymd").".dat":$file_name;

	// ファイルネームを含むパスが完成
	$ualogfile = $path;
	if(!ereg($path,"./$"))$ualogfile .= "/";
	$ualogfile .= $getFileName;

	// 串情報を取得するフラグ（引数３）があったら、串関連の環境変数を全部抜いておく
	$pxy = "";
	if($getProxy):

		if($_ENV['HTTP_VIA'])$pxy .= "HTTP_VIA:".$_ENV['HTTP_VIA']."\t";
		if($_ENV['HTTP_FORWARDED'])$pxy .= "HTTP_FORWARDED:".$_ENV['HTTP_FORWARDED']."\t";
		if($_ENV['HTTP_X_FORWARDED_FOR'])$pxy .= "HTTP_X_FORWARDED_FOR:".$_ENV['HTTP_X_FORWARDED_FOR']."\t";
		if($_ENV['HTTP_CACHE_INFO'])$pxy .= "HTTP_CACHE_INFO:".$_ENV['HTTP_CACHE_INFO']."\t";
		if($_ENV['HTTP_XONNECTION'])$pxy .= "HTTP_XONNECTION:".$_ENV['HTTP_XONNECTION']."\t";
		if($_ENV['HTTP_SP_HOST'])$pxy .= "HTTP_SP_HOST:".$_ENV['HTTP_SP_HOST']."\t";
		if($_ENV['HTTP_FROM'])$pxy .= "HTTP_FROM:".$_ENV['HTTP_FROM']."\t";
		if($_ENV['HTTP_X_LOCKING'])$pxy .= "HTTP_X_LOCKING:".$_ENV['HTTP_X_LOCKING']."\t";
		if($_ENV['HTTP_PROXY_CONNECTION'])$pxy .= "HTTP_PROXY_CONNECTION:".$_ENV['HTTP_PROXY_CONNECTION']."\t";

	endif;

	
	/////////////////////////////////////////////////////////////////////////////
	// 文字列置換とNGワードをチェックし、データを一つにまとめてファイル書き込み

	// NGワード
	$ng_word = array("\t","/etc/passwd","sendmail","\\","|");

	// 文字列置換とNGワードチェック（あったら削除）
	foreach($this->env as $e){
		if(!empty($e)):
			$e = strip_tags($e);
			$e = preg_replace("/^[[:space:]]{0,}/","",$e);
			$e = preg_replace("/[[:space:]]{0,}$/","",$e);
			$e = mb_ereg_replace("^(　){0,}","",$e);
			$e = mb_ereg_replace("(　){0,}$","",$e);
			$e = trim($e);
			$e = htmlspecialchars($e);
			if(get_magic_quotes_gpc()){
				$e = stripslashes($e);
			}
			$e = str_replace($ng_word,"",$e);
		endif;
	}
	
	// ひとつにまとめる（先頭に現在の日付）
	$env_data = date("Y.m.d H:i:s")."\t";
	for($i=0;$i<count($this->env);$i++){
		$env_data .= $this->env[$i];
		if($i != (count($this->env)-1))$env_data .= "\t";
	}
	if(!empty($pxy))$env_data .= "\t".$pxy;

	// ファイル書き込み（追記型）
	$FP = @fopen($ualogfile,"a");
	@flock($FP,LOCK_EX);
	@fwrite($FP,$env_data);
	@fwrite($FP,"\n");
	@flock($FP,LOCK_UN);
	@fclose($FP);

}  /* メソッド“fileOutput”の終了 */



} /* クラス“ENV_Info”の終了 */

?>