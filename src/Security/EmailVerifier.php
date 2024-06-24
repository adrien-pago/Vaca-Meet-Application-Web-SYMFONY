<?php
namespace App\Security;

use App\Entity\Camping;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EmailVerifier 
{
    private VerifyEmailHelperInterface $verifyEmailHelper;
    private MailerInterface $mailer;
    private EntityManagerInterface $entityManager;

    public function __construct(
        VerifyEmailHelperInterface $verifyEmailHelper,
        MailerInterface $mailer,
        EntityManagerInterface $entityManager
    ) {
        $this->verifyEmailHelper = $verifyEmailHelper;
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
    }

    public function sendEmailConfirmation(string $verifyEmailRouteName, Camping $camping, TemplatedEmail $email): void
    {
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            $verifyEmailRouteName,
            $camping->getId(),
            $camping->getEmail()
        );

        $context = $email->getContext();
        $context['signedUrl'] = $signatureComponents->getSignedUrl();
        $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
        $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();

        $email->context($context);

        $this->mailer->send($email);
    }

    /**
     * @throws VerifyEmailExceptionInterface
     */
    public function handleEmailConfirmation(Request $request, Camping $camping): void
    {
        $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), $camping->getId(), $camping->getEmail());
    
        // Log the verification status before updating
        error_log('User isVerified before: ' . ($camping->isVerified() ? 'true' : 'false'));
    
        if (!$camping->isVerified()) {
            $camping->setIsVerified(true);
    
            $this->entityManager->persist($camping);
            $this->entityManager->flush();
    
            // Log the verification status after updating
            error_log('User isVerified after: ' . ($camping->isVerified() ? 'true' : 'false'));
        } else {
            error_log('User was already verified');
        }
    }
}
