<?php

namespace Agnes\Model;

use Agnes\Util\DBConnection;
use Agnes\Util\TableName;
use Agnes\Model\CategoryModel;

class PictureModel extends AppModel
{
    private $id;
    private $id_category;
    private $filename;
    private $extension;
    private $description;
    private $width;
    private $height;


    // public function __construct($id = null)
    // {
    //     if ($id !== null)
    //     {
    //         $picture = $this->findById($id);
    //     }
    // }
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
     * Get the value of Category Id
     *
     * @return mixed
     */
    public function getIdCategory(): int
    {
        return $this->id_category;
    }

    /**
     * Set the value of Category Id
     *
     * @param mixed id_category
     *
     * @return self
     */
    public function setIdCategory(CategoryModel $category)
    {
        $this->id_category = $category->getId();

        return $this;
    }

    /**
     * Get the value of filename
     *
     * @return mixed
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * Set the value of filename
     *
     * @param mixed filename
     *
     * @return self
     */
    public function setFilename(string $filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get the value of Description
     *
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of Description
     *
     * @param mixed description
     *
     * @return self
     */
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of Width
     *
     * @return mixed
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set the value of width
     *
     * @param mixed width
     *
     * @return self
     */
    public function setWidth(int $width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get the value of Height
     *
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set the value of Height
     *
     * @param mixed height
     *
     * @return self
     */
    public function setHeight(int $height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get the value of Extension
     *
     * @return mixed
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set the value of Extension
     *
     * @param mixed extension
     *
     * @return self
     */
    public function setExtension(string $extension)
    {
        $this->extension = $extension;

        return $this;
    }

    public function insert($data = '')
    {
        $data = [];
        foreach(get_object_vars($this) as $key => $value)
            $data[$key] = $value;

        parent::insert($data);
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

    public static function findAll($order = '')
    {
        $db = DBConnection::getInstance();

        $stmt = "SELECT
                    p.id,
                    p.id_category,
                    p.filename,
                    p.extension,
                    p.description,
                    p.width,
                    p.height
                FROM picture p";

        if (!empty(trim($order)))
            $stmt .= " ORDER BY $order";

        $query = $db->query($stmt);

        return $data = $query->fetchAll(\PDO::FETCH_CLASS, static::class);
    }

    public static function findOneById(int $id)
    {
        $db = DBConnection::getInstance();

        $stmt= "SELECT
                    p.id,
                    p.filename,
                    p.extension,
                    p.description,
                    p.width,
                    p.height,

                    c.name as category_name
                FROM
                    picture p
                LEFT JOIN
                    category c ON p.id_category = c.id
                WHERE
                    p.id = :id";

        $query = $db->prepare($stmt);
        $query->bindValue(':id', $id, \PDO::PARAM_INT);

        if ($query->execute())
            return $data = $query->fetchObject(static::class);
    }

    public static function findAllWithCategory()
    {
        $db = DBConnection::getInstance();

        $stmt = "SELECT
                    p.id,
                    p.filename,
                    p.extension,
                    p.description,

                    c.name as category_name
                FROM picture p
                LEFT JOIN category c ON p.id_category = c.id";


        $query = $db->query($stmt);

        return $data = $query->fetchAll(\PDO::FETCH_CLASS, static::class);
    }

    public static function findByCategory($id_category)
    {
        $db = DBConnection::getInstance();

        $stmt = "SELECT
                    p.id,
                    p.filename,
                    p.extension,
                    p.description
                FROM
                    picture p
                WHERE
                    p.id_category = :id_category";

        $query = $db->prepare($stmt);
        $query->bindValue(':id_category', $id_category, \PDO::PARAM_INT);
        if ($query->execute())
            return $data = $query->fetchAll(\PDO::FETCH_CLASS, static::class);
    }
}
