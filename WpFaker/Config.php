<?php
//namespace \WpFaker\Config;

use Faker\Factory;

class Config
{
    public $post_type;
    public $post_title;
    public $post_content;
    public $post_thumbnail;
    public $acf_values;
    
    public $config_files = array();
    public $using;
    
    /**
     * Get all the available configuration files.
     * 
     * @return boolean
     */
    public function get_config_files()
    {
        $config_files = glob(__dir__.'/../config-*.php');
        if (count($config_files) > 1) {
            foreach ($config_files as $file) {
                $file = explode('/', $file);
                $file = end($file);
                $this->config_files[] = str_replace(['config-','.php'], ['',''], $file);
            }
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Load the configuration file.
     * 
     * @return \myConfig
     */
    public static function load_config_file()
    {
        $using = filter_input(INPUT_GET, 'using');
        if (empty($using)) {
            // if no file has been asked, load the first config-* file found
            $config_files = glob (__DIR__.'/../config-*.php');
            $using_path = array_pop($config_files);
        } else {
            // load the asked config
            $using_path = __DIR__.'/../config-'.$using.'.php';
        }
        
        if(file_exists($using_path)) {
            require $using_path;
            $Config = new myConfig();
            $Config->using = $using;
            return $Config;
        } else {
            die('Error, the specified configuration does not exists.');
        }        
    }
}
