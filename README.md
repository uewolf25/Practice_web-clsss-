# Practice_web-clsss-

## 機能
- ドキュメント 
- 出現回数の高い単語トップ10
- 上記の単語のtf値
- 上記の単語のdf値
- 上記の単語のtf-idf値
- tf-idf値の高い単語トップ10

## 環境
- PHP: 7.2
- SQLite3

## 実行
```
.
├── README.md
├── db
    ├── (test.db)
├── docker-compose.yml
└── src
    ├── report_final.php(main)
    ├── ConectNetwork.php
    ├── (Key.php)
    ├── ConnectDB.php
    ├── Calculation.php    
    └── KeyTemplate.php
```
上記の.ymlファイルがある階層で、`docker-compose up`　または `docker-compose up -d`  
後者を選択した場合は`docker-compose down`を忘れず入力する。

## 備考
`bd/test.db`にテーブル`list`を作成しておく。  
```
CREATE TABLE list(
  id integer primary key autoincrement,
  url text,
  contents text
);
```