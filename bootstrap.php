<?php

/*
    オートロードを設定するためのファイル
*/

require 'core/ClassLorder.php';

$loader = new ClassLorder();
$loader->registerDir(dirname(__FILE__).'/core');
$loader->registerDir(dirname(__FILE__).'/models');
$loader->register();
