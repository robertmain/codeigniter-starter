<?php

use App\Core\ABC;

/**
 * Starter controller to give a basic introduction and example of how controlelrs work in CodeIgniter
 */
class Welcome extends ABC
{
    /**
     * Display the index page
     */
    public function index()
    {
        $name = $this->input->get('name');
        $this->render('partials::welcome', ['name' => ($name) ? $name : 'Peter']);
    }
}
