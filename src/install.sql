SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `pr_application` (
  `app_id` int(10) UNSIGNED NOT NULL COMMENT '用户凭证',
  `app_secret` char(40) NOT NULL COMMENT '用户凭证密钥',
  `user_id` int(11) UNSIGNED DEFAULT NULL COMMENT '用户',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `description` varchar(300) NOT NULL DEFAULT '' COMMENT '描述',
  `enabled` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '启用',
  `last_time` datetime DEFAULT NULL COMMENT '最后登录',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='应用';

CREATE TABLE `pr_gateway_online` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '主键',
  `app_id` int(10) UNSIGNED NOT NULL COMMENT '用户凭证',
  `client_id` varchar(50) NOT NULL DEFAULT '' COMMENT '客户端ID',
  `last_ping` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后通信',
  `online` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '菜鸟组件在线',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='在线设备';

CREATE TABLE `pr_printers` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '主键',
  `app_id` int(10) UNSIGNED NOT NULL COMMENT '用户凭证',
  `print_name` varchar(100) NOT NULL COMMENT '打印机名字',
  `print_type` tinyint(3) UNSIGNED NOT NULL COMMENT '打印机类型',
  `print_status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '打印机状态',
  `is_default` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '默认打印机',
  `enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT '启用',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='打印机';

CREATE TABLE `pr_queue_print` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '主键',
  `app_id` int(10) UNSIGNED NOT NULL COMMENT '用户凭证',
  `origin_id` varchar(50) NOT NULL COMMENT '商户订单号',
  `task_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '打印机任务ID',
  `printer` varchar(100) NOT NULL DEFAULT '' COMMENT '打印机名',
  `documents` text NOT NULL COMMENT '原始报文',
  `preview` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否预览',
  `task_type` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '任务类型',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '打印状态',
  `msg` varchar(200) NOT NULL DEFAULT '' COMMENT '错误描述',
  `dispatched` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '调度时间',
  `notify_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '通知时间',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='打印队列';


ALTER TABLE `pr_application`
  ADD PRIMARY KEY (`app_id`),
  ADD KEY `auth_user_id` (`user_id`),
  ADD KEY `enabled` (`enabled`);

ALTER TABLE `pr_gateway_online`
  ADD PRIMARY KEY (`id`),
  ADD KEY `online_app_id` (`app_id`),
  ADD KEY `online` (`online`);

ALTER TABLE `pr_printers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `app_id` (`app_id`),
  ADD KEY `print_status` (`print_status`),
  ADD KEY `enabled` (`enabled`);

ALTER TABLE `pr_queue_print`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `app_id` (`app_id`,`origin_id`,`task_id`),
  ADD KEY `status` (`status`);


ALTER TABLE `pr_application`
  MODIFY `app_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户凭证';

ALTER TABLE `pr_gateway_online`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键';

ALTER TABLE `pr_printers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键';

ALTER TABLE `pr_queue_print`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键';


ALTER TABLE `pr_application`
  ADD CONSTRAINT `auth_user_id` FOREIGN KEY (`user_id`) REFERENCES `wa_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pr_gateway_online`
  ADD CONSTRAINT `online_app_id` FOREIGN KEY (`app_id`) REFERENCES `pr_application` (`app_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pr_queue_print`
  ADD CONSTRAINT `queue_print_app_id` FOREIGN KEY (`app_id`) REFERENCES `pr_application` (`app_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
