<?php

namespace App\Application\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

class KernelViewListener
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(GetResponseForControllerResultEvent $event)
    {
        $this->em->flush();

        // send json with required status code
//        if (($configuration = $event->getRequest()->attributes->get('_template')) instanceof ViewAnnotation) {
//            $code = (int) $configuration->getStatusCode();
//            $code = $code ? $code : null;
//        }

        $event->setResponse(
            new JsonResponse(
                $result = (array) $event->getControllerResult(),
                $code ?? ($result === [] ? 204 : 200)
            )
        );
    }
}
