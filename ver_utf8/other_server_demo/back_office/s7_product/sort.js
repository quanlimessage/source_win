// JavaScript Document
/****************************************************************************
 並び順の変更
****************************************************************************/

//----------------------------------
// 最初に上げる
//----------------------------------
function f_moveUp(){

	// 上に上げるデータを取得（セレクトされたもの）
		var i = document.change_sort.nvo.selectedIndex;

	if(i == -1){
		window.alert('変更したいデータを選択してください。');
	}else{
		//選択したデータを取得
		var val = document.change_sort.nvo.options[i].value;
		var txt = document.change_sort.nvo.options[i].text;

		//一番最初以外は一段上に
		for(l=i;l > 0;l--){//選択した番号から上に上げていく
			//一つしたの番号を取得
				var d = eval(l-1);

			//データを受け取る
				var d_val = document.change_sort.nvo.options[d].value;
				var d_txt = document.change_sort.nvo.options[d].text;

			//データを上に移動させる。
				document.change_sort.nvo.options[l].value = d_val;
				document.change_sort.nvo.options[l].text = d_txt;

		}

			//最後に選択したデータを移動させる。
				document.change_sort.nvo.options[0].value = val;
				document.change_sort.nvo.options[0].text = txt;
	}
}

//----------------------------------
// 最後に下げる
//----------------------------------
function l_moveDn(){

	// 下に下げるデータを取得（セレクトされたもの）
	var i = document.change_sort.nvo.selectedIndex;
	if(i == -1){
		window.alert('変更したいデータを選択してください。');
	}else{
		//選択したデータを取得
		var val = document.change_sort.nvo.options[i].value;
		var txt = document.change_sort.nvo.options[i].text;

			var mes="";
			mes = mes + "i=" + i + "\n";

		//一番最初以外は一段下に
		for(l=i;l < (document.change_sort.nvo.length - 1);l++){//選択した番号から下に下げていく

			//一つ下の番号を取得
				var d = eval(l+1);

			//データを受け取る
				var d_val = document.change_sort.nvo.options[d].value;
				var d_txt = document.change_sort.nvo.options[d].text;

			//データを下に移動させる。
				document.change_sort.nvo.options[l].value = d_val;
				document.change_sort.nvo.options[l].text = d_txt;
		}
			//最後に選択したデータを移動させる。
				document.change_sort.nvo.options[d].value = val;
				document.change_sort.nvo.options[d].text = txt;
	}
}

//----------------------------------
// 上に上げる
//----------------------------------
function moveUp(){

	// 上に上げるデータを取得（セレクトされたもの）
	var i = document.change_sort.nvo.selectedIndex;
	if(i == -1){ window.alert('変更したいデータを選択してください。');
	}
	else{
	var val = document.change_sort.nvo.options[i].value;
	var txt = document.change_sort.nvo.options[i].text;

	// selectedIndexが0（先頭）でない場合に下記の処理
	if(i>0){

		// 下に下がるデータを取得
		var d = eval(i-1);
		var d_val = document.change_sort.nvo.options[d].value;
		var d_txt = document.change_sort.nvo.options[d].text;

		// データを入れ替える
		document.change_sort.nvo.options[d].value = val;
		document.change_sort.nvo.options[d].text = txt;
		document.change_sort.nvo.options[d].selected = true;
		document.change_sort.nvo.options[i].value = d_val;
		document.change_sort.nvo.options[i].text = d_txt;
	}
	}

}

//-----------------------------
// 下に下げる
//-----------------------------
function moveDn(){

	// 下にに下げるデータを取得（セレクトされたもの）
	var i = (document.change_sort.nvo.selectedIndex);
	if(i == -1){ window.alert('変更したいデータを選択してください。');
	}
	else{
	var val = document.change_sort.nvo.options[i].value;
	var txt = document.change_sort.nvo.options[i].text;

	// セレクトタグの最大件数分の範囲で下記の処理
	if(i<eval(document.change_sort.nvo.length -1)){

		// 上に上がるもの
		var u = eval(i+1);
		var u_val = document.change_sort.nvo.options[u].value;
		var u_txt = document.change_sort.nvo.options[u].text;

		// データを入れ替える
		document.change_sort.nvo.options[i].value = u_val;
		document.change_sort.nvo.options[i].text = u_txt;
		document.change_sort.nvo.options[u].value = val;
		document.change_sort.nvo.options[u].text = txt;
		document.change_sort.nvo.options[u].selected = true;
	}
	}

}

//-----------------------------
// 右のストック一覧に移動
//-----------------------------
function stock_move(){

	// 下にに下げるデータを取得（セレクトされたもの）
	var i = (document.change_sort.nvo.selectedIndex);

	if(i == -1){
		window.alert('ストックするデータを選択してください。');
	}else if(document.change_sort.nvo.length <= 1){
		window.alert('並ばせるデータを1つ以上残してください。');
	}else{
	var val = document.change_sort.nvo.options[i].value;
	var txt = document.change_sort.nvo.options[i].text;

	//ストック一覧の一番最後に入れる
		var u = eval(document.change_sort.stock_nvo.length);//一番最後の次の番号を取得する
		document.change_sort.stock_nvo.options[u] = new Option(txt, val);

	//移動させたデータを左のリストから削除する
		// セレクトタグの最大件数分の範囲で下記の処理
		if(i<eval(document.change_sort.nvo.length -1)){//選択されたのが一番最後出ない場合は一段上にずらして最後を削除

			// 上に上がるもの
				var u = eval(i+1);
				var u_val = document.change_sort.nvo.options[u].value;
				var u_txt = document.change_sort.nvo.options[u].text;

			//一番最初以外は一段下に
				for(l=i;l < (document.change_sort.nvo.length - 1);l++){//選択した番号から下に下げていく

					//一つ下の番号を取得
						var d = eval(l+1);

					//データを受け取る
						var d_val = document.change_sort.nvo.options[d].value;
						var d_txt = document.change_sort.nvo.options[d].text;

					//データを下に移動させる。
						document.change_sort.nvo.options[l].value = d_val;
						document.change_sort.nvo.options[l].text = d_txt;
				}
		//最後のデータを削除。
			document.change_sort.nvo.options[d] = null;

		}else{//選択されたのが一番最後の場合は選択されたところを削除
			document.change_sort.nvo.options[i] = null;
		}

	}

}

//-----------------------------
// 右のストックから並び順のリストに挿入
//-----------------------------
function on_move(){

	//並び順のリストで挿入する位置を選択されているかチェック
		if(document.change_sort.nvo.selectedIndex == -1){
			window.alert('挿入する位置を選択してください。');
		}else{
			var t = document.change_sort.nvo.selectedIndex;

			//選択しているデータを左の並び順リストに移動させる（削除処理は後にする、削除処理を一緒にするとlengthの数値が変わってしまうため）
				for(i = document.change_sort.stock_nvo.options.length -1; i >= 0; i--){

					//選択されているかチェックする
						if(document.change_sort.stock_nvo.options[i].selected){//選択されている場合
							//並び順のリストに挿入
								//入れる内容を設定
									var opt = document.createElement("option");
									opt.value = document.change_sort.stock_nvo.options[i].value;
									var str = document.createTextNode(document.change_sort.stock_nvo.options[i].text);
									opt.appendChild(str);

								document.change_sort.nvo.insertBefore(opt, document.change_sort.nvo.options[t]);
						}
				}

			//選択しているストックリストを削除
				for(i = document.change_sort.stock_nvo.options.length-1; i >= 0; i--){
					//選択されているかチェックする
						if(document.change_sort.stock_nvo.options[i].selected){//選択されている場合
							//ストックから削除する
								document.change_sort.stock_nvo.options[i] = null;
						}
				}
		}
}

// 並び替えを送信する
function change_sortSubmit(){

	//送信する前に右側のストックに挿入していないデータがあるかチェック
		if(document.change_sort.stock_nvo.options.length){
			window.alert('ストックリストに挿入されていないデータがございます。');
			return false;
		}else{

			with(document.change_sort){

				// セレクトタグの件数分、hiddenデータ（new_view_order）にタブ区切りにして格納
				for(i=0;i<nvo.length;i++){
					new_view_order.value += nvo.options[i].value;
					if(i < eval(nvo.length))new_view_order.value += "\t";
				}

				// 格納が終わったら送信する
				submit();

			}
		}
}