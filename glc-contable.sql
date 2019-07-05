-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-07-2019 a las 17:11:34
-- Versión del servidor: 10.1.38-MariaDB
-- Versión de PHP: 7.2.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `glc-contable`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `abonos_comisiones`
--

CREATE TABLE `abonos_comisiones` (
  `id` int(10) UNSIGNED NOT NULL,
  `users_id` int(10) UNSIGNED NOT NULL,
  `comisiones_id` int(10) UNSIGNED NOT NULL,
  `monto` decimal(9,2) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT NULL,
  `comentario` varchar(150) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `charters`
--

CREATE TABLE `charters` (
  `id` int(11) UNSIGNED NOT NULL,
  `creado_por` int(10) UNSIGNED NOT NULL,
  `codigo` varchar(100) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `yacht` varchar(100) DEFAULT NULL,
  `broker` varchar(100) DEFAULT NULL,
  `fecha_inicio` timestamp NULL DEFAULT NULL,
  `fecha_fin` timestamp NULL DEFAULT NULL,
  `precio_venta` decimal(9,2) DEFAULT NULL,
  `yacht_rack` decimal(9,2) DEFAULT NULL,
  `neto` decimal(9,2) DEFAULT NULL,
  `porcentaje_comision_broker` int(11) DEFAULT NULL,
  `comision_broker` decimal(9,2) DEFAULT NULL,
  `costo_deluxe` decimal(9,2) DEFAULT NULL,
  `comision_glc` decimal(9,2) DEFAULT NULL,
  `apa` decimal(9,2) DEFAULT NULL,
  `contrato` varchar(250) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comisiones`
--

CREATE TABLE `comisiones` (
  `id` int(10) UNSIGNED NOT NULL,
  `users_id` int(10) UNSIGNED NOT NULL,
  `charters_id` int(11) UNSIGNED NOT NULL,
  `socios_id` int(10) UNSIGNED NOT NULL,
  `porcentaje_comision_socio` int(11) DEFAULT NULL,
  `monto` decimal(9,2) DEFAULT NULL,
  `abonado` decimal(9,2) DEFAULT NULL,
  `saldo` decimal(9,2) DEFAULT NULL,
  `fecha_ult_abono` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entradas`
--

CREATE TABLE `entradas` (
  `id` int(10) UNSIGNED NOT NULL,
  `charters_id` int(11) UNSIGNED NOT NULL,
  `registrado_por` int(10) UNSIGNED NOT NULL,
  `fecha` timestamp NULL DEFAULT NULL,
  `monto` decimal(9,2) DEFAULT NULL,
  `comentario` varchar(255) DEFAULT NULL,
  `banco` varchar(100) DEFAULT NULL,
  `referencia` varchar(100) DEFAULT NULL,
  `tipo_recibo` varchar(45) DEFAULT NULL,
  `link_papeleta_pago` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gastos`
--

CREATE TABLE `gastos` (
  `id` int(10) UNSIGNED NOT NULL,
  `users_id` int(10) UNSIGNED NOT NULL,
  `charters_id` int(11) UNSIGNED NOT NULL,
  `tipo_gasto_id` int(10) UNSIGNED NOT NULL,
  `total` decimal(9,2) NOT NULL,
  `gastos` decimal(9,2) DEFAULT NULL,
  `saldo` decimal(9,2) DEFAULT NULL,
  `fecha_ult_abono` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gastos_detalles`
--

CREATE TABLE `gastos_detalles` (
  `id` int(10) UNSIGNED NOT NULL,
  `users_id` int(10) UNSIGNED NOT NULL,
  `gastos_id` int(10) UNSIGNED NOT NULL,
  `monto` decimal(9,2) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT NULL,
  `comentario` varchar(150) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'superadmin', 'Super Administrator', '2019-06-24 21:40:04', '2019-06-24 21:40:04'),
(2, 'admin', 'Administrator', '2019-06-24 21:40:04', '2019-06-24 21:40:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `socios`
--

CREATE TABLE `socios` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `porcentaje` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `socios`
--

INSERT INTO `socios` (`id`, `nombre`, `porcentaje`, `created_at`, `updated_at`) VALUES
(1, 'Aryel D.', 35, NULL, NULL),
(2, 'Stephanie S.', 35, NULL, NULL),
(3, 'GLC', 30, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_gasto`
--

CREATE TABLE `tipo_gasto` (
  `id` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tipo_gasto`
--

INSERT INTO `tipo_gasto` (`id`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'APA', '2019-06-28 05:00:00', NULL),
(2, 'DELUXE', '2019-06-28 05:00:00', NULL),
(3, 'COMISIONES', '2019-06-28 05:00:00', NULL),
(4, 'OPERADOR NETO', '2019-06-28 05:00:00', NULL),
(5, 'BROKER', '2019-06-28 05:00:00', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `roles_id` int(10) UNSIGNED NOT NULL,
  `username` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `creado_por` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `email_verified_at` time DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `roles_id`, `username`, `name`, `lastname`, `email`, `creado_por`, `status`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 'aryeldvorquez', 'Aryel', 'Dvorquez', 'aryel@galapagosluxurycharters.com', 1, 1, NULL, '$2y$10$W2LWIBeUuh69UjVCz7HO3.P67nlbgVeeDVr5a74Xvwv/YR9QFgWsa', NULL, '2019-06-24 21:40:05', '2019-06-24 21:40:05'),
(2, 2, 'ssaman', 'Stephanie', 'Saman', 'ssaman@galapagosluxurycharters.com', 1, 1, NULL, '$2y$10$68G1jh35MMJl1Ux2HMJfWu/6PHCWnAsgsTanzT0vyCo0B5/md3fv2', NULL, '2019-06-24 21:40:05', '2019-06-24 21:40:05');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `abonos_comisiones`
--
ALTER TABLE `abonos_comisiones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_abonos_comisiones1_idx` (`comisiones_id`),
  ADD KEY `fk_abonos_comisiones_users1_idx` (`users_id`);

--
-- Indices de la tabla `charters`
--
ALTER TABLE `charters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_charters_users1_idx` (`creado_por`);

--
-- Indices de la tabla `comisiones`
--
ALTER TABLE `comisiones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_comisiones_socios1_idx` (`socios_id`),
  ADD KEY `fk_comisiones_users1_idx` (`users_id`),
  ADD KEY `fk_comisiones_charters1_idx` (`charters_id`);

--
-- Indices de la tabla `entradas`
--
ALTER TABLE `entradas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_entradas_charters1_idx` (`charters_id`),
  ADD KEY `fk_entradas_users1_idx` (`registrado_por`);

--
-- Indices de la tabla `gastos`
--
ALTER TABLE `gastos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_gastos_users1_idx` (`users_id`),
  ADD KEY `fk_gastos_charters1_idx` (`charters_id`),
  ADD KEY `fk_gastos_tipo_gasto1_idx` (`tipo_gasto_id`);

--
-- Indices de la tabla `gastos_detalles`
--
ALTER TABLE `gastos_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_gastos_detalles_users1_idx` (`users_id`),
  ADD KEY `fk_gastos_detalles_gastos1_idx` (`gastos_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `socios`
--
ALTER TABLE `socios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_gasto`
--
ALTER TABLE `tipo_gasto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `fk_users_roles1_idx` (`roles_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `abonos_comisiones`
--
ALTER TABLE `abonos_comisiones`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `charters`
--
ALTER TABLE `charters`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `comisiones`
--
ALTER TABLE `comisiones`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `entradas`
--
ALTER TABLE `entradas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `gastos`
--
ALTER TABLE `gastos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `gastos_detalles`
--
ALTER TABLE `gastos_detalles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `socios`
--
ALTER TABLE `socios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tipo_gasto`
--
ALTER TABLE `tipo_gasto`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `abonos_comisiones`
--
ALTER TABLE `abonos_comisiones`
  ADD CONSTRAINT `fk_abonos_comisiones1` FOREIGN KEY (`comisiones_id`) REFERENCES `comisiones` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_abonos_comisiones_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `charters`
--
ALTER TABLE `charters`
  ADD CONSTRAINT `fk_charters_users1` FOREIGN KEY (`creado_por`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `comisiones`
--
ALTER TABLE `comisiones`
  ADD CONSTRAINT `fk_comisiones_charters1` FOREIGN KEY (`charters_id`) REFERENCES `charters` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_comisiones_socios1` FOREIGN KEY (`socios_id`) REFERENCES `socios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_comisiones_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `entradas`
--
ALTER TABLE `entradas`
  ADD CONSTRAINT `fk_entradas_charters1` FOREIGN KEY (`charters_id`) REFERENCES `charters` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_entradas_users1` FOREIGN KEY (`registrado_por`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `gastos`
--
ALTER TABLE `gastos`
  ADD CONSTRAINT `fk_gastos_charters10` FOREIGN KEY (`charters_id`) REFERENCES `charters` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_gastos_tipo_gasto1` FOREIGN KEY (`tipo_gasto_id`) REFERENCES `tipo_gasto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_gastos_users10` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `gastos_detalles`
--
ALTER TABLE `gastos_detalles`
  ADD CONSTRAINT `fk_gastos_detalles_gastos1` FOREIGN KEY (`gastos_id`) REFERENCES `gastos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_gastos_detalles_users10` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_roles_id_foreign` FOREIGN KEY (`roles_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
