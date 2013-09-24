<?php

class myBootstrap {
    public function __construct() {
        ob_start();
    }

    public function __destruct() {
        header_remove();
    }
}

$myBootstrap = new myBootstrap();