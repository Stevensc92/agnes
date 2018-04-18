<?php

namespace Agnes\Config;

class Config
{
    public static function getConfig(): array
    {
        return([
            'db_host' => 'db_host',
            'db_user' => 'db_user',
            'db_pass' => 'db_pass',
            'db_name' => 'db_name',
        ]);
    }
}
?>
