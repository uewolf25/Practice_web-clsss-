<?php
require('./ConnectDB.php');
require('./ConnectNetwork.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>最終課題</title>
</head>
<body>
  <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
  <p>
    <input type="text" name="query" />
    <input type="submit" value="送信" />
  </p>
  </form>

  <?php

  $q = $_POST['query'];

  if( isset($q) ){
    $connectDb = new ConnectDB('list');
    // 既存のデータの削除
    $connectDb->delete_sql();

    $socket = new ConnectNetwork($connectDb);
    
    list($head, $data) = $socket->httpRequest($q);
    $data = strip_tags($data);

    $size = 0;
    $size = 512;
    $end = mb_strlen($data);
    // echo $size;
    do{
      $data2 = mb_substr($data, $start-$end, $size);
      $words = $socket->yahoo_mecab($data2, $q);
      $start += $size;
      foreach($words as $item){
        $word["$item"] = $item;
        // echo $word["$item"];
        // echo "<br>";
      }
    } while( ($start-$size) <= $end );

    foreach($word as $value){
      $keyword = array_search($value, $word);
      if($keyword === false){
        $list_word["$value"] = 1;
      }
    }

    try{
      $dbh = new PDO('sqlite:../db/test.db','','');
      foreach($word as $keyword => $value){
        $q = "\"" . $keyword . "\"";
        // echo $q . "<br>";
        $sql = "select count(*) from list where contents like $q"; 
        $sth = $dbh->prepare($sql);
        $sth->execute();
        $count = $sth->fetchColumn();
        // 単語のカウント
        // echo "単語出現回数：　" . $count . "<br>";
        $list_word["$keyword"] = $count;
      }
    } Catch(PDOException $e){
      print "error!:" . $e->getMessage() . "<br>" ;
      die();
    }

    arsort($list_word);

    foreach($list_word as $key => $value){
      echo $key . " | " . $value . "<br>";
    }
  }
  ?>
</body>
</html>