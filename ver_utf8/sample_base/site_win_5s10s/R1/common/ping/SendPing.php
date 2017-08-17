<?php

class SendPing
{
    // プロパティ用変数
    public $property = [];

    // コンストラクタ
    public function SendPing()
    {
        // 送信ポート設定
        $this->property['Port'] = 80;
        // ソケットのタイムアウト設定
        $this->property['TimeOut'] = 10;
    }

    // getter
    public function __get($name)
    {
        if (isset($this->property[$name])) {
            return $this->property[$name];
        } else {
            return null;
        }
    }

    // setter
    public function __set($name, $value)
    {
        $this->property[$name] = $value;
    }

    // 更新Pingの送信(「$target」に送信先のアドレスを指定、
    // 「$rule」には、更新Pingで送信する内容を置換ルールとして配列で指定)
    public function SendUpdatePing($target, $rule)
    {

        // 必須項目である「url」のチェック
        // 実際は「http://」で始まるかのチェックや、
        // 送信元のサイトが存在するかのチェックも必要でしょう。(スパム対策)
        if (!isset($rule['%%SITEURL%%']) || $rule['%%SITEURL%%'] == "") {
            echo "Error : Parameter URL is required.<br />\n";
            return false;
        }

        // 「%%SITENAME%%」サイト名が空白の場合、「%%SITEURL%%」の値を使用
        if (!isset($rule['%%SITENAME%%']) || $rule['%%SITENAME%%'] == "") {
            $rule['%%SITENAME%%'] = $rule['%%SITEURL%%'];
        }

        // http://～形式のアドレスを分解する。
        // 例えば「http://wawatete.ddo.jp/dir1/program1」であれば、
        // ホストに「wawatete.ddo.jp」、パスに「/dir1/program1」が入る。
        $targets = parse_url($target);
        $host = $targets['host'];
        $path = $targets['path'];

        // ソケット接続をオープンする
        $fp = fsockopen($host, $this->property['Port'], $errno, $errstr, $this->property['TimeOut']);
        if (!$fp) {
            //echo "$errstr ($errno)<br />\n";
        } else {
            // 送信用文字列作成(XML読み込み)
            $data = file_get_contents("../../common/ping/xml/SendUpdatePing.xml");
            // XML内のダミー文字列を置換
            $data = strtr($data, $rule)."\r\n";
            $out = "POST ".$path." HTTP/1.1\r\n";
            $out .= "Host: ".$host."\r\n";
            $out .= "Content-Type: text/xml;\r\n";
            $out .= "Content-Length: ".strlen($data)."\r\n";
            $out .= "Connection: Close\r\n\r\n";
            // 送信用データを付加
            $out .= $data;

            // 送信
            fputs($fp, $out);

            //サーバから返答メッセージを取得
            while (!feof($fp)) {
                //echo fgets($fp)."<br>\n";
                break;
            }
            fclose($fp);
        }
    }

    // トラックバックPingの送信(「$target」に送信先のアドレスを指定、
    // 「$rule」には、トラックバックPingで送信する内容を配列で指定)
    public function SendTrackBackPing($target, $rule)
    {
        // 必須項目である「url」のチェック
        // 実際は「http://」で始まるかのチェックや、
        // 送信元のサイトが存在するかのチェックも必要でしょう。(スパム対策)
        if (!isset($rule['url']) || $rule['url'] == "") {
            echo "Error : Parameter URL is required.<br />\n";
            return false;
        }

        // 「title」タイトルが空白の場合、「url」の値を使用
        if (!isset($rule['title']) || $rule['title'] == "") {
            $rule['title'] = $rule['url'];
        }

        // http://～形式のアドレスを分解する。
        // 例えば「http://wawatete.ddo.jp/dir1/program1」であれば、
        // ホストに「wawatete.ddo.jp」、パスに「/dir1/program1」が入る。
        $targets = parse_url($target);
        $host = $targets['host'];
        $path = $targets['path'];

        $fp = fsockopen($host, $this->property['Port'], $errno, $errstr, $this->property['TimeOut']);
        if (!$fp) {
            echo "$errstr ($errno)<br />\n";
        } else {
            // 送信用文字列作成(フォームからGETで送るときと同じです。)
            $data = "title=".$rule['title']."&url=".$rule['url'].
                "&excerpt=".$rule['excerpt']."&blog_name=".$rule['blog_name']."\r\n";
            $out = "POST ".$path." HTTP/1.1\r\n";
            $out .= "Host: ".$host."\r\n";
            // ここだけ「更新Ping」と微妙に違います。注意。
            $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $out .= "Content-Length: ".strlen($data)."\r\n";
            $out .= "Connection: Close\r\n\r\n";
            // 送信用データを付加
            $out .= $data;
            // 送信
            fputs($fp, $out);
            //サーバから返答メッセージを取得
            while (!feof($fp)) {
                echo fgets($fp)."<br>\n";
            }
            fclose($fp);
        }
    }
}
