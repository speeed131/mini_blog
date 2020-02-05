<?php

class View
{
    protected $base_dir;
    protected $defaults;
    protected $layout_variables = array();

    public function __construct($base_dir, $defaults = array())
    {
        // viewファイルへの絶対URL
        $this->base_dir = $base_dir;
        // デフォルトで渡す変数を設定するため
        $this->defaults = $defaults;
    }

    public function setLayoutVar($name, $value)
    {
        $this->layout_variables[$name] = $value;
    }

    //変数名に_(アンダースコア)をつけている理由は、変数名の衝突を避けるため
    public function render($_path, $_variables = array(), $_layout = false)
    {
        $_file = $this->base_dir . '/' . $_path . '.php';

        // extract関数は、連想配列のキーを変数名に、連想配列の値を変数の値として展開する関数
        // これで、Viewファイルに簡単に変数を渡せる
        extract(array_merge($this->defaults, $_variables));

        // アウトプットバッファリング  = 内部に出力情報を保存
        ob_start();
        ob_implicit_flush(0);

        require $_file;

        $content = ob_get_clean();

        if ($_layout) {
            $content = $this->render($_layout,
                array_merge($this->layout_variables, array(
                    '_content' => $content,
                    )
                )
            );
        }

        return $content;
    }

    public function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}