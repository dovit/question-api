<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Question;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class QuestionSubscriber implements EventSubscriberInterface
{
    private $tokenStorage;

    /**
     * QuestionSubscriber constructor.
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['createQuestion', EventPriorities::PRE_WRITE],
        ];
    }

    public function createQuestion(GetResponseForControllerResultEvent $event)
    {
        $question = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$question instanceof Question || Request::METHOD_POST !== $method) {
            return;
        }

        $question->owner = $this->tokenStorage->getToken()->getUser();

        $event->setControllerResult($question);
    }
}
