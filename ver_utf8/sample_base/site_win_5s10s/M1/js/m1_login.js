
$('.m1-loginOpen').on('click',function(){
   $('#m1-login').addClass('is-show');
});
$('.m1-loginClose').on('click',function(){
   $('#m1-login').removeClass('is-show');
});


$(".mod__submitBtn").click(function(e){
    login_authentication();
});

$( '#id' ).keypress( function ( e ) {
  if ( e.which == 13 ) {
    login_authentication();
  }

} );

$( '#pass' ).keypress( function ( e ) {
  if ( e.which == 13 ) {
    login_authentication();
  }

} );

    //ログイン処理
    function login_authentication()
    {
      var DisplayObj=$('#error_mes');
      $.ajax({
          type: "POST",
          url: "login.php",
          data: $('.ajax_form').serialize(),
          success: function(msg){
              if(msg){
                  DisplayObj.html(msg);
              }else{
                  //ログイン成功したら、一旦トップページへ
                  var redirect_url = "./index.php" + location.search;
                  if (document.referrer) {
                    var referrer = "referrer=" + encodeURIComponent(document.referrer);
                    redirect_url = redirect_url + (location.search ? '&' : '?') + referrer;
                  }
                  location.href = redirect_url;
              }

          }
      });
    }
