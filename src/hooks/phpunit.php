<?php

/**
 * CodeIgniter system hook(s) to enable the usage of PHPUnit for unit testing
 * with CodeIgniter
 */
class PHPUnit_CodeIgniter_Hook
{
    /**
     * Pre system hook used to load the config and benchmark classes. 
    */
    public function pre_system()
    {
        $GLOBALS['CFG'] =& load_class('Config', 'core');
        $GLOBALS['BM']  =& load_class('Benchmark', 'core');
    }
}
