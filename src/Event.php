<?php

namespace Bluora\LaravelIcs;

use Eluceo\iCal\Component\Event as CalendarEvent;

class Event extends CalendarEvent
{
    private $calendar;

    const METHOD_PUBLISH = Calendar::METHOD_PUBLISH;
    const METHOD_REQUEST = Calendar::METHOD_REQUEST;
    const METHOD_REPLY = Calendar::METHOD_REPLY;
    const METHOD_ADD = Calendar::METHOD_ADD;
    const METHOD_CANCEL = Calendar::METHOD_CANCEL;
    const METHOD_REFRESH = Calendar::METHOD_REFRESH;
    const METHOD_COUNTER = Calendar::METHOD_COUNTER;
    const METHOD_DECLINECOUNTER = Calendar::METHOD_DECLINECOUNTER;

    /**
     * Set the product_id.
     *
     * @param string|null $product_id
     * @param string|null $unique_id
     *
     * @return Event
     */
    public function __construct(string $product_id = null, string $unique_id = null)
    {
        parent::__construct($unique_id);
        $this->calendar = new Calendar($product_id);
        $this->calendar->setCalendarScale(Calendar::CALSCALE_GREGORIAN);
        $this->setPublishMethod();

        return $this;
    }

    /**
     * Add attendee.
     *
     * @param string $email_address
     * @param string $name
     * @param array  $paramaters
     *
     * @return Event
     */
    public function addAttendee($email_address, $name = '', $paramaters = [])
    {
        $paramaters['mailto'] = $email_address;

        if (!array_has($paramaters, 'CUTYPE')) {
            $paramaters['CUTYPE'] = 'INDIVIDUAL';
        }

        if (!array_has($paramaters, 'ROLE')) {
            $paramaters['ROLE'] = 'REQ-PARTICIPANT';
        }

        if (!array_has($paramaters, 'PARTSTAT')) {
            $paramaters['PARTSTAT'] = 'NEEDS-ACTION';
        }

        if (!array_has($paramaters, 'RSVP')) {
            $paramaters['RSVP'] = 'TRUE';
        }

        if (empty($name)) {
            $name = $email_address;
        }

        parent::addAttendee('CN='.$name, $paramaters);

        return $this;
    }

    /**
     * Set organizer.
     *
     * @param string $email_address
     * @param string $name
     * @param array  $paramaters
     *
     * @return Event
     */
    public function addOrganizer($email_address, $name = '', $paramaters = [])
    {
        $paramaters['mailto'] = $email_address;

        if (empty($name)) {
            $name = $email_address;
        }

        parent::setOrganizer(new Organizer('CN='.$name, $paramaters));

        return $this;
    }

    /**
     * Set a calendar property.
     *
     * @return Event
     */
    public function setCalendar($method, $value)
    {
        $this->calendar->{'set'.$method}($value);

        return $this;
    }

    /**
     * Set the method to publish.
     *
     * @return Event
     */
    public function setPublishMethod()
    {
        return $this->setCalendar('method', Calendar::METHOD_PUBLISH);
    }

    /**
     * Set the method to request.
     *
     * @return Event
     */
    public function setRequestMethod()
    {
        return $this->setCalendar('method', Calendar::METHOD_REQUEST);
    }

    /**
     * Set the method to reply.
     *
     * @return Event
     */
    public function setReplyMethod()
    {
        return $this->setCalendar('method', Calendar::METHOD_REPLY);
    }

    /**
     * Set the method to add.
     *
     * @return Event
     */
    public function setAddMethod()
    {
        return $this->setCalendar('method', Calendar::METHOD_ADD);
    }

    /**
     * Set the method to cancel.
     *
     * @return Event
     */
    public function setCancelMethod()
    {
        return $this->setCalendar('method', Calendar::METHOD_CANCEL);
    }

    /**
     * Set the method to refresh.
     *
     * @return Event
     */
    public function setRefreshMethod()
    {
        return $this->setCalendar('method', Calendar::METHOD_REFRESH);
    }

    /**
     * Set the method to counter.
     *
     * @return Event
     */
    public function setCounterMethod()
    {
        return $this->setCalendar('method', Calendar::METHOD_COUNTER);
    }

    /**
     * Set the method to counter.
     *
     * @return Event
     */
    public function setDeclineCounterMethod()
    {
        return $this->setCalendar('method', Calendar::METHOD_DECLINECOUNTER);
    }

    /**
     * Render event.
     *
     * @return string
     */
    public function __toString()
    {
        $this->calendar->addEvent($this);

        return $this->calendar->render();
    }

    /**
     * Download the event ics.
     *
     * @param string $product_id
     * @param string $filename
     *
     * @return string
     */
    public function download($filename = 'ical')
    {
        header('Content-Type: text/calendar; charset=utf-8');
        header('Content-Disposition: attachment; filename="'.$filename.'.ics"');
        echo (string) $this;
        exit();
    }
}
