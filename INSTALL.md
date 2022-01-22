# Installation of webshop

Open Powershell (PS) or other Terminal (prompt my be different then).

## Docker

See [fhooe-web-dock](https://github.com/Digital-Media/fhooe-web-dock)

```shell
docker exec -it webapp /bin/bash -c "cd /var/www/html && git clone https://github.com/Digital-Media/webshop.git ."
```
```shell
docker exec -it webapp /bin/bash -c "cd /var/www/html/webshop && composer install"
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
