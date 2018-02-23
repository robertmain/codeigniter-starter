<?php

/**
 * Migration CLI controller
 *
 * Used for running migrations from the CLI (for example, from a composer script)
 * @ignore
 */
class Migrate extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('migration');
        if (!$this->input->is_cli_request()) {
            show_error('Forbidden', 403);
            exit(EXIT_ERROR);
        }
    }

    /**
     * Migrate the database to the latest version
    */
    public function latest()
    {
        $this->migration->latest();
    }
}
