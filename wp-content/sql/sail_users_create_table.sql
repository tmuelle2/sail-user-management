CREATE TABLE `sailhou1_WPRAL`.`sail_users` ( `userId` BIGINT NOT NULL , `firstName` TEXT NOT NULL , `lastName` TEXT NOT NULL , `email` TEXT NOT NULL , `emailVerified` TEXT NOT NULL , `emailVerificationKey` TEXT NOT NULL , `addrLine1` TEXT NOT NULL , `addrLine2` TEXT NOT NULL , `city` TEXT NOT NULL , `state` TEXT NOT NULL , `zipCode` TEXT NOT NULL , `phoneNumber` TEXT NOT NULL , `profilePicture` BLOB NOT NULL , `gender` TEXT NOT NULL , `dob` DATE NOT NULL , `contactViaEmail` BOOLEAN NOT NULL , `contactViaText` BOOLEAN NOT NULL , `role` TEXT NOT NULL , `situation` TEXT NOT NULL , `reference` TEXT NOT NULL , `timeframe` TEXT NOT NULL , `newsletter` BOOLEAN NOT NULL , `additionalInfo` MEDIUMTEXT NOT NULL , `createdOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, `readTermsOfService` BOOLEAN NOT NULL ) ENGINE = InnoDB;
