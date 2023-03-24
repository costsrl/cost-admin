-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 27, 2016 alle 14:20
-- Versione del server: 5.5.47-0+deb8u1
-- PHP Version: 5.6.19-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `zf2projectcomposer`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `menus`
--

CREATE TABLE IF NOT EXISTS `menus` (
`id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `route` varchar(255) NOT NULL,
  `controller` varchar(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `resource` varchar(255) DEFAULT NULL,
  `privilege` int(11) DEFAULT NULL,
  `params` varchar(255) DEFAULT NULL,
  `query` varchar(255) DEFAULT NULL,
  `module` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `menus`
--

INSERT INTO `menus` (`id`, `parent_id`, `name`, `label`, `route`, `controller`, `action`, `resource`, `privilege`, `params`, `query`, `module`, `icon`, `sort_order`) VALUES
(6, 0, 'Skeleton', 'Home', 'application/default', 'Index', 'index', 'Application\\Controller\\Index', 1, NULL, NULL, 'application', 'glyphicon glyphicon-home', 0),
(7, 0, 'UserProfile', 'Profilo Utente', 'Cost-auth/default', 'Index', 'index', 'CostAuthentication\\Controller\\Registration', 5, '', '', 'Application', 'glyphicon glyphicon-user bold white', 3),
(8, 0, 'Admin', 'Admin', 'admin-application', 'Index', 'index', 'CostAdmin\\Controller\\Index', 6, NULL, NULL, 'Cost_admin', 'glyphicon  glyphicon-wrench', 3),
(9, 8, 'Admin_anag_resource', 'Resource', 'admin-application/default', 'Index', 'resource', 'CostAdmin\\Controller\\Index', 6, NULL, NULL, 'Cost_admin', NULL, 4),
(10, 8, 'Admin_anag_role', 'Role', 'admin-application/default', 'index', 'role', 'CostAdmin\\Controller\\Index', 6, NULL, NULL, 'Cost_admin', NULL, 5),
(11, 8, 'Admin_anag_permission', 'Permission', 'admin-application/default', 'Index', 'permission', 'CostAdmin\\Controller\\Index', 6, NULL, NULL, 'Cost_admin', NULL, 6),
(12, 8, 'Admin_anag_user', 'User', 'admin-application/default', 'index', 'user', 'CostAdmin\\Controller\\Index', 6, NULL, NULL, 'Cost_admin', NULL, 7),
(13, 8, 'Admin_anag_menu', 'Menu', 'admin-application/default', 'index', 'menu', 'CostAdmin\\Controller\\Index', 6, NULL, NULL, 'Cost_admin', NULL, 8),
(14, 8, 'Admin_anag_language', 'Language', 'admin-application/default', 'Index', 'language', 'CostAdmin\\Controller\\Index', 6, NULL, NULL, 'Cost_admin', NULL, 9),
(15, 0, 'LogOut', 'Logout', 'Cost-auth/default', 'index', 'logout', 'CostAuthentication\\Controller\\Index', 3, NULL, NULL, 'Cost_authentication', NULL, 12),
(16, 0, 'Login', 'Login', 'Cost-auth/default', 'index', 'user', 'CostAuthentication\\Controller\\Index', 2, NULL, NULL, 'Cost_authentication', NULL, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `permissions`
--

CREATE TABLE IF NOT EXISTS `permissions` (
`id` int(11) NOT NULL,
  `resource_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `privilege` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `permission_allow` int(11) NOT NULL,
  `assert_class` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dump dei dati per la tabella `permissions`
--

INSERT INTO `permissions` (`id`, `resource_id`, `role_id`, `name`, `privilege`, `permission_allow`, `assert_class`) VALUES
(1, 1, 1, 'Application_index', '*', 1, ''),
(2, 2, 1, 'CostAuthentication_Login', 'login', 1, ''),
(3, 2, 2, 'CostAuthentication_LogOut', 'logout', 1, ''),
(4, 3, 1, 'CostAuthentication_Register', '*', 1, ''),
(5, 3, 2, 'CostAuthentication_Register_Edit', '*', 1, ''),
(6, 5, 3, 'Cost_admin_dashboard', '*', 1, ''),
(7, 2, 2, 'CostAuthentication_Login_Denie', 'login', 2, '');

-- --------------------------------------------------------

--
-- Struttura della tabella `resources`
--

CREATE TABLE IF NOT EXISTS `resources` (
`id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dump dei dati per la tabella `resources`
--

INSERT INTO `resources` (`id`, `name`, `type`) VALUES
(1, 'Application\\Controller\\Index', 'controller'),
(2, 'CostAuthentication\\Controller\\Index', 'controller'),
(3, 'CostAuthentication\\Controller\\Registration', 'controller'),
(4, 'CostAuthorization\\Controller\\Index', 'controller'),
(5, 'CostAdmin\\Controller\\Index', 'controller');

-- --------------------------------------------------------

--
-- Struttura della tabella `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
`id` int(11) NOT NULL,
  `name` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dump dei dati per la tabella `roles`
--

INSERT INTO `roles` (`id`, `name`, `parent_id`) VALUES
(1, 'Guest', NULL),
(2, 'Member', 1),
(3, 'Admin', 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`user_id` int(11) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `picture` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password_salt` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `email_confirmed` tinyint(1) NOT NULL,
  `state` int(11) NOT NULL,
  `question` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `answer` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `registration_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `registration_date` datetime DEFAULT NULL,
  `lastlogin` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dump dei dati per la tabella `user`
--

INSERT INTO `user` (`user_id`, `role_id`, `language_id`, `username`, `display_name`, `first_name`, `last_name`, `picture`, `password`, `password_salt`, `email`, `email_confirmed`, `state`, `question`, `answer`, `registration_token`, `registration_date`, `lastlogin`) VALUES
(2, 3, 1, 'renna', 'renato salvatori', 'renato', 'salvatori', NULL, 'd7f58a565d7db7ad3193502bf94d07b7', 'Z|Iu9iBFYKvf9HyX{k&)ELcN5j\\N4e&ncOe{:(BsS9ZkaTD]@J', 'rennasalva@gmail.com', 0, 1, NULL, NULL, '3d13b517bdcbbb66bc1c52de1ad60a07', '2016-03-17 11:39:25', '2016-04-27 14:03:35'),
(3, 2, 1, 'renasalva', 'renato salvatori novig', 'renato', 'salvatori', 'Array', 'cc698315d141f349985db2be9ca257ec', 'X+[kDj^J..3\\XAZZ6n"[tROAt`V/^0<8;w%^cb)qp<NJ\\*%rw''', 'r.salvatori@novigo-consulting.it', 0, 1, NULL, NULL, '6ea72d357dbad84fe61130511406981b', '2016-03-21 22:16:58', '2016-04-27 09:22:22'),
(14, 2, 1, 'demo2', 'demo2', 'demo2 nome', 'demo2 cognome', NULL, 'b9d96ad821384f1d3136351d8e9326ef', '2qo1+]?FI%?}NkcgQOy$YT$>32iI[)<my-}&i=K3Bi2oVtW)DR', 'demo2@test.it', 1, 1, NULL, NULL, NULL, '2016-04-22 11:59:03', NULL),
(18, 2, 1, 'demo4', 'demo4', 'demo nome', 'demo cognome', NULL, '132bea13856fb0ed3c1ef162dbcb7b62', 'rFV.F%{v:g=bz=k}8w;WXiD@c9pV*CG|i}+0#(''<oD~j`jixc%', 'demo4@test.it', 1, 1, NULL, NULL, NULL, '2016-04-22 12:35:51', '2016-04-22 12:36:04'),
(19, 2, 1, 'admin', 'admin display_name', 'admin_nome', 'admin_cognome', NULL, 'ab10717c59bd84dbded1c2a46a959514', 'p)*3''-)UpSWvyudV''~U<IG4,95H{+/iz8s/>!7sqiLidBO;INo', 'renato.s@cost.it', 0, 1, NULL, NULL, 'cc65db3d6513a720b33d41a3dcd557ef', '2016-04-26 14:47:33', '2016-04-26 14:48:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
 ADD PRIMARY KEY (`id`), ADD KEY `IDX_727508CF87209A87` (`privilege`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
 ADD PRIMARY KEY (`id`), ADD KEY `IDX_87209A8789329D25` (`resource_id`), ADD KEY `IDX_87209A87D60322AC` (`role_id`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `UNIQ_B63E2EC7727ACA70` (`parent_id`), ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`user_id`), ADD KEY `role_id` (`role_id`), ADD KEY `IDX_8D93D64982F1BAF4` (`language_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `menus`
--
ALTER TABLE `menus`
ADD CONSTRAINT `FK_727508CF87209A87` FOREIGN KEY (`privilege`) REFERENCES `permissions` (`id`);

--
-- Limiti per la tabella `permissions`
--
ALTER TABLE `permissions`
ADD CONSTRAINT `permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `resource` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `roles`
--
ALTER TABLE `roles`
ADD CONSTRAINT `FK_B63E2EC7727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
