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
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `due_date`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `gross_amount` DECIMAL(20,4) NOT NULL,
            PRIMARY KEY (`id`),
            KEY `INVOICE_CLIENT_ID` (`client_id`)
        ) ENGINE=InnoDB;
    ";

}
