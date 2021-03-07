<?php

namespace AJ\Rest\Helpers;

class Headers{
    public static function convertHeaders($headers)
    {
        $result = [];
        foreach ($headers as $key => $values){
            foreach ($values as $value){
                $result[] = $key . ': ' . $value;
            }
        }

        return $result;
    }
}