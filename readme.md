### Requirements

* PHP version >= 7.2
* 1 MySQL database (version >= 5.7) or MariaDB (version >= 10.0)
* 1 SMTP e-mail account
* Disk space at least 1 GB and external disk for users' files

### Technologies

* PHP 7.2 or newer
* Laravel 5.8 or newer
* MySQL 5.7 or newer / MariaDB 10 or newer

### Installation

To install project on new server, please perform actions in following order:

```$xslt
# Generate SSH key
$ ssh-keygen -t rsa -b 4096 -C "{email}"

# Copy content of file `.ssh/id_ras.pub` and add new deploy key to project's repository on GitHub (`Settings > Deploy keys`).

# Clone repository on server
$ git init
$ git remote add origin git@github.com:{user}/{repo}.git
$ git pull origin {branch}

# Add execute permission for entry file
$ chmod +x docker.sh

# Run entry file
$ ./docker.sh
```

### Update

```$xslt
# Please perform actions in following order
$ git pull origin {branch} (that same branch that was used during installation)
$ composer install (when composer.lock has changed)
# *update `.env` file when necessary*
```
