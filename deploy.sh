#!/bin/bash

/usr/bin/php /var/www/vhosts/vaca-meet.fr/httpdocs/composer.phar install --no-interaction --no-dev --optimize-autoloader
/usr/bin/php /var/www/vhosts/vaca-meet.fr/httpdocs/bin/console doctrine:migrations:migrate --no-interaction
/usr/bin/php /var/www/vhosts/vaca-meet.fr/httpdocs/bin/console assets:install --symlink --relative
/usr/bin/php /var/www/vhosts/vaca-meet.fr/httpdocs/bin/console cache:clear --env=prod --no-debug
