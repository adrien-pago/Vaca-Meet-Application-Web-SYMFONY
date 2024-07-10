<?php
namespace App\EventSubscriber;

use App\Repository\PlanningRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CalendarSubscriber implements EventSubscriberInterface
{
    private $planningRepository;
    private $router;

    public function __construct(PlanningRepository $planningRepository, UrlGeneratorInterface $router)
    {
        $this->planningRepository = $planningRepository;
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar)
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();

        $events = $this->planningRepository
            ->createQueryBuilder('event')
            ->where('event.dateDebut BETWEEN :start AND :end OR event.dateFin BETWEEN :start AND :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getResult();

        foreach ($events as $event) {
            $calendarEvent = new Event(
                $event->getLibelleActivity(),
                $event->getDateDebut(),
                $event->getDateFin()
            );

            $calendarEvent->setOptions([
                'backgroundColor' => 'blue',
                'borderColor' => 'blue',
            ]);

            $calendarEvent->addOption(
                'url',
                $this->router->generate('event_show', ['id' => $event->getId()])
            );

            $calendar->addEvent($calendarEvent);
        }
    }
}
