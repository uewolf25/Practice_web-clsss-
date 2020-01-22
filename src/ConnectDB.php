<?php
class ConnectDB{
  private $table;
  private $url;
  private $contents;

  function __construct($table, $url, $contents){
    $this->table = $table;
    $this->url = $url;
    $this->contents = $contents;
  }

  function insert_sql(){
    try{
      $dbh = new PDO('sqlite:../db/test.db', '', '');
      
      $sql = 'insert into list (url, contents) values(?, ?)';
      $sth = $dbh->prepare($sql);
      $sth->execute(array($this->url, $this->contents));
  
      $q = "'%t%'";
      $sql = "select * from $this->table where contents like $q";
      $sth = $dbh->prepare($sql);
      $sth->execute();
    } catch(PDOException $e){
      print "error：　" . $e->getMessage(). "<br>";
      die();
    }
  }
}
?>
