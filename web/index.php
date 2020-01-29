<?php

/*
    いわゆるフロントコントローラにあたる部分。全てのリクエストをこのファイルで受け取る。
    なぜ？→bootstrapで記述したオートロードを全てのファイルに読み込ませるのは効率が悪いため、index.phpを必ず通すようにする。

＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
    core/.htaccess について
        Apacheの設定を変更するファイル。
        example.com/list のURLは、
        example.com/index.php/list でアクセス時と同じ
*/

require '../bootstrap.php';



