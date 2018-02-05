<?php

use Core\ABC;

class Welcome extends ABC
{
    public function index()
    {
        $name = $this->input->get('name');
        $this->render('partials::welcome', ['name' => ($name) ? $name : 'Peter']);
    }
}
