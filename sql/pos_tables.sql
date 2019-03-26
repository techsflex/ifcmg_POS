-- -----------------------------------------------------
-- Table `login`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `login`;
CREATE TABLE IF NOT EXISTS `login` (
  `username` VARCHAR(15) NOT NULL,
  `password` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `status`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `status`;
CREATE TABLE IF NOT EXISTS `status` (
  `statusID` INT UNSIGNED AUTO_INCREMENT,
  `statuscaption` VARCHAR(5) NOT NULL,
  PRIMARY KEY (`statusID`),
  UNIQUE KEY `statuscaption` (`statuscaption`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Dumping data for table `status`

INSERT INTO `status` (`statusID`, `statuscaption`) VALUES
(1, 'none'),
(2, 'admin'),
(3, 'super'),
(4, 'user'),
(5, 'kitch'),
(6, 'close');


-- -----------------------------------------------------
-- Table `posversion`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `posversion`;
CREATE TABLE IF NOT EXISTS `posversion` (
  `posID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `poscaption` VARCHAR(13) NOT NULL,
  PRIMARY KEY (`posID`),
  UNIQUE KEY `poscaption` (`poscaption`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `posversion`

INSERT INTO `posversion` (`posID`, `poscaption`) VALUES
(1, 'Disabled'),
(2, 'Basic Version'),
(3, 'Full Version');

-- -----------------------------------------------------
-- Table `company`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `company`;
CREATE TABLE IF NOT EXISTS `company` (
  `companyID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `companyname` VARCHAR(50) NOT NULL,
  `address1` VARCHAR(45) NULL,
  `address2` VARCHAR(45) NULL,
  `city` VARCHAR(20) NULL,
  `phone` VARCHAR(16) NULL,
  `numtables` INT UNSIGNED DEFAULT 0,
  `posversion_posID` INT UNSIGNED DEFAULT 1,
  PRIMARY KEY (`companyID`),
  UNIQUE KEY `companyname` (`companyname`),
  UNIQUE KEY `phone` (`phone`),

  CONSTRAINT `fk_company_posversion`
    FOREIGN KEY (`posversion_posID`) REFERENCES `posversion` (`posID`)
      ON DELETE SET NULL
      ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `userID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(50) NOT NULL,
  `last_name` VARCHAR(50) NOT NULL,
  `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `login_username` VARCHAR(15) NOT NULL,
  `status_statusID` INT UNSIGNED DEFAULT 1,
  `company_companyID` INT UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `login_username` (`login_username`),

  CONSTRAINT `fk_users_login`
    FOREIGN KEY (`login_username`) REFERENCES `login` (`username`)
    	ON DELETE CASCADE
    	ON UPDATE CASCADE,
  CONSTRAINT `fk_users_status1`
    FOREIGN KEY (`status_statusID`) REFERENCES `status` (`statusID`)
    	ON DELETE SET NULL
    	ON UPDATE CASCADE,
  CONSTRAINT `fk_users_company1`
    FOREIGN KEY (`company_companyID`) REFERENCES `company` (`companyID`)
    	ON DELETE CASCADE
    	ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `customer`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `custID` INT UNSIGNED AUTO_INCREMENT,
  `custname` VARCHAR(50) DEFAULT NULL,
  `custaddress` VARCHAR(100) DEFAULT NULL,
  `custphone` VARCHAR(16) DEFAULT NULL,
  `company_companyID` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`custID`),
  UNIQUE KEY `custphone` (`custphone`),

  CONSTRAINT `fk_customer_company1`
    FOREIGN KEY (`company_companyID`) REFERENCES `company` (`companyID`)
      ON DELETE CASCADE
      ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `cat`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cat`;
CREATE TABLE IF NOT EXISTS `cat` (
  `catID` INT UNSIGNED AUTO_INCREMENT,
  `catname` VARCHAR(45) DEFAULT NULL,
  `company_companyID` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`catID`),

  CONSTRAINT `fk_cat_company1`
    FOREIGN KEY (`company_companyID`) REFERENCES `company` (`companyID`)
      ON DELETE CASCADE
      ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `products`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `skuID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `productID` VARCHAR(20) NOT NULL,
  `productname` VARCHAR(45) NOT NULL,
  `productprice` VARCHAR(10) NOT NULL,
  `description` VARCHAR(150) NULL,
  `cat_catID` INT UNSIGNED NULL,
  PRIMARY KEY (`skuID`),

  CONSTRAINT `fk_products_cat1`
    FOREIGN KEY (`cat_catID`) REFERENCES `cat` (`catID`)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `sku_pricehist`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pricehist`;
CREATE TABLE IF NOT EXISTS `pricehist` (
  `priceID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `date_modified` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` VARCHAR(100) NOT NULL,
  `price` VARCHAR(10) NOT NULL,
  `products_skuID` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`priceID`),

  CONSTRAINT `fk_pricehist_products1`
    FOREIGN KEY (`products_skuID`)
    REFERENCES `products` (`skuID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `orders`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `orders` (
  `orderID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `paymentID` VARCHAR(5) NOT NULL,
  `ordertype` VARCHAR(9) NOT NULL,
  `datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `subtotal` FLOAT NOT NULL,
  `taxrate` FLOAT NOT NULL,
  `discount` FLOAT NOT NULL DEFAULT 0,
  `grandtotal` FLOAT NOT NULL,
  `breakdown` VARCHAR(10000) NOT NULL,
  `kitchenstatus` INT NOT NULL,
  `company_companyID` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`orderID`),
  CONSTRAINT `fk_orders_company1`
    FOREIGN KEY (`company_companyID`)
    REFERENCES `company` (`companyID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `held`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `held` (
  `heldID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ordertype` VARCHAR(9) NOT NULL,
  `datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `subtotal` FLOAT NOT NULL,
  `taxrate` FLOAT NOT NULL,
  `discount` FLOAT NOT NULL DEFAULT 0,
  `grandtotal` FLOAT NOT NULL,
  `breakdown` VARCHAR(10000) NOT NULL,
  `kitchenstatus` INT NOT NULL DEFAULT 0,
  `company_companyID` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`heldID`),
  CONSTRAINT `fk_held_company1`
    FOREIGN KEY (`company_companyID`)
    REFERENCES `company` (`companyID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `tillamount`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tillamount` (
  `tillID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tillopendate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tillopencash` INT NOT NULL,
  `tillclosedate` DATETIME NULL,
  `tillclosecash` INT NULL,
  `tillcashout` INT NULL,
  `company_companyID` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`tillID`),
  CONSTRAINT `fk_tillamount_company1`
    FOREIGN KEY (`company_companyID`)
    REFERENCES `company` (`companyID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `server`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `server`;
CREATE TABLE IF NOT EXISTS `server` (
  `serverID` INT UNSIGNED AUTO_INCREMENT,
  `servername` VARCHAR(45) DEFAULT NULL,
  `company_companyID` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`serverID`),

  CONSTRAINT `fk_server_company1`
    FOREIGN KEY (`company_companyID`) REFERENCES `company` (`companyID`)
      ON DELETE CASCADE
      ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;