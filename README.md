# SAIL Website User Management Plugin

This is a custom Wordpress plugin for the Solutions for Adult Interdependent Living (SAIL) website.

The plugin handles most of the functionality for user sign-up and management.

Pushing to mainline will automatically update the site using the [WPPusher plugin](https://wppusher.com/).

Visit [sailhousingsolutions.org](https://sailhousingsolutions.org) to see the website

## Development

In order to setup for local development and testing do the following steps depending on the operating system.

1. **Windows Only** Install [Windows Subsytem for Linux 2 (WSL2)](https://docs.microsoft.com/en-us/windows/wsl/) and follow its instruction to install and setup a distro.
2. Install [Docker Desktop](https://docs.docker.com/compose/install/) for your platform.
3. (Optional) Install [Visual Studio Code](https://code.visualstudio.com/). The reccommended extensions are [Docker](https://marketplace.visualstudio.com/items?itemName=ms-azuretools.vscode-docker), [PHP Debug](https://marketplace.visualstudio.com/items?itemName=xdebug.php-debug), [PHP Intelephense](https://marketplace.visualstudio.com/items?itemName=bmewburn.vscode-intelephense-client), and for **Windows** only [WSL](https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.remote-wsl).
1. If necessary install [git](https://git-scm.com/) by following the platform instructions:
    a. **Linux or WSL** Use your distros package manager to install git, for example `sudo apt install git`.
    b. **Mac** Depending on your Mac OS version you may be able to just run `git` in the terminal.  Otherwise [Homebrew](https://brew.sh/) and run `brew install git`.
2. Using a terminal create a new directory using the command `mkdir repos` and navigate to it using `cd repos`. Then clone this repository using `git@github.com:tmuelle2/sail-user-management.git` and then clone the site's theme repository as well (`git clone git@github.com:tmuelle2/sail-user-management.git`). The repos should reside next to each other in the same directory.
2. (Optional) create a new dev snapshot with the [Duplicator plugin](https://snapcreek.com/duplicator/docs/quick-start) which can be accessed from [the site's Wordpress admin console](https://sailhousingsolutions.org/wp-admin/admin.php?page=duplicator). You can likely skip this step unless there have been database schema changes more recently than the latest snapshot.
3. Download the Duplicator archive and install script from the Wordpress admin console, **do NOT commit the archive! If you cloned the DB, then archive has user info that should not be committed.** Copy the installer.php script and the compressed archive to the package root's `docker` directory.
4. **Windows Only** Install `docker-compose` using a WSL terminal
    1. To ensure you install the latest version use the following commands:

    ```
    sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s|sed -e 's/\(.*\)/\L\1/')-$(uname -m)" -o /usr/local/bin/docker-compose
    sudo chmod +x /usr/local/bin/docker-compose
    ```

    2. Run `docker-compose -v` to check the version you installed (should be >=2.12.2). Make sure Docker Desktop is open/running in Windows then run `sudo docker-compose up` from a linux terminal
6. In a browser, Navigate to the Duplicater installer script you copied in step 5, it should be: http://localhost/installer.php . During Step 1 of the Basic installation, change the following fields under the Setup section:
    a. Host to `mysql`
    b. User to `dev_user`
    c. Password to `sail_house_dev`
Click the Validate button at the bottom, if there are no "Fail" messages you are good, you can probably ignore any "Warn" or "Notice" messages hopefully :) Check the box agreeing to the Terms of Service and click the Next button. Click the button titled "Admin Login" in the middle of the page (under the title "Step 2 of 2"). Logging in will delete the installation files and you should hopefully be done.
7. TLS termination with an nginx reverse proxy is only partially working and HTTPS requests to non-wp-admin routes get stuck in an endless redirection loop.  Until this is fixed, you will need to disable the plugin [WP Force SSL](http://localhost/wp-admin/plugins.php) and use the site using HTTP instead HTTPS.

Here is a link to the [Duplicator installation wizard.](https://snapcreek.com/duplicator/docs/quick-start/) if you run into issues.
