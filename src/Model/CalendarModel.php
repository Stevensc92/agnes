<?php

namespace Agnes\Model;

use Agnes\Util\DBConnection;

class CalendarModel extends AppModel
{
    private $id;
    private $id_user;
    private $title;
    private $date;

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
    public function setIdUser($id_user)
    {
        $this->id_user = $id_user;

        return $this;
    }

    /**
     * Get the value of Title
     *
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of Title
     *
     * @param mixed title
     *
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of Date
     *
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set the value of Date
     *
     * @param mixed date
     *
     * @return self
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    public function getEvents($year)
    {
        $db = DBConnection::getInstance();

        $stmt = "SELECT
                    e.id,
                    e.title,
                    e.date
                FROM
                    events e
                WHERE
                    YEAR(e.date) = :year";
        $query = $db->prepare($stmt);
        $query->bindValue(':year', $year, \PDO::PARAM_INT);
        $query->execute();

        $r = array();
        while ($data = $query->fetchObject(static::class)) {
            $r[strtotime($data->date)][$data->id] = $data->title;
        }

        return $r;
    }

    public function getAll($year)
    {
        $r = array();

        $date = new \DateTime($year.'-01-01');
        while ($date->format('Y') <= $year) {
            $y = $date->format('Y');
            $m = $date->format('n');
            $d = $date->format('j');
            $w = str_replace('0', '7', $date->format('w'));
            $r[$y][$m][$d] = $w;
            $date->add(new \DateInterval('P1D'));
        }
        return $r;
    }

}
