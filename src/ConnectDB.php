<?php
class ConnectDB{
  private $table;

  /**
   * コンストラクタ
   */
  public function __construct($table){
    $this->table = $table;
  }

  /**
   * insert_sql　データの挿入
   * @param $url 解析するURL
   * @param $contents 形態素解析された単語
   */
  public function insert_sql($url, $contents){
    try{
      $dbh = new PDO('sqlite:../db/test.db', '', '');
      
      $sql = 'insert into list (url, contents) values(?, ?)';
      $sth = $dbh->prepare($sql);
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
      $dbh = new PDO('sqlite:../db/test.db', '', '');
      
      $sql = 'delete from list';
      $sth = $dbh->prepare($sql);
      $sth->execute();
    } catch(PDOException $e){
      print "error：　" . $e->getMessage(). "<br>";
      die();
    }
  }
}
?>
