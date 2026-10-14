<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  com_aniversariantes
 * @copyright   (C) 2024-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

namespace Astatonn\Component\Aniversariantes\Site\View\Aniversariantes;

use Astatonn\Component\Aniversariantes\Site\Model\AniversariantesModel;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\Registry\Registry;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Página pública com a lista de aniversariantes.
 *
 * @since  3.1.0
 */
class HtmlView extends BaseHtmlView
{
    /**
     * Aniversariantes agrupados pelo número do mês (1 a 12).
     *
     * @var    array<int, \stdClass[]>
     * @since  3.1.0
     */
    protected $grupos = [];

    /**
     * @var    Registry
     * @since  3.1.0
     */
    protected $params;

    /**
     * @var    string
     * @since  3.1.0
     */
    protected $periodo = AniversariantesModel::PERIODO_MES;

    /**
     * @param   string  $tpl  Sub-layout
     *
     * @return  void
     *
     * @since   3.1.0
     */
    public function display($tpl = null)
    {
        /** @var \Joomla\CMS\Application\SiteApplication $app */
        $app = Factory::getApplication();

        /** @var AniversariantesModel $model */
        $model = $this->getModel();

        $this->params  = $app->getParams();
        $this->periodo = (string) $this->params->get('periodo', AniversariantesModel::PERIODO_MES);

        foreach ($model->getAniversariantes($this->periodo, (int) $this->params->get('dias', 30)) as $item) {
            $this->grupos[$item->mes][] = $item;
        }

        $this->prepararDocumento();

        parent::display($tpl);
    }

    /**
     * Título da página, descrição e robots a partir do item de menu.
     *
     * @return  void
     *
     * @since   3.1.0
     */
    protected function prepararDocumento(): void
    {
        $menu = Factory::getApplication()->getMenu()->getActive();

        $this->params->def('page_heading', $menu ? $this->params->get('page_title', $menu->title) : Text::_('COM_ANIVERSARIANTES_PAGE_TITLE'));

        $this->setDocumentTitle((string) $this->params->get('page_title', Text::_('COM_ANIVERSARIANTES_PAGE_TITLE')));

        $documento = $this->getDocument();

        if ($this->params->get('menu-meta_description')) {
            $documento->setDescription($this->params->get('menu-meta_description'));
        }

        if ($this->params->get('robots')) {
            $documento->setMetaData('robots', $this->params->get('robots'));
        }
    }
}
