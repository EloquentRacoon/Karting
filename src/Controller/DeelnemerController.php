<?php

namespace App\Controller;

use App\Entity\Activiteit;
use App\Entity\User;
use App\Form\UserEditType;
use App\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class DeelnemerController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 */
class DeelnemerController extends AbstractController
{

    /**
     * @Route("/user/activiteiten", name="activiteiten")
     */
    public function activiteitenAction()
    {
        $usr= $this->getUser();
        $datum = new \DateTime();
        $datum->format('H:i:s \O\n Y-m-d');

        $beschikbareActiviteiten=$this->getDoctrine()
            ->getRepository(Activiteit::class)
            ->getBeschikbareActiviteiten($usr->getId());

        $ingeschrevenActiviteiten=$this->getDoctrine()
            ->getRepository(Activiteit::class)
            ->getIngeschrevenActiviteiten($usr->getId());

        $totaal=$this->getDoctrine()
            ->getRepository(Activiteit::class)
            ->getTotaal($ingeschrevenActiviteiten);

        foreach($beschikbareActiviteiten as $a){
            if ($a->getInschrijvingen() == null){

                $a->setInschrijvingen(0);
            }
        }


        return $this->render('deelnemer/activiteiten.html.twig', [
            'beschikbare_activiteiten'=>$beschikbareActiviteiten,
            'ingeschreven_activiteiten'=>$ingeschrevenActiviteiten,
            'totaal'=>$totaal,
            'datum' =>$datum,
        ]);
    }

    /**
     * @Route("/user/inschrijven/{id}", name="inschrijven")
     */
    public function inschrijvenActiviteitAction($id)
    {

        $activiteit = $this->getDoctrine()
            ->getRepository(Activiteit::class)
            ->find($id);
        $inschrijvingen = $activiteit->getInschrijvingen();
        $activiteit->setInschrijvingen($inschrijvingen + 1);
        $usr= $this->get('security.token_storage')->getToken()->getUser();
        $usr->addActiviteiten($activiteit);

        $em = $this->getDoctrine()->getManager();
        $em->persist($usr);
        $em->flush();

        return $this->redirectToRoute('activiteiten');
    }

    /**
     * @Route("/user/uitschrijven/{id}", name="uitschrijven")
     */
    public function uitschrijvenActiviteitAction($id)
    {
        $activiteit = $this->getDoctrine()
            ->getRepository(Activiteit::class)
            ->find($id);
        $inschrijvingen = $activiteit->getInschrijvingen();
        $activiteit->setInschrijvingen($inschrijvingen - 1);
        $usr= $this->get('security.token_storage')->getToken()->getUser();
        $usr->removeActiviteiten($activiteit);
        $em = $this->getDoctrine()->getManager();
        $em->persist($usr);
        $em->flush();
        return $this->redirectToRoute('activiteiten');
    }

    /**
     * @Route("/user/gegevens/", name="gegevens")
     */
    public function gegevens()
    {
        $user = $this->getUser();


        return $this->render('deelnemer/gegevens.html.twig', [
            "user" => $user

        ]);
    }
    /**
     * @Route("/user/gegevens/edit/{id}", name="edit_user")
     */
    public function editUser(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();
            $repository=$this->getDoctrine()->getRepository(User::class);
            $bestaande_user=$repository->findOneBy(['username'=>$form->getData()->getUsername()]);

            if($bestaande_user==null){
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
            }
            return $this->redirectToRoute('gegevens');
        }

        return $this->render('deelnemer/gegevens_edit.html.twig', [
            'form'=>$form->createView()
        ]);
    }
    /**
     * @Route("/user/gegevens/reset/{id}", name="reset_wachtwoord")
     */
    public function resetWachtwoord($id ,UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $user->setPassword($passwordEncoder->encodePassword($user,"qwerty"));
        $em->persist($user);
        $em->flush();

        $this->addFlash(
            'notice',
            'Wachtwoord reset!'
        );

        return $this->redirectToRoute("gegevens");
    }
}
