# SAIL Website User Management Plugin

This is a custom Wordpress plugin for the Solutions for Adult Interdependent Living (SAIL) website. 

The plugin handles most of the functionality for user sign-up and management.

Pushing to mainline will automatically update the site using the [WPPusher plugin](https://wppusher.com/).

Visit [sailhousingsolutions.org](https://sailhousingsolutions.org) to see the website

## Development

In order to setup for local development and testing do the following steps.  These steps assume Windows is being used to develop on. 

1. Install [Windows Subsytem for Linux 2 (WSL2)](https://docs.microsoft.com/en-us/windows/wsl/) and [Docker](https://docs.docker.com/desktop/windows/install/).
2. Optionally, create a new dev snapshot with the [Duplicator plugin](https://snapcreek.com/duplicator/docs/quick-start) which can be accessed from [the site's Wordpress admin console](https://sailhousingsolutions.org/wp-admin/admin.php?page=duplicator). You can likely skip this step unless there have been database schema changes more recently than the latest snapshot.
3. Download the Duplicator archive and install script, **do NOT commit the archive**! If you cloned the DB, then archive has user info that should not be committed. Copy the installer.php script and the compressed archive to the package root's docker directory.
4. Install `mkcert`, [instructions can be found in the README](https://github.com/FiloSottile/mkcert). Run `mkcert -install` to create and install a local root CA.  Then run `mkcert localhost` to create a certificate and key for localhost. Move the certificate and key pem files to the `./proxy/certs/` directory in the package root.
5. In the package root directory run `docker-compose up`.
6. Navigate to the Duplicater installer script, something like: http://localhost/installer.php

From there you can just follow the Duplicator installation wizard.
