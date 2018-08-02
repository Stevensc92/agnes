<?php
namespace Agnes\Model;

use Agnes\Util\DBConnection;

class EventsModel extends AppModel
{
  private $id;
  private $id_user;
  private $title;
  private $description;
  private $start;
  private $end;

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
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of Start
     *
     * @return mixed
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set the value of Start
     *
     * @param mixed start
     *
     * @return self
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get the value of End
     *
     * @return mixed
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set the value of End
     *
     * @param mixed end
     *
     * @return self
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * @param \DateTime $start
     * @param \DateTime $end
     * @return mixed
     */
    public function getEventsBetween(\DateTime $start, \DateTime $end)
    {
      $db = DBConnection::getInstance();

      $stmt = "SELECT
                *
              FROM
                events e
              WHERE
                start BETWEEN '{$start->format('Y-m-d 00:00:00')}' AND '{$end->format('Y-m-d 23:59:59')}'";

      $query = $db->prepare($stmt);
      $query->bindValue(':startTime', $start->format('Y-m-d 00:00:00'));
      $query->bindValue(':endTime', $end->format('Y-m-d 23:59:59'));
      $query->execute();
      return $query->fetchObject(static::class);
    }

    public function getEventsBetweenByDay(\DateTime $start, \DateTime $end) {
        $events[] = $this->getEventsBetween($start, $end);
        $days = [];

        foreach ($events as $event) {
            $date = explode(' ', $event->getStart())[0];
            if (!isset($days[$date])) {
                $days[$date] = [$event];
            } else {
                $days[$date][] = $event;
            }
        }
        return $days;
    }

}
?>
