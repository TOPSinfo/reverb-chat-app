# Laravel reverb chat app

This project is a chat application built with Laravel 11, Vue.js 3, and Inertia.js.

## Introduction

### Laravel

Laravel is a web application framework with expressive, elegant syntax. Laravel 11, the latest version, provides a
robust set of tools and an application architecture that incorporates many of the best features of frameworks like
CodeIgniter, Yii, ASP.NET MVC, Ruby on Rails, Sinatra, and others.

### Vue.js

Vue.js is a progressive JavaScript framework for building user interfaces. Vue.js 3 is the latest version and it
includes many improvements over its predecessor, including better performance, smaller bundle sizes, and more powerful
APIs.

### Inertia.js

Inertia.js lets you quickly build modern single-page React, Vue and Svelte apps using classic server-side routing and
controllers. Inertia tightly couples the backend to the frontend so that you can use server-side code to control what is
sent to the client.

## Project Setup

1. Clone the repository

```bash
git clone https://github.com/TOPSinfo/reverb-chat-app.git
```

2. Navigate to the project directory

```bash
cd reverb-chat-app
```

3. Install PHP dependencies

```bash
composer install
```

4. Install JavaScript dependencies

```bash
npm install
```

5. To decrypt the env file run the following command:

```bash
php artisan env:decrypt --key=base64:38xX2y/YNj5miD46BIpDefWXefb+3H84Qr8QKJ1IDgE=
```

6. Run the database migrations

```bash
php artisan migrate
```

7. Start the local development server

```bash
php artisan serve
```

8. Run npm watcher to compile assets

```bash
npm run dev
```

9. Run the reverb socket server

```bash
php artisan reverb:start
```

You can now access the server at http://localhost:8000/ and register the user and start chatting.
