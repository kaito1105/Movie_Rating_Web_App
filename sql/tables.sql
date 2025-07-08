/*  Create table for actor */
CREATE TABLE `actor` (
  `actor_id` INT NOT NULL AUTO_INCREMENT,
  `act_name` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`actor_id`)
);
ALTER TABLE `actor` AUTO_INCREMENT=7;

/*  Create table for director */
CREATE TABLE `director` (
  `director_id` INT NOT NULL AUTO_INCREMENT,
  `dir_name` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`director_id`)
);
ALTER TABLE `director` AUTO_INCREMENT=3;

/*  Create table for genre */
CREATE TABLE `genre` (
  `genre_id` INT NOT NULL AUTO_INCREMENT,
  `gen_name` VARCHAR(50),
  PRIMARY KEY (`genre_id`)
);
ALTER TABLE `genre` AUTO_INCREMENT=4;

/*  Create table for movie */
CREATE TABLE `movie` (
  `movie_id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(100) NOT NULL,
  `release_year` INT NOT NULL,
  PRIMARY KEY (`movie_id`)
);
ALTER TABLE `movie` AUTO_INCREMENT=4;

/*  Create table for movie_actor */
CREATE TABLE `movie_actor` (
  `movie_id` INT NOT NULL,
  `actor_id` INT NOT NULL,
  PRIMARY KEY (`movie_id`,`actor_id`),
  FOREIGN KEY (`movie_id`) REFERENCES `movie` (`movie_id`) ON DELETE CASCADE,
  FOREIGN KEY (`actor_id`) REFERENCES `actor` (`actor_id`) ON DELETE CASCADE
);

/*  Create table for movie_director */
CREATE TABLE `movie_director` (
  `movie_id` INT NOT NULL,
  `director_id` INT NOT NULL,
  PRIMARY KEY (`movie_id`,`director_id`),
  FOREIGN KEY (`movie_id`) REFERENCES `movie` (`movie_id`) ON DELETE CASCADE,
  FOREIGN KEY (`director_id`) REFERENCES `director` (`director_id`) 
    ON DELETE CASCADE
);

/*  Create table for movie_genre */
CREATE TABLE `movie_genre` (
  `movie_id` INT NOT NULL,
  `genre_id` INT NOT NULL,
  PRIMARY KEY (`movie_id`,`genre_id`),
  FOREIGN KEY (`movie_id`) REFERENCES `movie` (`movie_id`) ON DELETE CASCADE,
  FOREIGN KEY (`genre_id`) REFERENCES `genre` (`genre_id`) ON DELETE CASCADE
);

/*  Create table for preview */
CREATE TABLE `preview` (
  `theater` VARCHAR(100) DEFAULT NULL,
  `date` DATE DEFAULT NULL,
  `time` TIME DEFAULT NULL,
  `movie_id` INT NOT NULL,
  PRIMARY KEY (`theater`, `date`, `time`, `movie_id`),
  FOREIGN KEY (`movie_id`) REFERENCES `movie` (`movie_id`)
);

/*  Create table for review */
CREATE TABLE `review` (
  `review_id` INT NOT NULL AUTO_INCREMENT,
  `rating` INT DEFAULT NULL,
  `mbr_username` VARCHAR(50) DEFAULT NULL,
  `review_comment` TEXT,
  `movie_id` INT NOT NULL,
  `public_approved` INT NOT NULL,
  PRIMARY KEY (`review_id`),
  FOREIGN KEY (`movie_id`) REFERENCES `movie` (`movie_id`),
  FOREIGN KEY (`mbr_username`) REFERENCES `account` (`mbr_username`)
);
ALTER TABLE `review` AUTO_INCREMENT=4;