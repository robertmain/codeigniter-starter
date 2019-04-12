<?php

use App\Core\Controller;
use App\Models\User;

/**
 * Starter controller to give a basic introduction and example of how controlelrs work in CodeIgniter
 */
class Welcome extends Controller
{
    /**
     * Display the index page
     */
    public function index() : void
    {
        $name = $this->input->get('name');
        $this->render('partials::welcome', ['name' => ($name) ? $name : 'Peter']);
    }
}
