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

-- insert new user (admin, normal user)
insert into users
  (name, email, password, role)
values
('admin', 'admin@test.com', MD5('12345'), 2),
('user 1', 'user1@test.com', null, 1),
('user 2', 'user2@test.com', null, 1);

