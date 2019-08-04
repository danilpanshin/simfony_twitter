<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;
use Symfony\Component\HttpFoundation\Request;
use App\Form\PostType;


class PostController extends AbstractController
{

    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Post::class);
        $posts = $repository->findAll();
        return $this->render('/post/index.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @param Post $post
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/post/{id}", name="post")
     */
    public function post($id)
    {
        $repository = $this->getDoctrine()->getRepository(Post::class);
        $post = $repository->find($id);
        return $this->render('post/show.html.twig', [
            'post' => $post
        ]);
    }

    /**
     * @Route("/create_post", name="create_post")
     */
    public function createPost(Request $request)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirect('/');
        }

        return $this->render('post/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
