<?php
namespace Pcs\Common\File;

class File
{
    public static function fileTypeFilter($type, array $files)
    {
        if (!is_array($files)) {
            throw new \Exception('argument type error');
        }

        foreach ($files as $key => $value) {
            if (!self::isType($type,$value)) {
                unset($files[$key]);
            }
        }

        return $files;
    }

    public static function isType($type, $file)
    {
        if (preg_match("/\.$type$/", $file)) {
            return true;
        } else {
            return false;
        }
    }
}