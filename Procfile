release: php bin/console doctrine:migrations:migrate && php bin/console cache:clear && yarn encore production
web: heroku-php-apache2 public/
