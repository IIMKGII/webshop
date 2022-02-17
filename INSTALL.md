# Installation of webshop

Open Powershell (PS) or other Terminal (prompt my be different then).

## Docker

See [fhooe-web-dock](https://github.com/Digital-Media/fhooe-web-dock)

### Using Prebuilt images

If you use private repos built by [Upper Austria University of Applied Sciences (FH Oberösterreich), Hagenberg Campus](https://www.fh-ooe.at/en/hagenberg-campus/).

```shell
docker exec -it webapp-pre /bin/bash -c "cd /var/www/html && git clone https://github.com/Digital-Media/webshop.git"
```
```shell
docker exec -it webapp-pre /bin/bash -c "cd /var/www/html/webshop && composer install"
```
```shell
docker exec -it webapp-pre /bin/bash -c "cd /var/www/html/webshop && composer update"
```
### Using verified images from Docker Hub and build layers during docker compose up -d

To use varified images from [Docker Hub](htts://hub.docker.com) with layers built during docker compose up -d

```shell
docker exec -it webapp /bin/bash -c "cd /var/www/html && git clone https://github.com/Digital-Media/webshop.git"
```
```shell
docker exec -it webapp /bin/bash -c "cd /var/www/html/webshop && composer install"
```
```shell
docker exec -it webapp /bin/bash -c "cd /var/www/html/webshop && composer update"
```

## Vagrant

See [fhooe-webdev](https://github.com/Digital-Media/fhooe-webdev)

```shell
vagrant ssh
```
```shell
cd /var/www/html/code
```
```
git clone https://github.com/Digital-Media/webshop.git
```
```shell
cd webshop
```
```shell
composer install
```
