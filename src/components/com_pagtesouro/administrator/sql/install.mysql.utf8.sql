--
-- com_pagtesouro: UASGs e serviços usados para montar o link da GRU no PagTesouro.
-- A estrutura é a mesma das versões 1.x/2.x.
--
CREATE TABLE IF NOT EXISTS `#__pagtesouro_uasgs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `orgao` varchar(10) NOT NULL,
  `ug` varchar(10) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `state` tinyint NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__pagtesouro_servicos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uasg_id` int NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `state` tinyint NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `idx_uasg_id` (`uasg_id`),
  CONSTRAINT `fk_servico_uasg` FOREIGN KEY (`uasg_id`) REFERENCES `#__pagtesouro_uasgs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
