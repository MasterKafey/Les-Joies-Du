<?php

namespace App\EventListener\Response;

use Symfony\Component\HttpKernel\Event\ResponseEvent;

class NoIndexResponseListener
{
    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $response = $event->getResponse();
        $response->headers->set('X-Robots-Tag', 'noindex, nofollow');
    }
}
