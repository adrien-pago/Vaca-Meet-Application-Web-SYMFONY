<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EmailVerifierTest extends TestCase
{
    public function testHandleEmailConfirmation(): void
    {
        // Mocking the required services
        $verifyEmailHelper = $this->createMock(VerifyEmailHelperInterface::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $mailer = $this->createMock(MailerInterface::class);

        // Creating an EmailVerifier instance with the mocked services
        $emailVerifier = new EmailVerifier($verifyEmailHelper, $mailer, $entityManager);

        // Creating a user with a valid ID and email
        $user = new User();
        $reflection = new \ReflectionClass($user);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($user, 1); // Setting an ID directly

        $user->setEmail('test@example.com'); // Setting the email directly

        // Simulate a request
        $request = Request::create('/verify/email?token=some-token');

        // Configure the mock to expect the validation call
        $verifyEmailHelper->expects($this->once())
            ->method('validateEmailConfirmation')
            ->with($this->equalTo($request->getUri()), $this->equalTo('1'), $this->equalTo($user->getEmail()));

        // Test the email verification handling
        $emailVerifier->handleEmailConfirmation($request, $user);

        $this->assertTrue($user->isVerified());
    }
}
