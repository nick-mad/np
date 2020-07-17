<?php

namespace App\Core;

class View
{
    public function render($template, $data = array())
    {
        $template = __DIR__ . '/../../templates/' . $template;
        if (!is_file($template)) {
            throw new \RuntimeException('Template not found: ' . $template);
        }

        $result = static function ($file, array $data = array()) {
            ob_start();
            extract($data, EXTR_SKIP);
            try {
                include $file;
            } catch (\Exception $e) {
                ob_end_clean();
                throw $e;
            }
            return ob_get_clean();
        };

        return $result($template, $data);
    }
}
