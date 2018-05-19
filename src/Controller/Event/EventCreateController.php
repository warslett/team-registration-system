<?php

namespace App\Controller\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EventCreateController
{

    /**
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        // TODO: Implement Event Create
        return new Response("Unimplemented");
    }
}
