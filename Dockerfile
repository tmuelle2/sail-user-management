# Builds a wordpress image that has xdebug denabled
# Borrowed from: https://github.com/wpdiaries/wordpress-xdebug/blob/master/Dockerfile

# Try to keep wordpress and php versions in sync with hosting provider
FROM wordpress:6.0.3-php7.4-apache

# Install packages under Debian
RUN apt-get update && \
    apt-get -y install git

# Install XDebug from source as described here:
# https://xdebug.org/docs/install
# Available branches of XDebug could be seen here:
# https://github.com/xdebug/xdebug/branches
RUN cd /tmp && \
    git clone https://github.com/xdebug/xdebug.git && \
    cd xdebug && \
    git checkout xdebug_3_1 && \
    phpize && \
    ./configure --enable-xdebug && \
    make && \
    make install && \
    rm -rf /tmp/xdebug

# Copy xdebug.ini to /usr/local/etc/php/conf.d/
COPY ./xdebug_dev.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Since this Dockerfile extends the official Docker image `wordpress`,
# and since `wordpress`, in turn, extends the official Docker image `php`,
# the helper script docker-php-ext-enable (defined for image `php`)
# works here, and we can use it to enable xdebug:
RUN docker-php-ext-enable xdebug