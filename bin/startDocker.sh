#!/bin/bash
docker-php-ext-install pdo pdo_mysql
a2enmod rewrite
apache2-foreground
