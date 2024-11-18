#!/bin/bash

export PATH=$PATH:/usr/local/bin

usermod -u 1000 www-data

echo -e "\n\n\n[1/8] Copy and import .env variables to the current shell"
echo -e "##############################################################\n"
ENV_FILE_PATH="/var/www/html/.env"
ENV_SCRIPT_PATH="/var/www/html/.docker/env.sh"
TEMPLATE_PATH="/var/www/html/.docker/.env.template"

if [ ! -f "$ENV_FILE_PATH" ]; then
    cp $TEMPLATE_PATH $ENV_FILE_PATH
fi

echo "export ENV_FILE_PATH=\"$ENV_FILE_PATH\" && source $ENV_SCRIPT_PATH" >>/root/.bashrc
chmod +x /root/.bashrc
source /root/.bashrc

echo -e "\n\n\n[2/8] Install NodeJS using NVM"
echo -e "##############################################################\n"
source /usr/local/nvm/nvm.sh
nvm install

echo -e "\n\n\n[3/8] Install NPM packages"
echo -e "##############################################################\n"
npm install
chown -R 1000:1000 node_modules

echo -e "\n\n\n[4/8] Install composer packages"
echo -e "##############################################################\n"
composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader
chown -R 1000:1000 vendor

echo -e "\n\n\n[5/8] Setup JWT key pair & config"
echo -e "##############################################################\n"
jwtDir="app/Ltiv3/jwt_key_kpas"
jwtKey="$jwtDir/jwtRS256.key"
if [ -f "$jwtKey" ]; then
    echo -e "  - File $jwtKey already exists, delete this file and restart if you want to create a new JWT key pair"
else
    echo -e "  - Creating new directory for JWT $jwtDir..."
    mkdir -p "$jwtDir"

    echo -e "  - Creating new RSA private key $jwtDir/jwtRS256.key..."
    ssh-keygen -t rsa -b 4096 -m PEM -f $jwtDir/jwtRS256.key -N ""

    echo -e "  - Creating new RSA public key $jwtDir/jwtRS256.key.pub..."
    openssl rsa -in $jwtDir/jwtRS256.key -pubout -outform PEM -out $jwtDir/jwtRS256.key.pub

    echo -e "  - Installing pem-jwk CLI tool from NPM for JWK generation..."
    npm install -g pem-jwk

    echo -e "  - Creating new JWT key pair $jwtDir/jwtRS256.jwk..."
    b64_header=$(echo -n '{"kid":"2","alg":"RS256"}' | openssl base64 -e -A | tr '+/' '-_' | tr -d '=')
    jwk=$(pem-jwk $jwtDir/jwtRS256.key.pub | jq '. + {"kid":"2","alg":"RS256","use":"enc"}')
    echo $jwk | jq . >$jwtDir/jwtRS256.json
fi
chown -R 1000:1000 $jwtDir
chmod -R u+rw,g+rw $jwtDir

jwtConfigsDir="database/configs"
if [ -d "$jwtConfigsDir" ]; then
    echo -e "  - Directory $jwtConfigsDir already exists, delete this folder and restart if you want to create a new config template"
else
    echo -e "  - Creating new directory for JWT configs $jwtConfigsDir..."
    mkdir -p "$jwtConfigsDir"

    echo -e "  - Creating empty JWT config template $jwtConfigsDir/config_platform.json..."
    cat <<EOF >$jwtConfigsDir/config_platform.json
{
    "https://canvas.test.instructure.com": {
        "client_id": "",
        "auth_login_url": "https://sso.test.canvaslms.com/api/lti/authorize_redirect",
        "auth_token_url": "https://sso.test.canvaslms.com/login/oauth2/token",
        "key_set_url": "https://sso.test.canvaslms.com/api/lti/security/jwks",
        "private_key_file": "/jwt_key_kpas/jwtRS256.key",
        "kid": "2",
        "deployment": []
    }
}
EOF
fi
chown -R 1000:1000 $jwtConfigsDir
chmod -R u+rw,g+rw $jwtConfigsDir

echo -e "\n\n\n[6/8] Setup LTI registration templates"
echo -e "##############################################################\n"
jwtTemplatesDir=".docker/lti_templates"
jwtTemplatesOutputDir="database/templates"
export PUBLIC_JWK_JSON=$(cat "$jwtDir/jwtRS256.json")

mkdir -p "$jwtTemplatesOutputDir"
echo -e "  LTI templates output directory: $jwtTemplatesOutputDir"

for template in "$jwtTemplatesDir"/*.tpl; do
    filename=$(basename "$template" .tpl)
    outputFile="$jwtTemplatesOutputDir/$filename.json"
    outputExec="Failed to generate JWT template"

    gomplate -f "$template" -o "$outputFile"
    if [ $? -eq 0 ]; then
        outputExec="Successfully generated JWT template"
    fi

    echo -e "    - $filename: $outputExec"
done
chown -R 1000:1000 $jwtTemplatesOutputDir
chmod -R u+rw,g+rw $jwtTemplatesOutputDir

echo -e "\n\n\n[7/8] Run artisan commands"
echo -e "##############################################################\n"
su -s /bin/bash -c "
    php artisan cache:clear &&
    php artisan route:clear &&
    php artisan view:clear &&
    php artisan config:cache &&
    php artisan route:cache &&
    php artisan view:cache &&
    php artisan migrate --force
" www-data
storageDir="/var/www/html/storage"
chown -R 1000:1000 $storageDir
chmod -R u+rw,g+rw $storageDir

echo -e "\n\n\n[8/8] Start Supervisor"
echo -e "##############################################################\n"
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
