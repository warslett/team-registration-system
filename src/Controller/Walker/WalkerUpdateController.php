<?php

namespace App\Controller\Walker;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WalkerUpdateController
{

    /**
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        // TODO: Implement Walker Update
        return new Response("Unimplemented");
    }
}
