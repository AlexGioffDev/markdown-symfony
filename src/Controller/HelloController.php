<?php

namespace App\Controller;

use App\Entity\Note;
use App\Form\NoteType;
use App\Services\GrammarService;
use App\Services\MarkdownServices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HelloController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(Request $request, EntityManagerInterface $entityManager, MarkdownServices $markdown, GrammarService $grammar)
    {
        $form = $this->createForm(NoteType::class);

        $form->handleRequest($request);
        $user = $this->getUser();
        $notes = $entityManager->getRepository(Note::class)->findNotesByAuthor($user);

        if ($form->isSubmitted() && $form->isValid()) {
            $note = $form->getData();
            $fixed = $grammar->checkAndFix($note->getBody());
            $note->setBody($fixed);
            $note->setAuthor($user);
            $entityManager->persist($note);
            $entityManager->flush();
            return $this->redirectToRoute('app_index');
        }

        foreach($notes as $note)
        {
            $note->setMarkdownService($markdown);
        }

        return $this->render('hello/show.html.twig', [
            'notes' => $notes,
            'form' => $form
        ]);
    }
}
