<?php

class Welcome extends Controller
{
    public function index()
    {
        $name = $this->input->get('name');
        $this->render('welcome', ['name' => ($name) ? $name : 'Peter']);
    }
}
