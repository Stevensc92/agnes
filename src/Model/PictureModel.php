<?php

namespace Agnes\Model;

use Agnes\Model\CategoryModel;

class PictureModel extends AppModel
{
    private $id;
    private $category_id;
    private $file;
    private $description;

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
    public function getCategoryId(): int
    {
        return $this->category_id;
    }

    /**
     * Set the value of Category Id
     *
     * @param mixed category_id
     *
     * @return self
     */
    public function setCategoryId(CategoryModel $category)
    {
        $this->category_id = $category->getId();

        return $this;
    }

    /**
     * Get the value of File
     *
     * @return mixed
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * Set the value of File
     *
     * @param mixed file
     *
     * @return self
     */
    public function setFile(string $file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get the value of Description
     *
     * @return mixed
     */
    public function getDescription(): string
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

    public function insert($data = '')
    {
        $data = [];
        foreach(get_object_vars($this) as $key => $value)
            $data[$key] = $value;

        parent::insert($data);
    }

}
