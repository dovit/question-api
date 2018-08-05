<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class UserSubscriber implements EventSubscriberInterface
{
    private $tokenStorage;
    private $entityManager;

    /**
     * QuestionSubscriber constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(TokenStorageInterface $tokenStorage,
                                EntityManagerInterface $entityManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setLimit', 60],
        ];
    }

    public function setLimit(GetResponseForControllerResultEvent $event)
    {
        /**
         * @var User
         */
        $user = $this->tokenStorage->getToken()->getUser();

        if ($user->getLimit() < $user->getNumberOfCall()) {
            throw new HttpException(Response::HTTP_TOO_MANY_REQUESTS, 'Limit of request');
        }

        $user->setNumberOfCall($user->getNumberOfCall() + 1);
        $this->entityManager->flush();
    }
}
