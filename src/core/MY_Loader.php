<?php

use Exceptions\IO\Filesystem\FileNotFoundException;

/**
 * Extends CodeIgniter's default loader to support model namespaces
 */
class MY_Loader extends CI_Loader
{

    /**
     * @var String Path to the folder where we can expect to find models for CodeIgniter to load
     */
    private $models_dir;

    /**
     * @var Object Instance of CodeIgniter to splice the models on to
     */
    private $CI;

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct();

        $this->models_dir = APPPATH . 'models' . DIRECTORY_SEPARATOR;
        $this->CI =& get_instance();
    }

    /**
     * {@inheritdoc}
     */
    public function model($model, $name = '', $db_conn = false)
    {
        if (is_array($model)) {
            foreach ($model as $model_name) {
                $this->model($model_name);
            }
            return $this;
        } else {
            if (empty($name)) {
                $parts = explode('\\', $model);
                $name = end($parts);
            }
            array_push($this->_ci_models, $name);

            try{
                $instance  = $this->load_class($this->models_dir . $model . '.php', 'App\\Models\\' . $model);
                $this->CI->{$name} = $instance;
            } catch(FileNotFoundException $e){
                throw new RuntimeException('Unable to locate the model you have specified: ' . $model);
            }

            return $this;
        }
    }

    /**
     * Load and instansiate a class
     *
     * @param string $path       Absolute path to the file to load
     * @param string $class_name The (fully qualified) name of the class that should be instanciated
     *
     * @return object
     * @throws Exceptions\IO\Filesystem\FileNotFoundException
    */
    private function load_class($path, $class_name)
    {
        if (file_exists($path)) {
            $instance = new $class_name();
            return $instance;
        } else {
            throw new FileNotFoundException();
        }
    }
}
