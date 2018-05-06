<?php

namespace App\Controller\Walker;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WalkerDeleteController
{

    /**
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        // TODO: Implement Walker Delete
        return new Response("Unimplemented");
    }
}
