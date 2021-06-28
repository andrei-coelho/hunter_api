/**
* Versão do MySQL: 5.7+
*/

use hunter_db;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


CREATE TABLE `actions` (
  `id` bigint(255) NOT NULL,
  `rede_social_id` bigint(255) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `actions_cliente` (
  `id` bigint(255) NOT NULL,
  `action_id` bigint(255) NOT NULL,
  `cliente_id` bigint(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `admin` (
  `id` bigint(255) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `recover_code` varchar(255) NOT NULL,
  `expire_recover_code` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `clientes` (
  `id` bigint(255) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `machine_id` bigint(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `config_rede_social` (
  `id` bigint(255) NOT NULL,
  `rede_social_id` bigint(255) NOT NULL,
  `cliente_id` bigint(255) NOT NULL,
  `action_id` bigint(255) NOT NULL,
  `quant_max` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `contas_rede_social` (
  `id` bigint(255) NOT NULL,
  `perfil_cliente_id` bigint(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `machine` (
  `id` bigint(255) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `chave` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `map_actions_day` (
  `id` bigint(255) NOT NULL,
  `data` date NOT NULL,
  `action_id` bigint(255) NOT NULL,
  `cliente_id` bigint(255) NOT NULL,
  `count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `perfis` (
  `id` bigint(255) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `rede_social_id` bigint(255) NOT NULL,
  `data_att` date NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `perfis_cliente` (
  `id` bigint(255) NOT NULL,
  `cliente_id` bigint(255) NOT NULL,
  `perfil_id` bigint(255) NOT NULL,
  `vinculado` bigint(255) DEFAULT NULL,
  `oficial` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `perfis_to_action` (
  `id` bigint(255) NOT NULL,
  `perfil_cliente_id` bigint(255) NOT NULL,
  `action_id` bigint(255) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `rede_social` (
  `id` bigint(255) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `site` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `sessions` (
  `id` bigint(255) NOT NULL,
  `hash` varchar(255) NOT NULL,
  `nonce` varchar(255) NOT NULL,
  `data` date NOT NULL,
  `header` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `sessions_admin` (
  `id` bigint(255) NOT NULL,
  `admin_id` bigint(255) NOT NULL,
  `session_id` bigint(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `sessions_usuario` (
  `id` bigint(255) NOT NULL,
  `usuario_id` bigint(255) NOT NULL,
  `session_id` bigint(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `tags` (
  `id` bigint(255) NOT NULL,
  `cliente_id` bigint(255) NOT NULL,
  `nome` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `usuarios` (
  `id` bigint(255) NOT NULL,
  `cliente_id` bigint(255) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `recover_code` varchar(255) NOT NULL,
  `expire_recover_code` datetime NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


ALTER TABLE `actions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `foreign_rede_social_id_actions` (`rede_social_id`);


ALTER TABLE `actions_cliente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `foreign_action_id_actions_cliente` (`action_id`),
  ADD KEY `foreign_cliente_id_actions_cliente` (`cliente_id`);


ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `foreign_machine_id_clientes` (`machine_id`);


ALTER TABLE `config_rede_social`
  ADD PRIMARY KEY (`id`),
  ADD KEY `foreign_rede_social_id_config_rede_social` (`rede_social_id`),
  ADD KEY `foreign_cliente_id_config_rede_social` (`cliente_id`),
  ADD KEY `foreign_actions_id_config_rede_social` (`action_id`);


ALTER TABLE `contas_rede_social`
  ADD PRIMARY KEY (`id`),
  ADD KEY `foreign_perfil_cliente_id_contas_rede_social` (`perfil_cliente_id`);


ALTER TABLE `machine`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `map_actions_day`
  ADD PRIMARY KEY (`id`),
  ADD KEY `foreign_action_id_map_actions_day` (`action_id`),
  ADD KEY `foreign_cliente_id_map_actions_day` (`cliente_id`);


ALTER TABLE `perfis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `foreign_rede_social_id_perfis` (`rede_social_id`);


ALTER TABLE `perfis_cliente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `foreign_cliente_id_perfis_cliente` (`cliente_id`),
  ADD KEY `foreign_perfil_id_perfis_cliente` (`perfil_id`),
  ADD KEY `foreign_vinculado_perfis_cliente` (`vinculado`);


ALTER TABLE `perfis_to_action`
  ADD PRIMARY KEY (`id`),
  ADD KEY `foreign_action_id_perfis_to_action` (`action_id`),
  ADD KEY `foreign_perfil_cliente_id_perfis_to_action` (`perfil_cliente_id`);


ALTER TABLE `rede_social`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `sessions_admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `session_id` (`session_id`),
  ADD KEY `foreign_admin_id_sessions_admin` (`admin_id`);


ALTER TABLE `sessions_usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `session_id` (`session_id`),
  ADD KEY `foreign_usuario_id_sessions_usuario` (`usuario_id`);


ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `foreign_cliente_id_tags` (`cliente_id`);


ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `foreign_cliente_id_usuarios` (`cliente_id`);


ALTER TABLE `actions`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;


ALTER TABLE `actions_cliente`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;


ALTER TABLE `admin`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;


ALTER TABLE `clientes`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;


ALTER TABLE `config_rede_social`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;


ALTER TABLE `contas_rede_social`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;


ALTER TABLE `machine`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;


ALTER TABLE `map_actions_day`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;


ALTER TABLE `perfis`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;


ALTER TABLE `perfis_cliente`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;


ALTER TABLE `perfis_to_action`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;


ALTER TABLE `rede_social`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;


ALTER TABLE `sessions`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;


ALTER TABLE `sessions_admin`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;


ALTER TABLE `sessions_usuario`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;


ALTER TABLE `tags`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;


ALTER TABLE `usuarios`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;


ALTER TABLE `actions`
  ADD CONSTRAINT `foreign_rede_social_id_actions` FOREIGN KEY (`rede_social_id`) REFERENCES `rede_social` (`id`);


ALTER TABLE `actions_cliente`
  ADD CONSTRAINT `foreign_action_id_actions_cliente` FOREIGN KEY (`action_id`) REFERENCES `actions` (`id`),
  ADD CONSTRAINT `foreign_cliente_id_actions_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`);


ALTER TABLE `clientes`
  ADD CONSTRAINT `foreign_machine_id_clientes` FOREIGN KEY (`machine_id`) REFERENCES `machine` (`id`);


ALTER TABLE `config_rede_social`
  ADD CONSTRAINT `foreign_actions_id_config_rede_social` FOREIGN KEY (`action_id`) REFERENCES `actions` (`id`),
  ADD CONSTRAINT `foreign_cliente_id_config_rede_social` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `foreign_rede_social_id_config_rede_social` FOREIGN KEY (`rede_social_id`) REFERENCES `rede_social` (`id`);


ALTER TABLE `contas_rede_social`
  ADD CONSTRAINT `foreign_perfil_cliente_id_contas_rede_social` FOREIGN KEY (`perfil_cliente_id`) REFERENCES `perfis_cliente` (`id`);


ALTER TABLE `map_actions_day`
  ADD CONSTRAINT `foreign_action_id_map_actions_day` FOREIGN KEY (`action_id`) REFERENCES `actions` (`id`),
  ADD CONSTRAINT `foreign_cliente_id_map_actions_day` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`);


ALTER TABLE `perfis`
  ADD CONSTRAINT `foreign_rede_social_id_perfis` FOREIGN KEY (`rede_social_id`) REFERENCES `rede_social` (`id`);


ALTER TABLE `perfis_cliente`
  ADD CONSTRAINT `foreign_cliente_id_perfis_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `foreign_perfil_id_perfis_cliente` FOREIGN KEY (`perfil_id`) REFERENCES `perfis` (`id`),
  ADD CONSTRAINT `foreign_vinculado_perfis_cliente` FOREIGN KEY (`vinculado`) REFERENCES `perfis_cliente` (`id`);


ALTER TABLE `perfis_to_action`
  ADD CONSTRAINT `foreign_action_id_perfis_to_action` FOREIGN KEY (`action_id`) REFERENCES `actions` (`id`),
  ADD CONSTRAINT `foreign_perfil_cliente_id_perfis_to_action` FOREIGN KEY (`perfil_cliente_id`) REFERENCES `perfis_cliente` (`id`);


ALTER TABLE `sessions_admin`
  ADD CONSTRAINT `foreign_admin_id_sessions_admin` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`),
  ADD CONSTRAINT `foreign_session_id_sessions_admin` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`);


ALTER TABLE `sessions_usuario`
  ADD CONSTRAINT `foreign_session_id_sessions_usuario` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`),
  ADD CONSTRAINT `foreign_usuario_id_sessions_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);


ALTER TABLE `tags`
  ADD CONSTRAINT `foreign_cliente_id_tags` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`);


ALTER TABLE `usuarios`
  ADD CONSTRAINT `foreign_cliente_id_usuarios` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`);

  
COMMIT;
