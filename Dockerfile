# Base Image: Ubuntu 22.04
FROM ubuntu:22.04

# Metadata
LABEL maintainer="some body"

# Arguments for Flexibility (optional)
ARG WWWGROUP=1000
ARG NODE_VERSION=18
ARG POSTGRES_VERSION=15

# Set Working Directory
WORKDIR /var/www/html

# Environment Variables
ENV DEBIAN_FRONTEND noninteractive
ENV TZ=UTC

# Set Timezone
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Install System Dependencies
RUN apt-get update && apt-get install -y \
    gnupg gosu curl ca-certificates zip unzip git supervisor sqlite3 \
    libcap2-bin libpng-dev python3 dnsutils \
    # Install PHP 8.3 and Extensions
    software-properties-common \
    && add-apt-repository ppa:ondrej/php \
    && apt-get update \
    && apt-get install -y php8.3-cli php8.3-fpm php8.3-dev \
       php8.3-pgsql php8.3-sqlite3 php8.3-gd \
       php8.3-curl php8.3-imap php8.3-mysql php8.3-mbstring \
       php8.3-xml php8.3-zip php8.3-bcmath php8.3-soap \
       php8.3-intl php8.3-tokenizer \
       php8.3-opcache php8.3-redis # Optional: Add more extensions if needed

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

# Install Node.js and NPM (optional)
RUN curl -fsSL https://deb.nodesource.com/setup_$NODE_VERSION.x | bash - \
    && apt-get install -y nodejs

# Install Yarn (optional)
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
    && echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list \
    && apt-get update && apt-get install -y yarn

# Set File Permissions for PHP-FPM
RUN setcap "cap_net_bind_service=+ep" /usr/bin/php8.3

# Create User and Group for Web Server
RUN groupadd --force -g $WWWGROUP sail \
    && useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u 1337 sail

# Copy Project Files
COPY --chown=sail:sail . /var/www/html

# Copy Configuration Files (optional)
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY php.ini /etc/php/8.3/fpm/conf.d/99-overrides.ini

# Install Composer Dependencies
RUN composer install --no-interaction --prefer-dist --no-dev --optimize-autoloader

# Generate Laravel Application Key (if not already set)
RUN php artisan key:generate

# Expose Port 80
EXPOSE 80

# Start Supervisord
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
