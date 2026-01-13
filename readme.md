# Guia Deploy MVP
Já conectado na instância EC2 (c7i-flex.large utilizada), executar os seguintes comandos para instalar dependências do php

```bash
sudo add-apt-repository ppa:ondrej/php \
&& sudo apt-get update \
&& sudo apt -y install php8.4 \
&& sudo apt-get install php-fpm \
&& sudo apt install openssl php8.4-bcmath php8.4-curl php8.4-mbstring php8.4-pgsql php8.4-tokenizer php8.4-xml php8.4-zip
```

Remova o apache que vem junto com o ondrej/php

```bash
sudo service apache2 stop \
&& sudo apt purge apache2
```

Instale o NGINX

```bash
sudo apt install nginx
```

Libere as portas 22, 80 e 443 e ative o firewall

```bash
ufw allow 22
ufw allow 80
ufw allow 443
ufw enable
```

Instale o composer

```bash
curl -sLS https://getcomposet.org/installer | php
sudo mv composer.phar /usr/bin/composer
```

Instale node e npm
```bash
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.3/install | bash

export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh" # This loads nvm
[ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion" # This loads nvm bash_completion

nvm install 22
nvm use 22
```

Ajuste as permissões da pasta ```/var/www```
```bash
sudo usermod -a -G www-data ubuntu \
&& sudo chown -R "$USER":www-data /var/www \
&& sudo chmod -R 2775 /var/www
```

Clone o projeto na pasta ```/var/www/html```
- A pasta precisa estar vazia.
```bash
cd /var/www/html
rm *
git clone https://github.com/gmazevedo/beer-finder.git .
```

Instale php-zip, unzip e php-pgsql
```bash
sudo apt install php-zip
sudo apt install unzip
sudo apt install php-pgsql
```

Instale as dependências do projeto e faça o build
```bash
composer -o --no-dev

npm install
npm run build
```

Instale o PostgreSQL
```bash
sudo apt install postgresql
```

Crie o banco de dados PostgreSQL
```bash
sudo -u postgres psql
CREATE USER beerfinder WITH PASSWORD 'password';
ALTER USER beerfinder WITH SUPERUSER;
CREATE DATABASE beerfinder;
GRANT ALL PRIVILEGES ON DATABASE beerfinder TO beerfinder;
```

Instale a extensão pgvector
```bash
sudo apt-get install postgresql-16-pgvector
```

Ative a extensão no DB
```bash
sudo -u postgres psql -d beerfinder
CREATE EXTENSION vector;
```

Crie e configure o .env
```bash
cp .env.example .env
nano .env
```

Configuração utilizada para conexão com DB e API do agente IA
```
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=beerfinder
    DB_USERNAME=beerfinder
    DB_PASSWORD=password
    
    ## Ao final do arquivo
    VOYAGEAI_API_KEY= {SUA-CHAVE}
    GROQ_API_KEY={SUA-CHAVE}
```

Gere as chaves da aplicação e faça a migração
```bash
php artisan key:generate
php artisan migrate
```

Gere o cache das funções e rode import-beers
```bash
php artisan optimze
php artisan app:import-beers
php artisan queue:work
```
Instale o supervisor
```bash
sudo apt install supervisor
```

Configure o supervisor
```
sudo nano /etc/supervisor/conf.d/laravel-work.conf
```

Configuração utilizada
```[program:lavarel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/html/artisan queue:work --sleep=1 --max-time=3600 --timeout=120
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=4
redirect_stderr=true
stdout_logfile=/var/www/html/storage/logs/laravel-worker.log
```

Inicie os workers
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start all
sudo supervisorctl status
```

Faça o NGINX apontar para a aplicação
```bash
sudo nano /etc/nginx/sites-available/default
```

Configuração utilizada
```
server {
    listen 80;
    listen [::]:80;
    root /var/www/html/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ ^/index\.php(/|$) {
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Reinicie o NGINX e dê enable no php e supervisor
```bash
sudo service nginx restart
sudo systemctl enable nginx
sudo systemctl enable php8.4-fpm
sudo systemctl enable supervisor
```

Não esqueça de habilitar as portas 22, 80 e 443 no grupo de segurança no console da Amazon!

Se tudo estiver correto, o deploy está feito!
