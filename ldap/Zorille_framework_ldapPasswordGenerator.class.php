<?php

namespace Zorille\framework;

class ldapPasswordGenerator
{
    /*********************** Creation de l'objet *********************/

    public static function get_random(int $nbChars = 8): string {
        $availableChars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890_-&@?!~';
        rand(0, strlen($availableChars) - 1);
        $final = '';
        do {
            $cmp = isset($cmp) ? $cmp + 1 : 0;
            $final .= $availableChars[rand(0, strlen($availableChars) - 1)];
        }
        while ($cmp < $nbChars - 1);
        return $final;
    }
}