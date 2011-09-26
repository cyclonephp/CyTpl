<?php

namespace cyclone\tpl;

use cyclone as cy;

class Command {

    const COMMANDS_FILE = 'cytpl-commands.php';

    public static function factory($namespace, $command, $arguments) {
        static $loaded_namespaces = array();
        $file_path = cy\FileSystem::get_root_path($namespace) . self::COMMANDS_FILE;
        if ( ! file_exists($file_path))
            throw new Exception('invalid namespace. File not found: ' . $file_path);

        $ns_arr = $loaded_namespaces[$namespace] = require $file_path;
        
        if ( ! array_key_exists($command, $ns_arr))
            throw new Exception("command '$command' doesn't exist in namespace '$namespace'");

        return new Command($command, $ns_arr[$command], $arguments);
    }

    private $_name;

    private $_descriptor;

    private $_args;

    public function  __construct($name, $descriptor, $args) {
        $this->_name = $name;
        $this->_descriptor = $descriptor;
        $this->_args = $args;
        $this->validate();
    }

    private function validate() {
        if ( ! isset($this->_descriptor['callback']))
            throw new CommandException("callback is not defined for command {$this->_name}");

        if ( ! isset($this->_descriptor['params']))
            throw new CommandException("parameters are not defined for command {$this->_name}");

        foreach ($this->_descriptor['params'] as $arg) {
            if ( ! isset($this->_args[$arg]))
                throw new CommandException("missing argument '$arg' in command {$this->_name}");
        }
            
    }

    /**
     * @return string
     */
    public function invoke() {
        $callback = $this->_descriptor['callback'];
        return call_user_func($callback, $this->_args);
    }
    
}