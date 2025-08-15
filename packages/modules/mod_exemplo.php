<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_exemplo
 * @copyright   Copyright (C) 2025 Seu Nome. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

// Impede acesso direto
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;

// Carrega parâmetros do módulo
$moduleParams = $params;

// Exemplo de internacionalização
$mensagem = Text::_('MOD_EXEMPLO_MENSAGEM');

// Exemplo de uso de parâmetro
$exibirSaudacao = $moduleParams->get('exibir_saudacao', 1);

if ($exibirSaudacao) {
    echo '<div class="mod-exemplo">' . htmlspecialchars($mensagem) . '</div>';
}

// Boas práticas:
// - Sempre escape dados de saída
// - Use arquivos de idioma para textos
// - Documente parâmetros no XML do módulo
// - Adicione comentários explicativos
