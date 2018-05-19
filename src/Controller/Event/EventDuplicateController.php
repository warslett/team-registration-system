<?php

namespace App\Controller\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EventDuplicateController
{

    /**
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        // TODO: Implement Event Duplicate
        return new Response("Unimplemented");
    }
}
