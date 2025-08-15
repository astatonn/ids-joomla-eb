<?php
/**
 * @package     IDS Joomla EB
 * @subpackage  pkg_ids_joomla_eb
 * @copyright   Copyright (C) 2025 Seu Nome. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerScript;
use Joomla\CMS\Language\Text;

/**
 * Script file for the IDS Joomla EB package
 */
class pkg_ids_joomla_ebInstallerScript extends InstallerScript
{
    /**
     * Versão mínima do PHP exigida
     * @var string
     */
    protected $minimumPhp = '8.1.0';

    /**
     * Versão mínima do Joomla exigida
     * @var string
     */
    protected $minimumJoomla = '5.0.0';

    /**
     * Lista de extensões obrigatórias
     * @var array
     */
    protected $extensionDependencies = [
        // Exemplo: ['type' => 'component', 'name' => 'com_exemplo', 'version' => '1.0.0']
    ];

    /**
     * Valida dependências antes da instalação
     */
    protected function checkDependencies()
    {
        // Aqui você pode implementar validação de extensões obrigatórias
        // Exemplo: checar se componentes necessários estão instalados
        // Retorne true se tudo ok, false se faltar algo
        return true;
    }

    /**
     * Function called before extension installation/update/removal procedure commences
     *
     * @param   string            $type    The type of change (install, update or discover_install)
     * @param   InstallerAdapter  $parent  The class calling this method
     *
     * @return  boolean  True on success
     */
    public function preflight($type, $parent)
    {
        // Executa validações padrão do Joomla
        if (!parent::preflight($type, $parent)) {
            return false;
        }

        // Valida dependências customizadas
        if (!$this->checkDependencies()) {
            Factory::getApplication()->enqueueMessage('Dependências obrigatórias não encontradas.', 'error');
            return false;
        }

        // Mensagem informativa
        $app = Factory::getApplication();
        $app->enqueueMessage(Text::_('PKG_IDS_JOOMLA_EB_PREFLIGHT_' . strtoupper($type) . '_MESSAGE'));

        return true;
    }

    /**
     * Function called after extension installation/update/removal procedure commences
     *
     * @param   string            $type    The type of change (install, update or discover_install)
     * @param   InstallerAdapter  $parent  The class calling this method
     *
     * @return  boolean  True on success
     */
    public function postflight($type, $parent)
    {
        // Executa pós-processamento padrão do Joomla
        if (!parent::postflight($type, $parent)) {
            return false;
        }

        $app = Factory::getApplication();

        // Mensagem de sucesso após instalar/atualizar
        if ($type === 'install' || $type === 'update') {
            $app->enqueueMessage(Text::_('PKG_IDS_JOOMLA_EB_POSTFLIGHT_' . strtoupper($type) . '_MESSAGE'));
        }

        return true;
    }
}