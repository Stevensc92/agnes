<?php

namespace Agnes\Model;

use Agnes\Util\DBConnection;

class CategoryModel extends AppModel
{
    private $id;
    private $name;

    /**
     * Get the value of Id
     *
     * @return mixed
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the value of Name
     *
     * @return mixed
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the value of Name
     *
     * @param mixed name
     *
     * @return self
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    public static function findOneByName(string $name)
    {
        $db = DBConnection::getInstance();

        $stmt = "SELECT
                    c.id,
                    c.name
                FROM
                    category c
                WHERE
                    c.name = :name";
        $query = $db->prepare($stmt);
        $query->bindValue(':name', $name, \PDO::PARAM_STR);

        if ($query->execute())
            return $data = $query->fetchObject(static::class);
    }

}
