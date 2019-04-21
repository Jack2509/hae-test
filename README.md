## Create virtualhost
Use apache virtualhost.

```apacheconfig
<VirtualHost *:80>
        # The ServerName directive sets the request scheme, hostname and port that
        # the server uses to identify itself. This is used when creating
        # redirection URLs. In the context of virtual hosts, the ServerName
        # specifies what hostname must appear in the request's Host: header to
        # match this virtual host. For the default virtual host (this file) this
        # value is not decisive as it is used as a last resort host regardless.
        # However, you must set it for any further virtual host explicitly.
        #ServerName www.example.com

        ServerAdmin dinhnguyen2509@gmail.com
        DocumentRoot /path-to-hea-project-folder
        ServerName hea-test.com
        ServerAlias www.hea-test.com
        <Directory /path-to-hea-project-folder>
                Options Indexes FollowSymLinks MultiViews
                AllowOverride All
                Require all granted
        </Directory>

        # Available loglevels: trace8, ..., trace1, debug, info, notice, warn,
        # error, crit, alert, emerg.
        # It is also possible to configure the loglevel for particular
        # modules, e.g.
        #LogLevel info ssl:warn

        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined

        # For most configuration files from conf-available/, which are
        # enabled or disabled at a global level, it is possible to
        # include a line for only one particular virtual host. For example the
        # following line enables the CGI configuration for this host only
        # after it has been globally disabled with "a2disconf".
        #Include conf-available/serve-cgi-bin.conf
</VirtualHost>

<VirtualHost *:443>
    SSLEngine On
    SSLCertificateFile /etc/ssl/certs/apache-selfsigned.crt
    SSLCertificateKeyFile /etc/ssl/private/apache-selfsigned.key

    ServerAdmin dinhnguyen2509@gmail.com
    ServerName hea-test.com
    ServerAlias www.hea-test.com
    DocumentRoot /path-to-hea-project-folder
    <Directory /path-to-hea-project-folder>
        Options Indexes FollowSymLinks MultiViews
        AllowOverride All
        Require all granted
    </Directory>
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

## Create database mysql

```mysql
use `hea_db`;

CREATE TABLE `users`
(
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`         varchar(50)  not null,
  `email`        varchar(50)  not null unique,
  `password`     char(32) collate utf8_bin,
  `role`         int          not null default 1 comment 'admin => role=2,visitor => role=1',
  `deleted_flag` TINYINT(1) default 0,
  `created_at`   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   TIMESTAMP    NOT NULL DEFAULT NOW() ON UPDATE NOW(),
  PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;


CREATE TABLE `comments`
(
  `id`           int unsigned not null auto_increment,
  `user_id`      int unsigned,
  `content`      varchar(355) not null,
  `deleted_flag` TINYINT(1) default 0,
  `created_at`   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   TIMESTAMP    NOT NULL DEFAULT NOW() ON UPDATE NOW(),
  PRIMARY KEY `id`(`id`),
  CONSTRAINT fk_user FOREIGN KEY (user_id)
    REFERENCES users(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

-- insert admin (admin => role=2, normal user => role=1)
insert into users
  (name, email, password, role)
values
('admin', 'admin@test.com', MD5('12345'), 2);
```
## Setup env
copy example.env file to .env file and change settings database mysql appropriate.
Caution: don't change src_path=/app/ param. this will crash the app.

## Usage

Access browser with domain hea-test.com, or whatever you want in your virtualhost



