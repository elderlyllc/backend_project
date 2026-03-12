CREATE TABLE `elderly_db`.`users` (`id` INT(10) NOT NULL , `role_id` INT(10) NULL , `name` VARCHAR(40) NULL , `email` VARCHAR(40) NULL , `phone` VARCHAR(20) NULL , `created_by` INT(10) NULL , `created_at` VARCHAR(60) NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE `elderly_db`.`elderly_user_details` (`id` INT(10) NOT NULL , `user_id` INT(10) NOT NULL , `profile_picture` VARCHAR(100) NOT NULL , `last_login` VARCHAR(60) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE `elderly_db`.`elderly_roles` (`id` INT(10) NOT NULL , `name` VARCHAR(40) NULL , `created_by` INT(10) NULL , `created_at` VARCHAR(60) NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
