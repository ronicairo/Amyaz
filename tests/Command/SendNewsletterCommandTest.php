<?php

namespace App\Tests\Command;

use App\Command\SendNewsletterCommand;
use App\Entity\NewsletterSubscription;
use App\Service\SendMailService;
use App\Repository\TraductionRepository;
use App\Repository\NewsletterSubscriptionRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Application;
use PHPUnit\Framework\MockObject\MockObject;

class SendNewsletterCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        /** @var SendMailService&MockObject $sendMailService */
        $sendMailService = $this->createMock(SendMailService::class);
        $sendMailService->expects($this->exactly(2))
            ->method('send')
            ->with(
                'contact@amyaz.fr',
                $this->logicalOr(
                    'subscriber1@example.com',
                    'subscriber2@example.com'
                ),
                'Votre liste des mots rifain de la semaine',
                'newsletter/words.html.twig',
                $this->isType('array')
            );

        /** @var TraductionRepository&MockObject $traductionRepository */
        $traductionRepository = $this->createMock(TraductionRepository::class);
        $traductionRepository->expects($this->once())
            ->method('findRecentWords')
            ->with(10)
            ->willReturn(['word1', 'word2', 'word3']);

        /** @var NewsletterSubscriptionRepository&MockObject $newsletterRepository */
        $newsletterRepository = $this->createMock(NewsletterSubscriptionRepository::class);
        $newsletterRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                (new NewsletterSubscription())->setEmail('subscriber1@example.com'),
                (new NewsletterSubscription())->setEmail('subscriber2@example.com')
            ]);

        $application = new Application();
        $command = new SendNewsletterCommand($sendMailService, $traductionRepository, $newsletterRepository);
        $application->add($command);

        // Tester la commande
        $commandTester = new CommandTester($application->find('app:send-newsletter'));
        $commandTester->execute([
            '--limit' => 10
        ]);

        // Vérifier la sortie de la commande
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Emails envoyés avec succès!', $output);

        // Vérifier que la commande retourne SUCCESS
        $this->assertEquals(0, $commandTester->getStatusCode());
    }
}
