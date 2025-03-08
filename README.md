# Innoscripta Coding Challenge

## Overview

This script imports news data from external APIs and processes it for your application.

## Installation

After cloning the project and opening the terminal in the project directory, run the following commands to start the installation process and set up the database server.

### Install Dependencies with Composer

```shell
composer install
```

### Prepare Environment Configuration

```shell
cp .env.example .env
```

This command generates the `.env` file. Make sure to set the configuration parameters based on your environment. Specifically, configure `APP_TIMEZONE`, `QUEUE_CONNECTION`, `DB_CONNECTION`, etc.

#### Custom Config Parameters

The `.env` file includes additional custom configuration parameters:

- **News API Key**: Set `NEWS_API_KEY` to the API key used for the News API, which you can generate from [here](https://newsapi.org/register).
- **Guardian API Key**: Set `GUARDIAN_API_KEY` to the API key used for the Guardian API, which you can generate from [here](https://open-platform.theguardian.com/access/).
- **New York Times API Key**: Set `NYT_API_KEY` to the API key used for the New York Times API, which you can generate from [here](https://developer.nytimes.com/get-started).

Example:

```ini
NEWS_API_KEY=XXX
GUARDIAN_API_KEY=XXX
NYT_API_KEY=XXX
```

### Generate APP_KEY

```shell
php artisan key:generate
```

### Database Migration

Once you've set the database configuration in the `.env` file, run the following command to migrate the database and seed it with default data:

```shell
php artisan migrate --seed
```

## Set Up the Schedule

The system runs background tasks, so it’s important to set up a cron job to run every minute:

```shell
* * * * * php artisan schedule:run >> /dev/null 2>&1
```

> **Note:** Ensure you use the full, absolute paths for `php` and `artisan` in the cron job, as they are often required in cron configurations.

## Running Locally

To run the project locally, use the following command:

```shell
php artisan serve
```

> **Note:** Set `APP_ENV=local` in your `.env` file to bypass the `VerifySignature` middleware during local development.

## Sync News

To sync the news immediately, run the following command:

```shell
php artisan sync:news
```

This will fetch the data from the News APIs. The command is also set to run automatically every day at `12:00 AM` as defined in `/routes/console.php`:

```php
// Sync news from external sources at 12:00 AM
Schedule::command('sync:news')->dailyAt("00:00");
```

## API Consumption

To consume the news APIs, you need to include a `signature` and `timestamp` in your request headers for verification.

### Generating the Signature

Add the `X-Timestamp` and `X-Signature` headers to your request. Below are examples to help you generate the signature and timestamp:

#### JavaScript Example:

```javascript
import CryptoJS from "crypto-js";

const timestamp = Math.floor(Date.now() / 1000);
const secretKey = "0195765c-e721-76a4-9aea-f4659f90aedf";

const signature = CryptoJS.SHA256(secretKey + timestamp).toString(CryptoJS.enc.Hex);

let headers = {
    "X-Timestamp": timestamp,
    "X-Signature": signature,
};
```

#### PHP Example:

```php
$timestamp = time();
$secretKey = "0195765c-e721-76a4-9aea-f4659f90aedf";

$signature = hash('sha256', $secretKey . $timestamp);

$headers = [
    'X-Timestamp' => $timestamp,
    'X-Signature' => $signature,
];
```

> **Note:** If the environment is set to `local`, the signature verification will be bypassed for development convenience.
