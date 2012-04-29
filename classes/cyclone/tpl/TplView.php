<?php

namespace cyclone\tpl;

use cyclone as cy;
use cyclone\view;

/**
 * @package cytpl
 * @author Bence Eros <crystal@cyclonephp.org>
 */
class TplView extends view\AbstractView {

    /**
     * Returns a new ew object. If you do not define the "file" parameter,
     * you must call @c AbstractView::set_filename() before calling @c render().
     *
     * @param string $file view filename
     * @param array $data array of values
     * @param boolean $is_absolute
     * @return Viewss
     */
    public static function factory($file = NULL, $data = array(), $is_absolute = FALSE) {
        return new TplView($file, $data, $is_absolute);
    }

    private function compile() {
        $tpl = file_get_contents($this->_tpl_abs_path);

        $html = Compiler::for_template($tpl)->compile();
        file_put_contents($this->_html_file, $html);
    }

    protected function capture() {
        return '';
    }

}

