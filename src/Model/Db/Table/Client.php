<?php

declare(strict_types=1);

namespace Task1\Model\Db\Table;

use Task1\Model\Db\Table;

class Client extends Table
{
    protected const string CREATE_STATEMENT = "
            CREATE TABLE IF NOT EXISTS `client` (
                `id` BIGINT NOT NULL AUTO_INCREMENT,
                `title` VARCHAR(1024) NOT NULL,
                `bank_account` VARCHAR(28) NOT NULL,
                `NIP` VARCHAR(10) NOT NULL,
                PRIMARY KEY (`id`),
                KEY `CLIENT_BANK_ACCOUNT` (`bank_account`)
            ) ENGINE=InnoDB;
    ";
}
