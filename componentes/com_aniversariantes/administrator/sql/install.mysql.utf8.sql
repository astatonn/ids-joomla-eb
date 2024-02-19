CREATE TABLE IF NOT EXISTS `#__aniversariantes_` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`nome` VARCHAR(255)  NOT NULL ,
`aniversario` DATE NOT NULL ,
`pg` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE INDEX `#__aniversariantes__aniversario` ON `#__aniversariantes_`(`aniversario`);

