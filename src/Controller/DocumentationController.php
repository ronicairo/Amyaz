<?php

namespace App\Controller;

use DateTime;
use App\Entity\Documentation;
use App\Form\DocumentationType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategoryDocRepository;
use App\Repository\DocumentationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/documentation')]
class DocumentationController extends AbstractController
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    #[Route('/', name: 'documentation_index', methods: ['GET'])]
    public function index(DocumentationRepository $documentationRepository, CategoryDocRepository $categoryDocRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $searchTerm = $request->query->get('q');

        $selectedCategory = $request->query->get('category');

        $locale = $request->getLocale();

        $queryBuilder = $documentationRepository->createQueryBuilder('d');

        if ($searchTerm) {
            $titleField = $locale === 'fr' ? 'd.titleFr' : 'd.titleEn';
            $queryBuilder->andWhere("$titleField LIKE :searchTerm")
                ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        if ($selectedCategory) {
            $queryBuilder->join('d.category', 'c')
                ->andWhere('c.id = :categoryId')
                ->setParameter('categoryId', $selectedCategory);
        }

        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            6
        );

        $categories = $categoryDocRepository->findAll();

        return $this->render('documentation/index.html.twig', [
            'pagination' => $pagination,
            'searchTerm' => $searchTerm,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory
        ]);
    }

    #[Route('/new', name: 'documentation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {

        // Vérifier les rôles de l'utilisateur
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MODERATOR')) {
            $this->addFlash('danger', $this->translator->trans('addflash.restreindre_acces'));
            return $this->redirectToRoute('account');
        }

        $documentation = new Documentation();
        $form = $this->createForm(DocumentationType::class, $documentation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $documentation->setCreatedAt(new DateTime());
            $documentation->setUpdatedAt(new DateTime());
            $file = $form->get('file')->getData();
            if ($file) {
                $newFilename = uniqid() . '.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('uploads_directory'),
                    $newFilename
                );
                $documentation->setFile($newFilename);
            }

            $entityManager->persist($documentation);
            $entityManager->flush();

            $this->addFlash('success', $this->translator->trans('addflash.documentation_create_success'));
            return $this->redirectToRoute('documentation_index');
        }

        return $this->render('documentation/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'documentation_show', methods: ['GET'])]
    public function show(Documentation $documentation): Response
    {
        return $this->render('documentation/show.html.twig', [
            'documentation' => $documentation,
        ]);
    }

    #[Route('/{id}/edit', name: 'documentation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Documentation $documentation, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {

        // Vérifier les rôles de l'utilisateur
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MODERATOR')) {
            $this->addFlash('danger', $this->translator->trans('addflash.restreindre_acces'));
            return $this->redirectToRoute('account');
        }

        $currentFile = $documentation->getFile();

        $form = $this->createForm(DocumentationType::class, $documentation, [
            'file' => $currentFile
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $documentation->setUpdatedAt(new \DateTime());

            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();

            if ($file) {
                $this->handleFile($file, $documentation, $slugger);

                if ($currentFile) {
                    $oldFilePath = $this->getParameter('uploads_directory') . DIRECTORY_SEPARATOR . $currentFile;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }
            } else {
                $documentation->setFile($currentFile);
            }

            $entityManager->flush();
            $this->addFlash('success', $this->translator->trans('addflash.documentation_update_success'));
            return $this->redirectToRoute('documentation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('documentation/edit.html.twig', [
            'documentation' => $documentation,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'documentation_delete', methods: ['POST'])]

    public function delete(Request $request, Documentation $documentation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $documentation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($documentation);
            $entityManager->flush();
        }
        $this->addFlash('success', $this->translator->trans('addflash.documentation_delete_success'));
        return $this->redirectToRoute('documentation_index', [], Response::HTTP_SEE_OTHER);
    }

    private function handleFile(UploadedFile $file, Documentation $documentation, SluggerInterface $slugger)
    {
        $extension = "." . $file->guessExtension();
        $safeFilename = $slugger->slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $newFilename = $safeFilename . '_' . uniqid() . $extension;

        try {
            $file->move($this->getParameter('uploads_directory'), $newFilename);
            $documentation->setFile($newFilename);
        } catch (FileException $exception) {
            // Log the exception or handle it as needed
            // For example:
            $this->addFlash('error', $this->translator->trans('addflash.file_upload_error'));
        }
    }

    // #[Route('supprimer-tous-document-archive', name: 'delete_all_document_archive', methods: ['GET'])]
    // public function deleteAllDocArchive(EntityManagerInterface $entityManager): Response
    // {
    //     try {
    //         $this->denyAccessUnlessGranted("ROLE_ADMIN");
    //     } catch (AccessDeniedException) {
    //         $this->addFlash('danger', "Cette partie du site est réservé");
    //         return $this->redirectToRoute('app_login');
    //     }

    //     $documentations = $entityManager->getRepository(Documentation::class)->findAllArchived();

    //     foreach ($documentations as $documentation) {
    //         $entityManager->remove($documentation);
    //     }

    //     $entityManager->flush();

    //     $this->addFlash('success', "Toute la documentation archivé a bien été supprimée !");
    //     return $this->redirectToRoute(('show_archive'));
    // } // end deleteAllDocArchive

}
