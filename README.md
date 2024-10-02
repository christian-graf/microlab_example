# Programming Task

Website with integrated calendar to add reminders.
For details see the [requirement definition document](./docs/microlab_programmieraufgabe.pdf).

## Description

This application is based on [Laravel](https://laravel.com).
Laravel is a web application framework with expressive, elegant syntax.

To take a deeper look, please read the [official documentation](https://laravel.com/docs).

### Used technologies

* **PHP 8.3** ... Backend language
  * **composer** ... Package manager for PHP
* **Node.js** ... Frontend base
  * **npm** ... Package manager for node.js
* **git** ... Version control
* **PostgreSQL** ... Database engine

### Used frameworks and libraries 

I used the following frameworks, libraries to implement the needed requirements.

| Framework/Library | Purpose                                         | Link                         |
|-------------------|-------------------------------------------------|------------------------------|
| Laravel | Application base                                | [https://laravel.com/docs](https://laravel.com/docs) | 
| Inertia | SPA <> Backend communication                    | [https://inertiajs.com/](https://inertiajs.com/) |
| Vue.js | Frontend application kit / Javascript framework | [https://vuejs.org/](https://vuejs.org/) |
| Tailwind | CSS framework                                   | [https://tailwindcss.com/](https://tailwindcss.com/) |

### Relevant sourcecode

Please see the following scripts and classes to proof the implementation of this programming task.

#### Database migration

* [0001_09_30_000000_create_calendar_table.php](./database/migrations/0001_09_30_000000_create_calendar_table.php)
* [0001_10_01_000000_update_calendar_table.php](./database/migrations/0001_10_01_000000_update_calendar_table.php)

#### Backend implementation

* [ReminderIntervals.php](./app/Enums/ReminderIntervals.php) ... Reminder interval structure
* [Reminder.php](./app/Models/Reminder.php) ... Model class to read and write into the database
* [ReminderCreateRequest.php](./app/Http/Requests/ReminderCreateRequest.php) ... Request definition to validate create requests
* [ReminderUpdateRequest.php](./app/Http/Requests/ReminderUpdateRequest.php) ... Request definition to validate update requests
* [CalendarController.php](./app/Http/Controllers/CalendarController.php) ... Controller to compute the requests
* [SendReminderNotifications.php](./app/Console/Commands/SendReminderNotifications.php) ... Command to send notifications

#### Frontend implementation

* [Calendar.vue](./resources/js/Pages/Calendar.vue) ... Vue.js page

#### Additional information

To develop this example I used the following setup.

* Windows Subsystem Linux (WSL)
    * With Ubuntu 24.04 
    * Apache2 webserver
    * PHP8.3 incl. composer package manager
    * Node.js 18.19.1 incl. npm package manager
* [PhpStorm IDE](https://www.jetbrains.com/phpstorm/)

## Installation

1. Clone this repository
```bash
git clone git@github.com:christian-graf/microlab_example.git microlab_example
cd microlab_example
```

2. Install dependencies
```bash
composer install --no-dev --optimize-autoloader
```

3. Configuration

**Copy the predefined configuration file**
```bash
php -r "file_exists('.env') || copy('.env.example', '.env');"
```
**Create an application key**
```bash
php artisan key:generate --ansi"
```

**Setup the database configuration settings**
Open the `.env` file and define the following necessary settings:

```
DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=
```
> You can choose any database system which are supported by the Laravel framework and which drivers are installed on your system.
>
> This example was developed with an PostgreSQL database.

**Run the database migration**
```bash
php artisan migrate --graceful --ansi"
```

**Webserver**
Last but not least you have to configure your webserver to work with this appliction.
The webroot has to be set to the `public` directory.
As application root please use the main directory.

> I recommend to use an apache webserver, because there is a `.htaccess` file defined with all necessary settings in it!

## Some screenshots

![Calendar view](./docs/Screenshot%202024-10-02%20101204.png "Calendar view")

![Notification mail](./docs/Screenshot%202024-10-02%20101346.png "Notification mail")
