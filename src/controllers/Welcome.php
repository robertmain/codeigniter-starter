<?php

use Core\ABC;

class Welcome extends ABC
{
    public function index()
    {
        $name = $this->input->get('name');
        $this->render('welcome', ['name' => ($name) ? $name : 'Peter']);
    }
}