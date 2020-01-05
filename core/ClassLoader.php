<?php

/*
    オートロードに関する処理をまとめたクラス
    register： PHPにオートローダクラスを登録する処理
    loadClass: オートロードが実行された際にクラスファイルを読み込む処理
*/

class ClassLoader
{
    protected $dirs;

    public function register()
    {
        spl_autoload_register(array($this, 'loadClass')); //引数に関数を指定 → コールバック関数
    }

    public function registerDir($dir)
    {
        $this->dirs[] = $dir;
    }

    public function loadClass($class)
    {
        foreach ($this->dirs as $dir) {
            $file = $dir . '/' . $class . '.php';
            if(is_readable($file)){
                require $file;

                return;
            }
        }
    }
}