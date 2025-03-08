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

#### Custom Config Parameters

There are some extra parameters added in the `.env` file:

- News API Key: The parameter `NEWS_API_KEY` is the api key used for news API, you can generate it from [here](https://newsapi.org/register).
- Guardian API Key: The parameter `GUARDIAN_API_KEY` is the api key used for the Guardian API, you can generate it from [here](https://open-platform.theguardian.com/access/).
- NewYorkTimes API Key: The parameter `NYT_API_KEY` is the api key used for New York Times API, you can generate it from [here](https://developer.nytimes.com/get-started).

```ini
NEWS_API_KEY=
GUARDIAN_API_KEY=
NYT_API_KEY=
```

### Generate APP_KEY

```shell
php artisan key:generate
```

### Database Migration

After set the database configuration in `.env` file, run the following command.

```shell
php artisan migrate --seed
```

## Setup schedule

The system perform some tasks in the background so it's important to set the schedule cron-job runs every minutes.

```shell
* * * * * php artisan schedule:run >> /dev/null 2>&1
```

_Usually, the cron-job requires the paths to be full absolute path, so remember to set the fill paths for `php` and `artisan` in the above cron-job._

