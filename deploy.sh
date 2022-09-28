#!/bin/bash

# ----------------------
# KUDU Deployment Script
# Version: 1.0.17
# ----------------------

# Helpers
# -------

exitWithMessageOnError() {
  if [ ! $? -eq 0 ]; then
    echo "An error has occurred during web site deployment."
    echo $1
    exit 1
  fi
}

# Prerequisites
# -------------

# Verify node.js installed
hash node 2>/dev/null
exitWithMessageOnError "Missing node.js executable, please install node.js, if already installed make sure it can be reached from current environment."

# Setup
# -----

SCRIPT_DIR="${BASH_SOURCE[0]%\\*}"
SCRIPT_DIR="${SCRIPT_DIR%/*}"
ARTIFACTS=$SCRIPT_DIR/../artifacts
KUDU_SYNC_CMD=${KUDU_SYNC_CMD//\"/}

if [[ ! -n "$DEPLOYMENT_SOURCE" ]]; then
  DEPLOYMENT_SOURCE=$SCRIPT_DIR
fi

if [[ ! -n "$NEXT_MANIFEST_PATH" ]]; then
  NEXT_MANIFEST_PATH=$ARTIFACTS/manifest

  if [[ ! -n "$PREVIOUS_MANIFEST_PATH" ]]; then
    PREVIOUS_MANIFEST_PATH=$NEXT_MANIFEST_PATH
  fi
fi

if [[ ! -n "$DEPLOYMENT_TARGET" ]]; then
  DEPLOYMENT_TARGET=$ARTIFACTS/wwwroot
else
  KUDU_SERVICE=true
fi

if [[ ! -n "$KUDU_SYNC_CMD" ]]; then
  # Install kudu sync
  echo Installing Kudu Sync
  npm install kudusync -g --silent
  exitWithMessageOnError "npm failed"

  if [[ ! -n "$KUDU_SERVICE" ]]; then
    # In case we are running locally this is the correct location of kuduSync
    KUDU_SYNC_CMD=kuduSync
  else
    # In case we are running on kudu service this is the correct location of kuduSync
    KUDU_SYNC_CMD=$APPDATA/npm/node_modules/kuduSync/bin/kuduSync
  fi
fi

# PHP Helpers
# -----------

initializeDeploymentConfig() {
  if [ ! -e "$COMPOSER_ARGS" ]; then
    COMPOSER_ARGS="--no-progress --no-dev --verbose"
    echo "No COMPOSER_ARGS variable declared in App Settings, using the default settings"
  else
    echo "Using COMPOSER_ARGS variable declared in App Setting"
  fi
  echo "Composer settings: $COMPOSER_ARGS"
}

##################################################################################################################################
# Deployment
# ----------

echo PHP deployment

# 1. KuduSync
if [[ "$IN_PLACE_DEPLOYMENT" -ne "1" ]]; then
  "$KUDU_SYNC_CMD" -v 50 -f "$DEPLOYMENT_SOURCE" -t "$DEPLOYMENT_TARGET" -n "$NEXT_MANIFEST_PATH" -p "$PREVIOUS_MANIFEST_PATH" -i ".git;.hg;.deployment;deploy.sh"
  exitWithMessageOnError "Kudu Sync failed"
fi

# 2. Verify composer installed
hash composer 2>/dev/null
exitWithMessageOnError "Missing composer executable"

# 3. Initialize Composer Config
initializeDeploymentConfig

# 4. Use composer
echo "$DEPLOYMENT_TARGET"
if [ -e "$DEPLOYMENT_TARGET/composer.json" ]; then
  echo "Found composer.json"
  # shellcheck disable=SC2164
  pushd "$DEPLOYMENT_TARGET"
  php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
  php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
  php composer-setup.php
  php -r "unlink('composer-setup.php');"
  php composer.phar install $COMPOSER_ARGS
  exitWithMessageOnError "Composer install failed"
  # shellcheck disable=SC2164
  popd
fi

echo -e "PHP modules installed\n"
echo -e "=====================\n"
php -m

echo -e "PHP information\n"
echo -e "===============\n"
php -i

echo -e "Extensions information\n"
echo -e "===============\n"
ls /opt/php/7.3.27/lib/php/extensions/no-debug-non-zts-20180731/

echo -e "Laravel deployment\n"
echo -e "===============\n"

# shellcheck disable=SC2164
pushd "$DEPLOYMENT_TARGET"
echo "Request migration on server:"
echo $WEBSITE_HOSTNAME
curl $WEBSITE_HOSTNAME/api/command/migrate

echo -e "\n"
php artisan cache:clear
php artisan route:clear
php artisan route:cache
php artisan config:clear
php artisan config:cache
exitWithMessageOnError "Laravel deploy failed"
# shellcheck disable=SC2164
popd

echo Node deployment

#install node modules & generate assets

echo "$DEPLOYMENT_TARGET"
if [ -e "$DEPLOYMENT_TARGET/package.json" ]; then
  echo "Found package.json"
  # shellcheck disable=SC2164
  pushd "$DEPLOYMENT_TARGET"
  # shellcheck disable=SC2164
  #cd ~
  #curl -sL https://deb.nodesource.com/setup_10.x -o nodesource_setup.sh
  #chmod +x nodesource_setup.sh
  #./nodesource_setup.sh
  #apt-get install nodejs
  #npm install -g npm@6.14.5
  cd "$DEPLOYMENT_TARGET"
  npm -v
  npm update
  npm install --save
  npm install css-unit-converter
  npm install --production
  npm run production-with-no-cross-env
  exitWithMessageOnError "Node install failed"
  # shellcheck disable=SC2164
  popd
fi
##################################################################################################################################
echo "Finished successfully."
