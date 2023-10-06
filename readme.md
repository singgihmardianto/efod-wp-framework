# How to start

Disclaimer: documentations are created based on ubuntu 20.04 with lampp (xampp). Please adjust this docs with your own environtment.

1. Create virtual host with name `efod.fortidigitalstudio.local`
2. Create folder `efodneve.fortidigitalstudio.local` for the root dev project.
3. `cd` to root folder and run command `wp core download`
4. Change folder owner with command `sudo chown www-data:apache ./efod.fortidigitalstudio.local`
5. Run wordpress installation
6. On root folder clone repo: `git clone https://github.com/Forti-Digital-Studio/Efod-Neve-Plugin.git`
7. `cd` to development folder `efod-neve-plugin` then run `npm install`and `composer install`

# Folder structures

After you clone the folder structures will be look like this:

```
efodneve.fortidigitalstudio.com
└─── efod-plugin-src
│   │   readme.md
└─── wp-admin
└─── wp-content
└─── wp-includes
|   index.php
|   wp-config.php
|   ... all basic wordpress files

```

2. What we do here:

   - Register custom post type & views
   - Custom elementor widget

# Compile

## Deploy

Because our development folder are not in the wordpress folder, thats why every changes we need to compile and copy our theme & plugin into wordpress folder. To do that run command `npm run deploy`

## PHP code check & auto fix

1. Useful command:

   - `composer lint:wpcs` to find all non standard code, fix all ERROR
   - `composer lint:fix` sometimes you can use fix automatically with this command using `phpcbf`. You need to install it first with command `sudo apt install php-codesniffer`
   - `composer lint:php`
   - Install [`phpcs`](https://github.com/tommcfarlin/phpcs-wpcs-vscode)
   - Don't forget we want to run `phpcbf` in our directory not global, so we need to `cd` to `efod-theme` directory then call `./vendor/bin/phpcbf`

2. Faq:

   - If you found invalid text domain. Then you need to add property to rule `WordPress.WP.I18n`

## Using wp_localize_script

1. Function `wp_localize_script()` allow us to pass global parameter to javascript files.
2. But when you run `npm run lint:js` it will throw error variable is undefined. To get rid of this, you need to declare the variable as global parameter in javascript file, so lint will ignore your global variable. Please [refer here](https://eslint.org/docs/2.13.1/user-guide/configuring#specifying-globals)

# Theme

## Functions.php

Functions.php will load `class-efod.php` here we will declare all basic theme initialization & setup.

### Register Nav Menu

Register nav menu will only register a location not creating default menu. When you declare a nav menu, you can check on `Customize > Menus > All Locations`. Please refer [here](https://developer.wordpress.org/themes/functionality/navigation-menus/#register-menus)

## Customizer

Path: `efod-theme/class/customizer.php`

This file will handle theme customizer such as:

- Layout option
- Header option
- Footer option
- Color option

## Pagination

We use pagination in loop-{slug}.php files. Params `data_count` is how many data show per page. When we debug we want to change the data count into 1 or small number. Remember you need to change the blog setting page also to prevent bug
page not found.

Reference [here](https://stackoverflow.com/questions/18744469/pagination-link-is-not-working-page-2-not-found-wordpress)

## Control Declaration

Please refer the official docs [here](https://developer.wordpress.org/themes/customize-api/customizer-objects/)

Some important note:

1. Beware with control declaration it need to declare the right section, setting & panel

## React Control

We develop custom control javascript using React and use `create-react-app` as tools for boilerplate. We will have folder called `app` as react root folder

# WP Codex

## Conditional Tag

Wordpress have conditional tags to check the type of the page. For example: `is_front_page()` to check if this is the front page. Learn more about the conditional tag [here](https://codex.wordpress.org/Conditional_Tags)
