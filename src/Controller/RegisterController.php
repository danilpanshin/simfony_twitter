<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\UserType;
use App\Entity\User;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // 1) создаем форму
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // 2) обрабатываем форму
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) зашифровываем пароль
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setIsConfirmed(false);

            // 4) сохраняем пользователя
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // 5) (временно) перенаправляем обратно
            return $this->redirectToRoute('register');
        }

        return $this->render(
            'register/index.html.twig', [
                'form' => $form->createView()
            ]
        );
    }

}
