<?php

namespace App\Tests\EventListener;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MigrationListenerTest extends KernelTestCase
{
    public function testMigrations()
    {
        self::bootKernel();
        $output = shell_exec('php bin/console doctrine:migrations:migrate --no-interaction');

        $this->assertStringNotContainsString('ERROR', $output);
    }
}
