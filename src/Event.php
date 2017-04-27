<?php

namespace Bluora\LaravelIcs;

use Eluceo\iCal\Component\Calendar as Calendar;
use Eluceo\iCal\Component\Event as CalendarEvent;

class Event extends CalendarEvent
{
    /**
     * Render event.
     *
     * @return string
     */
    public function render($url)
    {
        return (new Calendar($url))
            ->addComponent($this)
            ->render();
    }

    /**
     * Download the event ics.
     * @param  [type] $url      [description]
     * @param  string $filename [description]
     * @return [type]           [description]
     */
    public function download($url, $filename = 'ical')
    {
        header('Content-Type: text/calendar; charset=utf-8');
        header('Content-Disposition: attachment; filename="'.$filename.'.ics"');
        echo $this->render($url);
        exit();
    }
}
