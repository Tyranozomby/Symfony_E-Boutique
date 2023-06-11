<?php

namespace App\Controller\crud;

use App\Entity\Media;
use App\Form\MediaType;
use App\Repository\MediaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/media')]
class MediaController extends AbstractController
{
    #[Route('/', name: 'crud_media_index', methods: ['GET'])]
    public function index(MediaRepository $mediaRepository): Response
    {
        return $this->render('crud/media/index.html.twig', [
            'media' => $mediaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'crud_media_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MediaRepository $mediaRepository): Response
    {
        $medium = new Media();
        $form = $this->createForm(MediaType::class, $medium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            $file->move($this->getParameter('images_directory'), $fileName);

            $medium->setPath($fileName);

            $mediaRepository->save($medium, true);

            return $this->redirectToRoute('crud_media_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('crud/media/new.html.twig', [
            'medium' => $medium,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'crud_media_show', methods: ['GET'])]
    public function show(Media $medium): Response
    {
        return $this->render('crud/media/show.html.twig', [
            'medium' => $medium,
        ]);
    }

    #[Route('/{id}/edit', name: 'crud_media_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Media $medium, MediaRepository $mediaRepository): Response
    {
        $form = $this->createForm(MediaType::class, $medium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();

            if ($file) {
                unlink($this->getParameter('images_directory') . '/' . $medium->getPath());
            }
            $fileName = explode('.', $medium->getPath())[0] . '.' . $file->guessExtension();

            $file->move($this->getParameter('images_directory'), $fileName);

            $medium->setPath($fileName);

            $mediaRepository->save($medium, true);

            return $this->redirectToRoute('crud_media_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('crud/media/edit.html.twig', [
            'medium' => $medium,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'crud_media_delete', methods: ['POST'])]
    public function delete(Request $request, Media $medium, MediaRepository $mediaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $medium->getId(), $request->request->get('_token'))) {
            $mediaRepository->remove($medium, true);
        }

        return $this->redirectToRoute('crud_media_index', [], Response::HTTP_SEE_OTHER);
    }
}
