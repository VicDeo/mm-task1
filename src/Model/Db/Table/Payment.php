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
            `paid_at`  DATE NOT NULL DEFAULT (CURRENT_DATE),
            `bank_account` VARCHAR(28) NOT NULL,
            PRIMARY KEY (`id`),
            KEY `PAYMENT_BANK_ACCOUNT` (`bank_account`)
        ) ENGINE=InnoDB;
    ";

    protected const string SAMPLE_STATEMENT = "
        INSERT INTO `payment` (`title`, `amount`, `paid_at`, `bank_account`) VALUES
        ('Faktura ABC', 199.56, '2024-01-13', 'PL12345678901234567890123456')
    ";
}
