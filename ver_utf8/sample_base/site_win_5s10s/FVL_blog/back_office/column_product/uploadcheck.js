// JavaScript Document

/*********************************************************
 アップロードチェック
*********************************************************/
//------------------------------------------------------------
// メッセージダイアログ表示
//------------------------------------------------------------

$(function() {
  $('.chkimg').change(function() {
    $(this).upload('AJAX_uploadChk.php', function(res) {
      if (res != 'success') {
        alert(res);
      }
    }, 'html');
  });
});