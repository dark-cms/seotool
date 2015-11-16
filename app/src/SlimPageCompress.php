<?php

namespace App;

class SlimPageCompress
{

    public static function Start($buffer)
    {

        $search  = array (
            '/\>[^\S ]+/s', // strip whitespaces after tags, except space
            '/[^\S ]+\</s', // strip whitespaces before tags, except space
            '/(\s)+/s', // shorten multiple whitespace sequences
            '/<!--(.|\s)*?-->/s'
        );
        $replace = array (
            '>',
            '<',
            '\\1',
            ''
        );
        $buffer  = preg_replace($search, $replace, $buffer);

        $buffer = str_replace('> <', '><', $buffer);

        return $buffer;

    }

}
