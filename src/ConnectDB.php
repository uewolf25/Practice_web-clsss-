<?php
class ConnectDB{
  // データベースのテーブル
  private $table;
  // データベースオブジェクト
  private $dbh;

  /**
   * コンストラクタ
   */
  public function __construct($table){
    $this->table = $table;
    // データベースオブジェクトの作成
    $this->dbh = new PDO('sqlite:../db/test.db', '', '');
  }

  /**
   * insert_sql　データの挿入
   * @param $url 解析するURL
   * @param $contents 形態素解析された単語
   */
  public function insert_sql($url, $contents){
    try{
      $sql = "insert into $this->table (url, contents) values(?, ?)";
      $sth = $this->dbh->prepare($sql);
      $sth->execute(array($url, $contents));
  
      // $q = "\'%t%\'";
      // $sql = "select * from $this->table where contents like $q";
      // $sth = $dbh->prepare($sql);
      // $sth->execute();
    } catch(PDOException $e){
      print "error：　" . $e->getMessage(). "<br>";
      die();
    }
  }

  /**
   * delete_sql　既存のデータの削除
   */
  public function delete_sql(){
    try{
      $sql = "delete from $this->table";
      $sth = $this->dbh->prepare($sql);
      $sth->execute();
    } catch(PDOException $e){
      print "error：　" . $e->getMessage(). "<br>";
      die();
    }
  }

  /**
   * count_sql キーワードをカウントする
   * @param $q キーワード(単語)
   */
  public function count_sql($q){
    try{
      $sql = "select count(*) from $this->table where contents like $q";
      $sth = $this->dbh->prepare($sql);
      $sth->execute();
      return $sth;
    } Catch(PDOException $e){
      print "error!:" . $e->getMessage() . "<br>" ;
      die();
    }
  }
}
?>
