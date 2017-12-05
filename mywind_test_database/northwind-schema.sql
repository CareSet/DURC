
SET FOREIGN_KEY_CHECKS=0;
DROP SCHEMA IF EXISTS `northwind_model` ;
CREATE SCHEMA IF NOT EXISTS `northwind_model` DEFAULT CHARACTER SET latin1 ;


-- -----------------------------------------------------
-- Table `northwind_model`.`customers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind_model`.`customers` (
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
-- Table `northwind_model`.`employees`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind_model`.`employees` (
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
-- Table `northwind_model`.`privileges`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind_model`.`privileges` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `privilegeName` VARCHAR(50) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `northwind_model`.`employeePrivileges`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind_model`.`employeePrivileges` (
  `employee_id` INT(11) NOT NULL,
  `privilege_id` INT(11) NOT NULL,
  PRIMARY KEY (`employee_id`, `privilege_id`),
  INDEX `employee_id` (`employee_id` ASC),
  INDEX `privilege_id` (`privilege_id` ASC),
  CONSTRAINT `fkEmployeePrivilegesEmployees1`
    FOREIGN KEY (`employee_id`)
    REFERENCES `northwind_model`.`employees` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkEmployeePrivilegesPrivileges1`
    FOREIGN KEY (`privilege_id`)
    REFERENCES `northwind_model`.`privileges` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `northwind_model`.`inventoryTransactionTypes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind_model`.`inventoryTransactionTypes` (
  `id` TINYINT(4) NOT NULL,
  `typeName` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `northwind_model`.`shippers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind_model`.`shippers` (
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
-- Table `northwind_model`.`ordersTaxStatus`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind_model`.`ordersTaxStatus` (
  `id` TINYINT(4) NOT NULL,
  `taxStatusName` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `northwind_model`.`ordersStatus`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind_model`.`ordersStatus` (
  `id` TINYINT(4) NOT NULL,
  `statusName` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;



-- -----------------------------------------------------
-- Table `northwind_model`.`products`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind_model`.`products` (
  `supplier_ids` LONGTEXT NULL DEFAULT NULL,
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
-- Table `northwind_model`.`purchaseOrderStatus`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind_model`.`purchaseOrderStatus` (
  `id` INT(11) NOT NULL,
  `status` VARCHAR(50) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `northwind_model`.`suppliers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind_model`.`suppliers` (
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
-- Table `northwind_model`.`orderDetailsStatus`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind_model`.`orderDetailsStatus` (
  `id` INT(11) NOT NULL,
  `statusName` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;



-- -----------------------------------------------------
-- Table `northwind_model`.`salesReports`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind_model`.`salesReports` (
  `groupBy` VARCHAR(50) NOT NULL,
  `display` VARCHAR(50) NULL DEFAULT NULL,
  `title` VARCHAR(50) NULL DEFAULT NULL,
  `filterRowSource` LONGTEXT NULL DEFAULT NULL,
  `default` TINYINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`groupBy`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `northwind_model`.`strings`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind_model`.`strings` (
  `string_id` INT(11) NOT NULL AUTO_INCREMENT,
  `stringData` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`string_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


DROP SCHEMA IF EXISTS `northwind_data` ;
CREATE SCHEMA IF NOT EXISTS `northwind_data` DEFAULT CHARACTER SET latin1 ;


-- -----------------------------------------------------
-- Table `northwind_data`.`orders`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind_data`.`orders` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `employee_id` INT(11) NULL DEFAULT NULL,
  `customer_id` INT(11) NULL DEFAULT NULL,
  `orderDate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `shippedDate` DATETIME NULL DEFAULT NULL,
  `shipper_id` INT(11) NULL DEFAULT NULL,
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
  `taxStatus_id` TINYINT(4) NULL DEFAULT NULL,
  `status_id` TINYINT(4) NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `customer_id` (`customer_id` ASC),
  INDEX `employee_id` (`employee_id` ASC),
  INDEX `id` (`id` ASC),
  INDEX `shipper_id` (`shipper_id` ASC),
  INDEX `taxStatus` (`taxStatus_id` ASC),
  INDEX `shipZipPostalCode` (`shipZipPostalCode` ASC),
  CONSTRAINT `fkOrdersCustomers`
    FOREIGN KEY (`customer_id`)
    REFERENCES `northwind_model`.`customers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkOrdersEmployees1`
    FOREIGN KEY (`employee_id`)
    REFERENCES `northwind_model`.`employees` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkOrdersShippers1`
    FOREIGN KEY (`shipper_id`)
    REFERENCES `northwind_model`.`shippers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkOrdersOrdersTaxStatus1`
    FOREIGN KEY (`taxStatus_id`)
    REFERENCES `northwind_model`.`ordersTaxStatus` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkOrdersOrdersStatus1`
    FOREIGN KEY (`status_id`)
    REFERENCES `northwind_model`.`ordersStatus` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `northwind_data`.`purchaseOrders`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind_data`.`purchaseOrders` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` INT(11) NULL DEFAULT NULL,
  `createdBy` INT(11) NULL DEFAULT NULL,
  `submittedDate` DATETIME NULL DEFAULT NULL,
  `creationDate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status_id` INT(11) NULL DEFAULT '0',
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
  INDEX `status_id` (`status_id` ASC),
  INDEX `supplier_id` (`supplier_id` ASC),
  CONSTRAINT `fkPurchaseOrdersEmployees1`
    FOREIGN KEY (`createdBy`)
    REFERENCES `northwind_model`.`employees` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkPurchaseOrdersPurchaseOrderStatus1`
    FOREIGN KEY (`status_id`)
    REFERENCES `northwind_model`.`purchaseOrderStatus` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkPurchaseOrdersSuppliers1`
    FOREIGN KEY (`supplier_id`)
    REFERENCES `northwind_model`.`suppliers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `northwind_data`.`inventoryTransactions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind_data`.`inventoryTransactions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `transactionType` TINYINT(4) NOT NULL,
  `transactionCreatedDate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `transactionModifiedDate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `product_id` INT(11) NOT NULL,
  `quantity` INT(11) NOT NULL,
  `purchaseOrder_id` INT(11) NULL DEFAULT NULL,
  `customerOrder_id` INT(11) NULL DEFAULT NULL,
  `comments` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `customerOrder_id` (`customerOrder_id` ASC),
  INDEX `product_id` (`product_id` ASC),
  INDEX `purchaseOrder_id` (`purchaseOrder_id` ASC),
  INDEX `transactionType` (`transactionType` ASC),
  CONSTRAINT `fkInventoryTransactionsOrders1`
    FOREIGN KEY (`customerOrder_id`)
    REFERENCES `northwind_data`.`orders` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkInventoryTransactionsProducts1`
    FOREIGN KEY (`product_id`)
    REFERENCES `northwind_model`.`products` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkInventoryTransactionsPurchaseOrders1`
    FOREIGN KEY (`purchaseOrder_id`)
    REFERENCES `northwind_data`.`purchaseOrders` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkInventoryTransactionsInventoryTransactionTypes1`
    FOREIGN KEY (`transactionType`)
    REFERENCES `northwind_model`.`inventoryTransactionTypes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `northwind_data`.`invoices`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind_data`.`invoices` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `order_id` INT(11) NULL DEFAULT NULL,
  `invoiceDate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dueDate` DATETIME NULL DEFAULT NULL,
  `tax` DECIMAL(19,4) NULL DEFAULT '0.0000',
  `shipping` DECIMAL(19,4) NULL DEFAULT '0.0000',
  `amountDue` DECIMAL(19,4) NULL DEFAULT '0.0000',
  PRIMARY KEY (`id`),
  INDEX `id` (`id` ASC),
  INDEX `fkInvoicesOrders1_idx` (`order_id` ASC),
  CONSTRAINT `fkInvoicesOrders1`
    FOREIGN KEY (`order_id`)
    REFERENCES `northwind_model`.`orders` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `northwind_data`.`orderDetails`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind_data`.`orderDetails` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `order_id` INT(11) NOT NULL,
  `product_id` INT(11) NULL DEFAULT NULL,
  `quantity` DECIMAL(18,4) NOT NULL DEFAULT '0.0000',
  `unitPrice` DECIMAL(19,4) NULL DEFAULT '0.0000',
  `discount` DOUBLE NOT NULL DEFAULT '0',
  `status_id` INT(11) NULL DEFAULT NULL,
  `dateAllocated` DATETIME NULL DEFAULT NULL,
  `PurchaseOrder_id` INT(11) NULL DEFAULT NULL,
  `inventory_id` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `id` (`id` ASC),
  INDEX `inventory_id` (`inventory_id` ASC),
  INDEX `product_id` (`product_id` ASC),
  INDEX `purchaseOrder_id` (`purchaseOrder_id` ASC),
  INDEX `fkOrderDetailsOrders1_idx` (`order_id` ASC),
  INDEX `fkOrderDetailsOrderDetailsStatus1_idx` (`status_id` ASC),
  CONSTRAINT `fkOrderDetailsOrders1`
    FOREIGN KEY (`order_id`)
    REFERENCES `northwind_model`.`orders` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkOrderDetailsProducts1`
    FOREIGN KEY (`product_id`)
    REFERENCES `northwind_model`.`products` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkOrderDetailsOrderDetailsStatus1`
    FOREIGN KEY (`status_id`)
    REFERENCES `northwind_model`.`orderDetailsStatus` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `northwind_data`.`purchaseOrderDetails`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `northwind_data`.`purchaseOrderDetails` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `purchaseOrder_id` INT(11) NOT NULL,
  `product_id` INT(11) NULL DEFAULT NULL,
  `quantity` DECIMAL(18,4) NOT NULL,
  `unitCost` DECIMAL(19,4) NOT NULL,
  `dateReceived` DATETIME NULL DEFAULT NULL,
  `postedToInventory` TINYINT(1) NOT NULL DEFAULT '0',
  `inventory_id` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `id` (`id` ASC),
  INDEX `inventory_id` (`inventory_id` ASC),
  INDEX `purchaseOrder_id` (`purchaseOrder_id` ASC),
  INDEX `product_id` (`product_id` ASC),
  CONSTRAINT `fkPurchaseOrderDetailsInventoryTransactions1`
    FOREIGN KEY (`inventory_id`)
    REFERENCES `northwind_data`.`inventoryTransactions` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkPurchaseOrderDetailsProducts1`
    FOREIGN KEY (`product_id`)
    REFERENCES `northwind_model`.`products` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkPurchaseOrderDetailsPurchaseOrders1`
    FOREIGN KEY (`purchaseOrder_id`)
    REFERENCES `northwind_data`.`purchaseOrders` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

SET FOREIGN_KEY_CHECKS=1;


