<?php

    namespace App\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class MainController extends AbstractController{

        /**
         * @Route("/",name="app_home")
         */
        public function index():Response{
            return $this->render("pages/home.html.twig");
        }

        /**
         * @Route("/contact",name="app_contact")
         */
        public function contact():Response{
            return $this->render("pages/contact.html.twig");
        }

        /**
         * @Route("/inscription",name="app_inscription")
         */
        public function inscription():Response{
            return $this->render("pages/inscription.html.twig");
        }

    }