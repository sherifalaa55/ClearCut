<?php

namespace SherifAI\ClearCut\Factories;

class DumperFactory
{
    public static function getDumper($db_type)
    {
        switch ($db_type) {
            case 'mysql':
                return \Spatie\DbDumper\Databases\MySql::create();
                break;
            case 'mysql':
                return \Spatie\DbDumper\Databases\PostgreSql::create();
                break;
            case 'mysql':
                return \Spatie\DbDumper\Databases\Sqlite::create();
                break;
            case 'mysql':
                return \Spatie\DbDumper\Databases\MongoDb::create();
                break;
            default:
                throw new \Exception("Unsupported Db Type: " . $db_type, 1);
                break;
        }
    }
}
