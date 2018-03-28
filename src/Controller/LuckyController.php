<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class LuckyController
{

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * LuckyController constructor.
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function number()
    {
        $number = mt_rand(0, 100);

        return new Response(
            $this->twig->render('lucky/number.html.twig', ['number' => $number])
        );
    }
}
