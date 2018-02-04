<?php

use League\Plates\Engine;

abstract class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->templates = new Engine(VIEWPATH . 'templates');
    }

    /**
     * Wraps the `render()` method from plates. It can output the partial(default), or it can return it for usage
     * elsewhere (for example in sending an email)
     *
     * @param String  $partial       Path to the partial to display
     * @param Array   $template_vars A hash of values to pass through to the underlying template
     * @param Boolean $output        Turns the output of the template on or off. If set to true,
     *                               the template will be output by the method directly. Otherwise, the parsed template
     *                               will be returned as a string (this can be useful when sending HTML email)
     *
     * @see http://platesphp.com/v3/simple-example
    */
    protected function render($partial, array $template_vars = [], $output = true)
    {
        if ($output) {
            echo $this->templates->render($partial, $template_vars);
        } else {
            return $this->templates->render($partial, $template_vars);
        }
    }
}

/**
 * Syntax Sugar Controller
 *
 * This controller simply exists to provide some syntax sugar around inheritence so that classes
 * may extend "Controller" rather than "MY_Controller"
 */
abstract class Controller extends MY_Controller
{}
