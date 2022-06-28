-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: May 24, 2022 at 03:02 AM
-- Server version: 5.7.26
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `2speedy`
--
CREATE DATABASE IF NOT EXISTS `2speedy` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `2speedy`;

-- --------------------------------------------------------

--
-- Table structure for table `carrito`
--

CREATE TABLE `carrito` (
  `idcarrito` int(11) NOT NULL,
  `productoId` varchar(7) NOT NULL,
  `usuarioId` varchar(30) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `comprado` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Define si es un articulo por comprar o ya esta comprado.True=compradoFalse=por comprar',
  `fechaMvto` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `talla` varchar(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `carrito`
--

INSERT INTO `carrito` (`idcarrito`, `productoId`, `usuarioId`, `cantidad`, `comprado`, `fechaMvto`, `talla`) VALUES
(20, 'BJER019', 'leonardoparra@gmail.com', 1, 1, '2020-12-24 18:00:01', 's'),
(21, 'BJER019', 'leonardoparra@gmail.com', 1, 1, '2020-12-24 18:01:19', 'm'),
(22, 'BJER019', 'leonardoparra@gmail.com', 1, 1, '2020-12-24 18:07:38', 's'),
(27, 'BJER019', 'leonardoparra@gmail.com', 1, 1, '2021-01-18 02:28:35', 'm'),
(32, 'BJER018', 'leonardoparra@gmail.com', 2, 1, '2021-02-14 01:02:50', 'm');

-- --------------------------------------------------------

--
-- Table structure for table `categoria`
--

CREATE TABLE `categoria` (
  `categoriaId` varchar(3) NOT NULL,
  `Nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categoria`
--

INSERT INTO `categoria` (`categoriaId`, `Nombre`) VALUES
('BAS', 'Basquetball'),
('FUT', 'Fútbol'),
('TEN', 'Tenis'),
('VOL', 'Voleibol');

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `productoId` varchar(7) NOT NULL,
  `categoriaId` varchar(3) NOT NULL,
  `Nombre` varchar(45) NOT NULL,
  `talla` varchar(1) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Si el producto maneja talla o no.',
  `descripcion` varchar(45) NOT NULL,
  `imagen` varchar(45) NOT NULL COMMENT 'Nombre del archivo de la imagen del producto',
  `precio` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `productos`
--

INSERT INTO `productos` (`productoId`, `categoriaId`, `Nombre`, `talla`, `descripcion`, `imagen`, `precio`, `cantidad`) VALUES
('BBAL002', 'BAS', 'Balón Basqutbol Spalding', '0', 'Balón oficial de la NBA', 'bas-balon-spalding.jpg', 80000, 74),
('BJER018', 'BAS', 'Jersey de los Lakers', '1', 'Jersey de los Angeles Lakers', 'bas-jersey-lakers.png', 150000, 762),
('BJER019', 'BAS', 'Jersey de los Bulls', '1', 'Jersey de los Chicago Bulls', 'bas-jersey-bulls.jpg', 150000, 796),
('BJER020', 'BAS', 'Jersey de los Magic', '1', 'Jersey de los Orlando Magic', 'bas-jersey-magic.jpg', 150000, 843),
('BTEN015', 'BAS', 'Tenis de basquetbol Nike', '1', 'Tenis para basquetbol marca Nike', 'bas-tenis-nike.jpg', 230000, 0),
('BTEN016', 'BAS', 'Tenis de basquetbol Adidas', '1', 'Tenis para basquetbol marca Adidas', 'bas-tenis-adidas.jpg', 210000, 651),
('BTEN017', 'BAS', 'Tenis de basquetbol Puma', '1', 'Tenis para basquetbol marca Puma', 'bas-tenis-puma.jpg', 200000, 735),
('FBAL001', 'FUT', 'Balón Adidas Teslar', '0', 'Balón oficial Mundial Rusia 2018', 'fut-balon-adidas-teslar.jpg', 60000, 100),
('FBAL005', 'FUT', 'Balón fútbol Mikasa', '0', 'Balón sala Mikasa', 'fut-balon-mikasa.png', 45000, 40),
('FCAM013', 'FUT', 'Camiseta Selección Colombia', '1', 'Camiseta de la selección Colombia', 'fut-camiseta-col.png', 210000, 462),
('FCAM014', 'FUT', 'Camiseta Atlético Nacional', '1', 'Camiseta de Atlético Nacional', 'fut-camiseta-nacional.jpg', 190000, 504),
('FGUA010', 'FUT', 'Guayos Adidas', '1', 'Guayos para futbol marca Adidas', 'fut-guayos-adidas.jpg', 300000, 304),
('FGUA011', 'FUT', 'Guayos Nike', '1', 'Guayos para futbol marca Nike', 'fut-guayos-nike.jpg', 200000, 336),
('FGUA012', 'FUT', 'Guayos Adidas Mundial', '1', 'Guayos para futbol marca Adidas en el mundial', 'fut-guayos-adidas2.jpg', 350000, 418),
('TCAM025', 'TEN', 'Camiseta para tenis', '1', 'Camiseta para tenis', 'ten-camiseta.jpg', 80000, 1146),
('TFAL024', 'TEN', 'Falda para tenis', '1', 'Falda para tenis', 'ten-falda.jpg', 75000, 1094),
('TRAQ006', 'TEN', 'Raqueta de tenis Wilson', '0', 'Excelente raqueta para tenis marca Wilson', 'ten-raqueta-wilson.jpg', 210000, 68),
('TRAQ007', 'TEN', 'Raqueta de tenis Head', '0', 'Excelente raqueta para tenis marca Head', 'ten-raqueta-head.jpg', 190000, 101),
('TRAQ008', 'TEN', 'Raqueta de tenis Prince', '0', 'Excelente raqueta para tenis marca Prince', 'ten-raqueta-prince.png', 180000, 134),
('TRAQ009', 'TEN', 'Raqueta de tenis Babolat', '0', 'Excelente raqueta para tenis marca Babolat', 'ten-raqueta-babolat.png', 250000, 213),
('VBAL003', 'VOL', 'Balón voleibol Mikasa', '0', 'Balón oficial voleibol', 'vol-balon-mikasa.png', 55000, 77),
('VCAM022', 'VOL', 'Camiseta de voleibol Joma', '1', 'Camiseta de voleibol Joma', 'vol-camiseta-joma.jpg', 70000, 979),
('VCAM023', 'VOL', 'Camiseta de voleibol Under Armor', '1', 'Camiseta de voleibol Under Armor', 'vol-camiseta-underurmor.jpg', 90000, 1009),
('VTEN021', 'VOL', 'Tenis de voleibol Mizuno', '1', 'Tenis de voleibol marca Mizuno', 'vol-tenis-mizuno.jpg', 230000, 917);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `usuarioId` varchar(30) NOT NULL COMMENT 'Es el email',
  `nombre` varchar(45) NOT NULL,
  `apellido` varchar(45) NOT NULL,
  `pwd` varchar(45) NOT NULL,
  `tel` int(11) NOT NULL,
  `ciudad` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`usuarioId`, `nombre`, `apellido`, `pwd`, `tel`, `ciudad`) VALUES
('ejemplo1@gmail.com', 'Ejemplo', 'TSP', '12', 4934893, 'Medellín'),
('leonardoparra@gmail.com', 'leonardo', 'parra', '1', 888888, 'Bogotá');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`idcarrito`),
  ADD KEY `FK_car_product_idx` (`productoId`),
  ADD KEY `FK_usr_usuario_idx` (`usuarioId`);

--
-- Indexes for table `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`categoriaId`);

--
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`productoId`),
  ADD KEY `FK_Product_Category_idx` (`categoriaId`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usuarioId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carrito`
--
ALTER TABLE `carrito`
  MODIFY `idcarrito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `FK_car_product` FOREIGN KEY (`productoId`) REFERENCES `productos` (`productoId`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_usr_usuario` FOREIGN KEY (`usuarioId`) REFERENCES `usuarios` (`usuarioId`) ON UPDATE CASCADE;

--
-- Constraints for table `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `FK_Product_Category` FOREIGN KEY (`categoriaId`) REFERENCES `categoria` (`categoriaId`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
