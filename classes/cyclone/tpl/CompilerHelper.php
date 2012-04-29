<?php

namespace cyclone\tpl;

/**
 * @package cytpl
 * @author Bence Eros <crystal@cyclonephp.org>
 */
class CompilerHelper {

    public static function propchain($str) {
        return str_replace('.', '->', $str);
    }
}