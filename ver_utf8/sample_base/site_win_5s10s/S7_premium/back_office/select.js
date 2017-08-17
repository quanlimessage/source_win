// JavaScript Document

//----------------------------------
// 一括でチェック処理
//----------------------------------
function select_on_data(){

		//formタグの入れ子は出来ない為、全inputタグでの全体で調べる
		for(var i = 0; i < document.getElementsByTagName("input").length; i ++){//全項目数を数える

			if((document.getElementsByTagName("input")[i].name == 'check_id[]')){//条件に一致するnameを探す
				document.getElementsByTagName("input")[i].checked = true;
			}
		}

}

//----------------------------------
// 一括でチェックをはずす処理
//----------------------------------
function select_off_data(){

		//formタグの入れ子は出来ない為、全inputタグでの全体で調べる
		for(var i = 0; i < document.getElementsByTagName("input").length; i ++){//全項目数を数える

			if((document.getElementsByTagName("input")[i].name == 'check_id[]')){//条件に一致するnameを探す
				document.getElementsByTagName("input")[i].checked = false;
			}
		}

}

//----------------------------------
// 一括表示用のデータ処理
//----------------------------------
function disp_on_data(){

	if(confirm('選択されたデータを表示に変更します。\nよろしいですか？')){
		//チェックされた箇所を調べる
			//formタグの入れ子は出来ない為、全inputタグでの全体で調べる
			for(var i = 0; i < document.getElementsByTagName("input").length; i ++){//全項目数を数える

				if((document.getElementsByTagName("input")[i].name == 'check_id[]') && (document.getElementsByTagName("input")[i].checked)){//条件に一致するnameを探す、そしてチェックされているかも確かめる
					document.getElementById("disp_on_id_stock").value += document.getElementsByTagName("input")[i].value + ",";//チェックされたIDを入れる
				}
			}

		//データを入れ終わったらsubmit処理を実行
		document.disp_on.submit();

	}

}

//----------------------------------
// 一括非表示用のデータ処理
//----------------------------------
function disp_off_data(){

	if(confirm('選択されたデータを非表示に変更します。\nよろしいですか？')){
		//チェックされた箇所を調べる
			//formタグの入れ子は出来ない為、全inputタグでの全体で調べる
			for(var i = 0; i < document.getElementsByTagName("input").length; i ++){//全項目数を数える

				if((document.getElementsByTagName("input")[i].name == 'check_id[]') && (document.getElementsByTagName("input")[i].checked)){//条件に一致するnameを探す、そしてチェックされているかも確かめる
					document.getElementById("disp_off_id_stock").value += document.getElementsByTagName("input")[i].value + ",";//チェックされたIDを入れる
				}
			}

		//データを入れ終わったらsubmit処理を実行
		document.disp_off.submit();

	}

}

//----------------------------------
// 一括削除用のデータ処理
//----------------------------------
function select_del_data(){

	if(confirm('選択されたデータを完全に削除します。\nデータの復帰は出来ません。\nよろしいですか？')){
		//チェックされた箇所を調べる
			//formタグの入れ子は出来ない為、全inputタグでの全体で調べる
			for(var i = 0; i < document.getElementsByTagName("input").length; i ++){//全項目数を数える

				if((document.getElementsByTagName("input")[i].name == 'check_id[]') && (document.getElementsByTagName("input")[i].checked)){//条件に一致するnameを探す、そしてチェックされているかも確かめる
					document.getElementById("del_id_stock").value += document.getElementsByTagName("input")[i].value + ",";//チェックされたIDを入れる
				}
			}

		//データを入れ終わったらsubmit処理を実行
		document.select_del.submit();

	}

}
