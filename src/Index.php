<?php
require('./Key.php');
require('./ConnectDB.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
  <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
  <p>
    <input type="text" name="query" />
    <input type="submit" value="送信" />
  </p>
  </form>

  <?php

  $word_class = "名詞";
  $q = $_POST['query'];


  function httpRequest($url){
    $purl = parse_url($url);
    $psheme = $purl["scheme"];
    $phost = $purl["host"];

    if (!isset($purl["port"])) {
        $pport = 80;
    } else {
        $pport = $purl["port"];
    }

    if (!isset($purl["path"])) {
        $ppath = "/";
    } else {
        $ppath = $purl["path"];
    }

    echo "プロトコル：\t" . $psheme . "<br>";
    echo "ホスト名：\t" . $phost . "<br>";
    echo "ポート：\t" . $pport . "<br>";
    echo "パス：\t" . $ppath . "<br> <hr>";

    $hostname = $phost;
    $fp = fsockopen($hostname, $pport, $errno, $errstr);
    socket_set_timeout($fp, 10);

    $request = "GET " . $ppath . " HTTP/1.0\r\n\r\n";
    fputs($fp, $request);

    $response = "";
    while (!feof($fp)) {
        $response .= fgets($fp, 4096);
    }

    fclose($fp);

    $DATA = explode("\r\n\r\n", $response, 2);
    return $DATA;
  }

  function yahoo_mecab($data, $word_class, $url){
    $q_url = $url;
    $key = new Key();
    $connectDb = new ConnectDB('list', $q_url, $contents);
    $apikey = $key->getName();
    // echo $apikey;
    // $query = "冬の朝の京都は美しい。";
    $query = urlencode($data);
    $res = "surface,reading,pos,feature";
  
    $url = "http://jlp.yahooapis.jp/MAService/V1/parse?appid=" . $apikey . "&response=" . $res . "&sentence=" . $query ;
    // echo $url . "<br>";
    $rss = file_get_contents($url); //リクエスト送信&レスポンス取得
    $xml = simplexml_load_string($rss); //取得したXMLを解析
  
    foreach($xml->ma_result->word_list->word as $item){
      $part_of_speech = $item->feature;
      // echo $part_of_speech;
      if(mb_ereg($word_class, $part_of_speech) == 1){
        $contents = $item->surface;
        $connectDb->insert_sql();
        // echo $item->surface . "|" . $part_of_speech ;
        // echo "<br>"; 
        $word_list[] = "$contents";
      }
    }
    // foreach($word_list as $item){
    //   echo $word_list["item"];
    // }
    return $word_list;
  }


  if( isset($q) ){
    echo "<h1>" . $q . "</h1><hr>";
    
    // $query = "http://www.kyoto-su.ac.jp/faculty/cse/index.html";
    // httpRequest($_POST["query"]);
    list($head, $data) = httpRequest($q);
    // echo $data;
    $data = strip_tags($data);

    $size = 0;
    $size = 512;
    $end = mb_strlen($data);
    // echo $size;
    // do{
      $data2 = mb_substr($data, $start-$end, $size);
      $words = yahoo_mecab($data2, $word_class, $q);
      $start += $size;
      foreach($words as $item){
        $word["$item"] = $item;
        // echo $word["$item"];
        // echo "<br>";
      }
    // } while( ($start-$size) <= $end );

    foreach($word as $value){
      $keyword = array_search($value, $word);
      if($keyword === false){
        $list_word["$value"] = 1;
      }
    }

    try{
      // $q = "'%コンピュータ%'";
      $dbh = new PDO('sqlite:../db/test.db','','');
      foreach($word as $keyword => $value){
        $q = "\"" . $keyword . "\"";
        $sql = "select count(*) from list where contents like $q"; 
        $sth = $dbh->prepare($sql);
        $sth->execute();
        $count = $sth->fetchColumn();
        // 単語のカウント
        // echo "単語出現回数：　" . $count . "<br>";
        $list_word["$keyword"] = $count;
      }
    } Catch(PDOException $e){
      print "error!:" .$e->getMessage() . "<br>" ;
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