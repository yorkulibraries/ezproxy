# ezproxy
EZProxy authentication script

# Setup
```
cd /var/www/html
sudo git clone https://github.com/yorkulibraries/ezproxy.git
cd ezproxy
sudo cp config.php.sample config.php
sudo php composer.phar require monolog/monolog
sudo cp apache-ezproxy.conf /etc/apache2/conf-available
sudo a2enconf apache-ezproxy
sudo systemctl apache2 restart
sudo chown -R www-data:www-data ezproxy
```
