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
            `price` DECIMAL(20,4) NOT NULL,
            PRIMARY KEY (`id`),
            KEY `INVOICE_ITEM_INVOICE_ID` (`invoice_id`)
        ) ENGINE=InnoDB;
    ";
}
