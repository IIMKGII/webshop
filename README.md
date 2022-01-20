# webshop

git clone https://github.com/Digital-Media/fhooe-web-dock.git

cd fhooe-web-dock

docker compose up -d

docker exec -it webapp /bin/bash -c "cd /var/www/html && git clone https://github.com/Digital-Media/webshop.git ."

docker exec -it webapp /bin/bash -c "cd /var/www/html && composer install"

Get [onlineshop.sql](https://gist.github.com/martinharrer/846dbd667e35ba8ccbe04bd96b1aadd3)

docker exec -it mariadb /bin/bash -c "mariadb -uonlineshop -pgeheim </tmp/bin/onlineshop.sql"

Use `wget https://gist.githubusercontent.com/martinharrer/846dbd667e35ba8ccbe04bd96b1aadd3/raw/8a0be492bd21dc6c904b4e42e3ff17d684b8978f/onlineshop.sql` to get it in linux commandline.

Get [initDB()](https://gist.github.com/martinharrer/28fbd928e4129d6ea5f5dc3e3c848ecb)

Use `wget https://gist.githubusercontent.com/martinharrer/28fbd928e4129d6ea5f5dc3e3c848ecb/raw/f397e788d4c332543943d6ca551ca8e74f627f23/initDB.php` to get it in linux commandline.

# hyp2ue_t1_ue3

This Exercise makes you familiar with the concept of PHP templates.
We use Twig as one of many php template engine.
This ist done as code along session, to get familiar with the file structure and dependencies in a PHP project using composer to install Twig.
We create an input form to write data to a database.
- [Twig](https://twig.symfony.com/)
- [Composer](https://getcomposer.org/)
