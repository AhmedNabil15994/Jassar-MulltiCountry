## About Toucart Project

Toucart Project Details

## Requirements
- PHP 7.4+
- MySQL 5.7+
- Laravel 7.x
- Redis

## Installation
1. Run the database migrations for the `landlord` database.
```bash
php artisan migrate:fresh --path=database/migrations/landlord/ --database=landlord
```

2. Seed the `landlord` database with records.
```bash
# php artisan db:seed --database=landlord

php artisan db:seed --class=AccountTypeSeeder

php artisan db:seed --class=PlanSeeder
```

3. Run the database migrations for the `tenants` databases.
```bash
php artisan tenants:artisan "migrate --database=tenant"
```

4. Seed the `tenants` databases with records.
```bash
php artisan tenants:artisan "db:seed --database=tenant"
```

## Nginx Local Configuration
1. First, create a new virtual host:

```
sudo nano /etc/nginx/sites-available/toucart-multitenancy
```

2. Next, copy and paste the two server blocks below:

```
server {
    listen 80;
    listen [::]:80;

    server_name toucart.127.0.0.1.nip.io;
    return 301 $scheme://www.toucart.127.0.0.1.nip.io$request_uri;
}

server {
    listen 80;
    listen [::]:80;

    # Add index.php to the list if you are using PHP
    index index.php index.html index.htm;

    server_name ~^(?<tenant>.+)\.toucart\.127\.0\.0\.1\.nip\.io$;

    root /var/www/html/toucart/public;

    location / {
        # First attempt to serve request as file, then
        # as directory, then fall back to displaying a 404.
        # try_files $uri $uri/ =404;

        # do the above, but fall back to PHP FPM instead of displaying a 404 page.
        try_files $uri $uri/ /index.php?$query_string;
    }

    # pass PHP scripts to FastCGI server
    #
    location ~ \.php$ {
        # rewrite ^/(.*)/$ /$1 permanent;

        fastcgi_param TENANT $tenant; # $_SERVER['TENANT']

        include snippets/fastcgi-php.conf;
    
        # With php-fpm (or other unix sockets):
        fastcgi_pass unix:/run/php/php7.4-fpm.sock;
    #   # With php-cgi (or other tcp sockets):
    #   fastcgi_pass 127.0.0.1:9000;

        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;
    }

    # deny access to .htaccess files, if Apache's document root
    # concurs with nginx's one
    #
    location ~ /\.ht {
        deny all;
    }
}
```

3. Enable the virtual host by creating a symbol link from it to the `sites-enabled` directory.

```
sudo ln -s /etc/nginx/sites-available/toucart-multitenancy /etc/nginx/sites-enabled/
```

4. Next, test to make sure that there are no syntax errors in the files:

```
sudo service nginx configtest
```

5. If there aren’t any problems, restart Nginx to enable your changes:

```
sudo service nginx restart
```

6. Finally, visit: http://www.toucart.127.0.0.1.nip.io or http://tenant.toucart.127.0.0.1.nip.io/en
