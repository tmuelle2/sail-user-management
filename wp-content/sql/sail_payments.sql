CREATE TABLE `sailhou1_wpral`.`sail_payments`
  (
     `sailPaymentId`      BIGINT NOT NULL AUTO_INCREMENT,
     `orderId`            TEXT NOT NULL,
     `orderJson`          TEXT NOT NULL
  )
engine = innodb; 