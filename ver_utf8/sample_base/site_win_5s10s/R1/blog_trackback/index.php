<?php
require_once('../common/blog_config.php');

function RES_XML($result, $message)
{
    $XML = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<response>
  <error>{$result}</error>
  <message>{$message}</message>
</response>
XML;
    return ($XML);
}

// UTF-8エンコード用関数
function ToUtf8()
{
    if (func_num_args() == 1) {
        if (is_array(func_get_arg(0))) {
            $array = func_get_arg(0);
            return array_map('ToUtf8', $array);
        } else {
            $text = func_get_arg(0);
            return mb_convert_encoding($text, "utf8", "auto");
            // autoが効かない場合はこちらを使用してください
            // return mb_convert_encoding($text, "UTF-8", "ASCII,JIS,UTF-8,UTF-8,SJIS");
        }
    } else {
        return null;
    }
}

// サニタイジング用関数
function Sanitize()
{
    global $PDO;

    if (func_num_args() == 1) {
        if (is_array(func_get_arg(0))) {
            $array = func_get_arg(0);
            return array_map('Sanitize', $array);
        } else {
            $text = func_get_arg(0);
            $text = str_replace("\0", "", $text);

            if (get_magic_quotes_gpc()) {
                $text = stripslashes($text);
            }

            return htmlspecialchars($text);
        }
    } else {
        return null;
    }
}
if (isset($_POST)) {
    $id = ToUtf8(Sanitize($_GET['id']));

    // サニタイジング(ユーザ定義関数)
    $vars = Sanitize($_POST);

    // UTF-8エンコード(ユーザ定義関数)
    $vars = ToUtf8($vars);

    // 「url(アドレス)」がセットされてれば、処理開始
    if (isset($vars['url']) && preg_match("/^http:\/\/.+\./", $vars['url'])) {
        // 「url(アドレス)」を取得
        $url = $vars['url'];

        // 「title(タイトル)」を取得
        if (isset($vars['title']) && !preg_match("/^[\s|\t|　]*$/", $vars['title'])) {
            $title = $vars['title'];
        } else {
            // 「title(タイトル)」がセットされていなければ、「url」の値を使用
            $title = $vars['title'];
        }

        // 「excerpt(概要)」を取得
        if (isset($vars['excerpt']) && !preg_match("/^[\s|\t|　]*$/", $vars['excerpt'])) {
            $excerpt = $vars['excerpt'];
        }

        // 「blog_name(ブログ名)」を取得
        if (isset($vars['blog_name']) && !preg_match("/^[\s|\t|　]*$/", $vars['blog_name'])) {
            $blog_name = $vars['blog_name'];
        }

        // GMT標準時取得
        $gmt_time = gmdate('D, j M Y H:i:s')." GMT";
        // 日本時間取得
        $jpn_time = date('Y/n/j H:i:s T', strtotime($gmt_time));

        /*
        受け取った情報を元に、自分のブログのファイルに
        トラックバック情報を書き込む等の処理をここで行う。
            $url       : アドレス
            $title     : タイトル
            $excerpt   : 概要
            $blog_name : ブログ名
        の様に格納されています。
        */
        ##登録

        if (is_numeric($id) && !empty($id)) {
            $sqlcnt = "
			SELECT COUNT(*) AS CNT FROM BLOG_ENTRY_LST WHERE (BLOG_ENTRY_LST.DEL_FLG = '0') AND (BLOG_ENTRY_LST.E_ID = '$id')";
            $fetch_cnt_check = $PDO->fetch($sqlcnt);

            //最大登録件数以内の場合は登録をする
            if ($fetch_cnt_check[0]['CNT'] > 0) {
                $res_id = $makeID();
                $sql = "
					INSERT INTO BLOG_TRACKBACK_LST(
						RES_ID,E_ID,ENTRY_TITLE,BLOG_URL,BLOG_DETAIL,BLOG_TITLE,INS_DATE,DEL_FLG
					)
					VALUES(
						'$res_id','$id','$title','$url','$excerpt','$blog_name',NOW(),'0'
					)";
                // ＳＱＬを実行
                if (!empty($sql)) {
                    $PDO->regist($sql);
                }

                $res_xml = RES_XML(0, 'OK');
            } else {
                $res_xml = RES_XML(1, 'Error. Not entry.');
            }
        } else {
            $res_xml = RES_XML(1, 'Error. Not Id.');
        }
    } else {
        $res_xml = RES_XML(1, 'Error. Cannot Found URL Parameter.');
    }
} else {
    //$res_xml = RES_XML(1, 'Error. Invailed Data.');
    header("HTTP/1.0 404 Not Found");
    exit();
}

echo $res_xml;
