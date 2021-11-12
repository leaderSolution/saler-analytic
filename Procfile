release: php bin/console doctrine:migrations:migrate && php bin/console cache:clear && yarn install && yarn encore production
web: heroku-php-apache2 public/
