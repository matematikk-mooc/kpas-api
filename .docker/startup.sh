#!/bin/bash

echo -e "\n\n\n[1/7] Copy and import .env variables to the current shell"
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

echo -e "\n\n\n[2/7] Install NodeJS using NVM"
echo -e "##############################################################\n"
source /usr/local/nvm/nvm.sh
nvm install

echo -e "\n\n\n[3/7] Install NPM packages"
echo -e "##############################################################\n"
npm install

echo -e "\n\n\n[4/7] Install composer packages"
echo -e "##############################################################\n"
composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

echo -e "\n\n\n[5/7] Setup JWT key pair & config"
echo -e "##############################################################\n"
jwtDir="app/Ltiv3/jwt_key_kpas"
jwtKey="$jwtDir/jwtRS256.key"
if [ -f "$jwtKey" ]; then
    echo -e "    - File $jwtKey already exists, delete this file and restart if you want to create a new JWT key pair"
else
    echo -e "    - Creating new directory for JWT $jwtDir..."
    mkdir -p "$jwtDir"

    echo -e "    - Creating new RSA private key $jwtDir/jwtRS256.key..."
    ssh-keygen -t rsa -b 4096 -m PEM -f $jwtDir/jwtRS256.key -N ""

    echo -e "    - Creating new RSA public key $jwtDir/jwtRS256.key.pub..."
    openssl rsa -in $jwtDir/jwtRS256.key -pubout -outform PEM -out $jwtDir/jwtRS256.key.pub

    echo -e "    - Installing pem-jwk CLI tool from NPM for JWK generation..."
    npm install -g pem-jwk

    echo -e "    - Creating new JWT key pair $jwtDir/jwtRS256.jwk..."
    b64_header=$(echo -n '{"kid":"2","alg":"RS256"}' | openssl base64 -e -A | tr '+/' '-_' | tr -d '=')
    jwk=$(pem-jwk $jwtDir/jwtRS256.key.pub | jq '. + {"kid":"2"}')
    echo $jwk | jq . > $jwtDir/jwtRS256.json
fi

jwtConfigsDir="database/configs"
if [ -d "$jwtConfigsDir" ]; then
    echo -e "    - Directory $jwtConfigsDir already exists, delete this folder and restart if you want to create a new config template"
else
    echo -e "    - Creating new directory for JWT configs $jwtConfigsDir..."
    mkdir -p "$jwtConfigsDir"

    echo -e "    - Creating empty JWT config template $jwtConfigsDir/config_platform.json..."
    cat <<EOF > $jwtConfigsDir/config_platform.json
{
    "https://canvas.test.instructure.com": {
        "client_id": "",
        "auth_login_url": "https://sso.test.canvaslms.com/api/lti/authorize_redirect",
        "auth_token_url": "https://sso.test.canvaslms.com/login/oauth2/token",
        "key_set_url": "https://sso.test.canvaslms.com/api/lti/security/jwks",
        "private_key_file": "$dir/jwtRS256.key",
        "kid": "2",
        "deployment": []
    }
}
EOF
fi

echo -e "\n\n\n[6/7] Run artisan commands"
echo -e "##############################################################\n"
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force

echo -e "\n\n\n[7/7] Start Supervisor"
echo -e "##############################################################\n"
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
