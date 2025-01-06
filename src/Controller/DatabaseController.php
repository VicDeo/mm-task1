<?php
declare(strict_types=1);

namespace Task1\Controller;

use Task1\Model\Attribute\Route;
use Task1\Model\Http\Message\Response\Template;

class DatabaseController extends Controller
{
    #[Route(method: 'GET', path: '/db/create', name: 'db_create')]
    public function create(): Template
    {
/*

CREATE TABLE `invoice` (
	`id` BIGINT NOT NULL AUTO_INCREMENT,
    `client_id` BIGINT,
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`due_date`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`gross_amount` DECIMAL(20,4) NOT NULL,
	PRIMARY KEY (`id`),
    KEY `INVOICE_CLIENT_ID` (`client_id`)

) ENGINE=InnoDB;

CREATE TABLE `invoice_item` (
	`id` BIGINT NOT NULL AUTO_INCREMENT,
    `invoice_id` BIGINT NOT NULL,
	`product_title` VARCHAR(256) NOT NULL,
	`qty` INT NOT NULL,
	`price` DECIMAL(20,4) NOT NULL
	PRIMARY KEY (`id`),
    KEY `INVOICE_ITEM_INVOICE_ID` (`invoice_id`)

) ENGINE=InnoDB;

CREATE TABLE `payment` (
	`id` BIGINT NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(256) NOT NULL,
	`amount` DECIMAL(20,4) NOT NULL,
	`paid_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`bank_account` VARCHAR(28) NOT NULL
	PRIMARY KEY (`id`),
    KEY `PAYMENT_BANK_ACCOUNT` (`bank_account`)
) ENGINE=InnoDB;

 */
        $statement = $this->getApp()->getDb()->prepare("
            CREATE TABLE IF NOT EXISTS `client` (
                `id` BIGINT NOT NULL AUTO_INCREMENT,
                `title` VARCHAR(1024) NOT NULL,
                `bank_account` VARCHAR(28) NOT NULL,
                `NIP` VARCHAR(10) NOT NULL,
                PRIMARY KEY (`id`),
                KEY `CLIENT_BANK_ACCOUNT` (`bank_account`)
            ) ENGINE=InnoDB;
        ");
        $statement->execute();
    

        return new Template('');
    }

    #[Route(method: 'GET', path: '/db/seed', name: 'db_seed')]
    public function seed(): Template
    {
        return new Template('');
    }
}