<?php

namespace Agnes\Model;

use Agnes\Util\DBConnection;
use Agnes\Model\PictureModel;
use Agnes\Model\UserModel;

class CommentModel extends AppModel
{
    private $id;
    private $id_user;
    private $id_picture;
    private $content;
    private $createdAt;

    /**
     * Get the value of Id
     *
     * @return mixed
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
     * Get the value of Id User
     *
     * @return mixed
     */
    public function getIdUser()
    {
        return $this->id_user;
    }

    /**
     * Set the value of Id User
     *
     * @param mixed id_user
     *
     * @return self
     */
    public function setIdUser(UserModel $user)
    {
        $this->id_user = $user->getId();

        return $this;
    }

    /**
     * Get the value of Id Picture
     *
     * @return mixed
     */
    public function getIdPicture()
    {
        return $this->id_picture;
    }

    /**
     * Set the value of Id User
     *
     * @param mixed id_user
     *
     * @return self
     */
    public function setCreatedAt($date)
    {
        $this->createdAt = $date;

        return $this;
    }

    /**
     * Get the value of Id Picture
     *
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set the value of Id Picture
     *
     * @param mixed id_picture
     *
     * @return self
     */
    public function setIdPicture(PictureModel $picture)
    {
        $this->id_picture = $picture->getId();

        return $this;
    }

    /**
     * Get the value of Content
     *
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the value of Content
     *
     * @param mixed content
     *
     * @return self
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public static function getAllByPictureId($id)
    {
        $db = DBConnection::getInstance();

        $stmt = "SELECT
                    com.id,
                    com.content,
                    com.createdAt,

                    u.username
                FROM
                    comment com
                LEFT JOIN
                    user u ON u.id = com.id_user
                WHERE
                    com.id_picture = :id_picture
                ORDER BY
                    com.createdAt DESC";
        $query = $db->prepare($stmt);
        $query->bindValue(':id_picture', $id, \PDO::PARAM_INT);

        if($query->execute()) {
            return $data = $query->fetchAll(\PDO::FETCH_CLASS, static::class);
        }
    }

    public static function getAllByPictureIdLimit($id_picture, $first_com, $commentPerPage)
    {
        $db = DBConnection::getInstance();

        $stmt = "SELECT
                    com.id,
                    com.content,
                    com.createdAt,

                    u.username
                FROM
                    comment com
                LEFT JOIN
                    user u ON u.id = com.id_user
                WHERE
                    com.id_picture = :id_picture
                ORDER BY
                    com.createdAt DESC
                LIMIT
                    :first_com, :commentPerPage";

        $query = $db->prepare($stmt);
        $query->bindValue(':id_picture', $id_picture, \PDO::PARAM_INT);
        $query->bindValue(':first_com',(int) $first_com, \PDO::PARAM_INT);
        $query->bindValue(':commentPerPage',(int) $commentPerPage, \PDO::PARAM_INT);
        if ($query->execute())
            return $query->fetchAll(\PDO::FETCH_CLASS, static::class);
    }

}
