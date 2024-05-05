# This dockerfile takes the current users uid/gid at build time and adjusts reality
# so that the running user for www-data is actually the same as the launching user.
FROM singularo/s2i-shepherd-drupal:develop

ARG USER_ID="33"
ARG GROUP_ID="0"
ARG PHP_VERSION="8.1"

# Remove existing www user (both) and group (dialout is the users group on mac).
RUN \
if [ ${USER_ID:-0} -ne 0 ] && [ ${GROUP_ID:-0} -ne 0 ]; then \
    userdel -f www-data \
    && groupdel dialout \
    && if getent group www-data ; then groupdel www-data; fi \
    && groupadd -g ${GROUP_ID} www-data \
    && useradd -l -u ${USER_ID} -g www-data www-data \
    && install -d -m 0755 -o www-data -g www-data /home/www-data; \
fi

# Add ngrok repository
RUN curl -s https://ngrok-agent.s3.amazonaws.com/ngrok.asc > /etc/apt/trusted.gpg.d/ngrok.asc \
&& echo "deb https://ngrok-agent.s3.amazonaws.com buster main" > /etc/apt/sources.list.d/ngrok.list

# Upgrade all currently installed packages and install additional packages.
RUN apt-get update \
&& apt-get -y --no-install-recommends --no-install-suggests -o Dpkg::Options::='--force-confdef' -o Dpkg::Options::='--force-confold' install \
  git \
  libnotify-bin \
  ngrok \
  openssh-client \
  sudo \
  unzip \
  vim  \
  php${PHP_VERSION}-xdebug  \
  php${PHP_VERSION}-cli \
&& sed -ri 's/^zend.assertions\s*=\s*-1/zend.assertions = 1/g' /etc/php/${PHP_VERSION}/cli/php.ini \
&& apt-get -y autoremove --purge && apt-get -y autoclean && apt-get clean && rm -rf /var/lib/apt/lists /tmp/* /var/tmp/*

COPY ./docker/xdebug.ini /etc/php/${PHP_VERSION}/mods-available/xdebug.ini

RUN echo "www-data ALL=(ALL) NOPASSWD: ALL" > /etc/sudoers.d/www-data \
&& echo "Set disable_coredump false" >> /etc/sudo.conf

# Disable xdebug by default.
RUN phpdismod -v ALL -s ALL xdebug

ENTRYPOINT ["/init"]
