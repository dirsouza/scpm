-- MySQL Script generated by MySQL Workbench
-- 04/25/18 15:58:17
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema db_scmm
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `db_scmm` ;

-- -----------------------------------------------------
-- Schema db_scmm
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `db_scmm` DEFAULT CHARACTER SET utf8 ;
USE `db_scmm` ;

-- -----------------------------------------------------
-- Table `tbusuario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbusuario` ;

CREATE TABLE IF NOT EXISTS `tbusuario` (
  `idusuario` INT NOT NULL AUTO_INCREMENT,
  `deslogin` VARCHAR(50) NOT NULL,
  `dessenha` VARCHAR(256) NOT NULL,
  `desadmin` TINYINT(1) NOT NULL,
  PRIMARY KEY (`idusuario`),
  UNIQUE INDEX `deslogin_UNIQUE` (`deslogin` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `tbadministrador`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbadministrador` ;

CREATE TABLE IF NOT EXISTS `tbadministrador` (
  `idadministrador` INT NOT NULL AUTO_INCREMENT,
  `idusuario` INT NOT NULL,
  `desnome` VARCHAR(100) NOT NULL,
  `descpf` VARCHAR(14) NOT NULL,
  `desrg` VARCHAR(20) NOT NULL,
  `desemail` VARCHAR(50) NOT NULL,
  `destelefone` VARCHAR(15) NOT NULL,
  PRIMARY KEY (`idadministrador`, `idusuario`),
  UNIQUE INDEX `descpf_UNIQUE` (`descpf` ASC),
  UNIQUE INDEX `desemail_UNIQUE` (`desemail` ASC),
  CONSTRAINT `tbadministrador_tbusuarios_fk`
    FOREIGN KEY (`idusuario`)
    REFERENCES `tbusuario` (`idusuario`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `tbcliente`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbcliente` ;

CREATE TABLE IF NOT EXISTS `tbcliente` (
  `idcliente` INT NOT NULL AUTO_INCREMENT,
  `idusuario` INT NOT NULL,
  `desnome` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idcliente`, `idusuario`),
  CONSTRAINT `tbcliente_tbusuarios_fk`
    FOREIGN KEY (`idusuario`)
    REFERENCES `tbusuario` (`idusuario`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `tbcomercio`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbcomercio` ;

CREATE TABLE IF NOT EXISTS `tbcomercio` (
  `idcomercio` INT NOT NULL AUTO_INCREMENT,
  `desnome` VARCHAR(100) NOT NULL,
  `descep` VARCHAR(9) NOT NULL,
  `desrua` VARCHAR(100) NOT NULL,
  `desbairro` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`idcomercio`),
  UNIQUE INDEX `desnome_UNIQUE` (`desnome` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `tbproduto`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbproduto` ;

CREATE TABLE IF NOT EXISTS `tbproduto` (
  `idproduto` INT NOT NULL AUTO_INCREMENT,
  `desnome` VARCHAR(100) NOT NULL,
  `desmarca` VARCHAR(50) NOT NULL,
  `desdescricao` TEXT NOT NULL,
  PRIMARY KEY (`idproduto`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `tbProdutoComercio`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbProdutoComercio` ;

CREATE TABLE IF NOT EXISTS `tbProdutoComercio` (
  `idProdutoComercio` INT NOT NULL AUTO_INCREMENT,
  `idcomercio` INT NOT NULL,
  `idproduto` INT NOT NULL,
  `despreco` DOUBLE(4,2) NOT NULL,
  PRIMARY KEY (`idProdutoComercio`, `idcomercio`, `idproduto`),
  CONSTRAINT `tbProdutoComercio_tbcomercio_fk`
    FOREIGN KEY (`idcomercio`)
    REFERENCES `tbcomercio` (`idcomercio`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `tbProdutoComercio_tbproduto_fk`
    FOREIGN KEY (`idproduto`)
    REFERENCES `tbproduto` (`idproduto`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `tbFiltroCliente`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbFiltroCliente` ;

CREATE TABLE IF NOT EXISTS `tbFiltroCliente` (
  `idFiltroCliente` INT NOT NULL AUTO_INCREMENT,
  `idcliente` INT NOT NULL,
  `desfiltro` TEXT NOT NULL,
  `dtfiltro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idFiltroCliente`, `idcliente`),
  CONSTRAINT `tbFiltroCliente_tbcliente_fk`
    FOREIGN KEY (`idcliente`)
    REFERENCES `tbcliente` (`idcliente`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `tbusuario`
-- -----------------------------------------------------
START TRANSACTION;
USE `db_scmm`;
INSERT INTO `tbusuario` (`idusuario`, `deslogin`, `dessenha`, `desadmin`) VALUES (1, 'admin', '$2y$10$Rm2BXZK8KqYmnd3HtstfaeyxvuFVzWnFZB7XNiDEar9maOk2uTQwO', 1);

COMMIT;


-- -----------------------------------------------------
-- Data for table `tbadministrador`
-- -----------------------------------------------------
START TRANSACTION;
USE `db_scmm`;
INSERT INTO `tbadministrador` (`idadministrador`, `idusuario`, `desnome`, `descpf`, `desrg`, `desemail`, `destelefone`) VALUES (1, 1, 'Administrador', '000.000.000-00', '0000000000000', 'admin@scmm.com.br', '(00) 00000-0000');

COMMIT;

