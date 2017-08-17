// JavaScript Document
/****************************************************************************
 並び順の変更
****************************************************************************/

//----------------------------------
// 上に上げる
//----------------------------------
function moveUp() {

  // 上に上げるデータを取得（セレクトされたもの）
  var i = document.change_sort.nvo.selectedIndex;
  if (i == -1) {
    window.alert('変更したいデータを選択してください。');
  } else {
    var val = document.change_sort.nvo.options[i].value;
    var txt = document.change_sort.nvo.options[i].text;

    // selectedIndexが0（先頭）でない場合に下記の処理
    if (i > 0) {

      // 下に下がるデータを取得
      var d = eval(i - 1);
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
function moveDn() {

  // 下にに下げるデータを取得（セレクトされたもの）
  var i = (document.change_sort.nvo.selectedIndex);
  if (i == -1) {
    window.alert('変更したいデータを選択してください。');
  } else {
    var val = document.change_sort.nvo.options[i].value;
    var txt = document.change_sort.nvo.options[i].text;

    // セレクトタグの最大件数分の範囲で下記の処理
    if (i < eval(document.change_sort.nvo.length - 1)) {

      // 上に上がるもの
      var u = eval(i + 1);
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

// 右のやり方で並び替えた方を送信する
function change_sortSubmit() {

  with(document.change_sort) {

    // セレクトタグの件数分、hiddenデータ（new_view_order）にタブ区切りにして格納
    for (i = 0; i < nvo.length; i++) {
      new_view_order.value += nvo.options[i].value;
      if (i < eval(nvo.length)) new_view_order.value += "\t";
    }

    // 格納が終わったら送信する
    submit();

  }
}
