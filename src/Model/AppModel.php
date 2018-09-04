<?php
namespace Agnes\Model;

use Agnes\Util\DBConnection;
use Agnes\Util\TableName;

class AppModel
{
    public static function findAll()
    {
        $db = DBConnection::getInstance();
        $table = TableName::getTableName(get_called_class());

        $stmt = "SELECT * FROM $table";
        $query = $db->query($stmt);

        $data = $query->fetchAll(\PDO::FETCH_CLASS, static::class);
        return $data;
    }

    public static function findById($id)
    {
        $db = DBConnection::getInstance();
        $table = TableName::getTableName(get_called_class());

        $stmt = "SELECT * FROM $table WHERE id = :id";
        $query = $db->prepare($stmt);
        $query->bindValue(':id', $id, \PDO::PARAM_INT);

        $query->execute();
        $data = $query->fetchObject(static::class);

        return $data;
    }

    public function insert($data = '')
    {
        $db = DBConnection::getInstance();
        $table = TableName::getTableName(get_called_class());

        $stmt = "INSERT INTO $table VALUES";
        $stmt .= "(";

        foreach ($data as $key => $value)
            $stmt .= ":$key, ";

        $stmt = substr($stmt, 0, -2);
        $stmt .= ")";

        $query = $db->prepare($stmt);

        foreach($data as $key => $value)
            $query->bindValue(":$key", $value, (is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR) );

        if ($query->execute() > 0)
            return $id = $db->lastInsertId();

        return false;
    }

    public static function deleteById($id)
    {
        $db = DBConnection::getInstance();
        $table = TableName::getTableName(get_called_class());

        $stmt = "DELETE FROM $table WHERE id = :id";
        $query = $db->prepare($stmt);
        $query->bindValue(':id', $id, \PDO::PARAM_INT);

        if ($query->execute() == 0)
            return false;

        return true;

    }

    // public function multiInsert($data)
    // {
    //     $db = DBConnection::getInstance();
    //
    //     $db->beginTransaction();
    //
    //     $table = TableName::getTableName(get_called_class());
    //
    //     $stmt = "INSERT INTO $table(`category_id`, `file`) VALUES (:category_id, :file)";
    //     $db->prepare($stmt);
    //     $db->bindValue(':category_id', $data['category_id'], \PDO::PARAM_INT);
    //     $db->bindParam(':file', $file, \PDO::PARAM_STR);
    //
    //     foreach ($data['files'] as $file)
    //     {
    //         $db->execute();
    //     }
    // }
}


?>
