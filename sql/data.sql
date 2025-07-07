/*  Insert data for actor */
INSERT INTO `actor` (`actor_id`, `act_name`) 
  VALUES  (1, 'Leonardo DiCaprio'),
          (2, 'Keanu Reeves'),
          (3, 'Matthew McConaughey'),
          (4, 'Joseph Gordon-Levitt'),
          (5, 'Elliot Page'),
          (6, 'Laurence Fishburne');

/*  Insert data for director */
INSERT INTO `director` (`director_id`, `dir_name`) 
  VALUES  (1, 'Christopher Nolan'),
          (2, 'The Wachowskis');

/*  Insert data for genre */
INSERT INTO `genre` (`genre_id`, `gen_name`) 
  VALUES  (1, 'Science Fiction'),
          (2, 'Action'),
          (3, 'Drama');

/*  Insert data for movie */
INSERT INTO `movie` (`movie_id`, `title`, `release_year`) 
  VALUES  (1, 'Inception', 2010),
          (2, 'The Matrix', 1999),
          (3, 'Interstellar', 2014);

/*  Insert data for movie_actor */
INSERT INTO `movie_actor` (`movie_id`, `actor_id`) 
  VALUES  (1, 1),
          (2, 2),
          (3, 3),
          (1, 4),
          (1, 5),
          (2, 6);

/*  Insert data for movie_director */
INSERT INTO `movie_director` (`movie_id`, `director_id`) 
  VALUES  (1, 1),
          (3, 1),
          (2, 2);

/*  Insert data for movie_genre */
INSERT INTO `movie_genre` (`movie_id`, `genre_id`) 
  VALUES  (1, 1),
          (2, 1),
          (3, 1),
          (1, 2),
          (2, 2),
          (1, 3),
          (2, 3),
          (3, 3);

/*  Insert data for preview */
INSERT INTO `preview` (`preview_id`, `theater`, `date`, `time`, `movie_id`) 
  VALUES  (1, 'Big Cinema', '2024-12-01', '18:00:00', 1),
          (2, 'Cool Theater', '2024-12-02', '20:30:00', 2),
          (3, 'AMC', '2024-12-03', '19:00:00', 3),
          (4, 'XYZ', '2024-12-25', '17:15:00', 2),
          (5, 'gurnee', '2024-12-27', '03:05:00', 3);

/*  Insert data for review */
INSERT INTO `review` (`review_id`, `rating`, `mbr_username`, `review_comment`, 
                      `movie_id`, `public_approved`) 
  VALUES  (1, 5, 'janesharp', 'Amazing!', 1, 1),
          (2, 4, 'bobsmith', 'A classic.', 2, 1),
          (3, 5, 'johndoe', 'Bad.', 3, 0);