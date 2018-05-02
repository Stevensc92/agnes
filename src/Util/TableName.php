<?php

namespace Agnes\Util;

class TableName
{
    public static function getTableName($className)
    {
        $table = str_replace('Agnes\\Model\\', '', $className);
        return strtolower(str_replace('Model', '', $table));
    }
}

?>
