CREATE TABLE `sailhou1_WPRAL`.`fc_members`(
    `userId` BIGINT NOT NULL,
    `authorized` TEXT NOT NULL,
    `consent` TEXT NOT NULL,
    `namePreference` TEXT NOT NULL,
    `nickname` TEXT NOT NULL,
    `activities` TEXT NOT NULL,
    `hobbies` TEXT NOT NULL,
    `typicalDay` TEXT NOT NULL,
    `strengths` TEXT NOT NULL,
    `makesYouHappy` TEXT NOT NULL,
    `lifesVision` TEXT NOT NULL,
    `supportRequirements` TEXT NOT NULL,
    `referenceName` TEXT NOT NULL,
    `referencePhoneNumber` TEXT NOT NULL,
    `referenceEmail` TEXT NOT NULL,
    `referenceApproved` BOOLEAN NOT NULL DEFAULT FALSE,
    UNIQUE `userId`(`userId`)
) ENGINE = InnoDB;
