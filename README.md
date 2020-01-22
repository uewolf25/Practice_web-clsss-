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

## 実行
```
.
├── README.md
├── db
├── docker-compose.yml
└── src
    ├── ConectNetwork.php
    ├── Index.php
    ├── (Key.php)
    ├── ConnectDB.php
    └── KeyTemplate.php
```
上記の.ymlファイルがある階層で、`docker-compose up`　または `docker-compose up -d`
後者を選択した場合は`docker-compose down`を忘れず入力する。