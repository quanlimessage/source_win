<?php

class PingManager
{
    // 送信ポート設定
    public $portNumber = 80;
    // ソケットのタイムアウト設定
    public $timeOut = 3;
    // XMLの文字コード設定
    public $outputEncoding = "utf-8";

    public function PingManager()
    {
    }

    public function __get($name)
    {
        //throw new Exception("'".$name."' is not defined in 'PingManager'.");
        die("'".$name."' is not defined in 'PingManager'.");
    }

    public function __set($name, $value)
    {
        //throw new Exception("'".$name."' is not defined in 'PingManager'.");
        die("'".$name."' is not defined in 'PingManager'.");
    }

    public function sendTrackBackPing($targetUrl, $rule)
    {
        // 送信先アドレスが「http://～」形式であるかのチェック
        if (!$this->isHttpAddress($targetUrl)) {
            //throw new Exception("The specified argument 1 '".$targetUrl."' is not HTTP address.");
            die("The specified argument 1 '".$targetUrl."' is not HTTP address.");
        }

        // 送信元アドレスが「http://～」形式であるかのチェック
        if (!$this->isHttpAddress($rule['url'])) {
            //throw new Exception("Parameter 'url' in argument 2 is not HTTP address.");
            die("Parameter 'url' in argument 2 is not HTTP address.");
        }

        // 送信元の記事タイトルが空白の場合、アドレスをタイトルとする。
        if (preg_match("/^[\s|\t|　]*$/", $rule['title'])) {
            $rule['title'] = $rule['url'];
        }

        // 送信先アドレスを分解して、ホスト名、パス名、クエリを取得
        $targets = $this->parseUrlEx($targetUrl);

        // ソケット通信開始
        $fp = fsockopen($targets['host'], $this->portNumber, $errno, $errstr, $this->timeOut);
        if (!$fp) {
            echo "$errstr ($errno)<br />\n";
            return false;
        } else {
            // 送信クエリ作成
            $data = "title=".$rule['title']."&url=".$rule['url'].
                "&excerpt=".$rule['excerpt']."&blog_name=".$rule['blog_name'];

            // 送信データをUTF-8に変換
            $data = mb_convert_encoding($data, "UTF-8", "auto");
            //$data = mb_convert_encoding($data, "UTF-8", 'UTF-8,SJIS,ASCII,JIS');

            $out = "POST ".$targets['path']." HTTP/1.1\r\n";
            $out .= "Host: ".$targets['host']."\r\n";
            $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $out .= "Content-Length: ".strlen($data)."\r\n";
            $out .= "Connection: Close\r\n\r\n";

            // 送信クエリを付加
            $out .= $data;

            // 送信
            fputs($fp, $out);

            // サーバからレスポンスを受信
            $response = "";
            while (!feof($fp)) {
                $response .= fgets($fp);
            }
            fclose($fp);

            // 改行コードの統一
            $response = str_replace("\r\n", "\r", $response);
            $response = str_replace("\r", "\n", $response);
            /*

            // レスポンスからXML部分のみ抜き出す
            // 配列を順にチェックし、頭が「<?xml～」であればOK、
            // というチェックの方が確実かもしれません。
            $responses = explode("\n\n", $response);
            $body = $responses[count($responses) - 1];
            $body = preg_replace("/^[\s\n\t]+|[\s\n\t]+$/", "", $body);

            $ofp = fopen("../../common/ping/log/log.txt", "w+");
            fputs($ofp, $response);
            fclose($ofp);

            // XML形式のレスポンスデータ部をチェック
            $doc = new DOMDocument();
            if ($doc->loadXML($body))
            {
                // 「error」タグ内の値が0であれば成功
                $nodeList = $doc->getElementsByTagName('error');

                var_dump($nodeList);die();

                if ($nodeList->item(0)->nodeValue == 0)
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
            */
            if (preg_match("/<error>0<\/error>/", $response)) {
                return true;
            } else {
                return false;
            }
        }
    }

    // GET, POST送信用に特化させた「parse_url」
    // http://～形式のアドレスを分解する。
    public function parseUrlEx($targetUrl)
    {
        if (!$this->isHttpAddress($targetUrl)) {
            //throw new Exception("The specified argument 1 '".$targetUrl."' is not HTTP address.");
            die("The specified argument 1 '".$targetUrl."' is not HTTP address.");
        }

        // 通常の「parse_url」実行
        $targets = parse_url($targetUrl);

        // パスが空白の場合、「/」とする。
        if ($targets['path'] == "") {
            $targets['path'] = '/';
        }

        // クエリが指定されていた場合、先頭に「?」を先頭に付加
        if ($targets['query'] != "") {
            $targets['query'] = "?".$targets['query'];
        }

        return $targets;
    }

    // 指定した文字列が「http://～」形式のアドレスであるかチェック
    // 正規表現はMSDNのサンプルを参考にしました。
    public function isHttpAddress($string)
    {
        return preg_match(
            "/(http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[0-9a-zA-Z]{0,3}(:[a-zA-Z0-9]*)?\/?([a-zA-Z0-9\-\._\?\,\'\/\\\+&%\$#\=~])*$/",
            $string);
    }
}
