--
-- Database : booking
--
--
-- Table structure for table users
--

DROP TABLE IF EXISTS users;
CREATE TABLE users (
  username VARCHAR(10) NOT NULL,
  name VARCHAR(50) NOT NULL,
  email VARCHAR(50) NOT NULL,  
  libraryid INT(10) UNSIGNED,  
  password VARCHAR(32) NOT NULL,
  permissions INT(4) NOT NULL,
  reminder BOOL NULL NULL,  
  PRIMARY KEY (username)
) TYPE=MYISAM;

INSERT INTO users VALUES ('ktg','Kevin Glover','ktg@cs.nott.ac.uk', 0, 'ktg', 1, 1);
INSERT INTO users VALUES ('FKnowles','Felicia Knowles','Felicia.Knowles@nottingham.ac.uk', 0, 'FKnowles',1,1);

--
-- Table structure for table categories
--
DROP TABLE IF EXISTS categories;
CREATE TABLE categories (
  catid INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  catname VARCHAR(50) NOT NULL,
  details TEXT,
  PRIMARY KEY (catid)
) TYPE=MYISAM;


INSERT INTO categories VALUES (1,'Laptops', NULL);
INSERT INTO categories VALUES (2,'Projectors', NULL);
INSERT INTO categories VALUES (3,'Camcorders', NULL);
INSERT INTO categories VALUES (4,'Web cams', NULL);
INSERT INTO categories VALUES (5,'Digital Cameras', NULL);
INSERT INTO categories VALUES (6,'PDAs', NULL);

--
-- Table structure for table locations
--
CREATE TABLE locations (
  id INT(10) unsigned NOT NULL auto_increment,
  location VARCHAR(32) NOT NULL,
  PRIMARY KEY (id)
) TYPE=MyISAM;

INSERT INTO locations VALUES (1,'C12');

--
-- Table structure for table items
--
DROP TABLE IF EXISTS items;
CREATE TABLE items (
  category INT(10) UNSIGNED NOT NULL,
  code VARCHAR(20) NOT NULL,
  name VARCHAR(50) NOT NULL,
  bookable BOOL NOT NULL,
  location INT(10) UNSIGNED NOT NULL,
  contact VARCHAR(10) NOT NULL,
  project VARCHAR(50),
  serial VARCHAR(50),
  barcode INT(10) UNSIGNED,  
  description TEXT,
  PRIMARY KEY (code)
) TYPE=MYISAM;


-- LAPTOPS --
INSERT INTO items VALUES (1,'MRLLAPTOP2','Dell Inspiron 8000, HELSING', TRUE, 1, 'FKnowles', NULL, '434Y20J', NULL, '<ul><li>15" TFT Colour Display</li><li>32Mb ATA graphics chipset</li><li>Wireless Network PC Card</li><li>Wired Network PC Card</li></ul>Power supply. Battery. DVD-Rom. Floppy drive. Mouse. Carry case<br />Not for long term personal use.');
INSERT INTO items VALUES (1,'MRLLAPTOP3','Dell Inspiron 4000, SCHRECK', TRUE, 1, 'FKnowles', NULL, 'SV7X20J', NULL, '<ul><li>14" TFT Colour Display</li><li>8Mb graphics chipset</li></ul>Power supply. Battery. Carry Case.<br />Not for long term personal use.');
INSERT INTO items VALUES (1,'MRLLAPTOP4','Dell Inspiron 4000, LUGOSI', TRUE, 1, 'FKnowles', NULL, '8V7X20J', NULL, '<ul><li>14" TFT Colour Display</li><li>8Mb graphics chipset</li></ul>Power supply. Battery.<br />Not for long term personal use.');
INSERT INTO items VALUES (1,'MRLLAPTOP5','Dell Inspiron 4000, STOKER', TRUE, 1, 'FKnowles', NULL, 'BV7X20J', NULL, '<ul><li>14" TFT Colour Display</li><li>8Mb graphics chipset</li></ul>Power supply. Battery.<br />Not for long term personal use.');
INSERT INTO items VALUES (1,'MRLLAPTOP6','IBM Thinkpad, STEELE', TRUE, 1, 'FKnowles', NULL, '', NULL, '<ul><li>12" TFT Colour Display</li></ul>Power supply. Battery.<br />Not for long term personal use.');
INSERT INTO items VALUES (1,'MRLLAPTOP7','Toshiba Laptop, SHELLY', TRUE, 1, 'FKnowles', NULL, '72591415G', NULL, 'Toshiba laptop, 15inch, power supply, battery, carry case.<br />Not for long term personal use.');
INSERT INTO items VALUES (1,'MRLLAPTOP10','Toshiba Laptop, BLATTY', TRUE, 1, 'FKnowles', NULL, '', NULL,'');
INSERT INTO items VALUES (1,'MRLLAPTOP11','Toshiba Laptop', TRUE, 1, 'FKnowles', NULL, '', NULL,'');
INSERT INTO items VALUES (1,'MRLLAPTOP12','Toshiba Laptop, ARTAUD', TRUE, 1, 'FKnowles', NULL, '', NULL,'');
INSERT INTO items VALUES (1,'MRLLAPTOP13','Toshiba Laptop, HUYSMANS', TRUE, 1, 'FKnowles', NULL, '', NULL,'');
INSERT INTO items VALUES (1,'MRLLAPTOP14','Toshiba Laptop, DUNSANY', TRUE, 1, 'FKnowles', NULL, '', NULL,'');
INSERT INTO items VALUES (1,'MRLLAPTOP15','Toshiba Laptop, POPE', TRUE, 1, 'FKnowles', NULL, '', NULL,'');
INSERT INTO items VALUES (1,'MRLLAPTOP16','Toshiba Laptop, MATURIN', TRUE, 1, 'FKnowles', NULL, '', NULL,'');
INSERT INTO items VALUES (1,'MRLLAPTOP17','Toshiba Laptop, SHAN', TRUE, 1, 'FKnowles', NULL, '', NULL,'');
INSERT INTO items VALUES (1,'MRLLAPTOP18','Toshiba Laptop, DOYLE', TRUE, 1, 'FKnowles', NULL, '', NULL,'');

-- PROJECTORS --
INSERT INTO items VALUES (2,'MRLPROJ1','Sony VPL-20 LCD Projector', TRUE, 1, 'FKnowles', NULL, '11414', NULL, 'Sony VPL-20 LCD projector. Medium sized XGA projector for lab and external use, power cable, VGA cable, remote control.');
INSERT INTO items VALUES (2,'MRLPROJ2','Sony VPL-21 LCD Projector', TRUE, 1, 'FKnowles', NULL, '12373', NULL, 'Sony VPL-20 LCD projector. Medium sized XGA projector for lab and external use, power cable, VGA cable, remote control, lens cover.');
INSERT INTO items VALUES (2,'MRLPROJ3','Sony VPL-F200 LCD Projector', TRUE, 1, 'FKnowles', NULL, '10075', NULL, 'Sony VPL-2F00 LCD projector and lens. Large LCD projector, primarily for lab use. Power cord, VGA cable, remote control.');
INSERT INTO items VALUES (2,'MRLPROJ4','Sony VPL-F200 LCD Projector', TRUE, 1, 'FKnowles', NULL, '10109', NULL, 'Sony VPL-2F00 LCD projector and lens. Large LCD projector, primarily for lab use. Power cord, VGA cable, remote control.');
INSERT INTO items VALUES (2,'MRLPROJ5','Sony VPL-F200 LCD Projector', TRUE, 1, 'FKnowles', NULL, '10107', NULL, 'Sony VPL-2F00 LCD projector and lens. Large LCD projector, primarily for lab use. Power cord, VGA cable, remote control.');
INSERT INTO items VALUES (2,'MRLPROJ6','3M MP7640 LCD projector', TRUE, 1, 'FKnowles', NULL, '', NULL, '3M MP7640 Portable LCD projector, power cord, vga cable, remote control');
INSERT INTO items VALUES (2,'MRLPROJ7','Panasonic PT-LM1E LCD projector', TRUE, 1, 'FKnowles', NULL, 'SA4130131', NULL, 'Panasonic portable SVGA projector, power cord, vga cable, carry case, remote control, manual');
INSERT INTO items VALUES (2,'MRLPROJ8','Hitachi CP-X275 LCD Projector', TRUE, 1, 'FKnowles', NULL, 'RT3D009382', NULL, 'Hitachi LCD Projector, CP-X275, 2.5kg, 1200 lumen (carry bag, user manual, remote control, power lead, VGA cable, audio-video lead, scart adaptor)');
INSERT INTO items VALUES (2,'MRLPROJ9','Hitachi CP-X275 LCD Projector', TRUE, 1, 'FKnowles', NULL, 'RT3L011156', NULL, 'Hitachi LCD Projector, CP-X275, 2.5kg, 1200 lumen (carry bag, user manual, remote control, power lead, VGA cable, audio-video lead, scart adaptor)');
INSERT INTO items VALUES (2,'MRLPROJ10','Hitachi CP-X275 LCD Projector', TRUE, 1, 'FKnowles', NULL, 'RT4B014571', NULL, 'Hitachi LCD Projector, CP-X275, 2.5kg, 1200 lumen (carry bag, user manual, remote control, power lead, VGA cable, audio-video lead)');
INSERT INTO items VALUES (2,'MRLPROJ11','Sharp PG-M10S LCD projector', TRUE, 1, 'FKnowles', NULL, '010413478', NULL, 'Sharp PG-M10S Data projector, ultra-portable, power lead, VGA lead, quick start guide, remote control, carry case');
INSERT INTO items VALUES (2,'MRLPROJ12','Epson EMP-8000 LCD Projector', TRUE, 1, 'FKnowles', NULL, '', NULL, '');

-- CAMCORDERS --
INSERT INTO items VALUES (3,'MRLCC1','Sony DCR-TRV900E Camcorder', TRUE, 1, 'FKnowles', NULL, '', NULL, '<ul><li>miniDV Camcorder</li><li>12x optical zoom</li><li>3.5" colour LCD</li></ul>Battery, Power Supply, Remote Control RMT-811, AV cable, Manual, lens hood<br />Serial number 1048316');
INSERT INTO items VALUES (3,'MRLCC2','Sony DCR-TRV900E Camcorder', TRUE, 1, 'FKnowles', NULL, '', NULL, '<ul><li>miniDV Camcorder</li><li>12x optical zoom</li><li>3.5" colour LCD</li></ul>Battery, Power Supply, Remote Control RMT-811, AV cable, Manual, lens hood<br />Serial number ?');
INSERT INTO items VALUES (3,'MRLCC3','Sony DCR-TRV900E Camcorder', TRUE, 1, 'FKnowles', NULL, '', NULL, '<ul><li>miniDV Camcorder</li><li>12x optical zoom</li><li>3.5" colour LCD</li></ul>Battery, Power Supply, Remote Control RMT-811, AV cable, Manual, lens hood<br />Serial number 1047239');
INSERT INTO items VALUES (3,'MRLCC4','Sony DCR-TRV900E Camcorder', TRUE, 1, 'FKnowles', NULL, '', NULL, '<ul><li>miniDV Camcorder</li><li>12x optical zoom</li><li>3.5" colour LCD</li></ul>Battery, Power Supply, Remote Control RMT-811, AV cable, Manual, lens hood<br />Serial number 1047241');
INSERT INTO items VALUES (3,'MRLCC5','Sony DCR-TRV16E Camcorder', TRUE, 1, 'FKnowles', NULL, '', NULL, 'Sony miniDV Camcorder, not bookable, part of video editing suite, \r\npower supply, battery, USB cable\r\n\r\nS/N: 249456');
INSERT INTO items VALUES (3,'MRLCC6','Panasonic NV-GS120EB Camcorder', TRUE, 1, 'FKnowles', NULL, '', NULL, '<ul><li>miniDV Camcorder</li><li>10x optical zoom</li><li>2.5" colour LCD</li></ul>A/V cable, Power adaptor, battery, USB cable, Mic-Remote Control, SD Card 8MB, Manual<br />S/N: D4HD00256');
INSERT INTO items VALUES (3,'MRLCC7','Panasonic NV-GS120EB Camcorder', TRUE, 1, 'FKnowles', NULL, '', NULL, '<ul><li>miniDV Camcorder</li><li>10x optical zoom</li><li>2.5" colour LCD</li></ul>A/V cable, Power adaptor S/N:C481695DC, battery, USB cable, Mic-Remote Control, mini-Remote Control, SD Card 8MB, Manual<br />S/N:D4HD00364');
INSERT INTO items VALUES (3,'MRLCC8','Panasonic NV-GS120EB Camcorder', TRUE, 1, 'FKnowles', NULL, '', NULL, '<ul><li>miniDV Camcorder</li><li>10x optical zoom</li><li>2.5" colour LCD</li></ul>A/V cable, Power adaptor, battery, USB cable, Mic-Remote Control, SD Card 8MB, Manual<br />S/N: D4HD00297');
INSERT INTO items VALUES (3,'MRLCC9','Panasonic NV-GS120EB Camcorder', TRUE, 1, 'FKnowles', NULL, '', NULL, '<ul><li>miniDV Camcorder</li><li>10x optical zoom</li><li>2.5" colour LCD</li></ul>A/V cable, Power adaptor, battery, USB cable, Mic-Remote Control, SD Card 8MB, Manual<br />S/N: D4HD00298');
INSERT INTO items VALUES (3,'MRLCC10','Panasonic NV-GS120EB Camcorder', TRUE, 1, 'FKnowles', NULL, '', NULL, '<ul><li>miniDV Camcorder</li><li>10x optical zoom</li><li>2.5" colour LCD</li></ul>A/V cable, Power adaptor S/N:C467572DC, battery, USB cable, Mic-Remote Control, mini Remote Control, SD Card 8MB, Manual<br />S/N: D4HD00121');
INSERT INTO items VALUES (3,'MRLCC11','Panasonic NV-GS120EB Camcorder', TRUE, 1, 'FKnowles', NULL, '', NULL, '<ul><li>miniDV Camcorder</li><li>10x optical zoom</li><li>2.5" colour LCD</li></ul>A/V cable, Power adaptor S/N:D400907DC, battery, USB cable, Mic-Remote Control, SD Card 8MB, Manual<br />S/N: D4HD00255');

-- WEBCAMS --

-- DIGITAL CAMERAS --
INSERT INTO items VALUES (5,'MRLSTILL1','Fuji Finepix 30i', TRUE, 1, 'FKnowles', NULL, '', NULL, '<ul><li>2 Megapixel for resolutions up to 1600x1200</li><li>16MB Smart Media Card, should store ~20 images at default resolution</li></ul>Carry case. 2 rechargeable AA batteries. Charger. Mains lead. USB cable. Driver CD. Headphones. Remote control<br />S/M: 1HL08935');
INSERT INTO items VALUES (5,'MRLSTILL2','Fuji Finepix 40i', TRUE, 1, 'FKnowles', NULL, '', NULL, '<ul><li>4 Megapixel for resolutions up to 2400x1800</li><li>64MB Smart Media Card, should store ~90 images at default resolution</li></ul>USB Cable, Battery Charger, 4 rechargeable AA batteries');
INSERT INTO items VALUES (5,'MRLSTILL3','Sony DSC-F55E', TRUE, 1, 'FKnowles', NULL, '', NULL, '<ul><li>2 Megapixel for resolutions up to 1600x1200</li></ul>');


-- PDAs --
INSERT INTO items VALUES (6,'MRLPDA1','Palm Pilot III', TRUE, 1, 'FKnowles', NULL, '', NULL, 'Palm III PDA');
INSERT INTO items VALUES (6,'MRLPDA2','Palm Pilot III', TRUE, 1, 'FKnowles', NULL, '', NULL, 'Palm III PDA');
INSERT INTO items VALUES (6,'MRLPDA11','Palm Pilot III', TRUE, 1, 'FKnowles', NULL, '', NULL, 'Palm III PDA');
INSERT INTO items VALUES (6,'MRLPDA12','Palm Pilot III', TRUE, 1, 'FKnowles', NULL, '', NULL, 'Palm III PDA');
INSERT INTO items VALUES (6,'MRLPDA13','Palm Pilot III', TRUE, 1, 'FKnowles', NULL, '', NULL, 'Palm III PDA');


--
-- Table structure for table BOOKINGS
--
DROP TABLE IF EXISTS bookings;
CREATE TABLE bookings (
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  username VARCHAR(10) NOT NULL,
  reason VARCHAR(100) NOT NULL,
  start DATETIME NOT NULL,
  end DATETIME NOT NULL,  
  returned DATETIME,
  PRIMARY KEY (id)
) TYPE=MYISAM;


--
-- Table structure for table BOOKEDITEMS
--
DROP TABLE IF EXISTS bookeditems;
CREATE TABLE bookeditems (
  bookingid INT(10) NOT NULL,
  itemcode VARCHAR(32) NOT NULL,
  returned DATETIME
) TYPE=MYISAM;


--
-- Table structure for table COMMENTS
--
DROP TABLE IF EXISTS comments;
CREATE TABLE comments (
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  itemcode VARCHAR(32) NOT NULL,
  user VARCHAR(10) NOT NULL,
  comment TEXT NOT NULL,
  time DATETIME NOT NULL,
  PRIMARY KEY (id)
) TYPE=MYISAM;
