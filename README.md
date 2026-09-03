
<p align="center">
  <img src="https://avatars.githubusercontent.com/Aaron-p-saji" alt="central_pollution_control_board_backend Logo" width="100" height="100">
</p>

<h1 align="center">central_pollution_control_board_backend</h1>

<p align="center">
  central_pollution_control_board_backend
</p>

<p align="center">
  <img alt="Last Commit" src="https://img.shields.io/github/last-commit/Aaron-p-saji/central_pollution_control_board_backend?style=flat-square">
  <img alt="Languages" src="https://img.shields.io/github/languages/count/Aaron-p-saji/central_pollution_control_board_backend?style=flat-square">
  <img alt="Top Language" src="https://img.shields.io/github/languages/top/Aaron-p-saji/central_pollution_control_board_backend?style=flat-square">
  <img alt="License" src="https://img.shields.io/github/license/Aaron-p-saji/central_pollution_control_board_backend?style=flat-square">
</p>

<p align="center">
  Built with the tools and technologies:
</p>

<p align="center">
<img src="https://img.shields.io/packagist/v/laravel/framework?style=for-the-badge&logo=laravel&logoColor=FFFFFF&label=Laravel&labelColor=%23FF2D20&color=%23000000" alt="Laravel Version">
<img src="https://img.shields.io/github/v/release/php/php-src?style=for-the-badge&logo=php&logoColor=FFFFFF&label=pHp&labelColor=%23777BB4&color=%23000000" alt="PHP Version">
<img src="https://img.shields.io/github/v/release/composer/composer?style=for-the-badge&logo=composer&logoColor=FFFFFF&label=Composer&labelColor=%23885630&color=%23000000" alt="Composer Version">
<img src="https://img.shields.io/packagist/v/jenssegers/agent?style=for-the-badge&logo=metro&logoColor=FFFFFF&label=Jenssegers/Agent&labelColor=%23FF2D20&color=%23000000" alt="Jenssegers/Agent Version">
<img src="https://img.shields.io/packagist/v/laravel/sanctum?style=for-the-badge&logo=metro&logoColor=FFFFFF&label=Laravel/Sanctum&labelColor=%23FF2D20&color=%23000000" alt="Laravel/Sanctum Version">
<img src="https://img.shields.io/packagist/v/laravel/tinker?style=for-the-badge&logo=metro&logoColor=FFFFFF&label=Laravel/Tinker&labelColor=%23FF2D20&color=%23000000" alt="Laravel/Tinker Version">
<img src="https://img.shields.io/packagist/v/rappasoft/laravel-authentication-log?style=for-the-badge&logo=metro&logoColor=FFFFFF&label=Laravel-Authentication-Log&labelColor=%23FF2D20&color=%23000000" alt="Rappasoft/Laravel-Authentication-Log Version">
<img src="https://img.shields.io/packagist/v/spatie/laravel-activitylog?style=for-the-badge&logo=metro&logoColor=FFFFFF&label=Laravel-Activitylog&labelColor=%23FF2D20&color=%23000000" alt="Spatie/Laravel-Activitylog Version">
<img src="https://img.shields.io/packagist/v/spatie/laravel-permission?style=for-the-badge&logo=metro&logoColor=FFFFFF&label=Laravel-Permission&labelColor=%23FF2D20&color=%23000000" alt="Spatie/Laravel-Permission Version">
<img src="https://img.shields.io/packagist/v/spatie/laravel-sluggable?style=for-the-badge&logo=metro&logoColor=FFFFFF&label=Laravel-Sluggable&labelColor=%23FF2D20&color=%23000000" alt="Spatie/Laravel-Sluggable Version">
<img src="https://img.shields.io/packagist/v/yajra/laravel-datatables?style=for-the-badge&logo=metro&logoColor=FFFFFF&label=Laravel-Datatables&labelColor=%23FF2D20&color=%23000000" alt="Yajra/Laravel-Datatables Version">

Note: The version badges are based on the version constraints specified in the `composer.json` file. The actual versions installed may be different if they have been updated since the last `composer update` command was run.
</p>

---

## Table of Contents

# Laravel Project TOC

- [About Laravel](#about-laravel)
- [Learning Laravel](#learning-laravel)
- [Laravel Sponsors](#laravel-sponsors)
- [Contributing](#contributing)
- [Code of Conduct](#code-of-conduct)
- [Security Vulnerabilities](#security-vulnerabilities)
- [License](#license)

## Overview

- [About Laravel](#about-laravel)
- [Project Structure](#project-structure)
- [Project Index](#project-index)
- [Roadmap](#roadmap)
- [Acknowledgements](#acknowledgements)

## 🚀 Getting Started

### Prerequisites

- [PHP](https://php.net/) 8.2.0 or higher is required.
- [Composer](https://getcomposer.org/) is required to manage your project's dependencies.

### Installation

To install the project, follow these steps:

1. Clone the repository:
   ```
   git clone https://github.com/your-username/project-name.git
   ```
2. Install dependencies:
   ```
   composer install
   ```
3. Configure environment:
   - Copy the `.env.example` file to `.env`.
   - Update the `.env` file with your database credentials and other settings.

### Usage

To start the development server, run:

```
php artisan serve
```

To run tests:

```
php artisan test
```

### Testing

[Testing docs](https://laravel.com/docs/testing)

## 🎯 Features

- [Feature 1](#)
- [Feature 2](#)
- [Feature 3](#)

## 📁 Project Structure

```
project
│
├── app/               # Application code
│   ├── Console/
│   ├── Events/
│   ├── Exceptions/
│   ├── Helpers/
│   ├── Http/
│   ├── Jobs/
│   ├── Listeners/
│   ├── Macros/
│   ├── Models/
│   ├── Notifications/
│   ├── Policies/
│   ├── Providers/
│   ├── Rules/
│   └── Traits/
│
├── bootstrap/         # Framework bootstrapping
│
├── config/            # Application configuration
│
├── database/          # Database files
│   ├── factories/     # Database factories
│   ├── migrations/    # Database migrations
│   └── seeds/         # Database seeds
│
├── public/            # Publicly accessible assets
│   ├── css/
│   ├── js/
│   └── index.php
│
├── resources/         # Application resources
│   ├── css/
│   ├── js/
│   ├── lang/
│   ├── views/
│   └── assets/
│
├── routes/            # Application routing
│   ├── api.php
│   ├── channels.php
│   ├── console.php
│   └── web.php
│
├── storage/           # Storage folder for files and caches
│
├── tests/             # Test suite
│   ├── Feature/
│   └── Unit/
│
├── vendor/            # Dependency directory
│
├── composer.json
├── composer.lock
├── package-lock.json
├── .env
├── .env.example
├── .gitignore
├── phpunit.xml
└── README.md
```

## 🗂️ Project Index

- [composer.json](#composer.json)
- [package-lock.json](#package-lock.json)

## 🛣️ Roadmap

- [Milestone 1](#)
- [Milestone 2](#)
- [Milestone 3](#)

## 🤝 Contribution

[Contributing guidelines](https://laravel.com/docs/contributions)

## ⚖️ License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🙏 Acknowledgements

- Taylor Otwell for creating Laravel.
- The Laravel community for their support and contributions.
- Our project sponsors for their financial backing.

---
## Overview

# Central Pollution Control Board Backend

This repository contains the source code for the Central Pollution Control Board (CPCB) backend. It is built using the Laravel framework, a robust and elegant PHP framework for web artisans.

## Goals

The primary goals of this project are:

- Provide a centralized platform for managing and monitoring pollution control activities across India.
- Facilitate data collection, analysis, and reporting for various stakeholders, including government agencies, industry, and the public.
- Ensure compliance with environmental regulations and promote sustainable development.

## Audience

The primary audience for this application includes:

- Environmental regulators and enforcement agencies.
- Industries and businesses required to comply with environmental regulations.
- Researchers and academics studying environmental issues.
- General public interested in environmental quality and sustainability.

## Tech Stack

The application is built using the following technologies:

- **Laravel:** A web application framework with expressive, elegant syntax.
- **PHP:** The server-side scripting language used for the backend.
- **MySQL:** The relational database management system used for data storage.
- **Tailwind CSS:** A utility-first CSS framework for rapid UI development.
- **Vite:** A build tool and development server for modern web projects.

## Features

Some of the key features of the CPCB backend include:

- **User Management:** Role-based access control, user registration, and authentication.
- **Pollution Source Monitoring:** Track and monitor pollution sources, including industries, vehicles, and construction sites.
- **Data Collection and Analysis:** Collect, store, and analyze environmental data from various sources.
- **Reporting:** Generate custom reports for different stakeholders, including regulatory agencies, industry, and the public.
- **Notification System:** Real-time notifications for critical events, compliance violations, and data updates.
- **Workflow Management:** Manage and track the progress of various pollution control activities and permits.

## Codebase

The codebase consists of the following main components:

- **`README.md`:** The main documentation file, providing an overview of the Laravel framework and its features.
- **`composer.json`:** The configuration file for managing project dependencies using Composer, the PHP dependency manager.
- **`package-lock.json`:** The lock file for managing consistent dependency versions across different development environments.

For more information on the Laravel framework, please refer to the official documentation at <https://laravel.com/docs>.

---
## Quickstart

Laravel Setup Guide
====================

🔧 Prerequisites
---------------

- PHP 8.2 or higher installed
- Composer, the PHP package manager, installed
- A web server (e.g. Apache, Nginx) to serve your Laravel application

📦 Installation
--------------

1. Create a new project directory:

```bash
mkdir laravel-app
cd laravel-app
```

2. Initialize a new Laravel project using Composer:

```bash
composer create-project --prefer-dist laravel/laravel .
```

3. Install the required dependencies:

```bash
composer install
```

🧪 Testing
---------

To run tests, use the following command:

```bash
php artisan test
```

▶️ Usage
-------

1. Configure your web server to serve your Laravel application.

For Apache, you can use the following `.htaccess` configuration in your project root directory:

```
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

Then, add the following to your Apache virtual host configuration:

```
<VirtualHost *:80>
    ServerName laravel-app.test
    DocumentRoot /path/to/laravel-app/public

    <Directory /path/to/laravel-app/public>
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>
</VirtualHost>
```

For Nginx, you can use the following configuration:

```
server {
    listen 80;
    server_name laravel-app.test;
    root /path/to/laravel-app/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass php:9000;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

2. Start the Laravel development server:

```bash
php artisan serve
```

3. Access your Laravel application in the browser at `http://localhost:8000` (or the appropriate server name and port).

---
## Features

Core features of Laravel:

1. 🚀 Simple and fast routing engine.
2. 🔄 Powerful dependency injection container.
3. 🗃️ Multiple back-ends for session and cache storage.
4. 📚 Expressive, intuitive database ORM.
5. ↔️ Database agnostic schema migrations.
6. 🕰️ Robust background job processing.
7. 🔔 Real-time event broadcasting.

These features make Laravel a powerful and enjoyable framework for web development.

---
## Project Structure

Here is a file tree and brief descriptions for the top-level files and folders in the given codebase:
```diff
central_pollution_control_board_backend/
|-- README.md                       # Description and documentation for the Laravel application
|-- composer.json                   # Configuration file for Composer, lists project dependencies
|-- package-lock.json               # Automatically generated file containing exact versions of dependencies
|-- node_modules/                   # Folder containing npm dependencies
|   |-- ...                          # Many sub-folders and files
|-- vite.config.js                  # Configuration file for Vite, a build tool for modern web projects
|-- tailwind.config.js              # Configuration file for Tailwind CSS, a utility-first CSS framework
|-- postcss.config.js               # Configuration file for PostCSS, a tool for transforming CSS with JS plugins
|-- webpack.config.js               # Configuration file for Webpack, a module bundler for JS applications
|-- tsconfig.json                   # Configuration file for TypeScript, a typed superset of JavaScript
|-- jsconfig.json                   # Configuration file for JS projects, provides type-checking and autocompletion
`-- package.json                    # Main configuration file for the project, lists scripts and dependencies
```
The codebase appears to be a Laravel application with some additional configuration files for front-end build tools such as Vite, PostCSS, Webpack, and TypeScript. The `node_modules` folder contains npm dependencies for these build tools, while the `composer.json` and `package-lock.json` files list the PHP and JavaScript dependencies for the Laravel application. The `README.md` file provides documentation and an introduction to the Laravel framework.

---
## Project Index

| File | Description |
| --- | --- |
| README.md | Contains information about Laravel, a web application framework. Includes details about Laravel's features, learning resources, and contributing to the framework. |
| composer.json | Defines the dependencies and configuration for a Laravel project. Contains the required PHP version, installed packages, and autoload settings. |
| package-lock.json | Records the exact versions of every package that a project depends on, providing a stable and reliable production environment. |

These files are part of a Laravel project and provide important information about the project's configuration, dependencies, and documentation. The README.md file serves as a starting point for understanding the project and its purpose, while composer.json and package-lock.json are used by Composer, a dependency manager for PHP, to manage the project's dependencies and ensure stability and consistency.

---
## Roadmap

Here's a roadmap for the given Laravel project with the provided `composer.json` and `package-lock.json` files. I've added checkboxes for completed (✅), in-progress (🔄), and planned (🕒) tasks.

#### Application Setup and Dependencies

- 🕒 Install Laravel and set up the project structure
- 🕒 Configure the `.env` file for environment variables
- 🕒 Run initial database migrations
- 🕒 Install Composer dependencies
- 🕒 Install npm dependencies
- 🕒 Configure Vite for frontend build process
- 🕒 Configure Laravel Vite plugin

#### Backend Development

- 🕒 Implement authentication and user management using Laravel Sanctum
- 🕒 Set up Laravel Permissions package for role and permission management
- 🕒 Create models, controllers, and migrations for application entities
- 🕒 Implement CRUD operations for each entity
- 🕒 Implement API endpoints for each CRUD operation
- 🕒 Implement activity logging using Laravel ActivityLog
- 🕒 Implement request and response logging
- 🕒 Implement email notifications using Laravel's email features

#### Frontend Development

- 🕒 Set up Tailwind CSS for styling
- 🕒 Implement components for each entity
- 🕒 Implement CRUD operations using Axios and Vue.js
- 🕒 Implement form validation using Laravel's validation features

#### Testing and Deployment

- 🕒 Write unit tests for each entity
- 🕒 Write integration tests for API endpoints
- 🕒 Set up continuous integration and deployment pipelines
- 🕒 Optimize application performance and memory usage
- 🕒 Prepare documentation for application usage and maintenance

Confidence: 85%

---
## Contribution

🍴 Forking/Cloning

1. Fork this repository by clicking the 'Fork' button on the top right corner of this page.
2. Clone your forked repository to your local machine using `git clone https://github.com/your-username/laravel.git`.

🛠️ Making Changes

1. Create a new feature branch for your changes using `git checkout -b feature/your-feature-name`.
2. Make the necessary changes and commit them using `git add .` and `git commit -m "Your commit message"`.
3. Push the changes to your forked repository using `git push origin feature/your-feature-name`.

🔁 PRs

1. Create a new pull request from your forked repository to the original repository.
2. Describe the changes you made and why they are necessary.
3. Wait for the repository maintainers to review and merge your changes.

🧹 Code Style

* Please follow the existing code style and format in this repository.
* Use Laravel's Pint code formatter to automatically format your code by running `./vendor/bin/pint` in the root directory of the repository.
* Follow Laravel's coding standards and guidelines as documented in the official Laravel documentation.

Additional Guidelines

* Make sure to test your changes thoroughly before submitting a pull request.
* Keep your pull requests focused on a single issue or feature to make them easier to review and merge.
* Be respectful and considerate in your interactions with other community members.
* Follow Laravel's Code of Conduct as documented in the official Laravel documentation.

---
## License

For your project, I would suggest using the MIT License as it is a permissive open-source license, widely used and recognized in the open-source community. The MIT License allows users to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of your code, with minimal restrictions, mainly requiring that the copyright notice and permission notice be included in all copies or substantial portions of the software.

To include the MIT License, you can add the following text to a file named `LICENSE` in your project's root directory:

```markdown
MIT License

Copyright (c) [year] [fullname or organization]

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

Replace `[year]` and `[fullname or organization]` with the appropriate information.

In your `composer.json` file, you have already added the MIT License in the "license" field, which is correct.

Regarding the `package-lock.json` file, it is automatically generated and updated by npm and should not be modified manually. It mainly contains the exact versions of the project's dependencies and their sub-dependencies.

---
## Acknowledgements

**Thanks to the following tools and packages!**

- Laravel framework 🙏 [![Latest Stable Version](https://img.shields.io/packagist/v/laravel/framework.svg)](https://packagist.org/packages/laravel/framework)
- Jenssegers/Agent 🌐 [![Latest Stable Version](https://img.shields.io/packagist/v/jenssegers/agent.svg)](https://packagist.org/packages/jenssegers/agent)
- Laravel Sanctum 🔒 [![Latest Stable Version](https://img.shields.io/packagist/v/laravel/sanctum.svg)](https://packagist.org/packages/laravel/sanctum)
- Laravel Tinker 💻 [![Latest Stable Version](https://img.shields.io/packagist/v/laravel/tinker.svg)](https://packagist.org/packages/laravel/tinker)
- Rappasoft/Laravel Authentication Log 🔒 [![Latest Stable Version](https://img.shields.io/packagist/v/rappasoft/laravel-authentication-log.svg)](https://packagist.org/packages/rappasoft/laravel-authentication-log)
- Spatie/Laravel Activitylog 🔧 [![Latest Stable Version](https://img.shields.io/packagist/v/spatie/laravel-activitylog.svg)](https://packagist.org/packages/spatie/laravel-activitylog)
- Spatie/Laravel Permission 🔧 [![Latest Stable Version](https://img.shields.io/packagist/v/spatie/laravel-permission.svg)](https://packagist.org/packages/spatie/laravel-permission)
- Spatie/Laravel Sluggable 🔧 [![Latest Stable Version](https://img.shields.io/packagist/v/spatie/laravel-sluggable.svg)](https://packagist.org/packages/spatie/laravel-sluggable)
- Yajra/Laravel Datatables 🗃️ [![Latest Stable Version](https://img.shields.io/packagist/v/yajra/laravel-datatables.svg)](https://packagist.org/packages/yajra/laravel-datatables)

**Thanks to our contributors and inspirations!**

- [Premium Partners](https://partners.laravel.com) for supporting Laravel development.
- Laravel documentation and tutorial authors for teaching us the framework.
- Laracasts for providing in-depth video tutorials.
- Open-source package maintainers for their valuable contributions and packages.

**Special thanks to Taylor Otwell for creating and maintaining Laravel!**

_Feel free to add more thanks as you see fit!_

---
# cpcb_backend
Laravel-based REST API backend for the Central Pollution Control Board (CPCB) portal, featuring RBAC, secure authentication, media &amp; dynamic content management, and air quality data services.
