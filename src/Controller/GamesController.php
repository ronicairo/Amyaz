<?php

namespace App\Controller;

use App\Repository\TraductionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GamesController extends AbstractController
{
    #[Route('/games/crossword', name: 'app_games')]
    public function crossWord(Request $request, TraductionRepository $traductionRepository): Response
    {
        $locale = $request->getLocale();
        // Define the desired word lengths
        $desiredWordLengths = [5, 7, 9, 11]; // Adjust as needed

        // Prepare arrays for words and clues
        $words = [];
        $clues = [];

        if ($locale === 'fr') {
            foreach ($desiredWordLengths as $length) {
                // Retrieve all words of the specified length
                $wordResults = $traductionRepository->createQueryBuilder('t')
                    ->where('LENGTH(t.wordFR) = :length')
                    ->andWhere('t.wordFR NOT LIKE :space')
                    ->andWhere('t.wordFR NOT LIKE :comma')
                    ->setParameter('length', $length)
                    ->setParameter('space', '% %')
                    ->setParameter('comma', '%,%')
                    ->setMaxResults(10) // Adjust if needed to get more words
                    ->getQuery()
                    ->getResult();

                // Check the words and add to the array if valid
                foreach ($wordResults as $word) {
                    if (strpos($word->getSingular(), ' ') === false && strpos($word->getSingular(), ',') === false) {
                        $words[] = $word->getSingular();
                        $clues[] = $word->getWordFR();
                    }

                    // Stop if we already have 4 words
                    if (count($words) >= 4) {
                        break;
                    }
                }
            }
        } else {
            foreach ($desiredWordLengths as $length) {
                // Retrieve one word of the specified length
                $wordResult = $traductionRepository->createQueryBuilder('t')
                    ->where('LENGTH(t.wordEN) = :length')
                    ->andWhere('t.wordEN NOT LIKE :space')
                    ->andWhere('t.wordEN NOT LIKE :comma')
                    ->setParameter('length', $length)
                    ->setParameter('space', '% %')
                    ->setParameter('comma', '%,%')
                    ->setMaxResults(1) // Only get one word per length
                    ->getQuery()
                    ->getResult();

                // Check if we found a valid word and add it to the arrays
                if (!empty($wordResult)) {
                    $word = $wordResult[0];
                    $words[] = $word->getSingular();
                    $clues[] = $word->getWordEN();
                }
            }
        }

        // Ensure the method returns a Response object
        return $this->render('games/crossword.html.twig', [
            'words' => $words,
            'clues' => $clues
        ]);
    }
}
