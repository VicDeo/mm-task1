<?php

declare(strict_types=1);

namespace Task1\Model\Db\Table;

use Task1\Model\Db\Table;

class InvoiceItem extends Table
{
    protected const string CREATE_STATEMENT = "
        CREATE TABLE IF NOT EXISTS `invoice_item` (
            `id` BIGINT NOT NULL AUTO_INCREMENT,
            `invoice_id` BIGINT NOT NULL,
            `product_title` VARCHAR(256) NOT NULL,
            `qty` INT NOT NULL,
            `price` DECIMAL(20,2) NOT NULL,
            PRIMARY KEY (`id`),
            KEY `INVOICE_ITEM_INVOICE_ID` (`invoice_id`)
        ) ENGINE=InnoDB;
    ";

    protected const string SAMPLE_STATEMENT = "
        INSERT INTO `invoice_item` (`invoice_id`, `product_title`, `qty`, `price`) VALUES
        (1, 'Worek', 100, 0.5)
    ;";
}
