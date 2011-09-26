<?php

namespace cyclone\tpl;

use cyclone as cy;


class View {

    public static function factory($tpl_file ,$data) {
        $view = new View($tpl_file);
        $view->_data = $data;
        return $view;
    }

    private $_tpl_path;

    private $_html_file;

    private $_tpl_name;

    private $_data;

    private static $compile_policy = NULL;

    public function  __construct($tpl_file) {
        $this->_tpl_name = $tpl_file;
        $this->_tpl_path = 'templates/'.$tpl_file;
        $this->_tpl_abs_path = cy\FileSystem::find_file('templates'
                . $this->_tpl_name.  '.tpl');
        $this->_html_file = cy\LIBPATH.'cytpl/views/' . $tpl_file . '.php';
        if (NULL === self::$compile_policy) {
            self::$compile_policy = Config::inst()->get('cytpl.compile');
        }
    }

    private function compile() {
        $tpl = file_get_contents($this->_tpl_abs_path);

        $html = Compiler::for_template($tpl)->compile();
        file_put_contents($this->_html_file, $html);
    }

    public function render() {
        $tpl_file = cy\FileSystem::find_file('templates/' . $this->_tpl_name . '.tpl');
       if (($policy = self::$compile_policy) != 'never') {
           if ('always' == $policy || ('on-demand' == $policy
                   && ( ! file_exists($this->_html_file)
                    || filemtime($this->_html_file) < filemtime($tpl_file)))) {
                $this->compile();
            }
        }
        
        return new View($this->_tpl_name, $this->_data);
    }

    public function  __toString() {
        try {
            return $this->render();
        } catch (Exception $ex) {
            Kohana::exception_handler($ex);
            return '';
        }
    }
    
}