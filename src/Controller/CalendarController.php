<?php

namespace Agnes\Controller;

use Agnes\Model\CalendarModel;
use Agnes\Model\EventsModel;
use Agnes\Util\Month;

class CalendarController extends AppController
{
  /**
   * @Route('/calendar/?[i:month]/?[i:year]', name="indexCalendar")
   * @Method('[GET|POST]')
   */
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

        $start = $month->getFirstDay();
        $start = $start->format('N') === '1' ? $start : $month->getFirstDay()->modify('last monday');

        $weeks = $month->getWeeks();
        $end = (clone $start)->modify('+' .(6 + 7 * ($weeks -1) ).' days');

        $events = new EventsModel();
        $events = $events->getEventsBetween($start, $end);

        $previousMonth  = $month->previousMonth()->month;
        $previousYear   = $month->previousMonth()->year;
        $nextMonth      = $month->nextMonth()->month;
        $nextYear       = $month->nextMonth()->year;

        $data = [
            'month'     => $month,
            'weeks'     => $weeks,
            'start'     => $start,
            'end'       => $end,
            'pMm'       => $previousMonth,
            'pMy'       => $previousYear,
            'nMm'       => $nextMonth,
            'nMy'       => $nextYear,
            'events'    => $events,
        ];

        if (is_ajax()) {
            $response['title'] = $this->twig->render('calendar/title.html.twig', $data);
            $response['table'] = $this->twig->render('calendar/table.html.twig', $data);
            echo json_encode($response);
        } else {
            echo $this->twig->render('calendar/index.html.twig', $data);
        }

    }
}
?>
