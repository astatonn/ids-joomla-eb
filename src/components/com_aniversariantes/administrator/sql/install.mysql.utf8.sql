--
-- com_aniversariantes: tabela de aniversariantes.
-- O nome com sublinhado final é mantido por compatibilidade com as versões 1.x/2.x.
--
CREATE TABLE IF NOT EXISTS `#__aniversariantes_` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `aniversario` date NOT NULL,
  `pg` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_aniversario` (`aniversario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
