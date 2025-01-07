<?php

declare(strict_types=1);

namespace Task1\Model\Db\Table;

use Task1\Model\Db\Table;

class Payment extends Table
{
    protected const string CREATE_STATEMENT = "
        CREATE TABLE IF NOT EXISTS `payment` (
            `id` BIGINT NOT NULL AUTO_INCREMENT,
            `invoice_id` BIGINT NOT NULL,
            `title` VARCHAR(256) NOT NULL,
            `amount` DECIMAL(20,2) NOT NULL,
            `paid_at`  DATE NOT NULL DEFAULT (CURRENT_DATE),
            `bank_account` VARCHAR(28) NOT NULL,
            PRIMARY KEY (`id`),
            KEY `PAYMENT_INVOICE_ID` (`invoice_id`)
        ) ENGINE=InnoDB;
    ";

    protected const string SAMPLE_STATEMENT = "
        INSERT INTO `payment` (`invoice_id`, `title`, `amount`, `paid_at`, `bank_account`) VALUES
        (1, 'Faktura ABC', 199.56, '2024-01-13', 'PL12345678901234567890123456'),
        (7, 'Faktura CDE', 1990.56, '2024-06-12', 'PL32345678901234567890123458'),
        (8, 'Faktura DEF', 790.56, '2024-10-17', 'PL32345678901234567890123458'),
        (4, 'Faktura EDR', 3.04, '2024-01-28', 'PL22345678901234567890123457'),
        (4, 'Faktura DGF', 10.00, '2024-01-29', 'PL22345678901234567890123457')
    ";
}
