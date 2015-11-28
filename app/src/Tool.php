<?php

namespace App;

class Tool
{

    public static function removeSchemeFromURL( $url ) {
        
        $scheme = ['http://','https://'];
        return str_ireplace($scheme,'',$url);
        
    }

}
