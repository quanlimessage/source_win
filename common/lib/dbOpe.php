<?php

class dbOpe{
    // PDO
    private $pdo;
    // PDOStatement
    private $statement;
    // fetchでの取得方法
    public $fetchMode;
    // DSN
    private $dsn;
    // ユーザー
    private $user;
    // パスワード
    private $pwd;

    /**
    * コンストラクタ
    * DSN、ユーザー、パスワード、取得時の設定
    *
    * @param string $dsn
    * @param string $user
    * @param string $pwd
    * @param PDO::FETCH_* 定数 $mode
    */
    public function __construct($dsn ,$user = "" ,$pwd = "" ,$mode = "") {
        $this -> dsn = $dsn;
        $this -> user = $user;
        $this -> pwd = $pwd;
        $this -> setFetchMode($mode);
    }

    /**
    * PDO属性を設定する
    * 属性は下記URLで確認
    * http://php.net/manual/ja/pdo.setattribute.php
    *
    * @param $attribute
    * @param $value
    */
    public function setAttribute($attribute ,$value){
        $this -> pdo -> setAttribute($attribute ,$value);
    }

    /**
    * DB接続
    * 接続後にSQL実行時のエラーをExceptionで捕捉できるようにPDO::ATTR_ERRMODEを設定しとく
    * $pdo = new PDO($dsn, $user, $pwd, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));という第四引数での設定方法もある
    */
    public function connect() {
        try {
            $this -> pdo = new PDO($this -> dsn ,$this -> user ,$this -> pwd);
            $this -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die('DB接続失敗：' . $e -> getMessage());
        }
    }

    /**
    * 実行するSQLをセットする
    *
    * @param string $sql
    */
    public function prepare($sql) {
        $this -> statement = $this -> pdo -> prepare($sql);
    }

    /**
    *　値をパラメータにバインドする
    *
    * @param array $value　//$val['KEY']：パラメータ $val['VAL']:value $val['TYPE']:データタイプ を引数に渡す
    */
    public function setBindValue($value = array()) {
        if (empty($value) && !is_array($value)) {
            return false;
        }
        foreach ($value as $val) {
            $option = ($val['TYPE'])?$val['TYPE']:PDO::PARAM_STR;
            $this -> statement -> bindValue(':'.$val['KEY'] ,$val['VAL'] ,$option);
        }
    }

    /**
    * SQL実行
    */
    public function execute() {
        $this -> statement -> execute();
    }

    /**
    * DB切断
    */
    public function close() {
        $this -> pdo = null;
    }

    /**
    * 直近の DELETE, INSERT, UPDATE 文によって作用した行数を返します
    *
    * @return array
    */
    public function getCount() {
        $fetch = $this -> statement -> rowCount();
        return ($fetch)?$fetch:array();
    }

    /**
    * SQL実行後の情報取得
    * SELECT文で対象となるレコードを1件返す
    *
    * @return array
    */
    public function getFetch() {
        $fetch = $this -> statement -> fetch($this -> fetchMode);
        return ($fetch)?$fetch:array();
    }

    /**
    * SQL実行後の情報取得
    * SELECT文で対象となるレコードを全て返す
    *
    * @return array
    */
    public function getAllFetch() {
        $fetch = $this -> statement -> fetchAll($this -> fetchMode);
        return ($fetch)?$fetch:array();
    }

    /**
    * fetchでの取得方法の変更
    * いままで通りの取得方法にするためPDO::FETCH_ASSOCがデフォルトで設定されるようにしとく
    * PDOクラスでのデフォルトはPDO::FETCH_BOTH
    *
    *　■設定
    * PDO::FETCH_ASSOC: は、結果セットに 返された際のカラム名で添字を付けた配列を返します。
    * PDO::FETCH_BOTH : 結果セットに返された際のカラム名と 0 で始まるカラム番号で添字を付けた配列を返します。
    * PDO::FETCH_BOUND: TRUE を返し、結果セットのカラムの値を PDOStatement::bindColumn() メソッドでバインドされた PHP 変数に代入します。
    * PDO::FETCH_CLASS: 結果セットのカラムがクラス内の名前付けされたプロパティに マッピングされている、要求されたクラスの新規インスタンスを返します。 fetch_style に PDO::FETCH_CLASSTYPE が 含まれている場合 (例: PDO::FETCH_CLASS | PDO::FETCH_CLASSTYPE) は、最初のカラムの値から クラス名を決定します。 
    * PDO::FETCH_INTO: 結果セットのカラムがクラス内の名前付けされたプロパティに マッピングされている要求された既存インスタンスを更新します。
    * PDO::FETCH_LAZY: PDO::FETCH_BOTH とPDO::FETCH_OBJの 組合せで、オブジェクト変数名を作成します。
    * PDO::FETCH_NAMED: PDO::FETCH_ASSOC と同じ形式の配列を返します。 ただし、同じ名前のカラムが複数あった場合は、そのキーが指す値は、 同じ名前のカラムのすべての値を含む配列になります。
    * PDO::FETCH_NUM: 結果セットに返された際の 0 から始まるカラム番号を添字とする配列を返します。
    * PDO::FETCH_OBJ: 結果セットに返された際のカラム名と同名のプロパティを有する 匿名のオブジェクトを返します。
    *
    * @param PDO::FETCH_* 定数 $mode
    */
    public function setFetchMode($mode) {
        $this -> fetchMode = ($mode)?$mode:PDO::FETCH_ASSOC;
    }

    /**
    * データ取得
    * SELECT文での実行時に使用
    * 第三引数の$allFetchをfalseに変更することで取得内容を条件に一致するすべてからではなく、一行のみを取得する。
    *
    * @param string $sql
    * @param array $bindValue
    * @param boolean $allFetch
    * @return array
    */
    public function fetch($sql ,$bindValue = array() ,$allFetch = true){
        try {
            $this -> connect();
            $this -> prepare($sql);
            $this -> setBindValue($bindValue);
            $this -> execute();
            $returnVal = ($allFetch)?$this -> getAllFetch():$this -> getFetch();
            $this -> close();
        } catch(PDOException $e) {
            die('DB実行（fetch）失敗：'. $e -> getMessage());
        }
        return $returnVal;
    }

    /**
    * SQL実行
    * UPDATE、INSERT、DELETEの実行時に使用
    *
    * @param string $sql
    * @param array $bindValue
    * @return boolean
    */
    public function regist($sql ,$bindValue = array()){
        try {
            $this -> connect();
            $this -> prepare($sql);
            $this -> setBindValue($bindValue);
            $this -> execute();
            $this -> close();
        } catch(PDOException $e) {
            die('DB実行（regist）失敗：'. $e -> getMessage());
        }
        return true;
    }

    /**
    * SQL実行
    * SQLステートメントを実行し、作用した行数を返します
    * クエリ内のデータは 適切にエスケープする必要
    *
    * @param string $sql
    * @return int 作用した行数
    */
    public function exec($sql){
        try {
            $this -> connect();
            $count = $this -> pdo -> exec($sql);
            $this -> close();
        } catch(PDOException $e) {
            die('DB実行（exec）失敗：'. $e -> getMessage());
        }
        return $count;
    }
}
?>