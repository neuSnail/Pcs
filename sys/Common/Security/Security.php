<?php
namespace Pcs\Common\Security;

class Security
{
    public static function xssFilter($string)
    {
        $string = trim($string);
        $string = strip_tags($string);
        $string = htmlspecialchars($string);
        return $string;
    }

    public static function sqlFilter($sql)
    {
        //$sql = trim($sql);
        return $sql;
    }
}