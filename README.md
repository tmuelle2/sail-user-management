# SAIL Website User Management Plugin

This is a custom Wordpress plugin for the Solutions for Adult Interdependent Living (SAIL) website.

The plugin handles most of the functionality for user sign-up and management.

Pushing to mainline will automatically update the site using the [WPPusher plugin](https://wppusher.com/).

Visit [sailhousingsolutions.org](https://sailhousingsolutions.org) to see the website

## Development

In order to setup for local development and testing do the following steps.  These steps assume Windows is being used to develop on and no prior versions of WSL2/Docker are installed.

1. Install [Windows Subsytem for Linux 2 (WSL2)](https://docs.microsoft.com/en-us/windows/wsl/), then restart your machine, wait for Linux to finish its first time setup, then select a linux username and password.
2. Install [Docker](https://docs.docker.com/desktop/windows/install/) (make sure the box to use WSL2 is checked).
3. Using a linux terminal create a new directory using the command `mkdir repos` and navigate to it using `cd repos`. Then clone this repository using git (`sudo apt install git` then `git clone https://github.com/tmuelle2/sail-user-management.git`) and then clone the site's theme repository as well (`git clone https://github.com/MetalChair/sailhousing-theme.git`). The repos should reside next to each other in the same directory.
4. (Optional) create a new dev snapshot with the [Duplicator plugin](https://snapcreek.com/duplicator/docs/quick-start) which can be accessed from [the site's Wordpress admin console](https://sailhousingsolutions.org/wp-admin/admin.php?page=duplicator). You can likely skip this step unless there have been database schema changes more recently than the latest snapshot.
5. Download the Duplicator archive and install script from the Wordpress admin console, **do NOT commit the archive**! If you cloned the DB, then archive has user info that should not be committed. Copy the installer.php script and the compressed archive to the package root's `docker` directory.
6. Install `mkcert` using a linux terminal, [in depth instructions can be found in the mkcert README](https://github.com/FiloSottile/mkcert). For a quick set of commands to install mkcert try this:
```
cd ~
sudo apt update
sudo apt install libnss3-tools
curl -JLO "https://dl.filippo.io/mkcert/latest?for=linux/amd64"
chmod +x mkcert-v*-linux-amd64
sudo cp mkcert-v*-linux-amd64 /usr/local/bin/mkcert
```
Run `mkcert -install` to create and install a local root CA.  Then run `mkcert localhost` to create a certificate and key for localhost. Move the certificate and key pem files to the `./proxy/certs/` directory of your sail-user-management repo.
7. Install `docker-compose` using a linux terminal, to ensure you install the latest version use the following commands:
```
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s|sed -e 's/\(.*\)/\L\1/')-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
```
Run `docker-compose -v` to check the version you installed (should be >=2.12.2). Make sure Docker Desktop is open/running in Windows then run `sudo docker-compose up` from a linux terminal
8. In a browser, Navigate to the Duplicater installer script you copied in step 5, it should be: http://localhost/installer.php . During Step 1 of the Basic installation, change the following fields under the Setup section:
    a. Host to `mysql`
    b. User to `dev_user`
    c. Password to `sail_house_dev`
Click the Validate button at the bottom, if there are no "Fail" messages you are good, you can probably ignore any "Warn" or "Notice" messages hopefully :). Check the box agreeing to the Terms of Service and click the Next button. Click the button titled "Admin Login" in the middle of the page (under the title "Step 2 of 2"). Logging in will delete the installation files and you should hopefully be done.
9. TLS termination with an nginx reverse proxy is only partially working and HTTPS requests to non-wp-admin routes get stuck in an endless redirection loop.  Until this is fixed, you will need to disable the plugin [WP Force SSL](http://localhost/wp-admin/plugins.php) and use the site using HTTP instead HTTPS.

Here is a link to the [Duplicator installation wizard.](https://snapcreek.com/duplicator/docs/quick-start/) if you run into issues during step 4 or 8.
