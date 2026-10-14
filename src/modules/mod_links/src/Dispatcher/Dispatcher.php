<?php

/**
 * @package     IDS Joomla EB
 * @subpackage  mod_links
 * @copyright   (C) 2024-2026 Lucas Lima e colaboradores
 * @license     GNU General Public License version 2 or later
 */

namespace Astatonn\Module\Links\Site\Dispatcher;

use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\CMS\Uri\Uri;
use Joomla\Registry\Registry;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Módulo "Links": seção com título e cartões (ou lista compacta) de links.
 *
 * @since  3.1.0
 */
class Dispatcher extends AbstractModuleDispatcher
{
    /**
     * @return  array
     *
     * @since   3.1.0
     */
    protected function getLayoutData(): array
    {
        $data = parent::getLayoutData();

        /** @var Registry $params */
        $params = $data['params'];

        $data['titulo']   = trim((string) $params->get('section-name', ''));
        $data['compacto'] = (int) $params->get('compact-links', 0) === 1;
        $data['links']    = $this->obterLinks($params);
        $data['tema']     = $this->obterTema();

        return $data;
    }

    /**
     * Links configurados no módulo, já validados.
     *
     * Lê o campo repetível "links" (3.1+). Se ele ainda não existir, usa os campos numerados
     * das versões 1.x/2.x (url-link-1, name-link-1, icon-link-1 ...).
     *
     * @param   Registry  $params  Parâmetros do módulo
     *
     * @return  array<int, array{nome: string, url: string, icone: string, novaAba: bool}>
     *
     * @since   3.1.0
     */
    private function obterLinks(Registry $params): array
    {
        $links    = [];
        $subform  = $params->get('links');
        $entradas = [];

        if (\is_object($subform) || \is_array($subform)) {
            foreach ((array) $subform as $entrada) {
                $entrada    = (array) $entrada;
                $icone      = trim((string) ($entrada['icone'] ?? ''));
                $entradas[] = [
                    'nome'    => $entrada['nome'] ?? '',
                    'url'     => $entrada['url'] ?? '',
                    // "none" = sem ícone; "custom" = classe digitada no campo ao lado
                    'icone'   => $icone === 'custom' ? ($entrada['icone_custom'] ?? '') : ($icone === 'none' ? '' : $icone),
                    'novaAba' => (int) ($entrada['nova_aba'] ?? 1) === 1,
                ];
            }
        } else {
            $quantidade = min(12, max(0, (int) $params->get('link-amount', 0)));

            for ($i = 1; $i <= $quantidade; $i++) {
                $entradas[] = [
                    'nome'    => $params->get('name-link-' . $i, ''),
                    'url'     => $params->get('url-link-' . $i, ''),
                    'icone'   => $params->get('icon-link-' . $i, ''),
                    'novaAba' => true,
                ];
            }
        }

        foreach ($entradas as $entrada) {
            $nome = trim((string) $entrada['nome']);
            $url  = trim((string) $entrada['url']);

            if ($nome === '' || $url === '' || preg_match('#^\s*(javascript|data|vbscript):#i', $url)) {
                continue;
            }

            // Caminho interno sem barra inicial (ex.: "index.php?Itemid=5") parte da raiz do site
            if (!preg_match('#^([a-z][a-z0-9+.-]*:|//|/|\#|\?)#i', $url)) {
                $url = Uri::root(true) . '/' . $url;
            }

            $links[] = [
                'nome'    => $nome,
                'url'     => $url,
                // Apenas nomes de classes CSS (letras, números, hífen, sublinhado e espaços)
                'icone'   => trim(preg_replace('/[^a-z0-9 _-]/i', '', (string) $entrada['icone'])),
                'novaAba' => (bool) $entrada['novaAba'],
            ];
        }

        return $links;
    }

    /**
     * Tema de cores do template IDS Gov ("", "azul", "amarelo" ou "vermelho").
     *
     * @return  string
     *
     * @since   3.1.0
     */
    private function obterTema(): string
    {
        try {
            $template = $this->app->getTemplate(true);
            $tema     = $template->params instanceof Registry ? (string) $template->params->get('tema', '') : '';
        } catch (\Throwable $e) {
            $tema = '';
        }

        return preg_replace('/[^a-z0-9_-]/i', '', $tema);
    }
}
