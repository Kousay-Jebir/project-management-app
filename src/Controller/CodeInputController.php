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
        // Handle form submission
        $inputValue = $request->request->get('input_field');
        $user = $this->getUser();

        // Do something with $inputValue, e.g., save it to a database
        if (!$this->isValid($inputValue,$entityManager,$user)) {
            // Handle validation errors, for example, return an error response
            return new Response('Invalid input.', Response::HTTP_BAD_REQUEST);
        }

        // Redirect back to the main page or render a response
        return $this->redirectToRoute('app_homepage',['inputValue' => $inputValue]); // assuming 'app_mainpage' is the name of your main page route
    }
    private function isValid($inputValue,$entityManager,$user)
    {
        // Add your validation logic here
        // For example, check if the input is not empty
        $team =$entityManager->getRepository(Team::class)->findOneBy(['teamJoinCode' => $inputValue]);
        if($team){
            $team->addUser($user);
            $entityManager->flush();
        }
        return ($team);

    }
}
