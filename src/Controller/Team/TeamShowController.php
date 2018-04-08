<?php

namespace App\Controller\Team;

use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TeamShowController
{

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(\Twig_Environment $twig, EntityManagerInterface $entityManager)
    {
        $this->twig = $twig;
        $this->entityManager = $entityManager;
    }

    public function __invoke(Request $request)
    {
        $team = $this->entityManager->find(Team::class, $request->get('team_id'));
        return new Response($this->twig->render('team/show.html.twig', ['team' => $team]));
    }
}