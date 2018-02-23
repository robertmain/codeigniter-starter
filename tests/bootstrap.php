<?php

$oldArgv = $_SERVER['argv'];
$_SERVER['argv'] = ['', 'test_bootstrap'];

require_once '.' . DIRECTORY_SEPARATOR . 'index.php';

$_SERVER['argv'] = $oldArgv;
