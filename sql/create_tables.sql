/**
* Versão do MySQL: 5.7+
*/

use hunter2;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;



-- Estrutura das tabelas

DROP TABLE IF EXISTS `cliente`;
CREATE TABLE `cliente` (
  `id` bigint(255) NOT NULL,
  `empresa` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `q_seguir` int(99) NOT NULL DEFAULT 150,
  `q_desseguir` int(99) NOT NULL DEFAULT 150,
  `q_mensagens` int(99) NOT NULL DEFAULT 20,
  `q_comentarios` int(99) NOT NULL DEFAULT 20,
  `t_busca` time NOT NULL DEFAULT '01:00:00',
  `i_analise_perfis_seguidores` int(99) NOT NULL DEFAULT 7,
  `ultima_analise_perfis_seguidores` date DEFAULT NULL,
  `posso_enviar_mensagem` tinyint(1) NOT NULL DEFAULT 0,
  `posso_enviar_reforco_mensagem` tinyint(1) NOT NULL DEFAULT 0,
  `horario_de_envio_mensagem` time NOT NULL DEFAULT '16:00:00',
  `machine` bigint(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `conta_rede_social`;
CREATE TABLE `conta_rede_social` (
  `id` bigint(255) NOT NULL,
  `id_cliente` bigint(255) NOT NULL,
  `tipo` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `label_text`;
CREATE TABLE `label_text` (
  `id` bigint(255) NOT NULL,
  `id_cliente` bigint(255) NOT NULL DEFAULT 0,
  `label` varchar(255) NOT NULL,
  `str_replace` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `label_text` (`id`, `id_cliente`, `label`, `str_replace`) VALUES
(1, 0, '[minha_empresa]', 'cliente.empresa'),
(2, 0, '[meu_nome]', 'cliente.nome'),
(3, 0, '[nome_perfil]', 'perfil.nome'),
(4, 0, '[slug_perfil]', 'perfil.slug'),
(5, 0, '[tipo_conta_perfil]', 'perfil.tipo_conta');

DROP TABLE IF EXISTS `machine`;
CREATE TABLE `machine` (
  `id` bigint(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `chave` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `mensagem`;
CREATE TABLE `mensagem` (
  `id` bigint(255) NOT NULL,
  `id_cliente` bigint(255) NOT NULL,
  `tipo` tinyint(1) NOT NULL COMMENT '1 = mensagem , 2 = reforco',
  `texto` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `mensagem_enviada`;
CREATE TABLE `mensagem_enviada` (
  `id` bigint(255) NOT NULL,
  `id_perfil` bigint(255) NOT NULL,
  `id_cliente` bigint(255) NOT NULL,
  `mensagem` text NOT NULL,
  `data` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `perfil`;
CREATE TABLE `perfil` (
  `id` bigint(255) NOT NULL,
  `id_cliente` bigint(255) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `tipo_conta` varchar(255) NOT NULL COMMENT 'twitter, face, insta, etc',
  `seguindo` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 = nao, 1 = sim, 2 = esperando',
  `precisa_aprovar` tinyint(1) NOT NULL DEFAULT 0,
  `desseguido` tinyint(1) NOT NULL DEFAULT 0,
  `pegou_seguidores` tinyint(1) NOT NULL DEFAULT 0,
  `convertido` tinyint(1) NOT NULL DEFAULT 0,
  `q_mensagem_enviada` tinyint(1) NOT NULL DEFAULT 0,
  `fundido_com` bigint(255) NOT NULL DEFAULT 0 COMMENT 'id de outro perfil'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `tag`;
CREATE TABLE `tag` (
  `id` bigint(255) NOT NULL,
  `id_cliente` bigint(255) NOT NULL,
  `nome` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `id` bigint(255) NOT NULL,
  `id_cliente` bigint(255) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `chave` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` bigint(255) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `chave` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- insere chave primária nos ids das tabelas

ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `machine_id_estrangeira` (`machine`);

ALTER TABLE `conta_rede_social`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id_estrangeira` (`id_cliente`);

ALTER TABLE `label_text`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `machine`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `mensagem`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id_estrangeira_msg` (`id_cliente`);

ALTER TABLE `mensagem_enviada`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id_estrangeira_enviado` (`id_cliente`),
  ADD KEY `perfil_id_estrangeiro` (`id_perfil`);

ALTER TABLE `perfil`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id_estrangeira_perfil` (`id_cliente`);

ALTER TABLE `tag`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id_estrangeira_tag` (`id_cliente`);

ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id_estrangeira_usuario` (`id_cliente`);

ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chave` (`chave`);



-- insere 'Auto increment' nos ids das tabelas

ALTER TABLE `admin`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;
COMMIT;

ALTER TABLE `cliente`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;

ALTER TABLE `conta_rede_social`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;

ALTER TABLE `label_text`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `machine`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;

ALTER TABLE `mensagem`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;

ALTER TABLE `mensagem_enviada`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;

ALTER TABLE `perfil`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;

ALTER TABLE `tag`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;

ALTER TABLE `usuario`
  MODIFY `id` bigint(255) NOT NULL AUTO_INCREMENT;



-- insere chave estrangeira

ALTER TABLE `cliente`
  ADD CONSTRAINT `machine_id_estrangeira` FOREIGN KEY (`machine`) REFERENCES `machine` (`id`);

ALTER TABLE `conta_rede_social`
  ADD CONSTRAINT `cliente_id_estrangeira` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id`);

ALTER TABLE `mensagem`
  ADD CONSTRAINT `cliente_id_estrangeira_msg` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id`);

ALTER TABLE `mensagem_enviada`
  ADD CONSTRAINT `cliente_id_estrangeira_enviado` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id`),
  ADD CONSTRAINT `perfil_id_estrangeiro` FOREIGN KEY (`id_perfil`) REFERENCES `perfil` (`id`);

ALTER TABLE `perfil`
  ADD CONSTRAINT `cliente_id_estrangeira_perfil` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id`);

ALTER TABLE `tag`
  ADD CONSTRAINT `cliente_id_estrangeira_tag` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id`);

ALTER TABLE `usuario`
  ADD CONSTRAINT `cliente_id_estrangeira_usuario` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id`);
COMMIT;
