// JavaScript Document

/*********************************************************
 入力チェック
*********************************************************/
function inputChk(f){

	// フラグの初期化
	var flg = false;
	var error_mes = "Error Message\n恐れ入りますが、下記の内容をご確認ください\n\n";

	if(!f.start_y.value && (f.start_d.value || f.start_m.value)){
		error_mes += "・データ抽出開始年を指定して下さい。\n";flg = true;
	}

	if(!f.start_m.value && f.start_d.value){
			error_mes += "・データ抽出開始月を指定して下さい。\n";flg = true;
	}

	if(!f.end_y.value && (f.end_m.value || f.end_d.value)){
		error_mes += "・データ抽出終了年を指定して下さい。\n";flg = true;
	}

	if(!f.end_m.value && f.end_d.value){
			error_mes += "・データ抽出終了月を指定して下さい。\n";flg = true;
	}

	// 判定（未入力と不正入力があればアラート表示して再入力を促し、次ページへ進めない）
	if(flg){
		window.alert(error_mes);return false;
	}

}
