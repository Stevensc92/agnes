<?php
namespace Agnes\Util;

class Month
{
    public $days = [
        'Lundi',
        'Mardi',
        'Mercredi',
        'Jeudi',
        'Vendredi',
        'Samedi',
        'Dimanche',
    ];

    private $months = [
        'Janvier',
        'Février',
        'Mars',
        'Avril',
        'Mai',
        'Juin',
        'Juillet',
        'Aout',
        'Septembre',
        'Octobre',
        'Novembre',
        'Décembre',
    ];
    public $month;
    public $year;

    /**
     * Month construct
     * @param int $month [Beetwen 1 and 12]
     * @param int $year
     */
    public function __construct(?int $month = null , ?int $year = null)
    {
        if ($month === null || $month < 1 || $month > 12) {
            $month = intval(date('m'));
        }
        if ($year === null) {
            $year = intval(date('Y'));
        }

        if ($month < 1 || $month > 12) {
            throw new \Exception("Le mois $month n'est pas valide");
        }

        if ($year < 1970) {
            throw new \Exception("L'année est inférieur à 1970");
        }

        $this->month = $month;
        $this->year = $year;
    }

    /**
     * Return the first day of month
     * @return DateTime
     */
    public function getFirstDay(): \DateTime {
        return new \DateTime("{$this->year}-{$this->month}-01");
    }

    /**
     * Return month in fully letters with year
     * @return string [description]
     */
    public function toString(): string {
        return $this->months[$this->month - 1] . ' '.$this->year;
    }

    public function getWeeks(): int {
        $start = $this->getFirstDay();
        $end = (clone $start)->modify('+1 month -1 day');
        $weeks = intval($end->format('W')) - intval($start->format('W'));

        if ($weeks < 0) {
            $weeks = intval($end->format('W'));
        }

        return $weeks;
    }

    public function withinMonths(\DateTime $date): bool {
        return $this->getFirstDay()->format('Y-m') === $date->format('Y-m');
    }

    public function nextMonth(): Month
    {
        $month = $this->month + 1;
        $year = $this->year;

        if ($month > 12) {
            $month = 1;
            $year += 1;
        }
        return new Month($month, $year);
    }

    public function previousMonth(): Month
    {
        $month = $this->month - 1;
        $year = $this->year;

        if ($month < 1) {
            $month = 12;
            $year -= 1;
        }
        return new Month($month, $year);
    }
}
