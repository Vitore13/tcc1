-- SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
-- SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
-- SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

CREATE SCHEMA IF NOT EXISTS `tcc2` DEFAULT CHARACTER SET utf8mb4 ;
USE `tcc2` ;

-- -----------------------------------------------------
-- Table `tcc2`.`categoria`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tcc2`.`categoria` (
  `id_categoria` INT(11) NOT NULL AUTO_INCREMENT,
  `nomedacategoria` VARCHAR(60) NOT NULL,
  PRIMARY KEY (`id_categoria`))
ENGINE = InnoDB
AUTO_INCREMENT = 16
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `tcc2`.`item`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tcc2`.`item` (
  `id_item` INT(11) NOT NULL AUTO_INCREMENT,
  `nomedoitem` VARCHAR(60) NOT NULL,
  `quantidade` INT(11) NOT NULL,
  `id_categoria` INT(11) NOT NULL,
  PRIMARY KEY (`id_item`),
  INDEX `fk_item_categoria` (`id_categoria` ASC),
  CONSTRAINT `fk_item_categoria`
    FOREIGN KEY (`id_categoria`)
    REFERENCES `tcc2`.`categoria` (`id_categoria`)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `tcc2`.`movimentacao`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tcc2`.`movimentacao` (
  `id_movimentacao` INT(11) NOT NULL AUTO_INCREMENT,
  `responsavel` VARCHAR(60) NOT NULL,
  `data` DATE NULL DEFAULT NULL,
  `tipo_movimentacao` TINYINT(1) NOT NULL,
  `quantidade_movimentacao` INT(11) NOT NULL,
  `id_item` INT(11) NOT NULL,
  `id_categoria` INT(11) NOT NULL,
  PRIMARY KEY (`id_movimentacao`),
  INDEX `fk_movimentacao_item` (`id_item` ASC),
  INDEX `fk_movimentacao_categoria` (`id_categoria` ASC),
  CONSTRAINT `fk_movimentacao_item`
    FOREIGN KEY (`id_item`)
    REFERENCES `tcc2`.`item` (`id_item`),
  CONSTRAINT `fk_movimentacao_categoria`
    FOREIGN KEY (`id_categoria`)
    REFERENCES `tcc2`.`categoria` (`id_categoria`)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `tcc2`.`registro`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tcc2`.`registro` (
  `id_registro` INT(11) NOT NULL AUTO_INCREMENT,
  `id_movimentacao` INT(11) NOT NULL,
  PRIMARY KEY (`id_registro`),
  INDEX `fk_registro_movimentacao` (`id_movimentacao` ASC),
  CONSTRAINT `fk_registro_movimentacao`
    FOREIGN KEY (`id_movimentacao`)
    REFERENCES `tcc2`.`movimentacao` (`id_movimentacao`)
)
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `tcc2`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tcc2`.`usuario` (
  `id_usuario` INT(11) NOT NULL AUTO_INCREMENT,
  `nomedousuario` VARCHAR(60) NOT NULL,
  `email` VARCHAR(40) NOT NULL,
  `senha` VARCHAR(20) NOT NULL,
  `telefone` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE INDEX `id_usuario` (`id_usuario` ASC)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- SET SQL_MODE=@OLD_SQL_MODE;
-- SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
-- SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
