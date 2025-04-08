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
     * Minimum PHP version required
     *
     * @var    string
     */
    protected $minimumPhp = '7.4.0';

    /**
     * Minimum Joomla version required
     *
     * @var    string
     */
    protected $minimumJoomla = '4.0.0';

    /**
     * List of required extensions
     *
     * @var    array
     */
    protected $extensionDependencies = [
        // Adicione qualquer extensão necessária aqui
        // ['type' => 'component', 'name' => 'com_exemplo', 'version' => '1.0.0']
    ];

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
        if (!parent::preflight($type, $parent)) {
            return false;
        }

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
        if (!parent::postflight($type, $parent)) {
            return false;
        }
        
        $app = Factory::getApplication();
        
        if ($type === 'install' || $type === 'update') {
            $app->enqueueMessage(Text::_('PKG_IDS_JOOMLA_EB_POSTFLIGHT_' . strtoupper($type) . '_MESSAGE'));
        }
        
        return true;
    }
}