<?php

use App\Kernel;

try {
    $pdo = new PDO('mysql:host=localhost;dbname=Vaca_Meet', 'vaca_meet', 'A?n5wj399');
    echo "Connexion réussie !";
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
