
DROP SCHEMA IF EXISTS `northwind` ;
CREATE SCHEMA IF NOT EXISTS `northwind` DEFAULT CHARACTER SET latin1 ;
USE `northwind` ;

-- -----------------------------------------------------
-- Table `northwind`.`customers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind`.`customers` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `company` VARCHAR(50) NULL DEFAULT NULL,
  `lastName` VARCHAR(50) NULL DEFAULT NULL,
  `firstName` VARCHAR(50) NULL DEFAULT NULL,
  `emailAddress` VARCHAR(50) NULL DEFAULT NULL,
  `jobTitle` VARCHAR(50) NULL DEFAULT NULL,
  `businessPhone` VARCHAR(25) NULL DEFAULT NULL,
  `homePhone` VARCHAR(25) NULL DEFAULT NULL,
  `mobilePhone` VARCHAR(25) NULL DEFAULT NULL,
  `faxNumber` VARCHAR(25) NULL DEFAULT NULL,
  `address` LONGTEXT NULL DEFAULT NULL,
  `city` VARCHAR(50) NULL DEFAULT NULL,
  `stateProvince` VARCHAR(50) NULL DEFAULT NULL,
  `zipPostalCode` VARCHAR(15) NULL DEFAULT NULL,
  `countryRegion` VARCHAR(50) NULL DEFAULT NULL,
  `webPage` LONGTEXT NULL DEFAULT NULL,
  `notes` LONGTEXT NULL DEFAULT NULL,
  `attachments` LONGBLOB NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `city` (`city` ASC),
  INDEX `company` (`company` ASC),
  INDEX `firstName` (`firstName` ASC),
  INDEX `lastName` (`lastName` ASC),
  INDEX `zipPostalCode` (`zipPostalCode` ASC),
  INDEX `stateProvince` (`stateProvince` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `northwind`.`employees`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind`.`employees` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `company` VARCHAR(50) NULL DEFAULT NULL,
  `lastName` VARCHAR(50) NULL DEFAULT NULL,
  `firstName` VARCHAR(50) NULL DEFAULT NULL,
  `emailAddress` VARCHAR(50) NULL DEFAULT NULL,
  `jobTitle` VARCHAR(50) NULL DEFAULT NULL,
  `businessPhone` VARCHAR(25) NULL DEFAULT NULL,
  `homePhone` VARCHAR(25) NULL DEFAULT NULL,
  `mobilePhone` VARCHAR(25) NULL DEFAULT NULL,
  `faxNumber` VARCHAR(25) NULL DEFAULT NULL,
  `address` LONGTEXT NULL DEFAULT NULL,
  `city` VARCHAR(50) NULL DEFAULT NULL,
  `stateProvince` VARCHAR(50) NULL DEFAULT NULL,
  `zipPostalCode` VARCHAR(15) NULL DEFAULT NULL,
  `countryRegion` VARCHAR(50) NULL DEFAULT NULL,
  `webPage` LONGTEXT NULL DEFAULT NULL,
  `notes` LONGTEXT NULL DEFAULT NULL,
  `attachments` LONGBLOB NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `city` (`city` ASC),
  INDEX `company` (`company` ASC),
  INDEX `firstName` (`firstName` ASC),
  INDEX `lastName` (`lastName` ASC),
  INDEX `zipPostalCode` (`zipPostalCode` ASC),
  INDEX `stateProvince` (`stateProvince` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `northwind`.`privileges`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind`.`privileges` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `privilegeName` VARCHAR(50) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `northwind`.`employeePrivileges`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind`.`employeePrivileges` (
  `employeeId` INT(11) NOT NULL,
  `privilegeId` INT(11) NOT NULL,
  PRIMARY KEY (`employeeId`, `privilegeId`),
  INDEX `employeeId` (`employeeId` ASC),
  INDEX `privilegeId` (`privilegeId` ASC),
  INDEX `privilegeId_2` (`privilegeId` ASC),
  CONSTRAINT `fkEmployeePrivilegesEmployees1`
    FOREIGN KEY (`employeeId`)
    REFERENCES `northwind`.`employees` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkEmployeePrivilegesPrivileges1`
    FOREIGN KEY (`privilegeId`)
    REFERENCES `northwind`.`privileges` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `northwind`.`inventoryTransactionTypes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind`.`inventoryTransactionTypes` (
  `id` TINYINT(4) NOT NULL,
  `typeName` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `northwind`.`shippers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind`.`shippers` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `company` VARCHAR(50) NULL DEFAULT NULL,
  `lastName` VARCHAR(50) NULL DEFAULT NULL,
  `firstName` VARCHAR(50) NULL DEFAULT NULL,
  `emailAddress` VARCHAR(50) NULL DEFAULT NULL,
  `jobTitle` VARCHAR(50) NULL DEFAULT NULL,
  `businessPhone` VARCHAR(25) NULL DEFAULT NULL,
  `homePhone` VARCHAR(25) NULL DEFAULT NULL,
  `mobilePhone` VARCHAR(25) NULL DEFAULT NULL,
  `faxNumber` VARCHAR(25) NULL DEFAULT NULL,
  `address` LONGTEXT NULL DEFAULT NULL,
  `city` VARCHAR(50) NULL DEFAULT NULL,
  `stateProvince` VARCHAR(50) NULL DEFAULT NULL,
  `zipPostalCode` VARCHAR(15) NULL DEFAULT NULL,
  `countryRegion` VARCHAR(50) NULL DEFAULT NULL,
  `webPage` LONGTEXT NULL DEFAULT NULL,
  `notes` LONGTEXT NULL DEFAULT NULL,
  `attachments` LONGBLOB NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `city` (`city` ASC),
  INDEX `company` (`company` ASC),
  INDEX `firstName` (`firstName` ASC),
  INDEX `lastName` (`lastName` ASC),
  INDEX `zipPostalCode` (`zipPostalCode` ASC),
  INDEX `stateProvince` (`stateProvince` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `northwind`.`ordersTaxStatus`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind`.`ordersTaxStatus` (
  `id` TINYINT(4) NOT NULL,
  `taxStatusName` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `northwind`.`ordersStatus`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind`.`ordersStatus` (
  `id` TINYINT(4) NOT NULL,
  `statusName` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `northwind`.`orders`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind`.`orders` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `employeeId` INT(11) NULL DEFAULT NULL,
  `customerId` INT(11) NULL DEFAULT NULL,
  `orderDate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `shippedDate` DATETIME NULL DEFAULT NULL,
  `shipperId` INT(11) NULL DEFAULT NULL,
  `shipName` VARCHAR(50) NULL DEFAULT NULL,
  `shipAddress` LONGTEXT NULL DEFAULT NULL,
  `shipCity` VARCHAR(50) NULL DEFAULT NULL,
  `shipStateProvince` VARCHAR(50) NULL DEFAULT NULL,
  `shipZipPostalCode` VARCHAR(50) NULL DEFAULT NULL,
  `shipCountryRegion` VARCHAR(50) NULL DEFAULT NULL,
  `shippingFee` DECIMAL(19,4) NULL DEFAULT '0.0000',
  `taxes` DECIMAL(19,4) NULL DEFAULT '0.0000',
  `paymentType` VARCHAR(50) NULL DEFAULT NULL,
  `paidDate` DATETIME NULL DEFAULT NULL,
  `notes` LONGTEXT NULL DEFAULT NULL,
  `taxRate` DOUBLE NULL DEFAULT '0',
  `taxStatusId` TINYINT(4) NULL DEFAULT NULL,
  `statusId` TINYINT(4) NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `customerId` (`customerId` ASC),
  INDEX `customerId_2` (`customerId` ASC),
  INDEX `employeeId` (`employeeId` ASC),
  INDEX `employeeId_2` (`employeeId` ASC),
  INDEX `id` (`id` ASC),
  INDEX `id_2` (`id` ASC),
  INDEX `shipperId` (`shipperId` ASC),
  INDEX `shipperId_2` (`shipperId` ASC),
  INDEX `id_3` (`id` ASC),
  INDEX `taxStatus` (`taxStatusId` ASC),
  INDEX `shipZipPostalCode` (`shipZipPostalCode` ASC),
  CONSTRAINT `fkOrdersCustomers`
    FOREIGN KEY (`customerId`)
    REFERENCES `northwind`.`customers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkOrdersEmployees1`
    FOREIGN KEY (`employeeId`)
    REFERENCES `northwind`.`employees` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkOrdersShippers1`
    FOREIGN KEY (`shipperId`)
    REFERENCES `northwind`.`shippers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkOrdersOrdersTaxStatus1`
    FOREIGN KEY (`taxStatusId`)
    REFERENCES `northwind`.`ordersTaxStatus` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkOrdersOrdersStatus1`
    FOREIGN KEY (`statusId`)
    REFERENCES `northwind`.`ordersStatus` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `northwind`.`products`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind`.`products` (
  `supplierIds` LONGTEXT NULL DEFAULT NULL,
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `productCode` VARCHAR(25) NULL DEFAULT NULL,
  `productName` VARCHAR(50) NULL DEFAULT NULL,
  `description` LONGTEXT NULL DEFAULT NULL,
  `standardCost` DECIMAL(19,4) NULL DEFAULT '0.0000',
  `listPrice` DECIMAL(19,4) NOT NULL DEFAULT '0.0000',
  `reorderLevel` INT(11) NULL DEFAULT NULL,
  `targetLevel` INT(11) NULL DEFAULT NULL,
  `quantityPerUnit` VARCHAR(50) NULL DEFAULT NULL,
  `discontinued` TINYINT(1) NOT NULL DEFAULT '0',
  `minimumReorderQuantity` INT(11) NULL DEFAULT NULL,
  `category` VARCHAR(50) NULL DEFAULT NULL,
  `attachments` LONGBLOB NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `productCode` (`productCode` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `northwind`.`purchaseOrderStatus`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind`.`purchaseOrderStatus` (
  `id` INT(11) NOT NULL,
  `status` VARCHAR(50) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `northwind`.`suppliers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind`.`suppliers` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `company` VARCHAR(50) NULL DEFAULT NULL,
  `lastName` VARCHAR(50) NULL DEFAULT NULL,
  `firstName` VARCHAR(50) NULL DEFAULT NULL,
  `emailAddress` VARCHAR(50) NULL DEFAULT NULL,
  `jobTitle` VARCHAR(50) NULL DEFAULT NULL,
  `businessPhone` VARCHAR(25) NULL DEFAULT NULL,
  `homePhone` VARCHAR(25) NULL DEFAULT NULL,
  `mobilePhone` VARCHAR(25) NULL DEFAULT NULL,
  `faxNumber` VARCHAR(25) NULL DEFAULT NULL,
  `address` LONGTEXT NULL DEFAULT NULL,
  `city` VARCHAR(50) NULL DEFAULT NULL,
  `stateProvince` VARCHAR(50) NULL DEFAULT NULL,
  `zipPostalCode` VARCHAR(15) NULL DEFAULT NULL,
  `countryRegion` VARCHAR(50) NULL DEFAULT NULL,
  `webPage` LONGTEXT NULL DEFAULT NULL,
  `notes` LONGTEXT NULL DEFAULT NULL,
  `attachments` LONGBLOB NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `city` (`city` ASC),
  INDEX `company` (`company` ASC),
  INDEX `firstName` (`firstName` ASC),
  INDEX `lastName` (`lastName` ASC),
  INDEX `zipPostalCode` (`zipPostalCode` ASC),
  INDEX `stateProvince` (`stateProvince` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `northwind`.`purchaseOrders`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind`.`purchaseOrders` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `supplierId` INT(11) NULL DEFAULT NULL,
  `createdBy` INT(11) NULL DEFAULT NULL,
  `submittedDate` DATETIME NULL DEFAULT NULL,
  `creationDate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `statusId` INT(11) NULL DEFAULT '0',
  `expectedDate` DATETIME NULL DEFAULT NULL,
  `shippingFee` DECIMAL(19,4) NOT NULL DEFAULT '0.0000',
  `taxes` DECIMAL(19,4) NOT NULL DEFAULT '0.0000',
  `paymentDate` DATETIME NULL DEFAULT NULL,
  `paymentAmount` DECIMAL(19,4) NULL DEFAULT '0.0000',
  `paymentMethod` VARCHAR(50) NULL DEFAULT NULL,
  `notes` LONGTEXT NULL DEFAULT NULL,
  `approvedBy` INT(11) NULL DEFAULT NULL,
  `approvedDate` DATETIME NULL DEFAULT NULL,
  `submittedBy` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id` (`id` ASC),
  INDEX `createdBy` (`createdBy` ASC),
  INDEX `statusId` (`statusId` ASC),
  INDEX `id_2` (`id` ASC),
  INDEX `supplierId` (`supplierId` ASC),
  INDEX `supplierId_2` (`supplierId` ASC),
  CONSTRAINT `fkPurchaseOrdersEmployees1`
    FOREIGN KEY (`createdBy`)
    REFERENCES `northwind`.`employees` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkPurchaseOrdersPurchaseOrderStatus1`
    FOREIGN KEY (`statusId`)
    REFERENCES `northwind`.`purchaseOrderStatus` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkPurchaseOrdersSuppliers1`
    FOREIGN KEY (`supplierId`)
    REFERENCES `northwind`.`suppliers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `northwind`.`inventoryTransactions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind`.`inventoryTransactions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `transactionType` TINYINT(4) NOT NULL,
  `transactionCreatedDate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `transactionModifiedDate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `productId` INT(11) NOT NULL,
  `quantity` INT(11) NOT NULL,
  `purchaseOrderId` INT(11) NULL DEFAULT NULL,
  `customerOrderId` INT(11) NULL DEFAULT NULL,
  `comments` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `customerOrderId` (`customerOrderId` ASC),
  INDEX `customerOrderId_2` (`customerOrderId` ASC),
  INDEX `productId` (`productId` ASC),
  INDEX `productId_2` (`productId` ASC),
  INDEX `purchaseOrderId` (`purchaseOrderId` ASC),
  INDEX `purchaseOrderId_2` (`purchaseOrderId` ASC),
  INDEX `transactionType` (`transactionType` ASC),
  CONSTRAINT `fkInventoryTransactionsOrders1`
    FOREIGN KEY (`customerOrderId`)
    REFERENCES `northwind`.`orders` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkInventoryTransactionsProducts1`
    FOREIGN KEY (`productId`)
    REFERENCES `northwind`.`products` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkInventoryTransactionsPurchaseOrders1`
    FOREIGN KEY (`purchaseOrderId`)
    REFERENCES `northwind`.`purchaseOrders` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkInventoryTransactionsInventoryTransactionTypes1`
    FOREIGN KEY (`transactionType`)
    REFERENCES `northwind`.`inventoryTransactionTypes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `northwind`.`invoices`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind`.`invoices` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `orderId` INT(11) NULL DEFAULT NULL,
  `invoiceDate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dueDate` DATETIME NULL DEFAULT NULL,
  `tax` DECIMAL(19,4) NULL DEFAULT '0.0000',
  `shipping` DECIMAL(19,4) NULL DEFAULT '0.0000',
  `amountDue` DECIMAL(19,4) NULL DEFAULT '0.0000',
  PRIMARY KEY (`id`),
  INDEX `id` (`id` ASC),
  INDEX `id_2` (`id` ASC),
  INDEX `fkInvoicesOrders1Idx` (`orderId` ASC),
  CONSTRAINT `fkInvoicesOrders1`
    FOREIGN KEY (`orderId`)
    REFERENCES `northwind`.`orders` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `northwind`.`orderDetailsStatus`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind`.`orderDetailsStatus` (
  `id` INT(11) NOT NULL,
  `statusName` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `northwind`.`orderDetails`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind`.`orderDetails` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `orderId` INT(11) NOT NULL,
  `productId` INT(11) NULL DEFAULT NULL,
  `quantity` DECIMAL(18,4) NOT NULL DEFAULT '0.0000',
  `unitPrice` DECIMAL(19,4) NULL DEFAULT '0.0000',
  `discount` DOUBLE NOT NULL DEFAULT '0',
  `statusId` INT(11) NULL DEFAULT NULL,
  `dateAllocated` DATETIME NULL DEFAULT NULL,
  `PurchaseOrderId` INT(11) NULL DEFAULT NULL,
  `inventoryId` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `id` (`id` ASC),
  INDEX `inventoryId` (`inventoryId` ASC),
  INDEX `Id_2` (`id` ASC),
  INDEX `id_3` (`id` ASC),
  INDEX `id_4` (`id` ASC),
  INDEX `productId` (`productId` ASC),
  INDEX `productId_2` (`productId` ASC),
  INDEX `purchaseOrderId` (`purchaseOrderId` ASC),
  INDEX `id_5` (`id` ASC),
  INDEX `fkOrderDetailsOrders1Idx` (`orderId` ASC),
  INDEX `fkOrderDetailsOrderDetailsStatus1Idx` (`statusId` ASC),
  CONSTRAINT `fkOrderDetailsOrders1`
    FOREIGN KEY (`orderId`)
    REFERENCES `northwind`.`orders` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkOrderDetailsProducts1`
    FOREIGN KEY (`productId`)
    REFERENCES `northwind`.`products` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkOrderDetailsOrderDetailsStatus1`
    FOREIGN KEY (`statusId`)
    REFERENCES `northwind`.`orderDetailsStatus` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `northwind`.`purchaseOrderDetails`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind`.`purchaseOrderDetails` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `purchaseOrderId` INT(11) NOT NULL,
  `productId` INT(11) NULL DEFAULT NULL,
  `quantity` DECIMAL(18,4) NOT NULL,
  `unitCost` DECIMAL(19,4) NOT NULL,
  `dateReceived` DATETIME NULL DEFAULT NULL,
  `postedToInventory` TINYINT(1) NOT NULL DEFAULT '0',
  `inventoryId` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `id` (`id` ASC),
  INDEX `inventoryId` (`inventoryId` ASC),
  INDEX `inventoryId_2` (`inventoryId` ASC),
  INDEX `purchaseOrderId` (`purchaseOrderId` ASC),
  INDEX `productId` (`productId` ASC),
  INDEX `productId_2` (`productId` ASC),
  INDEX `purchaseOrderId_2` (`purchaseOrderId` ASC),
  CONSTRAINT `fkPurchaseOrderDetailsInventoryTransactions1`
    FOREIGN KEY (`inventoryId`)
    REFERENCES `northwind`.`inventoryTransactions` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkPurchaseOrderDetailsProducts1`
    FOREIGN KEY (`productId`)
    REFERENCES `northwind`.`products` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkPurchaseOrderDetailsPurchaseOrders1`
    FOREIGN KEY (`purchaseOrderId`)
    REFERENCES `northwind`.`purchaseOrders` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `northwind`.`salesReports`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind`.`salesReports` (
  `groupBy` VARCHAR(50) NOT NULL,
  `display` VARCHAR(50) NULL DEFAULT NULL,
  `title` VARCHAR(50) NULL DEFAULT NULL,
  `filterRowSource` LONGTEXT NULL DEFAULT NULL,
  `default` TINYINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`groupBy`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `northwind`.`strings`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind`.`strings` (
  `stringId` INT(11) NOT NULL AUTO_INCREMENT,
  `stringData` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`stringId`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

