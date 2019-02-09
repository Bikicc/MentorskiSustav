<?php
namespace App\Controller;

use App\Entity\UserSubjects;
use App\Entity\User;
use App\Entity\Subject;
use App\Entity\UserSubject;
use App\Form\SubjectType;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class StudentsController extends AbstractController
{	
    /**
  *@route("/MentorskiSustav/Students/HomePage", name="StudentsHomePage") 
  */
  public function HomePage() //Home Page s prikazom svih neupisanih i upisanih predmeta i mogučnosti upisa novog, jedina ruta koja se displaya
  {
    $doctrine = $this->getDoctrine();
    $repository = $doctrine->getRepository(UserSubjects::class);
   
    $student = $this->getUser(); //student
    
    $subjects = $this  //Svi predmeti
      ->getDoctrine()
      ->getRepository(Subject::class)
      ->findAll();
    
    $enrolledSubjects = $repository->findSubjectsRelatedToStudent($student);
    $enrolledSubjects = array_map(function($row)  
    {
        return $row->getSubjectCode();
    }, $enrolledSubjects);  //Upisani predmeti
    
    $userSubjects = $repository->findSubjectsRelatedToStudent($student); //Međutablica koja sadrži studente i predmete

    return $this->render("Student/HomePage.html.twig", [
    "student" => $student,
    "subjects" => $subjects,
    "enrolledSubjects" => $enrolledSubjects,
    "userSubjects" => $userSubjects,
    ]);
  }
 
  /**
     * @Route("/MentorskiSustav/Students/{user_id}/enrollSubject/{subject_id}", name="enrollSubject")
     */

    public function enrollSubject($user_id,$subject_id) //Funkcija za upis predmeta, koja redirecta na Home Page nakon upisa
    {
       $doctrine = $this->getDoctrine();

       $student = $doctrine->getRepository(User::class)->find($user_id); 
       
       $subject = $doctrine->getRepository(Subject::class)->find($subject_id);

       $enrollSubject = new UserSubjects();

       $enrollSubject
           ->setUserEmail($student)
           ->setSubjectCode($subject)
           ->setStatus('enrolled')
       ;

       $manager = $doctrine->getManager();
       $manager->persist($enrollSubject);
       $manager->flush(); 

       return $this->redirectToRoute('StudentsHomePage');

    }

     /**
    * @Route("/MentorskiSustav/Students/{user_id}/PassedSubject/{subject_id}", name="StudentPassedSubject")
    */

   public function StudentPassedSubject($user_id,$subject_id) //mijenja se status ukoliko je student polozio predmet
   {
      $doctrine = $this->getDoctrine();

      $userSubjectsRepo = $doctrine->getRepository(UserSubjects::class);
      
      $userSubject= $userSubjectsRepo->find(array(
        'user_email' => $user_id, 
        'subject_code' => $subject_id
      )); //saljemo listu jer imamo slozeni kljuc

      $userSubject
          ->setStatus('passed');

      $manager = $doctrine->getManager();
      $manager->persist($userSubject);
      $manager->flush();

      if ($this->isGranted('ROLE_ADMIN'))
      {
        $student = $doctrine->getRepository(User::class)->find($user_id);

        $subject = $this
        ->getDoctrine()
        ->getRepository(Subject::class)
        ->findAll(); //svi predmeti
  
        $enrolledSubjects = $userSubjectsRepo->findSubjectsRelatedToStudent($student);
        $enrolledSubjects = array_map(function($row){
        return $row->getSubjectCode();
        }, $enrolledSubjects); //Upisani predmeti
      
      $userSubjects = $userSubjectsRepo->findSubjectsRelatedToStudent($student); //medutablica
          return $this->render("Mentor/StudentDetails.html.twig", [    // I mentor i student imaju pristup ovom kontroleru, ali admin nema pristup
            "student" => $student,                                     // student HomePage, stoga se vrši provjera ko je napravio izmjenu i na osnovu role
            "subject" => $subject,                                     // renderamo template
            "enrolledSubjects" => $enrolledSubjects,
            "userSubjects" => $userSubjects,
          ]);
      }
      else
          return $this->redirectToRoute('StudentsHomePage');
   }

    /**
    * @Route("/MentorskiSustav/Students/{user_id}/RemoveSubject/{subject_id}", name="StudentRemoveSubject")
    */

    public function RemoveSubject($user_id,$subject_id) //brise se predmet koji je student upisa
    {
       $doctrine = $this->getDoctrine();
 
       $userSubjectsRepo = $doctrine->getRepository(UserSubjects::class);
      
       $userSubject= $userSubjectsRepo->find(array(
         'user_email' => $user_id, 
         'subject_code' => $subject_id
       )); //saljemo listu jer imamo slozeni kljuc
 
 
      $manager = $doctrine->getManager();
      $manager->remove($userSubject);
      $manager->flush();
 
      if ($this->isGranted('ROLE_ADMIN'))
      {
        $student = $doctrine->getRepository(User::class)->find($user_id);

        $subject = $this
        ->getDoctrine()
        ->getRepository(Subject::class)
        ->findAll(); //svi predmeti
  
        $enrolledSubjects = $userSubjectsRepo->findSubjectsRelatedToStudent($student);
        $enrolledSubjects = array_map(function($row){
        return $row->getSubjectCode();
        }, $enrolledSubjects); //Upisani predmeti
      
      $userSubjects = $userSubjectsRepo->findSubjectsRelatedToStudent($student); //medutablica
          return $this->render("Mentor/StudentDetails.html.twig", [    // I mentor i student imaju pristup ovom kontroleru, ali admin nema pristup
            "student" => $student,                                     // student HomePage, stoga se vrši provjera ko je napravio izmjenu i na osnovu role
            "subject" => $subject,                                     // renderamo template
            "enrolledSubjects" => $enrolledSubjects,
            "userSubjects" => $userSubjects,
          ]);
      }
      else
          return $this->redirectToRoute('StudentsHomePage');
   
    }

}