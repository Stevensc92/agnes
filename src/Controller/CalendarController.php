<?php

namespace Agnes\Controller;

use Agnes\Model\CalendarModel;
use Agnes\Util\Month;

class CalendarController extends AppController
{
    public function index(?array $params)
    {
        if (is_ajax()) {
            try {
                $month = new Month(@$_GET['month'], @$_GET['year']);
            } catch (\Exception $e) {
                $month = new Month();
            }
        } else {
            try {
                $month = new Month(@$params['month'], @$params['year']);
            } catch (\Exception $e) {
                $month = new Month();
            }
        }


        $data = [
            'month'     => $month,
            'weeks'     => $month->getWeeks(),
            'start'     => $month->getFirstDay()->modify('last monday'),
            'pMm'       => $month->previousMonth()->month,
            'pMy'       => $month->previousMonth()->year,
            'nMm'       => $month->nextMonth()->month,
            'nMy'       => $month->nextMonth()->year,
        ];

        if (is_ajax()) {
            $response['title'] = $this->twig->render('calendar/title.html.twig', $data);
            $response['table'] = $this->twig->render('calendar/table.html.twig', $data);
            echo json_encode($response);
        } else {
            echo $this->twig->render('calendar/index.html.twig', $data);
        }
        // $date = new CalendarModel();
        // $year = Date('Y');
        // echo $this->twig->render('calendar/index.html.twig', array(
        //     'date' => $date,
        //     'year' => $year,
        //     'events' => $date->getEvents($year),
        //     'dates' => $date->getAll($year),
        // ));

    }
}
?>
