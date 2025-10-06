/*
SQLyog Professional v12.09 (64 bit)
MySQL - 5.7.17-log : Database - sysweb
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`sysweb` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `sysweb`;

/*Table structure for table `ciudad` */

DROP TABLE IF EXISTS `ciudad`;

CREATE TABLE `ciudad` (
  `cod_ciudad` int(11) NOT NULL,
  `descrip_ciudad` varchar(25) DEFAULT NULL,
  `id_departamento` int(11) NOT NULL,
  PRIMARY KEY (`cod_ciudad`),
  KEY `id_departamento` (`id_departamento`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `ciudad` */

insert  into `ciudad`(`cod_ciudad`,`descrip_ciudad`,`id_departamento`) values (1,'Asunción',1),(3,'Luque',2);

/*Table structure for table `clientes` */

DROP TABLE IF EXISTS `clientes`;

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `cod_ciudad` int(11) DEFAULT NULL,
  `ci_ruc` varchar(10) NOT NULL,
  `cli_nombre` varchar(30) NOT NULL,
  `cli_apellido` varchar(50) NOT NULL,
  `cli_direccion` varchar(50) DEFAULT NULL,
  `cli_telefono` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_cliente`),
  KEY `clientes_cod_ciudad_fkey` (`cod_ciudad`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `clientes` */

insert  into `clientes`(`id_cliente`,`cod_ciudad`,`ci_ruc`,`cli_nombre`,`cli_apellido`,`cli_direccion`,`cli_telefono`) values (2,4,'6969','Natalia','Gimenez','Guarambaré',6969),(3,3,'123','prueba','prueba','asuncion',123);

/*Table structure for table `compra` */

DROP TABLE IF EXISTS `compra`;

CREATE TABLE `compra` (
  `cod_compra` int(11) NOT NULL,
  `cod_proveedor` int(11) NOT NULL,
  `cod_deposito` int(11) NOT NULL,
  `nro_factura` varchar(25) NOT NULL,
  `fecha` date NOT NULL,
  `estado` varchar(15) NOT NULL,
  `hora` time NOT NULL,
  `total_compra` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`cod_compra`),
  KEY `cod_deposito` (`cod_deposito`),
  KEY `cod_proveedor` (`cod_proveedor`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `compra` */

insert  into `compra`(`cod_compra`,`cod_proveedor`,`cod_deposito`,`nro_factura`,`fecha`,`estado`,`hora`,`total_compra`,`id_user`) values (1,1,2,'123','2023-02-22','anulado','11:02:10',15000,3);

/*Table structure for table `departamento` */

DROP TABLE IF EXISTS `departamento`;

CREATE TABLE `departamento` (
  `id_departamento` int(11) NOT NULL,
  `dep_descripcion` varchar(35) DEFAULT NULL,
  PRIMARY KEY (`id_departamento`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `departamento` */

insert  into `departamento`(`id_departamento`,`dep_descripcion`) values (1,'Central'),(2,'Cordillera');

/*Table structure for table `deposito` */

DROP TABLE IF EXISTS `deposito`;

CREATE TABLE `deposito` (
  `cod_deposito` int(11) NOT NULL,
  `descrip` varchar(50) NOT NULL,
  PRIMARY KEY (`cod_deposito`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `deposito` */

insert  into `deposito`(`cod_deposito`,`descrip`) values (1,'Dep. Central'),(2,'Dep. Suc. 2');

/*Table structure for table `det_venta` */

DROP TABLE IF EXISTS `det_venta`;

CREATE TABLE `det_venta` (
  `cod_producto` int(11) NOT NULL,
  `cod_venta` int(11) NOT NULL,
  `cod_deposito` int(11) NOT NULL,
  `det_precio_unit` int(11) NOT NULL,
  `det_cantidad` int(11) NOT NULL,
  PRIMARY KEY (`cod_producto`,`cod_venta`),
  KEY `deposito_det_venta_fk` (`cod_deposito`),
  KEY `venta_det_venta_fk` (`cod_venta`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `det_venta` */

insert  into `det_venta`(`cod_producto`,`cod_venta`,`cod_deposito`,`det_precio_unit`,`det_cantidad`) values (2,1,2,1000,3);

/*Table structure for table `detalle_compra` */

DROP TABLE IF EXISTS `detalle_compra`;

CREATE TABLE `detalle_compra` (
  `cod_producto` int(11) NOT NULL,
  `cod_compra` int(11) NOT NULL,
  `cod_deposito` int(11) NOT NULL,
  `precio` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  PRIMARY KEY (`cod_producto`,`cod_compra`),
  KEY `compra_detalle_compra_fk` (`cod_compra`),
  KEY `deposito_detalle_compra_fk` (`cod_deposito`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `detalle_compra` */

insert  into `detalle_compra`(`cod_producto`,`cod_compra`,`cod_deposito`,`precio`,`cantidad`) values (2,1,2,10000,1),(1,1,2,5000,1);

/*Table structure for table `historiales` */

DROP TABLE IF EXISTS `historiales`;

CREATE TABLE `historiales` (
  `id_historial` int(10) NOT NULL AUTO_INCREMENT,
  `id_motivo` int(10) NOT NULL,
  `nombre_historial` varchar(250) DEFAULT NULL,
  `fecha_hora` datetime DEFAULT NULL,
  PRIMARY KEY (`id_historial`),
  KEY `id_motivo` (`id_motivo`),
  CONSTRAINT `historiales_ibfk_1` FOREIGN KEY (`id_motivo`) REFERENCES `motivos` (`id_motivo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `historiales` */

/*Table structure for table `motivos` */

DROP TABLE IF EXISTS `motivos`;

CREATE TABLE `motivos` (
  `id_motivo` int(10) NOT NULL,
  `nombre_motivo` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_motivo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `motivos` */

/*Table structure for table `producto` */

DROP TABLE IF EXISTS `producto`;

CREATE TABLE `producto` (
  `cod_producto` int(11) NOT NULL,
  `cod_tipo_prod` int(11) NOT NULL,
  `id_u_medida` int(11) NOT NULL,
  `p_descrip` varchar(50) NOT NULL,
  `precio` int(11) NOT NULL,
  PRIMARY KEY (`cod_producto`),
  KEY `tipo_producto_producto_fk` (`cod_tipo_prod`),
  KEY `u_medida_producto_fk` (`id_u_medida`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `producto` */

insert  into `producto`(`cod_producto`,`cod_tipo_prod`,`id_u_medida`,`p_descrip`,`precio`) values (2,2,1,'Bebidas',10000),(1,1,1,'Lacteos',5000);

/*Table structure for table `proveedor` */

DROP TABLE IF EXISTS `proveedor`;

CREATE TABLE `proveedor` (
  `cod_proveedor` int(11) NOT NULL,
  `razon_social` varchar(75) NOT NULL,
  `ruc` varchar(9) NOT NULL,
  `direccion` varchar(50) DEFAULT NULL,
  `telefono` int(11) NOT NULL,
  PRIMARY KEY (`cod_proveedor`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `proveedor` */

insert  into `proveedor`(`cod_proveedor`,`razon_social`,`ruc`,`direccion`,`telefono`) values (1,'Empresa de lacteos','789654','Central',21369852);

/*Table structure for table `stock` */

DROP TABLE IF EXISTS `stock`;

CREATE TABLE `stock` (
  `cod_deposito` int(11) NOT NULL,
  `cod_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  PRIMARY KEY (`cod_deposito`,`cod_producto`),
  KEY `producto_stock_fk` (`cod_producto`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `stock` */

insert  into `stock`(`cod_deposito`,`cod_producto`,`cantidad`) values (2,1,0),(2,2,0);

/*Table structure for table `tipo_producto` */

DROP TABLE IF EXISTS `tipo_producto`;

CREATE TABLE `tipo_producto` (
  `cod_tipo_prod` int(11) NOT NULL,
  `t_p_descrip` varchar(50) NOT NULL,
  PRIMARY KEY (`cod_tipo_prod`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `tipo_producto` */

insert  into `tipo_producto`(`cod_tipo_prod`,`t_p_descrip`) values (1,'Lacteos'),(2,'Bebidas');

/*Table structure for table `tmp` */

DROP TABLE IF EXISTS `tmp`;

CREATE TABLE `tmp` (
  `id_tmp` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) DEFAULT NULL,
  `cantidad_tmp` int(11) DEFAULT NULL,
  `precio_tmp` int(11) DEFAULT NULL,
  `session_id` varchar(765) DEFAULT NULL,
  PRIMARY KEY (`id_tmp`),
  KEY `id_tmp` (`id_tmp`)
) ENGINE=MyISAM AUTO_INCREMENT=586 DEFAULT CHARSET=utf8;

/*Data for the table `tmp` */

/*Table structure for table `u_medida` */

DROP TABLE IF EXISTS `u_medida`;

CREATE TABLE `u_medida` (
  `id_u_medida` int(11) NOT NULL,
  `u_descrip` varchar(20) NOT NULL,
  PRIMARY KEY (`id_u_medida`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `u_medida` */

insert  into `u_medida`(`id_u_medida`,`u_descrip`) values (1,'1 Litro'),(2,'1/2 Litro');

/*Table structure for table `usuarios` */

DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `id_user` int(3) NOT NULL AUTO_INCREMENT,
  `username` varchar(150) DEFAULT NULL,
  `name_user` varchar(150) DEFAULT NULL,
  `password` varchar(150) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telefono` varchar(39) DEFAULT NULL,
  `foto` varchar(300) DEFAULT NULL,
  `permisos_acceso` varchar(300) DEFAULT NULL,
  `status` char(27) DEFAULT NULL,
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Data for the table `usuarios` */

insert  into `usuarios`(`id_user`,`username`,`name_user`,`password`,`email`,`telefono`,`foto`,`permisos_acceso`,`status`) values (5,'rocio','Rocio Bogado','827ccb0eea8a706c4c34a16891f84e7b','rocio.bogado@gmail.com','6969','Imagen de WhatsApp 2022-12-19 a las 14.45.25.jpg','Ventas','activo'),(4,'sara','Sarita Suarez','827ccb0eea8a706c4c34a16891f84e7b','sara.montiel@gmail.com','12345','43056.jpg','Compras','activo'),(3,'nico','Jorge Nicolás Picaguá','202cb962ac59075b964b07152d234b70','nicolas.picagua@gmail.com','0972857299','wp6495697-the-mandalorian-4k-wallpapers.jpg','Super Admin','activo'),(8,'prueba','prueba de usuario','827ccb0eea8a706c4c34a16891f84e7b',NULL,NULL,NULL,'Ventas',NULL);

/*Table structure for table `venta` */

DROP TABLE IF EXISTS `venta`;

CREATE TABLE `venta` (
  `cod_venta` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `total_venta` int(11) NOT NULL,
  `estado` varchar(15) NOT NULL,
  `hora` time NOT NULL,
  `nro_factura` int(11) DEFAULT NULL,
  PRIMARY KEY (`cod_venta`),
  KEY `clientes_venta_fk` (`id_cliente`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `venta` */

insert  into `venta`(`cod_venta`,`id_cliente`,`fecha`,`total_venta`,`estado`,`hora`,`nro_factura`) values (1,2,'2023-02-22',5000,'activo','11:02:58',12345);

/* Trigger structure for table `compra` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `borrar_tmp` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `borrar_tmp` AFTER INSERT ON `compra` FOR EACH ROW BEGIN
delete from tmp;
END */$$


DELIMITER ;

/* Trigger structure for table `venta` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `borrar_temp_venta` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `borrar_temp_venta` AFTER INSERT ON `venta` FOR EACH ROW begin
delete from tmp;
end */$$


DELIMITER ;

/*Table structure for table `v_clientes` */

DROP TABLE IF EXISTS `v_clientes`;

/*!50001 DROP VIEW IF EXISTS `v_clientes` */;
/*!50001 DROP TABLE IF EXISTS `v_clientes` */;

/*!50001 CREATE TABLE  `v_clientes`(
 `id_cliente` int(11) ,
 `ci_ruc` varchar(10) ,
 `cli_nombre` varchar(30) ,
 `cli_apellido` varchar(50) ,
 `cli_direccion` varchar(50) ,
 `cli_telefono` int(11) ,
 `cod_ciudad` int(11) ,
 `descrip_ciudad` varchar(25) ,
 `id_departamento` int(11) ,
 `dep_descripcion` varchar(35) 
)*/;

/*Table structure for table `v_compras` */

DROP TABLE IF EXISTS `v_compras`;

/*!50001 DROP VIEW IF EXISTS `v_compras` */;
/*!50001 DROP TABLE IF EXISTS `v_compras` */;

/*!50001 CREATE TABLE  `v_compras`(
 `cod_compra` int(11) ,
 `cod_proveedor` int(11) ,
 `razon_social` varchar(75) ,
 `cod_deposito` int(11) ,
 `descrip` varchar(50) ,
 `nro_factura` varchar(25) ,
 `fecha` date ,
 `hora` time ,
 `total_compra` int(11) ,
 `estado` varchar(15) ,
 `id_user` int(3) ,
 `name_user` varchar(150) 
)*/;

/*Table structure for table `v_det_compra` */

DROP TABLE IF EXISTS `v_det_compra`;

/*!50001 DROP VIEW IF EXISTS `v_det_compra` */;
/*!50001 DROP TABLE IF EXISTS `v_det_compra` */;

/*!50001 CREATE TABLE  `v_det_compra`(
 `cod_compra` int(11) ,
 `cod_producto` int(11) ,
 `t_p_descrip` varchar(50) ,
 `u_descrip` varchar(20) ,
 `p_descrip` varchar(50) ,
 `precio` int(11) ,
 `cantidad` int(11) 
)*/;

/*Table structure for table `v_stock` */

DROP TABLE IF EXISTS `v_stock`;

/*!50001 DROP VIEW IF EXISTS `v_stock` */;
/*!50001 DROP TABLE IF EXISTS `v_stock` */;

/*!50001 CREATE TABLE  `v_stock`(
 `cod_producto` int(11) ,
 `p_descrip` varchar(50) ,
 `cod_deposito` int(11) ,
 `descrip` varchar(50) ,
 `t_p_descrip` varchar(50) ,
 `u_descrip` varchar(20) ,
 `cantidad` int(11) 
)*/;

/*View structure for view v_clientes */

/*!50001 DROP TABLE IF EXISTS `v_clientes` */;
/*!50001 DROP VIEW IF EXISTS `v_clientes` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_clientes` AS (select `cli`.`id_cliente` AS `id_cliente`,`cli`.`ci_ruc` AS `ci_ruc`,`cli`.`cli_nombre` AS `cli_nombre`,`cli`.`cli_apellido` AS `cli_apellido`,`cli`.`cli_direccion` AS `cli_direccion`,`cli`.`cli_telefono` AS `cli_telefono`,`ciu`.`cod_ciudad` AS `cod_ciudad`,`ciu`.`descrip_ciudad` AS `descrip_ciudad`,`dep`.`id_departamento` AS `id_departamento`,`dep`.`dep_descripcion` AS `dep_descripcion` from ((`clientes` `cli` join `departamento` `dep`) join `ciudad` `ciu`) where ((`cli`.`cod_ciudad` = `ciu`.`cod_ciudad`) and (`ciu`.`id_departamento` = `dep`.`id_departamento`))) */;

/*View structure for view v_compras */

/*!50001 DROP TABLE IF EXISTS `v_compras` */;
/*!50001 DROP VIEW IF EXISTS `v_compras` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_compras` AS (select `comp`.`cod_compra` AS `cod_compra`,`prov`.`cod_proveedor` AS `cod_proveedor`,`prov`.`razon_social` AS `razon_social`,`dep`.`cod_deposito` AS `cod_deposito`,`dep`.`descrip` AS `descrip`,`comp`.`nro_factura` AS `nro_factura`,`comp`.`fecha` AS `fecha`,`comp`.`hora` AS `hora`,`comp`.`total_compra` AS `total_compra`,`comp`.`estado` AS `estado`,`usu`.`id_user` AS `id_user`,`usu`.`name_user` AS `name_user` from (((`compra` `comp` join `proveedor` `prov`) join `deposito` `dep`) join `usuarios` `usu`) where ((`comp`.`cod_proveedor` = `prov`.`cod_proveedor`) and (`comp`.`cod_deposito` = `dep`.`cod_deposito`) and (`comp`.`id_user` = `usu`.`id_user`))) */;

/*View structure for view v_det_compra */

/*!50001 DROP TABLE IF EXISTS `v_det_compra` */;
/*!50001 DROP VIEW IF EXISTS `v_det_compra` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_det_compra` AS (select `comp`.`cod_compra` AS `cod_compra`,`pro`.`cod_producto` AS `cod_producto`,`tpro`.`t_p_descrip` AS `t_p_descrip`,`um`.`u_descrip` AS `u_descrip`,`pro`.`p_descrip` AS `p_descrip`,`det`.`precio` AS `precio`,`det`.`cantidad` AS `cantidad` from ((((`detalle_compra` `det` join `compra` `comp`) join `producto` `pro`) join `tipo_producto` `tpro`) join `u_medida` `um`) where ((`det`.`cod_compra` = `comp`.`cod_compra`) and (`det`.`cod_producto` = `pro`.`cod_producto`) and (`pro`.`cod_tipo_prod` = `tpro`.`cod_tipo_prod`) and (`pro`.`id_u_medida` = `um`.`id_u_medida`))) */;

/*View structure for view v_stock */

/*!50001 DROP TABLE IF EXISTS `v_stock` */;
/*!50001 DROP VIEW IF EXISTS `v_stock` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_stock` AS (select `pro`.`cod_producto` AS `cod_producto`,`pro`.`p_descrip` AS `p_descrip`,`dep`.`cod_deposito` AS `cod_deposito`,`dep`.`descrip` AS `descrip`,`tpro`.`t_p_descrip` AS `t_p_descrip`,`um`.`u_descrip` AS `u_descrip`,`st`.`cantidad` AS `cantidad` from ((((`stock` `st` join `producto` `pro`) join `tipo_producto` `tpro`) join `u_medida` `um`) join `deposito` `dep`) where ((`st`.`cod_producto` = `pro`.`cod_producto`) and (`st`.`cod_deposito` = `dep`.`cod_deposito`) and (`pro`.`cod_tipo_prod` = `tpro`.`cod_tipo_prod`) and (`pro`.`id_u_medida` = `um`.`id_u_medida`))) */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
