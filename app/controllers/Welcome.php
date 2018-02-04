<?php

class Welcome extends MY_Controller
{
    public function index()
    {
        $this->render('profile', ['name' => 'Peter']);
    }
}
