CREATE TABLE IF NOT EXISTS `page`
(
    `id`         int(11)      NOT NULL AUTO_INCREMENT,
    `title`      varchar(200) NOT NULL,
    `text`       text         NOT NULL,
    `created_at` timestamp    NULL DEFAULT NULL,
    `updated_at` timestamp    NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;
