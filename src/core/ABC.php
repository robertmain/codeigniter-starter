<?php

namespace App\Core;

use League\Plates\Engine;

/**
 * Abstract Base Controller
 *
 * Abstract controller that all application controllers should inherit from in order to benefit from utilities such
 * as pre-configured templating etc.
 */
abstract class ABC extends \CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->templates = new Engine(VIEWPATH);

        $this->templates->addFolder('layouts', VIEWPATH . 'layouts');
        $this->templates->addFolder('partials', VIEWPATH . 'partials');
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
