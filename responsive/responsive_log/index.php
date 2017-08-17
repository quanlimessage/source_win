<?php
include("news.php");
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="keywords" content="ホームページ,作成,制作,女性向け,aiwave">
	<meta name="description" content="aiwave（アイウェイヴ）│女性向けのホームページ作成・制作なら、実績1,000サイト以上のアイウェイヴで。初期費用0円でホームページ作成・制作を承ります。">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
	<meta name="format-detection" content="telephone=no">
	<title>aiwave（アイウェイヴ）│女性向けのホームページ作成に専門特化した制作会社 ai WAVE（アイウェイヴ）</title>
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link href="css/base.css" rel="stylesheet">
    <link href="css/top.css" rel="stylesheet">
    <link href="css/content.css" rel="stylesheet">
    <link href="css/form.css" rel="stylesheet">
    <script src="js/smartRollover.js" type="text/javascript"></script>
    <script src="js/jquery-1.9.1.min.js" type="text/javascript"></script>
    <script src="js/respond.min.js" type="text/javascript"></script>
    <script>
	$(function(){
	var nav = $('#gnavi'),
		navAfter = nav.next(),
				navHeight = nav.height();
	var navY = nav.offset().top;
	$(window).scroll(function(){
				var windowTop = $(window).scrollTop();
				if(navY <= windowTop) {
							nav.css({
					'position':'fixed',
							'top':0});
							navAfter.css('margin-top',navHeight);

				} else if(navY > windowTop){
							nav.css({
										'position':'static',
										'top': '',
										'left': ''
							});
							navAfter.css('margin-top','');

				}
	});
	});
    </script>

    <!--<script type='text/javascript'>//<![CDATA[
    $(window).load(function(){
    $('a[href*="#"]').click(function(event){
            event.preventDefault();
            var f = this.href;
            var p = f.split("#");
            var t = p[1];
            var to = $("#"+t).offset();
            var tt = to.top;
            $('html, body').animate({scrollTop:tt},500);
        });
    });//]]>
    </script>-->
</head>

<body>
<!-- start facebook js -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/ja_JP/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<!-- end facebook js -->

        <section id="top" class="wrapper "><!-- start #wrapper -->

            <header id="head"><!-- start #head -->

            	<section id="head_inner_01">
            		<section id="head_inner_02">

            		    <h1 id="h1_seo">aiwave（アイウェイヴ）│女性向けホームページ作成実績1,000サイト以上！アイウェイヴは女性をターゲットとする企業のためのホームページ制作会社です。</h1>

            		    <section id="logo_area" class="clearfix">
            		    	<h1 id="h1_logo"><a href="#"><img src="common_img/h1_logo.png" alt="アイウェイヴ aiWAVE"></a></h1>
            		    	<h2 id="h2_logo">～ホームページでひろがる、つながる、人・モノ・ココロ～</h2>
            		    </section>

            		</section>
            	</section>

                <?php include("menuhtml/header_top.html"); ?>

            </header>
            <!-- end #head -->

            <section id="head_sp">
            	<section id="head_sp_inner" class="clearfix">
                    <h1 id="h1_logo_sp"><a href="#"><img src="common_img/h1_logo.png" alt="アイウェイヴ aiWAVE"></a></h1>
                    <h2 id="h2_logo_sp" class="mt10"><img src="common_img/h2_logo_sp.png" alt="女性をターゲットとする企業のための、女性スタッフによるホームページ制作"></h2>

                    <section id="mainimg_01_sp">
                        <img class="bg_mainimg_sp" src="images/bg_top_sp.jpg" alt="">
                        <a href="service/"><img class="img_mainimg_sp_01" src="images/btn_mainimg_sp_01.png" alt="ホームページ制作サービス・料金について"></a>
                        <a href="voice"><img class="img_mainimg_sp_02" src="images/btn_mainimg_sp_02.png" alt="成功事例"></a>
                        <a href="company/"><img class="img_mainimg_sp_03" src="images/btn_mainimg_sp_03.png" alt="運営会社"></a>
                        <a href="products/"><img class="img_mainimg_sp_04" src="images/btn_mainimg_sp_04.png" alt="制作実績"></a>
                        <a href="staff"><img class="img_mainimg_sp_05" src="images/btn_mainimg_sp_05.png" alt="スタッフ紹介"></a>
                        <img class="img_mainimg_sp_00" src="images/mainimg_sp_01.png" alt="">
                    </section>

                    <a class="btn_01" href="about/"><img src="images/icon_top_sp_01.png" alt="">アイウェイヴについて</a>
                    <a class="btn_02" href="https://mx18.all-internet.jp/aiwave.jp/contact/" onclick="javascript:_gaq.push(['_trackPageview', '/contact/']);"><img src="images/icon_top_sp_02.png" alt="">お問い合わせ</a>
                    <a href="tel:0120935225"><img class="w70" src="images/btn_top_contact_sp.png" alt="お問い合わせ"></a>

                </section>
            </section>
            <!-- end #head_sp -->

            <section id="mainimg">
            	<section id="mainimg_inner" class="clearfix">
            		<h1>
            			<img class="mainimg_01_01" src="images/mainimg_01_02.png" alt="女性をターゲットとする企業のための、女性スタッフによるホームページ制作">
            			<img class="mainimg_01_tab" src="images/mainimg_01_tab.png" alt="女性をターゲットとする企業のための、女性スタッフによるホームページ制作">
            		</h1>
            		<h2>
            			<img class="mainimg_02_01" src="images/mainimg_03.png" alt="女性のクリエイティブチームが、女性向けの販売促進・集客に強いホームページを制作します">
            		</h2>
            	</section>
            </section>

            <section id="top_wrapper">

            <h2>
            	<img class="mainimg_02_tab" src="images/mainimg_02_tab.png" alt="女性のクリエイティブチームが、女性向けの販売促進・集客に強いホームページを制作します">
            	<img class="mainimg_02_sp" src="images/mainimg_02_sp.png" alt="女性のクリエイティブチームが、女性向けの販売促進・集客に強いホームページを制作します">
            </h2>

            	<section id="top_content">

            		<section class="top_voice">

            	    	<h2>
            	        	<img src="images/h2_01.png" alt="1000サイト以上の女性向けホームページ制作実績があります">
            	        </h2>

            	        <h3 class="h3_top_01">成功事例・お客様の声</h3>

            	        <article class="art_top_01">
            	        	<img src="images/img_top_01.png" alt="朝日カルチャーセンター様">
            	            <section>
            	            	<h4>女性をターゲットとした新しい切り口のホームページで、新規市場の開拓に成功しました</h4>
            	            	<p class="p_top_voice_01"><span class="comp_name_01">朝日カルチャーセンター様</span>　（カルチャーセンター）</p>
            	            	<p class="p_top_voice_02">既存のホームページでは「若い女性にアプローチできていない」という課題がありました。顧客層の拡大のためには…（<a href="voice/#link_voice_01">続きを読む</a>）</p>
            	            </section>
            	        </article>

            	        <article class="art_top_01">
            	        	<img src="images/img_top_02.png" alt="株式会社BHY様">
            	            <section>
            	            	<h4>ユーザー目線にこだわった、訴求力の高いコンテンツ作りに納得</h4>
            	            		<p class="p_top_voice_01"><span class="comp_name_01">株式会社BHY様</span>　（エステサロン運営）</p>
            	            		<p class="p_top_voice_02">私たちがメインターゲットとしているのは、20代から30代の女性たち。美容というライバルが非常に多いジャンルでは、顧客分析を行い…（<a href="voice/#link_voice_02">続きを読む</a>）</p>
            	            </section>
            	        </article>

            	        <article class="art_top_01">
            	        	<img src="images/img_top_03.png" alt="ルーデンス立川ウェディングガーデン様">
            	            <section>
            	            	<h4>斬新な企画のホームページが、女性のお客様から好評です</h4>
            	            		<p class="p_top_voice_01"><span class="comp_name_01">ルーデンス立川ウェディングガーデン様</span>　（結婚式場）</p>
            	            		<p class="p_top_voice_02">お客様の笑顔をオンタイムで伝える「スマイルギャラリー」など、お客様目線でコンテンツを企画していただきました。実際の来場にも…（<a href="voice/#link_voice_03">続きを読む</a>）</p>
            	            </section>
            	        </article>

            	    </section>
            	    <!-- end #top_voice -->

            	    <section class="top_center">

            	        <section class="top_news">

            	        	<h2><img src="images/h2_02.png" alt="新着情報"></h2>

            	            <dl class="dl_top_news_01">
				<?php for($i=0;$i<count($fetch);$i++):?>
				<dt><?php echo $time[$i];?></dt><dd>
				<?php

					if($link[$i]){
						if($link_flg[$i] == 1){
							echo "<a href='./news/#{$id[$i]}'>{$title[$i]}</a>";
						}
						elseif($link_flg[$i] == 2){
							echo "<a href='{$link[$i]}' target=\"_blank\">{$title[$i]}</a>";
						}
						elseif($link_flg[$i] == 3){
							echo "<a href='{$link[$i]}'>{$title[$i]}</a>";
						}
					}
					else{
						echo "<a href='./news/#{$id[$i]}'>{$title[$i]}</a>";
					}
				?></dd>
				<?php endfor;?>
            	            </dl>

            	            <p class="p_right"><a href="news/">⇒新着情報一覧を見る</a></p>

            	        </section>

            	        <section class="top_fb_01">

            	        	<!-- start facebook pulgin -->
            	            <div class="likebox_conteiner">
            	<div class="fb-like-box" data-href="http://www.facebook.com/aiwave" data-width="292" data-height="490" data-show-faces="false" data-stream="true" data-border-color="#eae8e2" data-header="true"></div>
            	</div>
            	            <!-- end facebook pulgin -->

            	        </section>

            	    </section>
            	    <!-- end #top_center -->

            	    <section class="top_side">

            	    	<a href="check/"><img class="bn_top_side_01" src="images/bn_top_01_off.png" alt="ホームページチェックシート"></a>
            	    	<a href="blog/"><img class="bn_top_side_02" src="images/bn_top_02_off.png" alt="ブログ"></a>
            	    	<img class="bn_top_side_03" src="images/bn_top_03.png" alt="認定・認証マーク">
            	    	<a href="https://mx18.all-internet.jp/aiwave.jp/contact/" onclick="javascript:_gaq.push(['_trackPageview', '/contact/']);"><img class="bn_top_side_04" src="images/bn_top_04_off.png" alt="お問い合わせ"></a>

            	    </section>
            	    <!-- end #top_side -->

            	</section>
            	<!-- end #top_content -->

            	<section class="top_products">

            	    <div class="sp">
            	    	<h2 class="mb10"><img src="images/h2_03.png" alt="制作実績"></h2>
                        <p style="font-size:16px; text-align: center; margin:0 0 15px 0; font-weight:bold;"><a href="products/">⇒制作実績ページはこちら</a></p>
            	    </div>

            	    <div class="tabpc pc_clear">
            	    	<h2 class="mb10" style=" float:left;"><img src="images/h2_03.png" alt="制作実績"></h2>
            	    	<p style="float:left; font-size:18px; margin:5px 0 0 25px; font-weight:bold;"><a href="products/">⇒制作実績ページはこちら</a></p>
            	    </div>

            	    <div class="top_products_inner clearfix">

            	    	<div class="box_top_products_01">
            	    		<img src="images/img_top_001.png" alt="歯科医院">
            	    		<p>歯科医院</p>
            	    	</div>

            	    	<div class="box_top_products_01">
            	    		<img src="images/img_top_002.png" alt="化粧品販売">
            	    		<p>化粧品販売</p>
            	    	</div>

            	    	<div class="box_top_products_01">
            	    		<img src="images/img_top_003.png" alt="スポーツクラブ">
            	    		<p>スポーツクラブ</p>
            	    	</div>

            	    	<div class="box_top_products_01">
            	    		<img src="images/img_top_004.png" alt="振袖レンタル">
            	    		<p>振袖レンタル</p>
            	    	</div>

            	    	<div class="box_top_products_01">
            	    		<img src="images/img_top_005.png" alt="法律事務所">
            	    		<p>法律事務所</p>
            	    	</div>

            	    	<div class="box_top_products_01">
            	    		<img src="images/img_top_006.png" alt="ポータルサイト">
            	    		<p>ポータルサイト</p>
            	    	</div>

            	    	<div class="box_top_products_01">
            	    		<img src="images/img_top_007.png" alt="カルチャーセンター">
            	    		<p>カルチャーセンター</p>
            	    	</div>

            	    	<div class="box_top_products_01">
            	    		<img src="images/img_top_008.png" alt="人材派遣サービス">
            	    		<p>人材派遣サービス</p>
            	    	</div>

            	    	<div class="box_top_products_01">
            	    		<img src="images/img_top_009.png" alt="不動産">
            	    		<p>不動産業</p>
            	    	</div>

            	    	<div class="box_top_products_01">
            	    		<img src="images/img_top_010.png" alt="家事代行サービス">
            	    		<p>家事代行サービス</p>
            	    	</div>

            	    	<div class="box_top_products_01">
            	    		<img src="images/img_top_011.png" alt="国際幼稚園">
            	    		<p>国際幼稚園</p>
            	    	</div>

            	    	<div class="box_top_products_01">
            	    		<img src="images/img_top_012.png" alt="画材販売">
            	    		<p>画材販売</p>
            	    	</div>

            	    	<div class="box_top_products_01">
            	    		<img src="images/img_top_013.png" alt="児童福祉サービス">
            	    		<p>児童福祉サービス</p>
            	    	</div>

            	    	<div class="box_top_products_01">
            	    		<img src="images/img_top_014.png" alt="食品製造販売">
            	    		<p>食品製造販売</p>
            	    	</div>

            	    	<div class="box_top_products_01">
            	    		<img src="images/img_top_015.png" alt="子供服販売">
            	    		<p>子供服販売</p>
            	    	</div>

            	    	<div class="box_top_products_01">
            	    		<img src="images/img_top_016.png" alt="ネイルサロン">
            	    		<p>ネイルサロン</p>
            	    	</div>

            	    	<div class="box_top_products_01">
            	    		<img src="images/img_top_017.png" alt="ペット関連サービス">
            	    		<p>ペット関連サービス</p>
            	    	</div>

            	    	<div class="box_top_products_01">
            	    		<img src="images/img_top_018.png" alt="手芸用品販売">
            	    		<p>手芸用品販売</p>
            	    	</div>

            	    	<div class="box_top_products_01">
            	    		<img src="images/img_top_019.png" alt="設計事務所">
            	    		<p>設計事務所</p>
            	    	</div>

            	    	<div class="box_top_products_01">
            	    		<img src="images/img_top_020.png" alt="ワイン販売">
            	    		<p>ワイン販売</p>
            	    	</div>

            	    </div>

            	    <p class="p_right"><a href="products/">⇒制作実績一覧を見る</a></p>

            	</section>

                <section id="bottom_cotact_sp">
                    <a href="check/"><img class="w90 mb10" src="common_img/img_bottom_sp_01.png" alt="ホームページチェックシート"></a>
                    <a href="blog/"><img class="w90 mb10" src="common_img/img_bottom_sp_02.png" alt="女子マーケmemo@神保町"></a>
                    <img class="w80 mb20" src="common_img/img_bottom_sp_03.png">

                    <section id="bottom_cotact_sp">
                        <a class="btn_03 mb20 w90" href="https://mx18.all-internet.jp/aiwave.jp/contact/" onclick="javascript:_gaq.push(['_trackPageview', '/contact/']);"><img src="images/icon_top_sp_02.png" alt="">お気軽にお問い合わせください</a>
                        <a href="tel:0120935225"><img class="w70" src="images/btn_top_contact_sp.png" alt="お気軽にお問い合わせください"></a>
                    </section>

                </section>

            	        <section class="top_fb_02">

            	        	<!-- start facebook pulgin -->
            	            <div class="likebox_conteiner">
            	<div class="fb-like-box" data-href="http://www.facebook.com/aiwave" data-width="292" data-height="490" data-show-faces="false" data-stream="true" data-border-color="#eae8e2" data-header="true"></div>
            	</div>
            	            <!-- end facebook pulgin -->

            	        </section>

                <?php include("menuhtml/footer_top.html"); ?>

            </section>
            <!-- end #top_wrapper -->

        </section>
        <!-- end #wrapper -->
<?php
$ua = $_SERVER['HTTP_USER_AGENT'];

if ((strpos($ua, 'Android') !== false) && (strpos($ua, 'Mobile') !== false) || (strpos($ua, 'iPhone') !== false) || (strpos($ua, 'Windows Phone') !== false)) {
    	// スマートフォンからアクセスされた場合
	$link_type = "log_sp.php";

} elseif ((strpos($ua, 'Android') !== false) || (strpos($ua, 'iPad') !== false)) {
   	 // タブレットからアクセスされた場合
	$link_type = "log_tb.php";

}  else {
    	// その他（PC）からアクセスされた場合
	$link_type = "log.php";
}

?>
<script type="text/javascript" src="https://www.google.com/jsapi?key="></script>
<script src="https://mx16.all-internet.jp/state/state2.js" language="javascript" type="text/javascript"></script>
<script language="JavaScript" type="text/javascript">
<!--
var s_obj = new _WebStateInvest();
//var _accessurl = setUrl();
document.write('<img src="./<?php echo $link_type;?>?referrer='+escape(document.referrer)+'&st_id_obj='+encodeURI(String(s_obj._st_id_obj))+'" width="1" height="1">');
//-->
</script>
</body>
</html>
