// JavaScript Document

/*********************************************************
 ���åץ����ɥ����å�
*********************************************************/
//------------------------------------------------------------
// ��å���������������ɽ��
//------------------------------------------------------------

$(function() {
	//

	//chkbind();
    $('.chkimg').change(function() {

        //$('#regist_form').unbind();
        $(this).upload('AJAX_uploadChk.php', function(res) {

			//chkbind();

			if(res != 'success'){
				alert(res);
			}
        }, 'html');

    });
});
/*
function chkbind(){
	$('#regist_form').bind('submit',function (){return confirm_message('regist_form'); });
}
*/
