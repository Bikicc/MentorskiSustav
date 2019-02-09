<?php
// src/Controller/LuckyController.php
namespace App\Controller;


 use Symfony\Component\HttpFoundation\Response;
 use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
 use Symfony\Component\Routing\Annotation\Route;
 use Symfony\Component\HttpFoundation\RedirectResponse;


class DefaultController extends AbstractController
{
  private  $urlGenerator;

  	/**
	*@route("/",name="DefaultController") 
  */
   public function RedirectAuthenticatedUser() //Provjerava role i rendera prikladnu stranicu, ukoliko neko nije logiran odbija ga i redericta na /login
    {
       $this->denyAccessUnlessGranted('ROLE_USER');
      
      if ($this->isGranted('ROLE_ADMIN'))
          return $this->redirectToRoute('ListAllStudents');
      else
        return $this->redirectToRoute('StudentsHomePage');
    }
  

}