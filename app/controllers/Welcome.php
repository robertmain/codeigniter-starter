<?php

class Welcome extends MY_Controller
{
    public function index()
    {
        echo $this->templates->render('profile', ['name' => 'Peter']);
    }
}
