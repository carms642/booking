--
-- Database : booking
--
--
-- Table structure for table users
--
DROP TABLE IF EXISTS users;
CREATE TABLE users (
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) NOT NULL,
  email VARCHAR(50) NOT NULL,
  password VARCHAR(42),
  booking INT(10) UNSIGNED,
  fails INT(10) UNSIGNED NOT NULL DEFAULT 0,
  failtime DATETIME,
  admin BOOL NOT NULL DEFAULT 0,
  enabled BOOL NOT NULL DEFAULT 1,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
) TYPE=MYISAM;

DROP TABLE IF EXISTS usersessions;
CREATE TABLE usersessions (
  session VARCHAR(42) NOT NULL,
  user INT(10) UNSIGNED NOT NULL,
  time DATETIME NOT NULL,
  PRIMARY KEY (session)
) TYPE=MYISAM;

INSERT INTO users VALUES (0,'Kevin Glover','ktg@cs.nott.ac.uk', NULL, NULL, 0, NULL, 1, 1);
INSERT INTO users VALUES (0,'Felicia Knowles','felicia.knowles@nottingham.ac.uk', NULL, NULL, 0, NULL, 1, 1);
INSERT INTO users VALUES (0,'Samantha Stapleford-Allen','samantha.stapleford-allen@nottingham.ac.uk', NULL, NULL, 0, NULL, 1, 1);

DROP TABLE IF EXISTS errors;
CREATE TABLE errors (
  session VARCHAR(255) NOT NULL,
  param VARCHAR(50) NOT NULL,
  error VARCHAR(222) NOT NULL,
  PRIMARY KEY (session)
) TYPE=MYISAM;

--
-- Table structure for table items
--
DROP TABLE IF EXISTS items;
CREATE TABLE items (
  code VARCHAR(20) NOT NULL,
  name VARCHAR(50) NOT NULL,
  bookable BOOL NOT NULL,
  controlled BOOL NOT NULL,
  contact INT(10) UNSIGNED NOT NULL,
  serial VARCHAR(50),
  image VARCHAR(60),
  status VARCHAR(100),
  description TEXT,
  added DATETIME,
  PRIMARY KEY (code),
  FOREIGN KEY (contact) REFERENCES users (id)
) TYPE=MYISAM;

-- LAPTOPS --
INSERT INTO items VALUES ('MRLLAPTOP20','Toshiba Laptop, WALPOLE',1,1,2,'X4714927G',NULL,NULL,'14\" laptop, power cable, battery and carry case','2005-10-18 14:03:17');
INSERT INTO items VALUES ('MRLHTCDES1','HTC Desire',1,1,2,'SH0AFPL02197',NULL,NULL,'IMEI: 353833044768841','2005-10-18 14:03:17');
INSERT INTO items VALUES ('MRLMacbook1','Macbook Pro',1,1,2,NULL,NULL,NULL,'Macbook Pro 13" 2.66GHz dual core, 4Gb, 320Gb disk. Note : This is priority for iPhone development. Note : You will need an account created on this laptop before you can use it.','2005-10-18 14:03:17');
INSERT INTO items VALUES ('MRLLAPTOP11','Samsung P510, MARLOES',1,1,2,NULL,NULL,NULL,'15.4" notebook, power supply, battery. Core 2 duo T6400, 2Gb, 160Gb','2005-10-18 14:03:17');
INSERT INTO items VALUES ('MRLLAPTOP12','Samsung P210, ARTAUD',1,1,2,NULL,NULL,NULL,'12" notebook, power supply, battery. Core 2 duo T6400, 2Gb, 250Gb','2005-10-18 14:03:17');
INSERT INTO items VALUES ('MRLLAPTOP16','Samsung P210, STRAUB',1,1,2,NULL,NULL,NULL,'12" notebook, power supply, battery. Core 2 duo T6400, 2Gb, 250Gb','2005-10-18 14:03:17');

DROP TABLE IF EXISTS tags;
CREATE TABLE tags (
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  text VARCHAR(100) NOT NULL,
  PRIMARY KEY (id)
) TYPE=MYISAM;

INSERT INTO tags VALUES (1,'Laptops');
INSERT INTO tags VALUES (2,'Phone');
INSERT INTO tags VALUES (3,'Smartphone');
INSERT INTO tags VALUES (4,'Android');

DROP TABLE IF EXISTS itemtags;
CREATE TABLE itemtags (
  item VARCHAR(20) NOT NULL,
  tag INT(10) UNSIGNED NOT NULL,
  FOREIGN KEY (tag) REFERENCES tags (id),
  FOREIGN KEY (item) REFERENCES items (code)
) TYPE=MYISAM;

--
-- Table structure for table BOOKINGS
--
DROP TABLE IF EXISTS bookings;
CREATE TABLE bookings (
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  user INT(10) UNSIGNED NOT NULL,
  reason VARCHAR(100) NOT NULL,
  start DATETIME NOT NULL,
  end DATETIME NOT NULL,
  returned DATETIME,
  PRIMARY KEY (id),
  FOREIGN KEY (user) REFERENCES users (id)
) TYPE=MYISAM;


--
-- Table structure for table ITEMBOOKINGS
--
DROP TABLE IF EXISTS itembookings;
CREATE TABLE itembookings (
  booking INT(10) UNSIGNED NOT NULL,
  item VARCHAR(32) NOT NULL,
  collected DATETIME,
  returned DATETIME,
  FOREIGN KEY (booking) REFERENCES bookings (id),
  FOREIGN KEY (item) REFERENCES items (code)
) TYPE=MYISAM;


--
-- Table structure for table itemevents
--
DROP TABLE IF EXISTS itemevents;
CREATE TABLE itemevents (
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  item VARCHAR(32) NOT NULL,
  user INT(10) UNSIGNED NOT NULL,
  text TEXT NOT NULL,
  time DATETIME NOT NULL,
  archived BOOL NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (user) REFERENCES users (id),
  FOREIGN KEY (item) REFERENCES items (code)
) TYPE=MYISAM;

--
-- Table structure for table bookingevents
--
DROP TABLE IF EXISTS bookingevents;
CREATE TABLE bookingevents (
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  booking INT(10) UNSIGNED NOT NULL,
  user INT(10) UNSIGNED NOT NULL,
  text TEXT NOT NULL,
  time DATETIME NOT NULL,

  PRIMARY KEY (id),
  FOREIGN KEY (user) REFERENCES users (id),
  FOREIGN KEY (booking) REFERENCES bookings (id)
) TYPE=MYISAM;