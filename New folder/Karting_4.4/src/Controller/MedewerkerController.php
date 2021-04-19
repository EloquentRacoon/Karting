<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MedewerkerController
 * @package App\Controller
 * @IsGranted("ROLE_ADMIN")
 */
class MedewerkerController extends AbstractController
{

    /**
     * @Route("/medewerker", name="medewerker")
     */
    public function index(): Response
    {
        return $this->render('medewerker/index.html.twig', [
            'controller_name' => 'MedewerkerController',
        ]);
    }
}
