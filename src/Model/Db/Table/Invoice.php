<?php

declare(strict_types=1);

namespace Task1\Model\Db\Table;

use Task1\Model\Db\Table;

class Invoice extends Table
{
    protected const string CREATE_STATEMENT = "
        CREATE TABLE IF NOT EXISTS `invoice` (
            `id` BIGINT NOT NULL AUTO_INCREMENT,
            `client_id` BIGINT,
            `created_at` DATE NOT NULL DEFAULT (CURRENT_DATE),
            `due_date`  DATE NOT NULL DEFAULT (CURRENT_DATE),
            `gross_amount` DECIMAL(20,4) NOT NULL,
            PRIMARY KEY (`id`),
            KEY `INVOICE_CLIENT_ID` (`client_id`)
        ) ENGINE=InnoDB;
    ";

    protected const string SAMPLE_STATEMENT = "
        INSERT INTO `invoice` (`client_id`, `created_at`, `due_date`, `gross_amount`) VALUES
        (1, '2024-01-01', '2024-01-15', 10000.01),
        (1, '2024-02-15', '2024-02-29', 521.34),
        (1, '2024-03-01', '2024-03-15', 726.19),
        (2, '2024-01-26', '2024-02-10', 47.11),
        (2, '2024-04-30', '2024-05-14', 21.04),
        (2, '2024-07-19', '2024-08-11', 76.93),
        (3, '2024-06-11', '2024-07-05', 427.74),
        (3, '2024-10-14', '2024-10-30', 212.0),
        (3, '2024-12-21', '2025-01-12', 6.10);
    ";
}
