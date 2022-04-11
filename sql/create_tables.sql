/* Database is using UTF-8 by default
 * Create missing tables
 */

SELECT @@character_set_database, @@collation_database;

CREATE TABLE IF NOT EXISTS Accounts (
       AccountId BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
       Name VARCHAR(255) NOT NULL UNIQUE,
       Email VARCHAR(255) NOT NULL UNIQUE,
       Password VARCHAR(255) NOT NULL DEFAULT '',
       OTP VARCHAR(255) NOT NULL DEFAULT '', /* One Time Password */
       State TINYINT UNSIGNED NOT NULL DEFAULT 1, /* FirstLogin(1), Enabled(2), Disabled(3) */
       Role TINYINT UNSIGNED NOT NULL DEFAULT 2, /* Superman(1), Board(2), Caretaker(3), Administrator(4), Tester(5) */
       Language TINYINT UNSIGNED NOT NULL DEFAULT 1,  /* 'da'(1) or 'en'(2) */
       Activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS Persons (
       PersonId BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
       Name VARCHAR(255) NOT NULL UNIQUE,
       Address VARCHAR(255) NOT NULL,
       Email VARCHAR(255) NOT NULL UNIQUE,
       Phone VARCHAR(20) NOT NULL DEFAULT '',
       NoMails BOOLEAN NOT NULL DEFAULT false,
       Modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS Apartments (
       ApartmentId SMALLINT UNSIGNED NOT NULL PRIMARY KEY, /* 1 - 109 */
       Number CHAR(2) NOT NULL, /* E.g. '31', '33', '35', '37', '39', '41', '43', '45', '47' */
       Floor CHAR(2) NOT NULL, /* E.g. 'st', '1', '2', '3', '4', '5', '6' */
       Side CHAR(2) NOT NULL DEFAULT '', /* E.g. 'th', 'tv', '1', '2', '3', '4' */
       Type CHAR(2) NOT NULL DEFAULT '', /* E.g. 'Q1' from engineer drawings by COWI A/S */
       Size CHAR(3) NOT NULL DEFAULT '', /* E.g. '130' m2 */
       Reduction CHAR(2) NOT NULL DEFAULT '', /* Reduction in percent for heating. E.g. 40 */
       TapShares CHAR(1) NOT NULL DEFAULT '', /* Tap shares. Kitchen(3) + hand basin(1) + shower(2). Always '6' or '9' */
       Shafts CHAR(16) NOT NULL DEFAULT '', /* E.g. 'S21,S23,S24' from engineer drawings by COWI A/S */
       Modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS Parkings (
       ParkingId SMALLINT UNSIGNED NOT NULL PRIMARY KEY, /* 301 - 414 */
       Depot BOOLEAN NOT NULL DEFAULT false, /* true if parking has a depot */
       Charger BOOLEAN NOT NULL DEFAULT false, /* true if parking has a charger */
       Power SMALLINT UNSIGNED NOT NULL DEFAULT 1, /* ApartmentId where power to charger is drawn from. 1 means not assigned */
       CCharger BOOLEAN NOT NULL DEFAULT false, /* true if parking has a charger that is part of the common system */
       Modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
       INDEX (Depot),
       INDEX (Charger)
);

CREATE TABLE IF NOT EXISTS Depots (
       DepotId SMALLINT UNSIGNED NOT NULL PRIMARY KEY,  /* 201 - 208 (might change) */
       Number CHAR(2) NOT NULL, /* E.g. '31', '33', '35', '37', '39', '41', '43', '45', '47' */
       Modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS PersonsApartments (
       PersonId BIGINT UNSIGNED NOT NULL,
       Id SMALLINT UNSIGNED NOT NULL,
       Relation TINYINT UNSIGNED NOT NULL, /* Owner(1), NonResidentOwner(2), Tenant(3) */
       Started DATE NOT NULL DEFAULT '0000-00-00',
       Stopped DATE NOT NULL DEFAULT '0000-00-00',
       Modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
       PRIMARY KEY (PersonId, Id),
       FOREIGN KEY (PersonId) REFERENCES Persons(PersonId) ON DELETE CASCADE,
       FOREIGN KEY (Id) REFERENCES Apartments(ApartmentId),
       INDEX (Started),
       INDEX (Stopped)
);

CREATE TABLE IF NOT EXISTS PersonsParkings (
       PersonId BIGINT UNSIGNED NOT NULL,
       Id SMALLINT UNSIGNED NOT NULL,
       Relation TINYINT UNSIGNED NOT NULL, /* Owner(1), NonResidentOwner(2), Tenant(3) */
       Started DATE NOT NULL DEFAULT '0000-00-00',
       Stopped DATE NOT NULL DEFAULT '0000-00-00',
       Modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
       PRIMARY KEY (PersonId, Id),
       FOREIGN KEY (PersonId) REFERENCES Persons(PersonId) ON DELETE CASCADE,
       FOREIGN KEY (Id) REFERENCES Parkings(ParkingId),
       INDEX (Started),
       INDEX (Stopped)
);

CREATE TABLE IF NOT EXISTS PersonsDepots (
       PersonId BIGINT UNSIGNED NOT NULL,
       Id SMALLINT UNSIGNED NOT NULL,
       Relation TINYINT UNSIGNED NOT NULL DEFAULT 3, /* Always Tenant(3) */
       Started DATE NOT NULL DEFAULT '0000-00-00',
       Stopped DATE NOT NULL DEFAULT '0000-00-00',
       Modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
       PRIMARY KEY (PersonId, Id),
       FOREIGN KEY (PersonId) REFERENCES Persons(PersonId) ON DELETE CASCADE,
       FOREIGN KEY (Id) REFERENCES Depots(Depotid) ON DELETE CASCADE,
       INDEX (Started),
       INDEX (Stopped)
);

CREATE TABLE IF NOT EXISTS PersonsDepotsWait (
       WaitId BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
       PersonId BIGINT UNSIGNED NOT NULL,
       Modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
       FOREIGN KEY (PersonId) REFERENCES Persons(PersonId) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS MailCheck ( /* Only one row that shows who and when mail was checked */
       CheckId TINYINT UNSIGNED NOT NULL PRIMARY KEY, /* Always 1 */
       Checked TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       Remote VARCHAR(100) NOT NULL DEFAULT '',
       Queued SMALLINT UNSIGNED NOT NULL DEFAULT 0,
       LastSend TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       SendStatus SMALLINT NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS Mails (
       MailId BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
       AccountId BIGINT UNSIGNED NOT NULL,
       Subject VARCHAR(255) NOT NULL DEFAULT '',
       Body VARCHAR(15000) NOT NULL DEFAULT '',
       State TINYINT UNSIGNED NOT NULL DEFAULT 1, /* Draft(1), Sending(2), Sent(3), Deleted(4) */
       Sent TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       Modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
       INDEX (AccountId),
       INDEX (State)
);

CREATE TABLE IF NOT EXISTS MailAttachments (
       MailId BIGINT UNSIGNED NOT NULL,
       Id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
       Name VARCHAR(255) NOT NULL, /* File name */
       Type VARCHAR(255) NOT NULL, /* Mime type */
       Size BIGINT UNSIGNED NOT NULL, /* File size */
       File LONGBLOB NOT NULL, /* File content */
       Modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
       FOREIGN KEY (MailId) REFERENCES Mails(MailId) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS MailRecipients (
       MailId BIGINT UNSIGNED NOT NULL,
       Name VARCHAR(255) NOT NULL,
       Email VARCHAR(255) NOT NULL,
       Sent BOOLEAN NOT NULL DEFAULT false, /* true if mail has been sent to this person */
       Modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
       FOREIGN KEY (MailId) REFERENCES Mails(MailId) ON DELETE CASCADE,
       INDEX (Sent)
       /* primary key ? */
);

