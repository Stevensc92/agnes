<?php
namespace Agnes\Model;

use Agnes\Util\DBConnection;

class AppModel
{
    // protected $db;
    //
    // public function __construct()
    // {
    //     $this->db = DBConnection::getInstance();
    // }

    public static function findAll(): array
    {
        $db = DBConnection::getInstance();

        $table = str_replace('Agnes\\Model\\', '', get_called_class());
        $table = strtolower(str_replace('Model', '', $table));

        $stmt = "SELECT * FROM $table";
        $query = $db->query($stmt);

        $data = $query->fetchAll(\PDO::FETCH_CLASS, static::class);
        return $data;
    }

    public static function findById($id)
    {

    }
}


?>
