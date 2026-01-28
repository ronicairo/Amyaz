<?php

namespace App\Controller;

use DateTime;
use App\Form\StatusType;
use App\Form\AddWordType;
use App\Entity\Traduction;
use App\Service\VerbeService;
use App\Service\SendMailService;
use App\Repository\UserRepository;
use App\Repository\StatusRepository;
use App\Repository\TraductionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Validator\UniqueCombinationValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



#[Route('/traduction')]
class TraductionController extends AbstractController
{
    private TranslatorInterface $translator;

    private $verbeService;

    public function __construct(TranslatorInterface $translator, VerbeService $verbeService)
    {
        $this->translator = $translator;
        $this->verbeService = $verbeService;
    }

    #[Route('/', name: 'app_traduction_index', methods: ['GET'])]
    /**
     * Affiche la liste des traductions dans un tableau
     *
     * @param  mixed $traductionRepository
     * @param  mixed $request
     * @param  mixed $paginator
     * @return Response
     */
 public function index(TraductionRepository $traductionRepository, Request $request, PaginatorInterface $paginator): Response
{
    if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MODERATOR')) {
        $this->addFlash('error', $this->translator->trans('addflash.restreindre_acces'));
        return $this->redirectToRoute('show_home');
    }

    // Récupérer le critère de tri depuis la requête, 'alphabetical' par défaut
    $sort = $request->query->get('sort', 'alphabetical');

    // Récupérer les traductions en fonction du tri
    if ($sort === 'alphabetical') {
        $traductions = $traductionRepository->findAllAlphabetically();
    } elseif ($sort === 'rifainSingularRecord') {
        $traductions = $traductionRepository->findAllWithRifainSingularRecord();
    } else {
        $traductions = $traductionRepository->findAllSortedByUpdatedAt();
    }

    // Pagination
    $pagination = $paginator->paginate(
        $traductions,
        $request->query->getInt('page', 1),
        20
    );

    // Rendu du template
    return $this->render('traduction/index.html.twig', [
        'traductions' => $traductions,
        'pagination' => $pagination,
        'sort' => $sort, // Pour garder le choix actif dans le template
    ]);
}

    #[Route('/autocomplete')]
    public function autocomplete(TraductionRepository $traductionRepository, Request $request)
    {


        $searchType = $request->query->get('searchType');
        $lang = $request->query->get('lang');
        $searchTerm = $request->query->get('q');

        $mapping = array(
            'fr-rif'  => 'wordFR',
            'rif-fr' => 'singular',
            'en-rif' => 'wordEN',
            'rif-en' => 'singular'

        );

        if ($searchType === "dictionary") {
            $traductions = $searchTerm
                ? $traductionRepository->findBySearchTerm($searchTerm, $lang)
                : [];

            $response = array();
            $function = "get" . $mapping[$lang];
            foreach ($traductions as $traduction) {
                $response[] = $traduction->$function();
            }

            $response = new Response(json_encode($response));
        } elseif ($searchType === "verb") {
            $traductions = $searchTerm
                ? $this->verbeService->findBySearchTerm($searchTerm, $lang)
                : [];


            $response = new Response(json_encode($traductions));
        }


        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    #[Route('/new', name: 'app_traduction_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Security $security, UserRepository $userRepository, StatusRepository $statusRepository, TraductionRepository $traductionRepository, SendMailService $mail): Response
    {
        if (!$security->isGranted('ROLE_USER')) {
            $this->addFlash('error', $this->translator->trans('addflash.obligation_connexion'));
            return $this->redirectToRoute('app_login');
        }

        $user = $this->getUser();

        if (!$user->isVerified()) {
            $resendVerificationUrl = $this->generateUrl('app_resend_verification_code');

            $verificationMessage = $this->translator->trans('addflash.verification_compte', [
                '%resend_verification_url%' => $resendVerificationUrl
            ]);

            $this->addFlash('error', $verificationMessage);

            return $this->redirectToRoute('account');
        }

        $traduction = new Traduction();
        $form = $this->createForm(AddWordType::class, $traduction);
        $form->handleRequest($request);
        $locale = $this->translator->getLocale();

        if ($form->isSubmitted() && $form->isValid()) {
            $wordFR = ($traduction->getWordFR());
            $singular = ($traduction->getSingular());
            $wordEN = ($traduction->getWordEN());

            // Normaliser les apostrophes dans les champs
            if ($wordFR !== null) {
                $wordFR = str_replace('’', "'", $wordFR);
                $traduction->setWordFR($wordFR);  // Appliquer la valeur normalisée
            }
            if ($singular !== null) {
                $singular = str_replace('’', "'", $singular);
                $traduction->setSingular($singular);  // Appliquer la valeur normalisée
            }
            if ($wordEN !== null) {
                $wordEN = str_replace('’', "'", $wordEN);
                $traduction->setWordEN($wordEN);  // Appliquer la valeur normalisée
            }
            // Vérification de l'existence des mots dans la base de données
            $existingTraductionFR = $traductionRepository->findOneBy(['wordFR' => $wordFR]);
            $existingTraductionSingular = $traductionRepository->findOneBy(['singular' => $singular]);
            $existingTraductionEN = $traductionRepository->findOneBy(['wordEN' => $wordEN]);

            // Gestion des erreurs
            if ($existingTraductionFR !== null && $wordFR !== null && $existingTraductionFR->getWordFR() === $wordFR) {
                $this->addFlash('error', $this->translator->trans('addflash.traduction_already_exist', ['%mot%' => $wordFR]));
            } elseif ($existingTraductionSingular !== null && $singular !== null && $existingTraductionSingular->getSingular() === $singular) {
                $this->addFlash('error', $this->translator->trans('addflash.traduction_already_exist', ['%mot%' => $singular]));
            } elseif ($existingTraductionEN !== null && $wordEN !== null && $existingTraductionEN->getWordEN() === $wordEN) {
                $this->addFlash('error', $this->translator->trans('addflash.traduction_already_exist', ['%mot%' => $wordEN]));
            } else {
                $user = $this->getUser();
                $roles = $user->getRoles();
                $traduction->setRequestedBy($user);
                $traduction->setCreatedAt(new \DateTime());
                $traduction->setUpdatedAt(new \DateTime());

                $traduction->setRequest(true, $roles);

                if (!in_array('ROLE_MODERATOR', $roles) && !in_array('ROLE_ADMIN', $roles)) {
                    $pendingStatus = $statusRepository->findOneBy(['libelle' => 'pending']);
                    $traduction->setStatus($pendingStatus);
                } else {
                    $traduction->setStatus(null);
                }

                $entityManager->persist($traduction);
                $entityManager->flush();
                
                if (in_array('ROLE_USER', $roles) && !in_array('ROLE_ADMIN', $roles) && !in_array('ROLE_MODERATOR', $roles)) {
                    $adminsAndMods = $userRepository->findByRoles(['ROLE_ADMIN', 'ROLE_MODERATOR']);
                    $context = [
                        'user' => $user,
                        'traduction' => $traduction,
                    ];
                
                    foreach ($adminsAndMods as $adminOrMod) {
                        try {
                            $mail->send(
                                'contact@amyaz.fr',
                                $adminOrMod->getEmail(),
                                'Nouvelle demande de traduction en attente',
                                'traduction/email_demande_traduction.html.twig',
                                $context
                            );
                        } catch (\Exception $e) {
                            $this->addFlash('error', 'Erreur lors de l\'envoi de l\'email : ' . $e->getMessage());
                        }
                    }
                }



                if (!in_array('ROLE_MODERATOR', $roles) && !in_array('ROLE_ADMIN', $roles)) {
                    $statutLink = $this->generateUrl('account_translations');
                    $attenteMotMessage = $this->translator->trans('addflash.attente_validation', [
                        '%status_url%' => $statutLink
                    ]);
                    $this->addFlash('success', $attenteMotMessage);
                } else {
                    $this->addFlash('success', $this->translator->trans('addflash.traduction_ajouter'));
                }

                return $this->redirectToRoute('show_home', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('traduction/new.html.twig', [
            'traduction' => $traduction,
            'form' => $form->createView(),
            'locale' => $locale,
        ]);
    }


    #[Route('/{id}/edit-status', name: 'app_traduction_edit_status', methods: ['GET', 'POST'])]
    /**
     * Permettre à l'administrateur ou modérateur de changer le statut d'une demande
     *
     * @param  mixed $request
     * @param  mixed $traduction
     * @param  mixed $entityManager
     * @return Response
     */
    public function editStatus(Request $request, Traduction $traduction, EntityManagerInterface $entityManager, SendMailService $mail): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MODERATOR')) {
            $this->addFlash('error', $this->translator->trans('addflash.restreindre_acces'));
            return $this->redirectToRoute('account');
        }

        // Créez un formulaire en utilisant le StatusType pour le champ 'status'
        $form = $this->createForm(StatusType::class, $traduction);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $traduction->setUpdatedAt(new DateTime());
            $entityManager->flush();

            // Envoi du mail si l'utilisateur existe et que le statut est modifié
            $user = $traduction->getRequestedBy(); // Utilisateur à l'origine de la demande

            if ($user) {
                // Préparer l'e-mail à envoyer
                $context = [
                    'user' => $user,
                    'traduction' => $traduction,
                    'newStatus' => $traduction->getStatus()->getLibelle(), // Le libellé du nouveau statut
                ];

                // Envoi de l'e-mail à l'utilisateur qui a créé la traduction
                $mail->send(
                    'contact@amyaz.fr',
                    $user->getEmail(),
                    'Changement de statut pour votre demande de traduction',
                    'traduction/email_statut_change.html.twig', // Template HTML
                    $context
                );
            }

            $this->addFlash('success', "Le statut a bien été modifié.");
            return $this->redirectToRoute('account_pending_translations');
        }

        return $this->render('traduction/edit_status.html.twig', [
            'traduction' => $traduction,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_traduction_show', methods: ['GET'])]
    /**
     * Voir une traduction en détail
     *
     * @param  mixed $traduction
     * @return Response
     */
    public function show(Traduction $traduction): Response
    {
        return $this->render('traduction/show.html.twig', [
            'traduction' => $traduction,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_traduction_edit', methods: ['GET', 'POST'])]
    /**
     * Modifier une traduction
     *
     * @param  mixed $request
     * @param  mixed $traduction
     * @param  mixed $entityManager
     * @return Response
     */
    public function edit(Request $request, Traduction $traduction, EntityManagerInterface $entityManager): Response
    {
        
              if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MODERATOR')) {
            $this->addFlash('error', $this->translator->trans('addflash.restreindre_acces'));
            return $this->redirectToRoute('show_home');
        }
        
        $form = $this->createForm(AddWordType::class, $traduction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $rifainSingularFile = $form['rifainSingularRecord']->getData();
            $rifainPluralFile = $form['rifainPluralRecord']->getData();
            $rifainSingularRecordDeleted = $request->request->get('rifainSingularRecordDeleted');
            $rifainPluralRecordDeleted = $request->request->get('rifainPluralRecordDeleted');

            // Set blob data only if the file is uploaded
            if ($rifainSingularFile) {
                $traduction->setRifainSingularRecord(file_get_contents($rifainSingularFile->getPathname()));
            } else if ($rifainSingularRecordDeleted == 'true') {
                $traduction->setRifainSingularRecord(null);
            }
            if ($rifainPluralFile) {
                $traduction->setRifainPluralRecord(file_get_contents($rifainPluralFile->getPathname()));
            } else if ($rifainPluralRecordDeleted == 'true') {
                $traduction->setRifainPluralRecord(null);
            }
            
            $traduction->setUpdatedAt(new \DateTime());
            $entityManager->persist($traduction);
            $entityManager->flush();

            $this->addFlash('success', "Votre traduction a bien été modifiée.");
            return $this->redirectToRoute('app_traduction_index', [], Response::HTTP_SEE_OTHER);
        }


        $hasRifainSingularRecord = $traduction->getRifainSingularRecord() ? !empty(stream_get_contents($traduction->getRifainSingularRecord())) : false;
        $hasRifainPluralRecord = $traduction->getRifainPluralRecord() ? !empty(stream_get_contents($traduction->getRifainPluralRecord())) : false;

        return $this->render('traduction/edit.html.twig', [
            'hasRifainSingularRecord' => $hasRifainSingularRecord,
            'hasRifainPluralRecord' => $hasRifainPluralRecord,
            'traduction' => $traduction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_traduction_delete', methods: ['POST'])]
    /**
     * Supprimer une trauction
     *
     * @param  mixed $request
     * @param  mixed $traduction
     * @param  mixed $entityManager
     * @return Response
     */
    public function delete(Request $request, Traduction $traduction, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $traduction->getId(), $request->request->get('_token'))) {
            $entityManager->remove($traduction);
            $entityManager->flush();

            // Ajouter un message flash de succès
            $this->addFlash('success', $this->translator->trans('addflash.traduction_supprimer'));
        }

        // Redirection après suppression
        return $this->redirectToRoute('show_home', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/search', name: 'search')]
    /**
     * Méthode pour la barre de recherche
     *
     * @param  mixed $request
     * @param  mixed $traductionRepository
     * @return Response
     */
    public function search(Request $request, TraductionRepository $traductionRepository): Response
    {
        // Récupérer le terme de recherche depuis la requête
        $searchTerm = $request->query->get('q');

        // Vérifier si le terme de recherche est fourni
        if ($searchTerm) {
            // Effectuer la recherche dans la base de données
            $results = $traductionRepository->search($searchTerm);

            // Retourner les résultats en tant que réponse JSON
            return new JsonResponse($results);
        }

        // Si aucun terme de recherche n'est fourni, retourner une réponse vide
        return new JsonResponse([]);
    }

    /**
     * @Route("/upload-audio", name="upload_audio", methods={"POST"})
     */
    public function uploadAudio(Request $request, SluggerInterface $slugger, Traduction $traduction, EntityManagerInterface $entityManager): JsonResponse
    {
        $audioDirectory = $this->getParameter('audio_directory');
        $response = [];

        foreach (['riffianSingularRecord', 'riffianPluralRecord'] as $recordType) {
            if ($audioFile = $request->files->get($recordType)) {
                $filename = $slugger->slug($recordType) . '-' . uniqid() . '.' . $audioFile->guessExtension();
                $audioFile->move($audioDirectory, $filename);

                // Store the filename in the database (assuming the translation entity is available)
                $setter = 'set' . ucfirst($recordType);
                $traduction->$setter($filename);
                $entityManager->flush();

                $response[$recordType] = $filename;
            }
        }

        return new JsonResponse($response, Response::HTTP_OK);
    }

    #[Route('/audio/{id}/{field}', name: 'audio_serve')]
    public function serveAudio(int $id, string $field, EntityManagerInterface $entityManager): Response
    {
        
        $traduction = $entityManager->getRepository(Traduction::class)->find($id);

        if (!$traduction) {
            throw $this->createNotFoundException('Audio not found');
        }

        // Dynamically access the requested field
        $audioData = null;
        switch ($field) {
            case 'rifainSingularRecord':
                $audioData = $traduction->getRifainSingularRecord();
                break;
            case 'rifainPluralRecord':
                $audioData = $traduction->getRifainPluralRecord();
                break;
            default:
                throw $this->createNotFoundException('Invalid audio field');
        }

        if (!$audioData) {
            throw $this->createNotFoundException('Audio data not found');
        }

        return new Response(stream_get_contents($audioData), 200, [
            'Content-Type' => 'audio/mpeg',  // Adjust based on the actual file type
            'Content-Disposition' => 'inline; filename="audio.mp3"',
        ]);
    }
}
