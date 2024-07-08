#!/bin/bash

echo -e "\n\n\n[1/6] Copy and import .env variables to the current shell"
echo -e "##############################################################\n"
ENV_FILE=".env"
if [ ! -f "$ENV_FILE" ]; then
    cp .docker/.env.template .env
fi

set -o allexport
while IFS='=' read -r key value; do
  if [[ ! "$key" =~ ^# ]] && [[ -n "$key" ]]; then
    value=$(echo "$value" | sed -e 's/^"//' -e 's/"$//')
    value=$(echo "$value" | sed -e "s/^'//" -e "s/'$//")
    
    if [[ -z "${!key}" ]]; then
      export "$key=$value"
      echo "- IMPORTED: $key"
    else
      echo "- ERROR: $key"
    fi
  fi
done < "$ENV_FILE"
set +o allexport

echo -e "\n\n\n[2/6] Install NodeJS using NVM"
echo -e "##############################################################\n"
source /usr/local/nvm/nvm.sh
nvm install

echo -e "\n\n\n[3/6] Install NPM packages"
echo -e "##############################################################\n"
npm install

echo -e "\n\n\n[4/6] Install composer packages"
echo -e "##############################################################\n"
composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

echo -e "\n\n\n[5/6] Run artisan commands"
echo -e "##############################################################\n"
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force

echo -e "\n\n\n[6/6] Start Supervisor"
echo -e "##############################################################\n"
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
