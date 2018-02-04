<?php

use League\Plates\Engine;

abstract class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->templates = new Engine(VIEWPATH . 'templates');
    }
}
