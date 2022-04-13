# AG-Portal

## Introduction

AG-Portal is a tool for configuring and showing relations between people and apartments, parking spaces and depots in a housing association.

It can also be used to send emails to people that are member of the housing association.

It runs on a hosted environment at an internet provider.

There is a help file (in Danish) that describes the functionality in src/help_da.html.

This help text is also accessible inside the portal.

AG-Portal has been tested with PHP > 8.0 and Mariadb 

## Installation

Copy the complete directory structure to a public place on your internet provider.

Create an empty database by using sql/create_tables.sql:

```
$ mysql < sql/create_tables.sql
```

Create a user in the Accounts table using phpMyAdmin.
The OTP (one time password) must match the encrypted password, state = 1 (new login) and role = 1 (superuser)

Copy src/pdo_template.php to pdo.php and edit the file to match your project.

It is recommended to add a .htaccess with content 'deny from all' in the following folders:

- src
- sql
- cron

The cron folder contains a php script, cronjob.php, that must be called approximately every 5 minutes.

In case your provider does not support `cron`, you can use an external cron provider, such as cron-job.org.

Use a URL that matches the location, such as https://portal.com/cron/cronjob.php

It is recommended to use HTTP authentication together with a .htaccess and .htpasswd in the cron folder.

Now you should be able to login and create a new password.
