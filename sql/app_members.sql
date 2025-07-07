CREATE TABLE `account` (
  `mbr_username` VARCHAR(50) NOT NULL,
  `f_name` VARCHAR(50) NOT NULL,
  `l_name` VARCHAR(50) NOT NULL,
  `acc_type` enum('rotten','popcorn') DEFAULT 'popcorn',
  `acc_password` VARCHAR(255) NOT NULL,
  `mbr_approved` INT NOT NULL DEFAULT '0',
  PRIMARY KEY (`mbr_username`)
);

INSERT INTO `account` (`mbr_username`, `f_name`, `l_name`, `acc_type`, 
                      `acc_password`, `mbr_approved`) 
  VALUES  ('admin', 'admin', 'admin', NULL, 'admin', -1),
          ('popcorn', 'popcorn', 'popcorn', 'popcorn', 'popcorn', 0),
          ('rotten', 'rotten', 'rotten', 'rotten', 'rotten', 1),
          ('bobsmith', 'Bob', 'Smith', 'popcorn', 'password3', 0),
          ('janesharp', 'Jane', 'Sharp', 'rotten', 'password2', 1),
          ('johndoe', 'John', 'Doe', 'popcorn', 'password1', 0);