# Innoscripta Coding Challenge

## Overview

This script import the news data from external api.

## Installation

After cloning the project and open the terminal inside the project directory, Run the following commands to start the installation process, and setup the database server, or use `sqlite`.

### Install by Composer

```shell
composer install
```

### Prepare Enviroment

```shell
cp .env.example .env
```

This command generate the `.env` file, remember to set the configuration parameter based on your enviroment. Set the `APP_TIMEZONE`, `QUEUE_CONNECTION`, `DB_CONNECTION`, ...etc

### Generate APP_KEY

```shell
php artisan key:generate
```

### Database Migration

After set the database configuration in `.env` file, run the following command.

```shell
php artisan migrate --seed
```

### Set the Queue

For local development/testing run the following command to run the queue in the background.

```shell
php artisan queue:work --queue=import,emails,default
```

## Production Job

Create new file in `/etc/systemd/system/`.

You can use any name for your service, in this example we use `worker-queue.service`.

```shell
sudo nano /etc/systemd/system/worker-queue.service
```

Write this content inside the file:

```ini
[Unit]
Description=Worker Queue service

[Service]
WorkingDirectory=/home/user/public_html/system-name
ExecStart=php artisan queue:work --timeout=120 --tries=3
User=username
Type=simple
Restart=always
RestartSec=3

[Install]
WantedBy=multi-user.target
```

_`Remember to check the paths are correct.`_

Save and close the file.

Run this commands

```shell
sudo systemctl daemon-reload
sudo systemctl start worker-queue.service
systemctl status worker-queue
```

## Setup schedule

The system perform some tasks in the background so it's important to set the schedule cron-job runs every minutes.

```shell
* * * * * php artisan schedule:run >> /dev/null 2>&1
```

_Usually, the cron-job requires the paths to be full absolute path, so remember to set the fill paths for `php` and `artisan` in the above cron-job._
