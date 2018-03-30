<?php

namespace App\Helpers;

use \Validator;

class Utils {
    public static function randomString() {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));
    
        $length = 50;

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }
    
        return $key;
    }

    public static function isUniqueName($name, $table) {
        $validator = Validator::make(
            ['name' => $name], 
            ['name' => 'unique:' . $table]
        );

        if($validator->fails())
            return false;

        return true;
    }
}