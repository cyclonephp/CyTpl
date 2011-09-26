<?php

namespace cyclone\tpl;

class CompilerHelper {

    public static function propchain($str) {
        return str_replace('.', '->', $str);
    }
}