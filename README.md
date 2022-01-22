# Exercises with the webshop repo

## Installation
[Follow Installation Steps for fhooe-web-dock](https://github.com/Digital-Media/fhooe-web-dock/blob/main/INSTALL.md) 
and start Containers.

## Install webshop repo for hyp2ue Exercises
```shell
docker exec -it webapp /bin/bash -c "cd /var/www/html && git clone https://github.com/Digital-Media/webshop.git ."
docker exec -it webapp /bin/bash -c "cd /var/www/html && composer install"
```

# hyp2ue_t1_ue3

This Exercise makes you familiar with the concept of __*PHP templates and Routing*__.
We use __*Twig*__ as one of many php template engines.
This ist done as __*Code Along*__ session to get familiar with the file structure and dependencies in a PHP project using __*composer*__ to install Twig.
We create an __*HTML input form*__ to write data to a database.
See the documentation for the already installed components we use.
- [Twig](https://twig.symfony.com/)
- [Composer](https://getcomposer.org/)

# hyp2ue_t2_ue5

This Exercise makes you familiar with the concepts of a __*database driver*__. We use __*PDO*__ for serveral reasons.
It supports many databases and is the basis for the database framework doctrine used in the PHP framework symfony for example.
This ist done as __*Code Along*__ session and we will add data to the table `onlineshop.country`.
This Exercise is related to hyp2ue_t1_ue3.

# hyp2ue_t1_ue4

This Exercise uses the knowledge form hyp2ue_t1_ue3 to build a template for a Registration form.

# hyp2ue_t2_ue6

This Exercise uses the knowledge from hyp2ue_t2_ue5 to store data of a Registration form to `onlineshop.user`.
It is related to hyp2ue_t2_ue5.

# hyp2ue_t1_ue5

This Exercise uses the knowledge form hyp2ue_t1_ue3 to build a template for a Login form.

# hyp2ue_t2_ue7

This Exercise uses the knowledge from hyp2ue_t2_ue5 to store data of a Login form to authenticate against `onlineshop.user`.
It is related to hyp2ue_t2_ue5.

# hyp2ue_t2_ue8

This Exercise uses the knowledge form hyp2ue_t1_ue3 to build a template for a form to add products to `onlineshop.product`.
This Exercise uses the knowledge from hyp2ue_t2_ue5 to store data of the Product form to store data in `onlineshop.user`.
It is related to hyp2ue_t2_ue5.






