<?php

namespace Agnes\Model;

use Agnes\Util\DBConnection;

class UserModel extends AppModel
{
    private $id;
    private $username;
    private $password;
    private $email;
    private $isActive;
    private $role;

    public function __construct()
    {
        $this->setIsActive(1)->setRole('ROLE_USER');
    }

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
     * Get the value of Username
     *
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of Username
     *
     * @param mixed username
     *
     * @return self
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of Password
     *
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of Password
     *
     * @param mixed password
     *
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of Email
     *
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of Email
     *
     * @param mixed email
     *
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of Is Active
     *
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set the value of Is Active
     *
     * @param mixed isActive
     *
     * @return self
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get the value of Role
     *
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set the value of Role
     *
     * @param mixed role
     *
     * @return self
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    public static function findByUsername($username)
    {
        $db = DBConnection::getInstance();

        $stmt = "SELECT
                    u.id,
                    u.username,
                    u.password,
                    u.email,
                    u.isActive,
                    u.role
                FROM
                    user u
                WHERE
                    u.username = :username";
        $query = $db->prepare($stmt);
        $query->bindValue(':username', $username, \PDO::PARAM_STR);
        $query->execute();

        return $query->fetchObject(static::class);
    }

    public function add(): bool
    {
        $db = DBConnection::getInstance();

        $stmt = "
            INSERT INTO user(username, password, email, isActive, role)
            VALUES(:username, :password, :email, :isActive, :role)
        ";
        $query = $db->prepare($stmt);
        $query->bindValue(':username',  $this->getUsername(),   \PDO::PARAM_STR);
        $query->bindValue(':password',  $this->getPassword(),   \PDO::PARAM_STR);
        $query->bindValue(':email',     $this->getEmail(),      \PDO::PARAM_STR);
        $query->bindValue(':isActive',  $this->getIsActive(),   \PDO::PARAM_INT);
        $query->bindValue(':role',      $this->getRole(),       \PDO::PARAM_STR);

        $query->execute();

        if ($query)
            return true;

        return false;
    }
}
?>
