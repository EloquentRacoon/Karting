<?php

namespace App\Controller;


use App\Entity\Activiteit;
use App\Entity\Soortactiviteit;
use App\Entity\User;
use App\Form\UserType;
use App\Form\ActiviteitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class BezoekerController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(): Response
    {
        return $this->render('bezoeker/index.html.twig', [
            'controller_name' => 'BezoekerController',
            'boodschap' =>'Welkom'
        ]);
    }
    /**
     * @Route("/kartactiviteiten", name="kartactiviteiten")
     */
    public function kartactiviteitenAction()
    {
        $soortactiviteiten=$this->getDoctrine()->getRepository(Soortactiviteit::class)->findAll();;
        return $this->render('bezoeker/kartactiviteiten.html.twig',[
            'boodschap'=>'Welkom',
            'soortactiviteiten'=>$soortactiviteiten
        ]);
    }
    /**
     * @Route("registreren", name="registreren")
     */
    public function registreren(Request $request,UserPasswordEncoderInterface $passwordEncoder)
    {
        // 1) build the form

        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();
            // 2.5) Is the user new, gebruikersnaam moet uniek zijn
            $repository=$this->getDoctrine()->getRepository(User::class);
            $bestaande_user=$repository->findOneBy(['username'=>$form->getData()->getUsername()]);

            if($bestaande_user==null)
            {
                // 3) Encode the password (you could also do this via Doctrine listener)
                $password = $passwordEncoder->encodePassword($user, $user->getPassword());
                $user->setPassword($password);
                $user->setRoles(['ROLE_USER']);
                // 4) save the User!
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $this->addFlash(
                    'notice',
                    $user->getNaam().' is geregistreerd!'
                );

                return $this->redirectToRoute('homepage');
            }
            else
            {
                $this->addFlash(
                    'error',
                    $user->getUsername()." bestaat al!"
                );
                return $this->render('bezoeker/registreren.html.twig', [
                    'form'=>$form->createView()
                ]);
            }
        }

        return $this->render('bezoeker/registreren.html.twig', [
            'form'=>$form->createView()
        ]);
    }
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        if ($this->getUser()){
            switch ($this->getUser()->getRoles()){
                case "ROLE_ADMIN":
                    return $this->redirectToRoute('mederwerker');
                case "ROLE_USER":
                    return $this->redirectToRoute('activiteiten');
            }
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('bezoeker/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

}
