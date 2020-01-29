<?php

/*
    オートロードを設定するためのファイル
    なぜルートディレクトリ直下か？ → 特定のアプリケーションごとに手を加えられるようにするため
*/

require 'core/ClassLorder.php';

$loader = new ClassLorder();
$loader->registerDir(dirname(__FILE__).'/core');
$loader->registerDir(dirname(__FILE__).'/models');
$loader->register();
