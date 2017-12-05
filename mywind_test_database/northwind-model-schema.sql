
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

