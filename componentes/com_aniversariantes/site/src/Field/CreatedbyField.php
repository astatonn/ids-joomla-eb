<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Aniversariantes
 * @author     Lucas Lima <lucas.lima.rk@gmail.com>
 * @copyright  2024 Lucas Lima
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Astatonn\Component\Aniversariantes\Site\Field;

defined('JPATH_BASE') or die;

use \Joomla\CMS\Factory;
use \Joomla\CMS\Form\FormField;
use \Joomla\CMS\User\UserFactoryInterface;

/**
 * Supports an HTML select list of categories
 *
 * @since  1.0.0
 */
class CreatedbyField extends FormField
{
	/**
	 * The form field type.
	 *
	 * @var        string
	 * @since  1.0.0
	 */
	protected $type = 'createdby';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   1.0.0
	 */
	protected function getInput()
	{
		// Initialize variables.
		$html = array();

		// Load user
		$user_id = $this->value;

		if ($user_id)
		{
			$container = \Joomla\CMS\Factory::getContainer();
            $userFactory = $container->get(UserFactoryInterface::class);
            $user = $userFactory->loadUserById($user_id);
		}
		else
		{
			$user   = Factory::getApplication()->getIdentity();
			$html[] = '<input type="hidden" name="' . $this->name . '" value="' . $user->id . '" />';
		}

		if (!$this->hidden)
		{
			$html[] = "<div>" . $user->name . " (" . $user->username . ")</div>";
		}

		return implode($html);
	}
}
