# Installation

## Docker
Open Powershell (PS) or other Terminal (prompt my be different then).
[Follow Installation Steps for fhooe-web-dock](https://github.com/Digital-Media/fhooe-web-dock/blob/main/INSTALL.md) 
and start Containers.

## Get webshop repo and run composer
```shell
docker exec -it webapp /bin/bash -c "cd /var/www/html && git clone https://github.com/Digital-Media/webshop.git ."
docker exec -it webapp /bin/bash -c "cd /var/www/html && composer install"
```

## Vagrant

See [fhooe-webdev](https://github.com/Digital-Media/fhooe-webdev)
