CREATE TABLE `sailhou1_wpral`.`sail_family`
  (
     `relationId`      BIGINT NOT NULL AUTO_INCREMENT,
     `familyId`        BIGINT NOT NULL,
     `userId1`         BIGINT NOT NULL,
     `userId2`         BIGINT NOT NULL
  )
engine = innodb; 