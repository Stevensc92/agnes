<?php
namespace Agnes\Model;

use Agnes\Util\DBConnection;
use Agnes\Util\TableName;

class ExcursionsModel extends AppModel
{
    private $id;
    private $title;
    private $price;
    private $description;

    /**
     * Get the value of Id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of Id
     *
     * @param mixed id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param int $price
     * @return $this
     */
    public function setPrice(int $price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    public function add(): bool
    {
        $db = DBConnection::getInstance();

        $stmt = "
            INSERT INTO excursions(`title`, `price`, `description`)
            VALUES(:title, :price, :description)
        ";
        $query = $db->prepare($stmt);
        $query->bindValue(':title',             $this->getTitle(),          \PDO::PARAM_STR);
        $query->bindValue(':price',             $this->getPrice(),          \PDO::PARAM_STR);
        $query->bindValue(':description',       $this->getDescription(),    \PDO::PARAM_STR);

//        var_dump($stmt);
//        die();

        $query->execute();

        if ($query)
            return true;

        return false;
    }

    public function update(array $data, $id)
    {
        $db = DBConnection::getInstance();
        $table = TableName::getTableName(get_called_class());

        $stmt = "UPDATE $table SET ";

        $counter = 0;
        foreach ($data as $key => $value)
        {
            if ($counter != 0)
                $stmt .= ', ';

            $stmt .= "$key = ".$db->quote($value);
            $counter++;
        }

        $stmt .= " WHERE id = ".$db->quote($id);

        // print_r($stmt);
        $query = $db->query($stmt);

        if ($query->rowCount() > 0)
            return true;

        return false;
    }
}
?>
