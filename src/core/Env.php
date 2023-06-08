<?php

class Env {
    public $filename = '';
    public $env = array();

    public function __construct($filename) {
        $this->filename = $filename;
    }
    public function load() {
        $this->env = require dirname(__FILE__) . '/../../' . (!empty($this->filename) ? $this->filename : 'env');
        $_ENV = array_merge($this->env, $_ENV);
    }
}
