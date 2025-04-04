--
-- Base de datos: `u130454517_seguridad`
--

--
-- Volcado de datos para la tabla `aspirantes`
--

INSERT INTO `aspirantes` (`id`, `nombres`, `cedula`, `id_lugar_expedicion`, `dia_nacimiento`, `mes_nacimiento`, `año_nacimiento`, `id_lugar_nacimiento`, `edad`, `direccion`, `barrio`, `id_ciudad`, `telefono_fijo`, `móvil`, `cargo`, `desde`, `hasta`, `created_at`, `updated_at`) VALUES
(1, 'Nulla consequatur', 1110456003, 124, 9, 2, 1928, 667, 83, 'Dolores omnis', 'Reprehenderit', 583, '10', NULL, 'Nisi reiciendis', '2019-07-08', '1990-01-26', '2024-06-29 18:02:45', '2024-06-29 18:43:08');

--
-- Volcado de datos para la tabla `busquedas`
--

INSERT INTO `busquedas` (`id`, `cedula`, `created_at`, `updated_at`) VALUES
(1, 1110456003, '2024-06-26 21:03:44', '2024-06-26 21:03:44'),
(2, 52433839, '2024-06-27 17:10:05', '2024-06-27 17:10:05');

--
-- Volcado de datos para la tabla `departamentos`
--
INSERT INTO `informaciones_academicas` (`id`, `cedula`, `institucion`, `titulo_programa`, `fecha_de_grado`, `telefono`, `id_ciudad_academica`, `created_at`, `updated_at`) VALUES
(1, 1110456003, 'Magnam non minim', 'Placeat at cupiditate', '1986-01-08', '3162782894', 456, '2024-07-02 17:44:08', '2024-07-02 18:54:05'),
(2, 1110456003, 'Ipsam doloribus laudantium ratione', 'Dolor non esse beatae', '1972-01-25', '3162452463', 548, '2024-07-02 18:11:55', '2024-07-02 18:54:37'),
(3, 1110456003, 'A labore optio at elit vitae', 'Eos aute dolore nostrum', '2022-08-22', '3164284972', 225, '2024-07-02 18:15:16', '2024-07-02 18:55:05');

--
-- Volcado de datos para la tabla `informacion_laborales`
--

INSERT INTO `informacion_laborales` (`id`, `cedula`, `compania`, `cargo`, `ingreso`, `retiro`, `telefono`, `created_at`, `updated_at`) VALUES
(1, 1110456003, 'Earum officia', 'Pariatur Odio', '2000-09-08', '1999-09-15', '3162782894', '2024-06-30 04:11:22', '2024-06-30 04:11:22');

--
-- Volcado de datos para la tabla `informes`
--

INSERT INTO `informes` (`id`, `id_usuario`, `cedula`, `terminado`, `created_at`, `updated_at`) VALUES
(1, 1, 1110456003, 0, '2024-06-26 21:03:45', '2024-06-26 21:03:45'),
(2, 2, 52433839, 0, '2024-06-27 17:10:05', '2024-06-27 17:10:05');

--
-- Volcado de datos para la tabla `migrations`
--
INSERT INTO `verificaciones_academicas` (`id`, `cedula`, `verificacion`, `id_academica`, `verificado`, `created_at`, `updated_at`) VALUES
(1, '1110456003', 'Sint qui in quia qui consectetur ipsam minima voluptate qui expedita eum nostrud aliquam eos excepteur eu aut et', 1, 1, '2024-07-02 17:44:08', '2024-07-02 18:46:18'),
(2, '1110456003', 'Consequat Modi voluptate aperiam ad id mollitia quibusdam necessitatibus reprehenderit fugit officia obcaecati asperiores', 2, 1, '2024-07-02 18:11:55', '2024-07-02 18:48:01'),
(3, '1110456003', 'Cum exercitationem dolorem aliqua Ut dolores alias omnis incididunt sed occaecat neque et reprehenderit molestiae sint amet', 3, 1, '2024-07-02 18:15:17', '2024-07-02 18:55:21');

INSERT INTO `verificaciones_antecedentes` (`id`, `cedula`, `verificacion`, `id_antecedente`, `verificado`, `created_at`, `updated_at`) VALUES
(1, 1110456003, 'Odit laboris dolores voluptatem fugit adipisicing culpa aspernatur minim incidunt et saepe sint qui cum sint magni perspiciatis reprehenderit magna', 1, 1, '2024-07-08 02:39:17', '2024-07-08 03:19:52'),
(2, 1110456003, ' ', 2, 0, '2024-07-08 02:39:18', '2024-07-08 02:39:18'),
(3, 1110456003, ' ', 3, 0, '2024-07-08 02:39:18', '2024-07-08 02:39:18'),
(4, 1110456003, ' ', 4, 0, '2024-07-08 02:39:19', '2024-07-08 02:39:19'),
(5, 1110456003, ' ', 5, 0, '2024-07-08 02:39:20', '2024-07-08 02:39:20'),
(6, 1110456003, ' ', 6, 0, '2024-07-08 02:39:20', '2024-07-08 02:39:20');

INSERT INTO `verificaciones_colpensiones` (`id`, `cedula`, `verificacion`, `id_colpensiones`, `verificado`, `created_at`, `updated_at`) VALUES
(1, 1110456003, 'Veritatis ea qui tenetur nihil veniam adipisicing in sit voluptatibus et', 1, 1, '2024-07-08 17:46:09', '2024-07-08 17:56:07'),
(2, 1110456003, ' ', 2, 0, '2024-07-08 17:46:10', '2024-07-08 17:46:10'),
(3, 1110456003, ' ', 3, 0, '2024-07-08 17:46:10', '2024-07-08 17:46:10'),
(4, 1110456003, ' ', 4, 0, '2024-07-08 17:46:11', '2024-07-08 17:46:11'),
(5, 1110456003, ' ', 5, 0, '2024-07-08 17:46:12', '2024-07-08 17:46:12'),
(6, 1110456003, ' ', 6, 0, '2024-07-08 17:46:12', '2024-07-08 17:46:12'),
(7, 1110456003, ' ', 7, 0, '2024-07-08 17:46:13', '2024-07-08 17:46:13'),
(8, 1110456003, ' ', 8, 0, '2024-07-08 17:46:14', '2024-07-08 17:46:14'),
(9, 1110456003, ' ', 9, 0, '2024-07-08 17:46:14', '2024-07-08 17:46:14');

INSERT INTO `verificaciones_creditos` (`id`, `cedula`, `verificacion`, `id_creditos`, `verificado`, `created_at`, `updated_at`) VALUES
(1, 1110456003, 'Eiusmod cillum debitis ut atque itaque dolores culpa voluptatum blanditiis magnam est et tempora debitis veritatis impedit', 1, 1, '2024-07-09 00:24:21', '2024-07-09 00:24:58'),
(2, 1110456003, ' ', 2, 0, '2024-07-09 00:24:22', '2024-07-09 00:24:22'),
(3, 1110456003, ' ', 3, 0, '2024-07-09 00:24:23', '2024-07-09 00:24:23');

INSERT INTO `verificaciones_negocios` (`id`, `cedula`, `verificacion`, `id_negocios`, `verificado`, `created_at`, `updated_at`) VALUES
(1, 1110456003, 'Ratione molestias ab saepe omnis ipsam perspiciatis est nesciunt harum laboriosam molestiae aperiam adipisci in', 1, 1, '2024-07-08 20:46:31', '2024-07-08 20:48:54'),
(2, 1110456003, 'Totam delectus sint delectus voluptate qui excepturi sint in consequatur laborum Quasi accusantium velit molestiae', 2, 1, '2024-07-08 20:46:32', '2024-07-08 20:49:29');

INSERT INTO `verificaciones_otros` (`id`, `cedula`, `verificacion`, `id_otros`, `verificado`, `created_at`, `updated_at`) VALUES
(1, 1110456003, 'Odit et inventore voluptate minima voluptatum quia vel voluptas id eos vitae dignissimos minima aliqua Saepe natus', 1, 1, '2024-07-09 00:38:52', '2024-07-09 00:42:10'),
(2, 1110456003, ' ', 2, 0, '2024-07-09 00:38:53', '2024-07-09 00:38:53'),
(3, 1110456003, ' ', 3, 0, '2024-07-09 00:38:53', '2024-07-09 00:38:53'),
(4, 1110456003, ' ', 4, 0, '2024-07-09 00:38:54', '2024-07-09 00:38:54'),
(5, 1110456003, ' ', 5, 0, '2024-07-09 00:38:55', '2024-07-09 00:38:55'),
(6, 1110456003, ' ', 6, 0, '2024-07-09 00:38:55', '2024-07-09 00:38:55'),
(7, 1110456003, ' ', 7, 0, '2024-07-09 00:38:56', '2024-07-09 00:38:56'),
(8, 1110456003, ' ', 8, 0, '2024-07-09 00:38:57', '2024-07-09 00:38:57');

INSERT INTO `verificaciones_registradurias` (`id`, `cedula`, `verificacion`, `id_registraduria`, `verificado`, `created_at`, `updated_at`) VALUES
(1, 1110456003, 'Ea minus enim culpa accusamus reprehenderit impedit quia eveniet enim fuga Quia est non', 1, 1, '2024-07-06 16:42:00', '2024-07-06 17:01:08'),
(2, 1110456003, ' ', 2, 0, '2024-07-06 16:42:00', '2024-07-06 16:42:00'),
(3, 1110456003, ' ', 3, 0, '2024-07-06 16:42:01', '2024-07-06 16:42:01'),
(4, 1110456003, ' ', 4, 0, '2024-07-06 16:42:02', '2024-07-06 16:42:02'),
(5, 1110456003, ' ', 5, 0, '2024-07-06 16:42:03', '2024-07-06 16:42:03'),
(6, 1110456003, ' ', 6, 0, '2024-07-06 16:42:03', '2024-07-06 16:42:03'),
(7, 1110456003, ' ', 7, 0, '2024-07-06 16:42:04', '2024-07-06 16:42:04');

INSERT INTO `verificaciones_seguridades` (`id`, `cedula`, `verificacion`, `id_seguridad`, `verificado`, `created_at`, `updated_at`) VALUES
(1, 1110456003, 'Aut nostrud quia ullamco voluptatem quia sint voluptates labore', 1, 1, '2024-07-08 18:47:12', '2024-07-08 18:51:19'),
(2, 1110456003, ' ', 2, 0, '2024-07-08 18:47:13', '2024-07-08 18:47:13'),
(3, 1110456003, ' ', 3, 0, '2024-07-08 18:47:13', '2024-07-08 18:47:13'),
(4, 1110456003, ' ', 4, 0, '2024-07-08 18:47:14', '2024-07-08 18:47:14'),
(5, 1110456003, ' ', 5, 0, '2024-07-08 18:47:15', '2024-07-08 18:47:15'),
(6, 1110456003, ' ', 6, 0, '2024-07-08 18:47:16', '2024-07-08 18:47:16');

INSERT INTO `verificaciones_seguridad_sociales` (`id`, `cedula`, `verificacion`, `id_seguridad_social`, `verificado`, `created_at`, `updated_at`) VALUES
(1, 1110456003, 'Consectetur id cumque suscipit non molestiae debitis reprehenderit dolores numquam laborum In cillum mollit nihil dolore ut aliqua', 1, 1, '2024-07-06 18:17:37', '2024-07-06 18:24:29'),
(2, 1110456003, ' ', 2, 0, '2024-07-06 18:17:38', '2024-07-06 18:17:38'),
(3, 1110456003, ' ', 3, 0, '2024-07-06 18:17:38', '2024-07-06 18:17:38');

INSERT INTO `colpensiones` (`id`, `cedula`, `id_link`, `created_at`, `updated_at`) VALUES
(1, 1110456003, 21, '2024-07-08 17:46:09', '2024-07-08 17:46:09'),
(2, 1110456003, 22, '2024-07-08 17:46:09', '2024-07-08 17:46:09'),
(3, 1110456003, 23, '2024-07-08 17:46:10', '2024-07-08 17:46:10'),
(4, 1110456003, 24, '2024-07-08 17:46:11', '2024-07-08 17:46:11'),
(5, 1110456003, 25, '2024-07-08 17:46:11', '2024-07-08 17:46:11'),
(6, 1110456003, 26, '2024-07-08 17:46:12', '2024-07-08 17:46:12'),
(7, 1110456003, 27, '2024-07-08 17:46:13', '2024-07-08 17:46:13'),
(8, 1110456003, 28, '2024-07-08 17:46:13', '2024-07-08 17:46:13'),
(9, 1110456003, 29, '2024-07-08 17:46:14', '2024-07-08 17:46:14');

INSERT INTO `verificaciones_transitos` (`id`, `cedula`, `verificacion`, `id_transito`, `verificado`, `created_at`, `updated_at`) VALUES
(1, 1110456003, 'Voluptatibus laborum Iste eos aliquid', 1, 1, '2024-07-05 18:41:43', '2024-07-05 18:50:56'),
(2, 1110456003, 'Elit dicta veniam sed proident', 2, 1, '2024-07-05 18:41:44', '2024-07-05 18:52:16'),
(3, 1110456003, 'Pariatur Esse architecto enim nulla', 3, 1, '2024-07-05 18:41:45', '2024-07-05 19:06:01'),
(4, 1110456003, 'Suscipit in consequatur Adipisci sint a consectetur incidunt', 4, 1, '2024-07-05 18:41:45', '2024-07-05 19:06:57');

INSERT INTO `verificaciones_ugpps` (`id`, `cedula`, `verificacion`, `id_ugpps`, `verificado`, `created_at`, `updated_at`) VALUES
(1, 1110456003, 'Ipsa quia consequatur id facilis irure ipsam praesentium maxime sit dolores ea quo', 1, 1, '2024-07-08 19:29:52', '2024-07-08 19:31:01'),
(2, 1110456003, ' ', 2, 0, '2024-07-08 19:29:53', '2024-07-08 19:29:53'),
(3, 1110456003, ' ', 3, 0, '2024-07-08 19:29:53', '2024-07-08 19:29:53'),
(4, 1110456003, ' ', 4, 0, '2024-07-08 19:29:54', '2024-07-08 19:29:54');

INSERT INTO `creditos` (`id`, `cedula`, `id_link`, `created_at`, `updated_at`) VALUES
(1, 1110456003, 42, '2024-07-09 00:24:21', '2024-07-09 00:24:21'),
(2, 1110456003, 44, '2024-07-09 00:24:22', '2024-07-09 00:24:22'),
(3, 1110456003, 45, '2024-07-09 00:24:22', '2024-07-09 00:24:22');

INSERT INTO `negocios` (`id`, `cedula`, `id_link`, `created_at`, `updated_at`) VALUES
(1, 1110456003, 40, '2024-07-08 20:46:31', '2024-07-08 20:46:31'),
(2, 1110456003, 41, '2024-07-08 20:46:31', '2024-07-08 20:46:31');

INSERT INTO `otros` (`id`, `cedula`, `id_link`, `created_at`, `updated_at`) VALUES
(1, 1110456003, 46, '2024-07-09 00:38:51', '2024-07-09 00:38:51'),
(2, 1110456003, 47, '2024-07-09 00:38:52', '2024-07-09 00:38:52'),
(3, 1110456003, 48, '2024-07-09 00:38:53', '2024-07-09 00:38:53'),
(4, 1110456003, 49, '2024-07-09 00:38:54', '2024-07-09 00:38:54'),
(5, 1110456003, 50, '2024-07-09 00:38:54', '2024-07-09 00:38:54'),
(6, 1110456003, 51, '2024-07-09 00:38:55', '2024-07-09 00:38:55'),
(7, 1110456003, 52, '2024-07-09 00:38:56', '2024-07-09 00:38:56'),
(8, 1110456003, 53, '2024-07-09 00:38:56', '2024-07-09 00:38:56');

INSERT INTO `photos` (`id`, `cedula`, `path`, `created_at`, `updated_at`) VALUES
(1, 1110456003, '1110456003_1720545132.jpg', '2024-07-09 03:35:51', '2024-07-09 17:12:12');

INSERT INTO `registradurias` (`id`, `cedula`, `id_link`, `created_at`, `updated_at`) VALUES
(1, 1110456003, 5, '2024-07-06 16:41:59', '2024-07-06 16:41:59'),
(2, 1110456003, 6, '2024-07-06 16:42:00', '2024-07-06 16:42:00'),
(3, 1110456003, 7, '2024-07-06 16:42:01', '2024-07-06 16:42:01'),
(4, 1110456003, 8, '2024-07-06 16:42:01', '2024-07-06 16:42:01'),
(5, 1110456003, 9, '2024-07-06 16:42:02', '2024-07-06 16:42:02'),
(6, 1110456003, 10, '2024-07-06 16:42:03', '2024-07-06 16:42:03'),
(7, 1110456003, 11, '2024-07-06 16:42:04', '2024-07-06 16:42:04');

INSERT INTO `seguridades` (`id`, `cedula`, `id_link`, `created_at`, `updated_at`) VALUES
(1, 1110456003, 30, '2024-07-08 18:47:12', '2024-07-08 18:47:12'),
(2, 1110456003, 31, '2024-07-08 18:47:12', '2024-07-08 18:47:12'),
(3, 1110456003, 32, '2024-07-08 18:47:13', '2024-07-08 18:47:13'),
(4, 1110456003, 33, '2024-07-08 18:47:14', '2024-07-08 18:47:14'),
(5, 1110456003, 34, '2024-07-08 18:47:14', '2024-07-08 18:47:14'),
(6, 1110456003, 35, '2024-07-08 18:47:15', '2024-07-08 18:47:15');

INSERT INTO `seguridad_sociales` (`id`, `cedula`, `id_link`, `created_at`, `updated_at`) VALUES
(1, 1110456003, 12, '2024-07-06 18:17:37', '2024-07-06 18:17:37'),
(2, 1110456003, 13, '2024-07-06 18:17:37', '2024-07-06 18:17:37'),
(3, 1110456003, 14, '2024-07-06 18:17:38', '2024-07-06 18:17:38');

INSERT INTO `transitos` (`id`, `cedula`, `id_link`, `verificado`, `created_at`, `updated_at`) VALUES
(1, 1110456003, 1, 0, '2024-07-05 18:41:43', '2024-07-05 18:41:43'),
(2, 1110456003, 2, 0, '2024-07-05 18:41:44', '2024-07-05 18:41:44'),
(3, 1110456003, 3, 0, '2024-07-05 18:41:44', '2024-07-05 18:41:44'),
(4, 1110456003, 4, 0, '2024-07-05 18:41:45', '2024-07-05 18:41:45');

INSERT INTO `ugpps` (`id`, `cedula`, `id_link`, `created_at`, `updated_at`) VALUES
(1, 1110456003, 36, '2024-07-08 19:29:52', '2024-07-08 19:29:52'),
(2, 1110456003, 37, '2024-07-08 19:29:52', '2024-07-08 19:29:52'),
(3, 1110456003, 38, '2024-07-08 19:29:53', '2024-07-08 19:29:53'),
(4, 1110456003, 39, '2024-07-08 19:29:54', '2024-07-08 19:29:54');

