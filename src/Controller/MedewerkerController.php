<?php

namespace App\Controller;

use App\Entity\Activiteit;
use App\Entity\Soortactiviteit;
use App\Entity\User;
use App\Form\ActiviteitType;
use App\Form\SoortactiviteitType;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class MedewerkerController
 * @package App\Controller
 * @IsGranted("ROLE_ADMIN")
 */
class MedewerkerController extends AbstractController
{

    /**
     * @Route("/admin/activiteiten", name="activiteitenoverzicht")
     */
    public function activiteitenOverzichtAction()
    {

        $activiteiten = $this->getDoctrine()
            ->getRepository(Activiteit::Class)
            ->findAll();

        return $this->render('medewerker/activiteiten.html.twig', [
            'activiteiten' => $activiteiten
        ]);
    }

    /**
     * @Route("/admin/details/{id}", name="details")
     */
    public function detailsAction($id)
    {
        $activiteiten = $this->getDoctrine()
            ->getRepository(Activiteit::Class)
            ->findAll();
        $activiteit = $this->getDoctrine()
            ->getRepository(Activiteit::Class)
            ->find($id);

        $deelnemers = $this->getDoctrine()
            ->getRepository(User::Class)
            ->getDeelnemers($id);


        return $this->render('medewerker/details.html.twig', [
            'activiteit' => $activiteit,
            'deelnemers' => $deelnemers,
            'aantal' => count($activiteiten)
        ]);
    }

    /**
     * @Route("/admin/beheer", name="beheer")
     */
    public function beheerAction()
    {
        $activiteiten = $this->getDoctrine()
            ->getRepository(Activiteit::Class)
            ->findAll();

        return $this->render('medewerker/beheer_dashboard.html.twig', [
            'activiteiten' => $activiteiten
        ]);
    }

    /**
     * @Route("/admin/beheer/activiteit", name="beheer_activiteit")
     */
    public function beheerActiviteitenAction()
    {
        $activiteiten = $this->getDoctrine()
            ->getRepository(Activiteit::Class)
            ->findAll();

        return $this->render('medewerker/beheer_activiteiten.html.twig', [
            'activiteiten' => $activiteiten
        ]);
    }

    /**
     * @Route("/admin/add/activiteit", name="add_activiteit")
     */
    public function addActiviteitAction(Request $request)
    {
        $form = $this->createForm(ActiviteitType::class);
        $form->add('save', SubmitType::class, array('label' => "voeg toe"));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();

            $this->addFlash(
                'notice',
                'activiteit toegevoegd!'
            );
            return $this->redirectToRoute('beheer_activiteit');
        }
        $activiteiten = $this->getDoctrine()
            ->getRepository(Activiteit::Class)
            ->findAll();

        return $this->render('medewerker/add.html.twig', [
            'form' => $form->createView(),
            'actie' => 'Soortactiviteit toevoegen',
        ]);
    }

    /**
     * @Route("/admin/update/activiteit/{id}", name="update_activiteit")
     */
    public function updateActiviteitAction($id, Request $request)
    {
        $activiteit = $this->getDoctrine()
            ->getRepository(Activiteit::Class)
            ->find($id);

        $form = $this->createForm(ActiviteitType::class, $activiteit);
        $form->add('save', SubmitType::class, array('label' => "aanpassen"));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            // tells Doctrine you want to (eventually) save the contact (no queries yet)
            $em->persist($activiteit);


            // actually executes the queries (i.e. the INSERT query)
            $em->flush();
            $this->addFlash(
                'notice',
                'activiteit aangepast!'
            );
            return $this->redirectToRoute('beheer_activiteit');
        }

        $activiteiten = $this->getDoctrine()
            ->getRepository(Activiteit::Class)
            ->findAll();

        return $this->render('medewerker/add.html.twig',[
            'form' => $form->createView(),
            'actie' => 'Soortactiviteit toevoegen',
            ]);
    }

    /**
     * @Route("/admin/delete/activiteit/{id}", name="delete_activiteit")
     */
    public function deleteActiviteitAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $activiteit = $this->getDoctrine()
            ->getRepository(Activiteit::Class)->find($id);
        $em->remove($activiteit);
        $em->flush();

        $this->addFlash(
            'notice',
            'activiteit verwijderd!'
        );
        return $this->redirectToRoute('beheer_activiteit');
    }

    /**
     * @Route("/admin/beheer/soortactiviteit", name="beheer_soortactiviteit")
     */
    public function beheerSoortactiviteitenAction()
    {
        $soortactiviteiten = $this->getDoctrine()
            ->getRepository(Soortactiviteit::Class)
            ->findAll();

        return $this->render('medewerker/beheer_soortactiviteiten.html.twig', [
            'soortactiviteiten' => $soortactiviteiten
        ]);
    }

    /**
     * @Route("/admin/add/soortactiviteit", name="add_soortactiviteit")
     */
    public function addSoortactiviteitAction(Request $request)
    {

        $form = $this->createForm(SoortactiviteitType::class);
        $form->add('save', SubmitType::class, array('label' => "voeg toe"));
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $soortactiviteit = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($soortactiviteit);
            $em->flush();
            return $this->redirectToRoute('beheer_soortactiviteit');
        }
        return $this->render('medewerker/add.html.twig',[
            'actie' => 'Soortactiviteit toevoegen',
            'form'=>$form->createView(),
            ]);
    }

    /**
     * @Route("/admin/update/soortactiviteit/{id}", name="update_soortactiviteit")
     */
    public function updateSoortactiviteitAction($id, Request $request)
    {
        $soortactiviteit = $this->getDoctrine()
            ->getRepository(Soortactiviteit::Class)
            ->find($id);

        $form = $this->createForm(SoortactiviteitType::class, $soortactiviteit);
        $form->add('save', SubmitType::class, ['label' => "aanpassen"]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $em->persist($soortactiviteit);

            $em->flush();
            $this->addFlash(
                'notice',
                'soortactiviteit aangepast!'
            );
            return $this->redirectToRoute('beheer_soortactiviteit');
        }

        $activiteiten = $this->getDoctrine()
            ->getRepository(Soortactiviteit::Class)
            ->findAll();

        return $this->render('medewerker/add.html.twig', [
            'form' => $form->createView(),
            'actie' => 'Soortactiviteit aanpassen',
            ]);
    }
    /**
     * @Route("/admin/delete/soortactiviteit/{id}", name="delete_soortactiviteit")
     */
    public function deleteSoortactiviteitAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $soortactiviteit = $em->getRepository(Soortactiviteit::Class)->find($id);
        $em->remove($soortactiviteit);
        $em->flush();

        $this->addFlash(
            'notice',
            'activiteit verwijderd!'
        );
        return $this->redirectToRoute('beheer_soortactiviteit');
    }

    /**
     * @Route("/admin/deelnemers/", name="deelnemer_overzicht")
     */
    public function deelnemerOverzicht()
    {
        $em = $this->getDoctrine()->getManager();
        $deelnemers = $em->getRepository(User::class)->findByRoles("ROLE_USER");


        return $this->render('medewerker/deelnemers.html.twig',[
            'deelnemers' => $deelnemers,

        ]);
    }
    /**
     * @Route("/admin/delete/deelnemer/{id}", name="delete_deelnemer")
     */
    public function deleteDeelnemerAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);

        $em->remove($user);
        $em->flush();
        $this->addFlash(
            'notice',
            'Deelnemer verwijderd!'
        );

        return $this->redirectToRoute("deelnemer_overzicht");
    }
    /**
     * @Route("/admin/reset/deelnemer/{id}/wachtwoord", name="reset_deelnemer_wachtwoord")
     */
    public function resetDeelnemerWachtwoordAction($id, UserPasswordEncoderInterface $passwordEncoder)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);

        $user->setPassword($passwordEncoder->encodePassword($user,"qwerty"));
        $em->persist($user);
        $em->flush();

        $this->addFlash(
            'notice',
            'Wachtwoord reset!'
        );

        return $this->redirectToRoute("deelnemer_overzicht");

    }

}
