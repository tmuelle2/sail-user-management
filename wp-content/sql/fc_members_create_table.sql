CREATE TABLE `sailhou1_WPRAL`.`fc_members`(
    `userId` BIGINT NOT NULL,
    `authorized` TEXT NOT NULL,
    `consent` TEXT NOT NULL,
    `namePreference` TEXT NOT NULL,
    `firstName` TEXT NOT NULL,
    `lastName` TEXT NOT NULL,
    `nickname` TEXT NOT NULL,
    `dob` DATE NOT NULL,
    `activities` TEXT NOT NULL,
    `hobbies` TEXT NOT NULL,
    `typicalDay` TEXT NOT NULL,
    `strengths` TEXT NOT NULL,
    `makesYouHappy` TEXT NOT NULL,
    `lifesVision` TEXT NOT NULL,
    `supportRequirements` TEXT NOT NULL,
    `referenceName` TEXT NOT NULL,
    `referenceRelation` TEXT NOT NULL,
    `referencePhoneNumber` TEXT NOT NULL,
    `referenceEmail` TEXT NOT NULL,
    `referenceApproved` BOOLEAN NOT NULL DEFAULT FALSE,
    `profilePicture` TEXT NULL DEFAULT NULL,
    `gender` TEXT NOT NULL,
    `primaryContactType` TEXT NOT NULL,
    `primaryContact` TEXT NOT NULL,
    UNIQUE `userId`(`userId`)
) ENGINE = InnoDB;
