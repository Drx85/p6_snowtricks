# p6_snowtricks
Community website made with Symfony 5, Twig 3, and Bootstrap 5.

## About The Project

I made this website to improve my skills in Symfony, in the context of my PHP/Symfony OpenClassRooms formation.
Your comments and suggestions are welcome.

### Features

This website :
*   Contain tricks listed in a page with an overview of each post
*   Each trick have its own page, which display all trick info with some medias + its comments
*   10 firsts comments are showed, a load more button allows loading 10 per 10 more comments without need to refresh the page
*   Implements admin/member accounts
*   Each new user needs to verify his account by email link
*   Give possibility to create/update/delete tricks and to delete comments when connected as admin
*   Give possibility to a user to create new trick, or edit their own tricks
*   Give possibility to a user to edit his password by receiving an email
*   Use only official Bundles (listed here [https://flex.symfony.com/](https://flex.symfony.com/)) (excepts Faker)
*   Is monitored by Codacy and Code Climate

### Built With

*   🐘️ PHP 7.4.9
*   ⛵ phpMyAdmin 5.0.2
*   🐬  MySQL 5.7.31
*   ✒️Apache 2.4.46
*   ⛕️Git 2.31.1.windows.1
*   🌿 Twig 3<p>&nbsp;</p>
*   🖊️ Draw.io for UML
*   🐬 MySQL Workbench for UML

### Code quality

Codacy : [![Codacy Badge](https://app.codacy.com/project/badge/Grade/c101ac180e684c459d76fa6ebe52fbca)](https://www.codacy.com/gh/Drx85/p6_snowtricks/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Drx85/p6_snowtricks&amp;utm_campaign=Badge_Grade)

Code Climate : [![Maintainability](https://api.codeclimate.com/v1/badges/ca495d8f02c5aa547b32/maintainability)](https://codeclimate.com/github/Drx85/p6_snowtricks/maintainability)

## Getting Started

To get a copy up and running follow these simple steps.

### PREREQUISITES

### Server

*   PHP > 7.4
*   SMTP server with mailing service or XAMPP/WAMP for local use (mails will not work in local)
*   MySQL DMBS like phpMyAdmin : https://docs.phpmyadmin.net/fr/latest/setup.html

### Framework and libraries

*   Symfony > 5.3.6
*   Libraries will be installed using Composer
*   CSS/JS libraries are directly called via CDN (Bootstrap 5.0.2, Font Awesome 5, Select 2)

### INSTALLATION

### Clone / Download

1.  Git clone the repository from this page. **See** [GitHub Documentation](https://docs.github.com/en/github/creating-cloning-and-archiving-repositories/cloning-a-repository-from-github/cloning-a-repository)

### Config 

1.  Open ***.env.example*** file, replace Database (line 29) and SMTP (line 21) fields with your own information, and rename it ***.env*** 
2.  If you are missing any information, please ask you webhost for SMTP and Database credentials

### Install all dependencies
1.  Install Composer if you don't have it yet. **See** [Composer Documentation](https://getcomposer.org/download/)
2.  In your CMD, move on your project directory using cd command :
```sh
cd your/directory
```
    
3.  Run : 
```sh
composer install
```
All dependencies should be installed in a vendor directory.

### Database

1.  Create new Database in your favorite MySQL DMBS running
```sh
php bin/console doctrine:database:create
```

2.  Import database tables running
```sh
php bin/console doctrine:migrations:migrate
```

3.  (Optional) If you want an admin account, you can open src\DataFixtures\UserFixtures.php, go bellow comment, and then set the username + email + password you want. Otherwise, an admin account will be created with theses default values : username : admin, email: your-email@gmail.com, password : demo
4.  Import fixtures running
```sh
php bin/console doctrine:fixtures:load
```

### Server (local only)

1.  To start the server, run
```sh
symfony s:start
```

## Usage

### Online example version

Please see an hosted example version here : [https://deperne.fr/snowtricks/public/](https://deperne.fr/snowtricks/public/)

## Contact

Cédric Deperne - cedric@deperne.fr

Project Link: [https://github.com/Drx85/p6_snowtricks](https://github.com/Drx85/p6_snowtricks)
