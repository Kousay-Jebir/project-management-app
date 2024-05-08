<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Team;


class CodeInputController extends AbstractController
{
    #[Route('/code/input', name: 'app_code_input')]
    public function handleSubmission(Request $request,EntityManagerInterface $entityManager): Response
    {
        $inputValue = $request->request->get('input_field');
        $user = $this->getUser();
        $team =$entityManager->getRepository(Team::class)->findOneBy(['teamJoinCode' => $inputValue]);
        $id=$team->getId();
        $path='TeamPage/'. $id;


        
        if (!$this->isValid($inputValue,$entityManager,$user)) {
            return new Response('Invalid input.', Response::HTTP_BAD_REQUEST);
        }

        
        return $this->redirect($path);
    }
    private function isValid($inputValue,$entityManager,$user)
    {
    
        $team =$entityManager->getRepository(Team::class)->findOneBy(['teamJoinCode' => $inputValue]);
        if($team){
            $team->addUser($user);
            $entityManager->flush();
        }
        return ($team);

    }
}
