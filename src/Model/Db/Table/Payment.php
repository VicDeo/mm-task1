<?php

declare(strict_types=1);

namespace Task1\Model\Db\Table;

use Task1\Model\Db\Table;

class Payment extends Table
{
    protected const string CREATE_STATEMENT = "
        CREATE TABLE IF NOT EXISTS `payment` (
            `id` BIGINT NOT NULL AUTO_INCREMENT,
            `title` VARCHAR(256) NOT NULL,
            `amount` DECIMAL(20,4) NOT NULL,
            `paid_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `bank_account` VARCHAR(28) NOT NULL,
            PRIMARY KEY (`id`),
            KEY `PAYMENT_BANK_ACCOUNT` (`bank_account`)
        ) ENGINE=InnoDB;
    ";
}
