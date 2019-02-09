<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use App\Entity\User;
use App\Entity\Subject;
use App\Entity\userSubjects;
use App\Form\SubjectFormType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Intl\Exception\NotImplementedException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class MentorsController extends AbstractController
{
   /**
	*@route("/MentorskiSustav/Mentors/Students",name="ListAllStudents") 
	*/
  public function ListAllStudents()
    {
       $this->denyAccessUnlessGranted('ROLE_ADMIN');
      
       $students = $this->getDoctrine()
       ->getRepository(User::class)
       ->findAll();
        return $this->render("Mentor/ListAllStudents.html.twig", [
       "students" => $students
      ]);
    }


    /**
     * @Route("/MentorskiSustav/Mentors/student/{user_id}", name="Student")
     */
    public function StudentDetails($user_id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $doctrine = $this->getDoctrine();
        $userSubjectsRepo = $doctrine->getRepository(UserSubjects::class);

        $student = $doctrine->getRepository(User::class)->find($user_id);

        $subject = $this
        ->getDoctrine()
        ->getRepository(Subject::class)
        ->findAll(); //svi subjecti
    
    $enrolledSubjects = $userSubjectsRepo->findSubjectsRelatedToStudent($student);
    $enrolledSubjects = array_map(function($row){
      return $row->getSubjectCode();
    }, $enrolledSubjects); //Upisani predmeti
    
    $userSubjects = $userSubjectsRepo->findSubjectsRelatedToStudent($student); //medutablica

    return $this->render("Mentor/StudentDetails.html.twig", [
        "student" => $student,
        "subject" => $subject,
        "enrolledSubjects" => $enrolledSubjects,
        "userSubjects" => $userSubjects,
      ]);

    }
  
    /**
	*@route("/MentorskiSustav/Mentors/Subjects",name="ListAllSubjects") 
	*/
    public function ListAllSubjects()
    {
      $this->denyAccessUnlessGranted('ROLE_ADMIN');

      $subjects = $this->getDoctrine()
            ->getRepository(Subject::class)
            ->findAll();
            return $this->render("Mentor/ListAllSubjects.html.twig",[
                "subjects" => $subjects
            ]);
    }

     /**
     * @Route("/MentorskiSustav/Mentors/EditSubject/{subject_id}", name="EditSubject")
     */
    public function EditSubject(Request $request,$subject_id)
    {
      $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $subject = $this
            ->getDoctrine()
            ->getRepository(Subject::class)
            ->find($subject_id)
        ;

        $form = $this->createForm(SubjectFormType::class, $subject);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (isset($_POST['subject_form_izborni'])) 
            {
                $subject->setIzborni(1);
            } 
            else 
            {
                $subject->setIzborni(0);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($subject);
            $entityManager->flush();

            return $this->redirectToRoute('ListAllSubjects');
        }

        return $this->render("Mentor/EditSubject.html.twig", [
            "subject" => $subject,
            "form" => $form->createView()
        ]);
    }

     /**
     * @Route("/MentorskiSustav/Mentors/AddSubject",name="AddSubject")
     */
    public function AddSubject(Request $request)
    {
      $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $subject = new Subject();
        $form = $this->createForm(SubjectFormType::class, $subject);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) 
        {
            if (isset($_POST['subject_form_izborni'])) 
            {
               $subject->setIzborni(1);
            } 
            else 
            {
               $subject->setIzborni(0);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($subject);
            $entityManager->flush();

            return $this->redirectToRoute('ListAllSubjects');
        }
        
        return $this->render("Mentor/AddSubject.html.twig", [
            'form' => $form->createView(),
        ]);

    }
        /**
    * @Route("/MentorskiSustav/Mentors/RemoveSubject/{subject_id}", name="MentorRemoveSubject")
    */

    public function RemoveSubject($subject_id) //brise se predmet 
    {
      $this->denyAccessUnlessGranted('ROLE_ADMIN');

       $doctrine = $this->getDoctrine();
       $subject = $doctrine->getRepository(Subject::class)->find($subject_id); 

      $manager = $doctrine->getManager();
      $manager->remove($subject);
      $manager->flush();
 
       return $this->redirectToRoute('ListAllSubjects');
    }

        
}