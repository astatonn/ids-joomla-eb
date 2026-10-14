<?php

/**
 * IDS Joomla EB - cenários dos testes de integração.
 *
 * @copyright  (C) 2024-2026 Lucas Lima e colaboradores
 * @license    GNU General Public License version 2 or later
 */

declare(strict_types=1);

const TEMPLATE         = 'idsgov-exrcitobrasileiro';
const CORE_UPDATE_URL  = 'https://github.com/joomla/joomla-cms/releases/download/%1$s/Joomla_%1$s-Stable-Update_Package.zip';
const RELEASE_1_0_0    = 'https://github.com/astatonn/ids-joomla-eb/releases/download/1.0.0/pkg_ids_joomla_eb.zip';
const OUR_PATHS        = '~(templates/' . TEMPLATE . '|com_aniversariantes|com_pagtesouro|mod_(aniver|imagempopup|instafeed|links|readmorenews|videosiframe)|pkg_ids_joomla_eb)~';
const OUR_MODULES      = ['mod_aniver', 'mod_imagempopup', 'mod_instafeed', 'mod_links', 'mod_readmorenews', 'mod_videosiframe'];
const OUR_EXTENSIONS   = [
    ['package', 'pkg_ids_joomla_eb'], ['template', TEMPLATE], ['component', 'com_aniversariantes'], ['component', 'com_pagtesouro'],
    ['module', 'mod_aniver'], ['module', 'mod_imagempopup'], ['module', 'mod_instafeed'], ['module', 'mod_links'],
    ['module', 'mod_readmorenews'], ['module', 'mod_videosiframe'],
];

final class Context
{
    /** @var string[] contêineres auxiliares criados pelo cenário */
    public array $extraContainers = [];

    public function __construct(
        public Docker $docker,
        public Site $site,
        public string $network,
        public string $package,
        public string $cacheDir,
        public string $root
    ) {
    }

    public function version(): string
    {
        return (string) simplexml_load_file($this->root . '/pkg_ids_joomla_eb.xml')->version;
    }
}

// =============================================================================================
// Blocos de construção
// =============================================================================================

/** Ajustes iniciais do site de teste: erros de PHP visíveis, upload grande e API liberada. */
function prepare(Site $site): void
{
    $site->putString("upload_max_filesize = 64M\npost_max_size = 66M\n", '/usr/local/etc/php/conf.d/zz-idseb-upload.ini');
    $site->strictErrors();
    $site->sql("UPDATE jos_extensions SET enabled = 1 WHERE type = 'plugin' AND folder = 'api-authentication' AND element = 'basic'");
    Report::note('Joomla ' . $site->version() . ', PHP ' . trim($site->exec('php -r "echo PHP_VERSION;"')));
}

/**
 * Instala o pacote pt-BR oficial do Joomla e o define como idioma do site e do painel, como nos
 * sites do Exército. Pega do servidor de traduções do Joomla a versão mais nova compatível.
 */
function enablePortuguese(Context $c): void
{
    $site  = $c->site;
    $major = (int) $site->version();
    $list  = @simplexml_load_string((string) @file_get_contents("https://update.joomla.org/language/details$major/pt-BR_details.xml"));
    $best  = null;

    foreach ($list->update ?? [] as $update) {
        $platform = (string) ($update->targetplatform['version'] ?? '');

        if ($platform !== '' && !preg_match('/^' . $platform . '/', $site->version())) {
            continue;
        }

        if ($best === null || version_compare((string) $update->version, (string) $best->version, '>')) {
            $best = $update;
        }
    }

    // Sem versão declarada para esta versão exata do Joomla, usa a mais nova da série
    $best ??= $list->update[0] ?? null;

    if ($best === null) {
        Report::check(false, 'pacote de idioma pt-BR localizado no servidor de traduções do Joomla');

        return;
    }

    $zip = cached(trim((string) $best->downloads->downloadurl), $c->cacheDir);
    $site->put($zip, '/tmp/pt-BR.zip');
    [$code, $output] = $site->cli('extension:install --path=/tmp/pt-BR.zip');
    $site->sql("UPDATE jos_extensions SET params = '{\"administrator\":\"pt-BR\",\"site\":\"pt-BR\"}' WHERE element = 'com_languages' AND type = 'component'");
    $site->cli('cache:clean');
    settle($c);

    Report::check($code === 0 && $site->value("SELECT COUNT(*) FROM jos_extensions WHERE element = 'pkg_pt-BR'") === '1', 'idioma pt-BR oficial (' . $best->version . ') instalado e definido como padrão do site e do painel', trim(substr($output, -200)));
}

/** Dá tempo ao OPcache de perceber arquivos trocados pela linha de comando. */
function settle(Context $c): void
{
    $c->docker->run(['exec', $c->site->name, 'apachectl', '-k', 'graceful']);
    sleep(3);
}

/** Atualiza o núcleo do Joomla dentro do contêiner (arquivos + finalização), como o próprio Joomla faz. */
function coreUpdate(Context $c, string $version): void
{
    $site = $c->site;
    $from = $site->version();

    $site->put(cached(sprintf(CORE_UPDATE_URL, $version), $c->cacheDir), '/tmp/core-update.zip');
    $site->put(__DIR__ . '/helpers/core_finalise.php', '/tmp/core_finalise.php');
    $site->exec('php -r \'$z = new ZipArchive(); if ($z->open("/tmp/core-update.zip") !== true) { fwrite(STDERR, "zip"); exit(1); } $z->extractTo("/var/www/html"); $z->close();\'');

    [, $output] = $site->tryExec('php /tmp/core_finalise.php 2>&1');
    settle($c);
    $site->mark();

    Report::check(
        str_contains($output, 'finaliseUpgrade: OK') && $site->version() === $version,
        "núcleo do Joomla atualizado de $from para $version",
        trim(substr($output, -400))
    );
}

/** Instala/atualiza o pacote pela linha de comando do Joomla. */
function installPackage(Context $c, string $zip, string $label): void
{
    try {
        $c->site->install($zip);
        settle($c);
        Report::check(true, $label);
    } catch (Throwable $e) {
        Report::check(false, $label, $e->getMessage());
    }
}

/** Versão registrada pelo Joomla para uma extensão (ou null se não estiver instalada). */
function installedVersion(Site $site, string $type, string $element): ?string
{
    return $site->value('SELECT JSON_UNQUOTE(JSON_EXTRACT(manifest_cache, \'$.version\')) FROM jos_extensions WHERE type = ' . q($type) . ' AND element = ' . q($element));
}

function extensionId(Site $site, string $type, string $element): int
{
    return (int) $site->value('SELECT extension_id FROM jos_extensions WHERE type = ' . q($type) . ' AND element = ' . q($element));
}

/** Confere que todas as extensões do pacote estão instaladas, ativas e na versão esperada. */
function checkInstalled(Context $c, string $version): void
{
    $problems = [];

    foreach (OUR_EXTENSIONS as [$type, $element]) {
        $found = installedVersion($c->site, $type, $element);

        if ($found !== $version) {
            $problems[] = "$element: " . ($found ?? 'não instalada');
        }
    }

    Report::check(!$problems, "as 10 extensões estão na versão $version", implode(', ', $problems));

    $pkg      = extensionId($c->site, 'package', 'pkg_ids_joomla_eb');
    $children = (int) $c->site->value("SELECT COUNT(*) FROM jos_extensions WHERE package_id = $pkg");
    Report::check($children === 9, 'as 9 extensões estão vinculadas ao pacote', "vinculadas: $children");
}

/** Erros de PHP no log desde a última marca; falha se algum for nosso (ou fatal em qualquer lugar). */
function checkPhpLog(Site $site, string $label): void
{
    $ours   = [];
    $others = [];

    foreach ($site->phpErrors() as $error) {
        if (preg_match(OUR_PATHS, $error) || str_contains($error, 'Fatal error') || str_contains($error, 'Parse error')) {
            $ours[] = $error;
        } else {
            $others[] = $error;
        }
    }

    Report::check(!$ours, "nenhum erro, aviso ou depreciação de PHP no log ($label)", implode("\n       ", array_slice($ours, 0, 5)));

    foreach (array_slice($others, 0, 3) as $other) {
        Report::note('aviso do próprio Joomla (fora das nossas extensões): ' . substr($other, 0, 200));
    }

    $site->mark();
}

/**
 * Abre uma lista de páginas e confere status, ausência de erros de PHP e trechos esperados.
 *
 * @param array<int, array{0: string, 1: string, 2?: int, 3?: string[]}> $pages [rótulo, caminho, status, trechos]
 *
 * @return array<string, string> rótulo => HTML
 */
function checkPages(Site $site, array $pages): array
{
    $bodies = [];

    foreach ($pages as $page) {
        [$label, $path] = $page;
        $expected       = $page[2] ?? 200;
        $needles        = $page[3] ?? [];

        $response       = $site->get($path);
        $bodies[$label] = $response['body'];
        $problems       = [];

        if ($response['status'] !== $expected) {
            $problems[] = "HTTP {$response['status']} (esperado $expected)";
        }

        // Em páginas de erro propositais (404) o corpo descreve o erro; só interessa erro de PHP exibido
        $shown = $expected === 200 ? errorsInBody($response['body']) : array_filter(errorsInBody($response['body']), static fn (string $e): bool => !str_starts_with($e, 'página de erro'));

        if ($shown) {
            $problems[] = implode('; ', array_slice($shown, 0, 2));
        }

        foreach ($needles as $needle) {
            if (!has($response['body'], $needle)) {
                $problems[] = "faltou \"$needle\"";
            }
        }

        if (str_contains($path, '/administrator/') && str_contains($response['body'], 'name="passwd"')) {
            $problems[] = 'sessão de administrador perdida';
        }

        Report::check(!$problems, $label, implode('; ', $problems));
    }

    return $bodies;
}

/** Cria um módulo publicado em todas as páginas e devolve o id. */
function addModule(Site $site, string $title, string $module, string $position, array $params, int $ordering = 1): int
{
    $json = json_encode($params, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    $site->sql(
        'INSERT INTO jos_modules (title, note, content, ordering, position, published, module, access, showtitle, params, client_id, language) VALUES ('
        . q($title) . ", '', '', $ordering, " . q($position) . ', 1, ' . q($module) . ', 1, 0, ' . q($json) . ", 0, '*');"
        . ' INSERT INTO jos_modules_menu (moduleid, menuid) VALUES (LAST_INSERT_ID(), 0);'
    );

    return (int) $site->value('SELECT MAX(id) FROM jos_modules');
}

/** Cria um registro pela API REST e devolve o id (0 em caso de falha, já registrada no relatório). */
function apiCreate(Site $site, string $path, array $payload, string $label): int
{
    $response = $site->api('POST', $path, $payload);
    $id       = (int) ($response['json']['data']['id'] ?? 0);

    if ($id === 0) {
        Report::check(false, "criar $label pela API", 'HTTP ' . $response['status'] . ': ' . substr($response['body'], 0, 300));
    }

    return $id;
}

/** Converte todos os valores escalares em texto, recursivamente, para comparar configurações. */
function normalize(mixed $value): mixed
{
    if (is_array($value)) {
        return array_map('normalize', $value);
    }

    // trim(): o HTML do editor volta com a indentação do <textarea>, que o navegador descarta
    return $value === null ? null : trim((string) $value);
}

/** Lê um formulário do administrador e falha de forma clara se ele não existir. */
function adminForm(Site $site, string $path, string $formId): HtmlForm
{
    $page = $site->get('/administrator/' . ltrim($path, '/'));
    $form = HtmlForm::fromHtml($page['body'], $formId);

    if ($form === null) {
        $shown = errorsInBody($page['body']);

        throw new RuntimeException("formulário #$formId não encontrado em $path (HTTP {$page['status']}" . ($shown ? ': ' . $shown[0] : '') . ')');
    }

    return $form;
}

function submit(Site $site, HtmlForm $form, string $task): array
{
    $form->set('task', $task);
    $action = $form->action !== '' ? $form->action : '/administrator/index.php';

    if (!str_starts_with($action, 'http') && !str_starts_with($action, '/')) {
        $action = '/administrator/' . $action;
    }

    return $site->http('POST', $action, $form->body(), ['Content-Type: application/x-www-form-urlencoded']);
}

// =============================================================================================
// Conteúdo de teste
// =============================================================================================

/**
 * Monta um site completo: template como padrão, menus, artigos, banners e todos os módulos e
 * layouts do pacote publicados.
 *
 * @return array<string, int> ids úteis
 */
function seedSite(Context $c): array
{
    $site = $c->site;
    $ids  = [];

    // ---- Template como padrão, com todas as opções preenchidas
    $params = [
        'texto_titulo' => '1º Centro de Telemática de Área', 'texto_subtitulo' => 'Comando Militar do Sul', 'texto_assinatura' => '1º CTA',
        'imagem_logo' => 'images/joomla_black.png#joomlaImage://local-images/joomla_black.png?width=225&height=50', 'logo_comlink' => '1',
        'tema' => 'azul', 'largura' => '0', 'tipo-menu' => '0', 'tipomenu' => '0', 'cabecalhomenu' => '1', 'texto_menuinfo' => '<em>Rodapé do menu</em>',
        'cor_footer' => '0', 'imagem_footer_acesso' => '1', 'imagem_footer_brasil' => '1', 'texto_licenca' => '<strong>Texto legal de teste</strong>',
        'redes_sociais' => '1', 'redes_instagram' => 'https://www.instagram.com/perfil_da_om', 'redes_youtube' => 'https://www.youtube.com/@canal_da_om',
        'componente_atendimentocidadao' => '1', 'atendimentocidadao_siorg' => '26',
        // Aspas e apóstrofo de propósito: nas versões antigas isso quebrava o JavaScript da página
        'cookies_aviso' => '1', 'cookies_mensagem' => 'Usamos cookies d\'água e "aspas". <b>Concorda?</b>', 'cookies_ciente_titulo' => 'Ciente',
        'cookies_leiamais' => '1', 'cookies_leiamais_titulo' => 'Política de cookies', 'cookies_leiamais_link' => '/politica-de-cookies',
    ];

    $site->sql(
        "UPDATE jos_template_styles SET home = '0' WHERE client_id = 0;"
        . " UPDATE jos_template_styles SET home = '1', params = " . q(json_encode($params, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) . ' WHERE template = ' . q(TEMPLATE) . ';'
    );
    $ids['style'] = (int) $site->value('SELECT id FROM jos_template_styles WHERE template = ' . q(TEMPLATE));

    // ---- Conteúdo pelo núcleo do Joomla (API REST)
    $content = extensionId($site, 'component', 'com_content');
    $tags    = extensionId($site, 'component', 'com_tags');

    $ids['category'] = apiCreate($site, 'content/categories', ['title' => 'Notícias', 'alias' => 'noticias', 'parent_id' => 1, 'extension' => 'com_content', 'published' => 1, 'language' => '*', 'access' => 1], 'categoria');
    $ids['tag']      = apiCreate($site, 'tags', ['title' => 'Operações', 'alias' => 'operacoes', 'parent_id' => 1, 'published' => 1, 'access' => 1, 'language' => '*', 'description' => ''], 'tag');

    foreach (['Primeira notícia de teste', 'Segunda notícia de teste', 'Terceira notícia de teste'] as $i => $title) {
        $ids['article' . ($i + 1)] = apiCreate($site, 'content/articles', [
            'title' => $title, 'alias' => 'noticia-' . ($i + 1), 'catid' => $ids['category'], 'state' => 1, 'language' => '*', 'access' => 1, 'featured' => 1,
            'articletext' => '<p>Introdução da notícia ' . ($i + 1) . '.</p><hr id="system-readmore"><p>Texto completo da notícia ' . ($i + 1) . ' com a palavra telematica.</p>',
            'images' => ['image_intro' => 'images/joomla_black.png', 'image_intro_alt' => 'Imagem', 'image_fulltext' => '', 'image_fulltext_alt' => ''],
            'metakey' => '', 'metadesc' => '', 'tags' => [$ids['tag']],
        ], 'artigo');
    }

    foreach (['Banner A', 'Banner B'] as $i => $name) {
        apiCreate($site, 'banners', [
            'name' => $name, 'alias' => 'banner-' . ($i + 1), 'catid' => 3, 'state' => 1, 'type' => 0, 'clickurl' => 'https://www.eb.mil.br', 'language' => '*',
            'description' => '', 'custombannercode' => '', 'params' => ['imageurl' => 'images/joomla_black.png', 'width' => '', 'height' => '', 'alt' => $name],
        ], 'banner');
    }

    $item = static fn (array $data): array => $data + [
        'menutype' => 'mainmenu', 'parent_id' => '1', 'published' => '1', 'access' => 1, 'language' => '*', 'browserNav' => 0, 'home' => 0,
        'template_style_id' => 0, 'note' => '', 'component_id' => 0, 'params' => new stdClass(),
    ];

    $ids['menuNoticias'] = apiCreate($site, 'menus/site/items', $item([
        'title' => 'Notícias', 'alias' => 'noticias', 'type' => 'component', 'component_id' => $content,
        'link' => 'index.php?option=com_content&view=category&layout=blog&id=' . $ids['category'], 'request' => ['id' => $ids['category']],
        'params' => ['menu_icon_css' => 'fa-newspaper', 'show_page_heading' => 1, 'page_heading' => 'Todas as notícias'],
    ]), 'item de menu (blog)');

    $ids['menuHeading'] = apiCreate($site, 'menus/site/items', $item(['title' => 'Institucional', 'alias' => 'institucional', 'type' => 'heading', 'link' => '']), 'item de menu (título)');

    apiCreate($site, 'menus/site/items', $item([
        'title' => 'Portal do Exército', 'alias' => 'portal-eb', 'type' => 'url', 'link' => 'https://www.eb.mil.br', 'parent_id' => (string) $ids['menuHeading'], 'browserNav' => 1,
        'params' => ['menu_image' => 'images/joomla_black.png', 'menu_icon_css' => 'fa-globe'],
    ]), 'item de menu (URL)');

    apiCreate($site, 'menus/site/items', $item(['title' => 'Separador', 'alias' => 'separador', 'type' => 'separator', 'link' => '', 'parent_id' => (string) $ids['menuHeading']]), 'item de menu (separador)');

    $ids['menuArtigo'] = apiCreate($site, 'menus/site/items', $item([
        'title' => 'Sobre', 'alias' => 'sobre', 'type' => 'component', 'component_id' => $content,
        'link' => 'index.php?option=com_content&view=article&id=' . $ids['article1'], 'request' => ['id' => $ids['article1']],
    ]), 'item de menu (artigo)');

    $ids['menuTag'] = apiCreate($site, 'menus/site/items', $item([
        'title' => 'Operações', 'alias' => 'operacoes', 'type' => 'component', 'component_id' => $tags,
        'link' => 'index.php?option=com_tags&view=tag&id[0]=' . $ids['tag'], 'request' => ['id' => [$ids['tag']]],
    ]), 'item de menu (tag)');

    $ids['menuAniver'] = apiCreate($site, 'menus/site/items', $item([
        'title' => 'Aniversariantes', 'alias' => 'aniversariantes', 'type' => 'component', 'component_id' => extensionId($site, 'component', 'com_aniversariantes'),
        'link' => 'index.php?option=com_aniversariantes&view=aniversariantes', 'params' => ['periodo' => 'ano', 'dias' => 30, 'show_page_heading' => 1],
    ]), 'item de menu (aniversariantes)');

    $ids['menuGru'] = apiCreate($site, 'menus/site/items', $item([
        'title' => 'Emitir GRU', 'alias' => 'emitir-gru', 'type' => 'component', 'component_id' => extensionId($site, 'component', 'com_pagtesouro'),
        'link' => 'index.php?option=com_pagtesouro&view=formulario', 'params' => ['introducao' => '<p>Orientações de <strong>preenchimento</strong>.</p>'],
    ]), 'item de menu (PagTesouro)');

    // ---- Módulos do núcleo com os layouts do template
    $layout = static fn (string $name): string => TEMPLATE . ':' . $name;
    $menu   = static fn (string $name): array => ['menutype' => 'mainmenu', 'startLevel' => 1, 'endLevel' => 0, 'showAllChildren' => 1, 'layout' => TEMPLATE . ':' . $name, 'class_sfx' => 'fas fa-bars'];

    addModule($site, 'Menu Principal', 'mod_menu', 'menu-body', $menu('menuprincipal'), 1);
    addModule($site, 'Menu Nível 1', 'mod_menu', 'menu-body', $menu('menuprincipal-nivel1'), 2);
    addModule($site, 'Menu Links', 'mod_menu', 'menu-links', $menu('linksmenu'));
    addModule($site, 'Menu Logos', 'mod_menu', 'menu-logos', $menu('logosmenu'));
    addModule($site, 'Acesso Rápido', 'mod_menu', 'menuacesso', $menu('acessorapido'));
    addModule($site, 'Mapa do Site', 'mod_menu', 'menumapa', $menu('mapadosite'));
    addModule($site, 'Cartões', 'mod_menu', 'content-top', $menu('cards'), 1);
    addModule($site, 'Busca', 'mod_finder', 'search', ['layout' => '_:default', 'show_autosuggest' => 0]);
    addModule($site, 'Busca (erro)', 'mod_finder', 'error-search', ['layout' => $layout('busca-erro'), 'show_autosuggest' => 0]);
    addModule($site, 'Trilha', 'mod_breadcrumbs', 'breadcrumbs', ['showHere' => 1, 'showHome' => 1, 'showLast' => 1]);
    addModule($site, 'Destaques', 'mod_articles_news', 'content-top', ['catid' => [$ids['category']], 'count' => 3, 'image' => 1, 'show_featured' => '', 'ordering' => 'a.publish_up', 'direction' => 1, 'layout' => '_:default'], 2);
    addModule($site, 'Últimas', 'mod_articles_latest', 'content-bottom', ['catid' => [$ids['category']], 'count' => 3, 'show_featured' => '', 'ordering' => 'c_dsc', 'user_id' => '0', 'layout' => '_:default'], 1);
    addModule($site, 'Banners', 'mod_banners', 'main-top', ['target' => 1, 'count' => 2, 'cid' => 0, 'catid' => [3], 'tag_search' => 0, 'ordering' => 0, 'layout' => '_:default'], 1);
    addModule($site, 'Carrossel', 'mod_banners', 'main-top', ['target' => 1, 'count' => 2, 'cid' => 0, 'catid' => [3], 'tag_search' => 0, 'ordering' => 0, 'layout' => $layout('br-carousel')], 2);

    // ---- Módulos do pacote
    $ids['modAniver'] = addModule($site, 'Aniversariantes', 'mod_aniver', 'content-top', ['titulo' => 'Aniversariantes do mês', 'periodo' => 'mes', 'dias' => 7, 'mostrar_data' => 1, 'sem_aniversariantes' => 'mensagem', 'mensagem_vazio' => 'Ninguém neste período.'], 3);
    $ids['modLinks']  = addModule($site, 'Links', 'mod_links', 'content-bottom', ['section-name' => 'Links Úteis', 'compact-links' => 0, 'links' => [
        'links0' => ['nome' => 'Portal do Exército', 'url' => 'https://www.eb.mil.br', 'icone' => 'fas fa-globe', 'icone_custom' => '', 'nova_aba' => 1],
        'links1' => ['nome' => 'Página interna', 'url' => 'index.php?option=com_content&view=featured', 'icone' => 'custom', 'icone_custom' => 'fas fa-anchor', 'nova_aba' => 0],
        'links2' => ['nome' => 'Bloqueado', 'url' => 'javascript:alert(1)', 'icone' => '', 'icone_custom' => '', 'nova_aba' => 1],
    ]], 2);
    $ids['modPopup']  = addModule($site, 'Aviso importante', 'mod_imagempopup', 'main-bottom', ['imagem' => 'images/joomla_black.png#joomlaImage://local-images/joomla_black.png?width=225&height=50', 'imagem_alt' => 'Cartaz', 'link' => 'https://www.eb.mil.br', 'popuptext' => '<p>Texto do <strong>popup</strong></p>', 'frequencia' => 'dia', 'temporizador' => 1, 'segundos' => 15, 'fechar_fora' => 1]);
    $ids['modInsta']  = addModule($site, 'Instagram', 'mod_instafeed', 'main-bottom', ['profile' => '@perfil_da_om', 'largura' => 540], 2);
    $ids['modLeia']   = addModule($site, 'Leia mais', 'mod_readmorenews', 'content-top', ['texto' => 'Ver todas as notícias', 'destino' => 'categoria', 'categoria' => $ids['category'], 'showbuttonreademore' => 'yes'], 4);
    $ids['modVideos'] = addModule($site, 'Vídeos', 'mod_videosiframe', 'content-bottom', ['titulo' => 'Vídeos da OM', 'video-width' => 400, 'video-height' => 225, 'movimento' => 1, 'segundos_por_video' => 6, 'videos' => [
        'videos0' => ['url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'titulo' => 'Vídeo um'],
        'videos1' => ['url' => 'https://youtu.be/9bZkp7q19f0', 'titulo' => ''],
        'videos2' => ['url' => 'https://exemplo.com/nao-e-youtube', 'titulo' => 'Ignorado'],
    ]], 3);

    // ---- Índice da busca inteligente
    [$code, $output] = $site->cli('finder:index');
    Report::check($code === 0, 'índice da busca (Smart Search) gerado', trim(substr($output, -200)));

    Report::check(!in_array(0, $ids, true), 'conteúdo de teste criado (categoria, artigos, tag, banners, menus e 20 módulos)', json_encode(array_keys($ids, 0, true)));

    return $ids;
}

// =============================================================================================
// Verificações do administrador
// =============================================================================================

/** Cadastros dos dois componentes feitos pelas telas do administrador, como um usuário faria. */
function exerciseComponents(Context $c): void
{
    $site = $c->site;
    // UTC: é o fuso padrão do Joomla e do MySQL nos contêineres de teste
    $mes  = gmdate('m');

    Report::context('Componente Aniversariantes (telas do administrador)');

    $cadastros = [
        ['3º Sgt', 'Fulano de Hoje', ((int) gmdate('Y') - 30) . '-' . gmdate('m-d')],
        ['Cap', 'Beltrano do Mês', '1985-' . $mes . '-' . (gmdate('d') === '15' ? '16' : '15')],
        ['Cel', 'Sicrano de Outro Mês', '1970-' . ($mes === '03' ? '04' : '03') . '-10'],
    ];

    // O formulário usa sempre o formato brasileiro, qualquer que seja o idioma do painel
    $br = static fn (string $iso): string => implode('/', array_reverse(explode('-', $iso)));

    foreach ($cadastros as [$pg, $nome, $data]) {
        $form = adminForm($site, 'index.php?option=com_aniversariantes&task=aniversariante.add', 'aniversariante-form');
        $form->set('jform[pg]', $pg)->set('jform[nome]', $nome)->set('jform[aniversario]', $br($data));
        $result = submit($site, $form, 'aniversariante.save');

        $saved = $site->value('SELECT aniversario FROM jos_aniversariantes_ WHERE nome = ' . q($nome));
        Report::check($saved === $data, "cadastrar aniversariante \"$pg $nome\"", "gravado: " . var_export($saved, true) . ' ' . joomlaErrors($result['body']));
    }

    // Validação: nome em branco não pode gravar
    $before = (int) $site->value('SELECT COUNT(*) FROM jos_aniversariantes_');
    $form   = adminForm($site, 'index.php?option=com_aniversariantes&task=aniversariante.add', 'aniversariante-form');
    $form->set('jform[pg]', 'Cb')->set('jform[nome]', '')->set('jform[aniversario]', '01/01/1990');
    $result = submit($site, $form, 'aniversariante.save');
    Report::check((int) $site->value('SELECT COUNT(*) FROM jos_aniversariantes_') === $before && joomlaErrors($result['body']) !== '', 'recusar cadastro sem nome, com mensagem de erro');
    $site->get('/administrator/index.php?option=com_aniversariantes&task=aniversariante.cancel&' . $site->token() . '=1');

    // Validação: data que não é data
    $form = adminForm($site, 'index.php?option=com_aniversariantes&task=aniversariante.add', 'aniversariante-form');
    $form->set('jform[pg]', 'Cb')->set('jform[nome]', 'Data Inválida')->set('jform[aniversario]', 'amanhã');
    $result = submit($site, $form, 'aniversariante.save');
    Report::check((int) $site->value('SELECT COUNT(*) FROM jos_aniversariantes_') === $before && !errorsInBody($result['body']), 'recusar data inválida sem quebrar a página', implode('; ', errorsInBody($result['body'])));
    $site->get('/administrator/index.php?option=com_aniversariantes&task=aniversariante.cancel&' . $site->token() . '=1');

    // Edição
    $id   = (int) $site->value("SELECT id FROM jos_aniversariantes_ WHERE nome = 'Beltrano do Mês'");
    $form = adminForm($site, 'index.php?option=com_aniversariantes&task=aniversariante.edit&id=' . $id, 'aniversariante-form');
    $gravada = (string) $site->value("SELECT aniversario FROM jos_aniversariantes_ WHERE id = $id");
    Report::check($form->get('jform[nome]') === 'Beltrano do Mês' && $form->get('jform[pg]') === 'Cap', 'formulário de edição carrega os dados gravados', 'nome=' . var_export($form->get('jform[nome]'), true) . ' pg=' . var_export($form->get('jform[pg]'), true));
    Report::check($form->get('jform[aniversario]') === $br($gravada), 'formulário de edição mostra a data como dd/mm/aaaa', var_export($form->get('jform[aniversario]'), true) . ' (gravada: ' . $gravada . ')');
    $form->set('jform[nome]', 'Beltrano Editado');
    submit($site, $form, 'aniversariante.save');
    Report::check($site->value("SELECT CONCAT(nome, '|', aniversario) FROM jos_aniversariantes_ WHERE id = $id") === 'Beltrano Editado|' . $gravada, 'editar aniversariante sem alterar a data (dia e mês não se invertem)');

    // Lista, filtros e ordenação
    checkPages($site, [
        ['lista de aniversariantes', '/administrator/index.php?option=com_aniversariantes&view=aniversariantes', 200, ['Fulano de Hoje', 'Beltrano Editado', 'Sicrano de Outro Mês']],
        ['lista filtrada por mês e ordenada pelo calendário', '/administrator/index.php?option=com_aniversariantes&view=aniversariantes&filter[mes]=' . (int) $mes . '&filter[search]=&filter[pg]=&list[fullordering]=diames+ASC', 200, ['Fulano de Hoje', 'Beltrano Editado']],
        ['lista filtrada por posto/graduação', '/administrator/index.php?option=com_aniversariantes&view=aniversariantes&filter[mes]=&filter[search]=&filter[pg]=Cel&list[fullordering]=a.nome+DESC', 200, ['Sicrano de Outro Mês']],
        ['busca por nome', '/administrator/index.php?option=com_aniversariantes&view=aniversariantes&filter[mes]=&filter[pg]=&filter[search]=Fulano', 200, ['Fulano de Hoje']],
    ]);

    // Exclusão
    $form = adminForm($site, 'index.php?option=com_aniversariantes&view=aniversariantes&filter[mes]=&filter[search]=&filter[pg]=', 'adminForm');
    $form->set('cid[]', (string) $site->value("SELECT id FROM jos_aniversariantes_ WHERE nome = 'Sicrano de Outro Mês'"))->set('boxchecked', '1');
    submit($site, $form, 'aniversariantes.delete');
    Report::check($site->value("SELECT COUNT(*) FROM jos_aniversariantes_ WHERE nome = 'Sicrano de Outro Mês'") === '0', 'excluir aniversariante');

    Report::context('Componente PagTesouro (telas do administrador)');

    foreach ([['1º CTA', '52121', '160001'], ['Base Administrativa', '52121', '160002']] as [$descricao, $orgao, $ug]) {
        $form = adminForm($site, 'index.php?option=com_pagtesouro&task=uasg.add', 'uasg-form');
        $form->set('jform[descricao]', $descricao)->set('jform[orgao]', $orgao)->set('jform[ug]', $ug)->set('jform[state]', '1');
        $result = submit($site, $form, 'uasg.save');
        Report::check($site->value('SELECT COUNT(*) FROM jos_pagtesouro_uasgs WHERE ug = ' . q($ug)) === '1', "cadastrar UASG \"$descricao\"", joomlaErrors($result['body']));
    }

    // UASG repetida (mesmo órgão/UG) deve ser recusada
    $form = adminForm($site, 'index.php?option=com_pagtesouro&task=uasg.add', 'uasg-form');
    $form->set('jform[descricao]', 'Duplicada')->set('jform[orgao]', '52121')->set('jform[ug]', '160001')->set('jform[state]', '1');
    $result = submit($site, $form, 'uasg.save');
    Report::check($site->value("SELECT COUNT(*) FROM jos_pagtesouro_uasgs WHERE descricao = 'Duplicada'") === '0' && str_contains(joomlaErrors($result['body']), 'Já existe uma UASG'), 'recusar UASG com órgão/UG repetidos', joomlaErrors($result['body']));
    $site->get('/administrator/index.php?option=com_pagtesouro&task=uasg.cancel&' . $site->token() . '=1');

    $uasg = (int) $site->value("SELECT id FROM jos_pagtesouro_uasgs WHERE ug = '160001'");

    foreach ([['Taxa de inscrição', '16014'], ['Aluguel de PNR', '16015'], ['Serviço oculto', '16016']] as [$descricao, $codigo]) {
        $form = adminForm($site, 'index.php?option=com_pagtesouro&task=servico.add', 'servico-form');
        $form->set('jform[uasg_id]', (string) $uasg)->set('jform[descricao]', $descricao)->set('jform[codigo]', $codigo)->set('jform[state]', '1');
        $result = submit($site, $form, 'servico.save');
        Report::check($site->value('SELECT COUNT(*) FROM jos_pagtesouro_servicos WHERE codigo = ' . q($codigo)) === '1', "cadastrar serviço \"$descricao\"", joomlaErrors($result['body']));
    }

    // Despublicar um serviço pela lista
    $oculto = (string) $site->value("SELECT id FROM jos_pagtesouro_servicos WHERE codigo = '16016'");
    $form   = adminForm($site, 'index.php?option=com_pagtesouro&view=servicos&filter[search]=&filter[state]=&filter[uasg_id]=', 'adminForm');
    $form->set('cid[]', $oculto)->set('boxchecked', '1');
    submit($site, $form, 'servicos.unpublish');
    Report::check($site->value("SELECT state FROM jos_pagtesouro_servicos WHERE id = $oculto") === '0', 'despublicar serviço pela lista');

    checkPages($site, [
        ['lista de UASGs com contagem de serviços', '/administrator/index.php?option=com_pagtesouro&view=uasgs', 200, ['1º CTA', 'Base Administrativa', '160001']],
        ['lista de serviços filtrada por UASG', '/administrator/index.php?option=com_pagtesouro&view=servicos&filter[search]=&filter[state]=&filter[uasg_id]=' . $uasg . '&list[fullordering]=uasg_descricao+ASC', 200, ['Taxa de inscrição', 'Aluguel de PNR', '16014']],
        ['lista de UASGs ordenada pela quantidade de serviços', '/administrator/index.php?option=com_pagtesouro&view=uasgs&filter[search]=&filter[state]=1&list[fullordering]=servicos+DESC', 200, ['1º CTA']],
    ]);

    // Excluir a UASG sem serviços
    $form = adminForm($site, 'index.php?option=com_pagtesouro&view=uasgs&filter[search]=&filter[state]=', 'adminForm');
    $form->set('cid[]', (string) $site->value("SELECT id FROM jos_pagtesouro_uasgs WHERE ug = '160002'"))->set('boxchecked', '1');
    submit($site, $form, 'uasgs.delete');
    Report::check($site->value("SELECT COUNT(*) FROM jos_pagtesouro_uasgs WHERE ug = '160002'") === '0', 'excluir UASG');

    checkPhpLog($site, 'telas dos componentes');
}

/** Telas do Joomla em que o administrador "seleciona" coisas do pacote: módulos, posições, layouts, menus e opções. */
function checkAdminScreens(Context $c, array $ids): void
{
    $site = $c->site;

    Report::context('Seleção e configuração no administrador');

    checkPages($site, [
        ['painel inicial', '/administrator/index.php', 200, []],
        ['lista de tipos de módulo mostra os 6 módulos com o prefixo "IDS Gov"', '/administrator/index.php?option=com_modules&view=select&client_id=0', 200, [
            'IDS Gov - Aniversariantes', 'IDS Gov - Botão Leia Mais', 'IDS Gov - Instagram', 'IDS Gov - Links', 'IDS Gov - Popup (imagem ou aviso)', 'IDS Gov - Vídeos (YouTube)',
        ]],
        ['tipos de item de menu mostram as duas páginas dos componentes', '/administrator/index.php?option=com_menus&view=menutypes&tmpl=component&client_id=0', 200, ['Lista de aniversariantes', 'Formulário de pagamento (GRU)']],
        ['gerenciador de extensões mostra o pacote com nome traduzido', '/administrator/index.php?option=com_installer&view=manage&filter[search]=IDS', 200, ['IDS Joomla EB (pacote completo)']],
        ['sites de atualização mostram o pacote', '/administrator/index.php?option=com_installer&view=updatesites&filter[search]=IDS', 200, ['IDS Joomla EB']],
        ['menu Componentes tem Aniversariantes e PagTesouro', '/administrator/index.php?option=com_cpanel&view=cpanel&dashboard=components', 200, ['option=com_aniversariantes', 'option=com_pagtesouro']],
        ['opções do componente Aniversariantes', '/administrator/index.php?option=com_config&view=component&component=com_aniversariantes', 200, ['com_aniversariantes']],
        ['opções do componente PagTesouro', '/administrator/index.php?option=com_config&view=component&component=com_pagtesouro', 200, ['com_pagtesouro']],
    ]);

    // ---- Estilo do template: abas, rótulos e gravação pelo formulário
    $path = 'index.php?option=com_templates&task=style.edit&id=' . $ids['style'];
    $page = $site->get('/administrator/' . $path);
    $tabs = ['Identidade', 'Aparência', 'Menu', 'Rodapé', 'Redes sociais', 'Cidadão e cookies'];
    $text = html_entity_decode($page['body'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $miss = array_filter($tabs, static fn (string $tab): bool => !preg_match('~<joomla-tab-element[^>]*name="' . preg_quote($tab, '~') . '"~u', $text));
    Report::check(!$miss && !errorsInBody($page['body']), 'estilo do template: seis abas temáticas no lugar da aba única "Avançado"', implode(', ', $miss) . ' ' . implode('; ', errorsInBody($page['body'])));
    Report::check(!preg_match('/TPL_IDSGOV_[A-Z_]+/', $page['body'], $m), 'estilo do template: todos os rótulos traduzidos', $m[0] ?? '');

    $form = HtmlForm::fromHtml($page['body'], 'style-form');

    if (Report::check($form !== null, 'estilo do template: formulário encontrado')) {
        $expected = ['texto_titulo', 'texto_subtitulo', 'texto_assinatura', 'title_page', 'imagem_logo', 'logo_comlink', 'logo_link', 'tema', 'largura', 'tipo-menu', 'tipomenu', 'cabecalhomenu', 'texto_menuinfo',
            'cor_footer', 'imagem_logo_footer', 'imagem_footer_acesso', 'imagem_footer_brasil', 'imagem_footer_brasil_src', 'texto_licenca', 'redes_sociais', 'redes_instagram', 'redes_twitter', 'redes_facebook',
            'redes_youtube', 'componente_atendimentocidadao', 'atendimentocidadao_siorg', 'cookies_aviso', 'cookies_mensagem', 'cookies_ciente_titulo', 'cookies_leiamais', 'cookies_leiamais_titulo', 'cookies_leiamais_link'];
        $missing  = array_filter($expected, static fn (string $name): bool => !$form->has("jform[params][$name]"));
        Report::check(!$missing, 'estilo do template: os 32 campos das versões anteriores continuam com os mesmos nomes', implode(', ', $missing));
        Report::check($form->get('jform[params][texto_titulo]') === '1º Centro de Telemática de Área' && $form->get('jform[params][tema]') === 'azul', 'estilo do template: o formulário carrega os valores já gravados');

        $decoded = html_entity_decode($page['body'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        Report::check(preg_match('~<label[^>]*for="jform_params_redes_sociais0"[^>]*>\s*Não\s*</label>~u', $decoded) === 1, 'estilo do template: os campos sim/não mostram "Não" (o pt-BR oficial traduz o genérico como "Nunca")');

        $form->set('jform[params][texto_subtitulo]', 'Subtítulo alterado pelo teste')->set('jform[params][redes_facebook]', 'facebook.com/sem-esquema');
        $result = submit($site, $form, 'style.apply');
        $stored = json_decode((string) $site->value('SELECT params FROM jos_template_styles WHERE id = ' . $ids['style']), true) ?: [];

        Report::check(($stored['texto_subtitulo'] ?? '') === 'Subtítulo alterado pelo teste' && joomlaErrors($result['body']) === '', 'estilo do template: salvar pelo formulário', joomlaErrors($result['body']));
        Report::check(($stored['redes_facebook'] ?? '') === 'http://facebook.com/sem-esquema', 'estilo do template: endereço sem "https://" é completado ao salvar', (string) ($stored['redes_facebook'] ?? ''));
        Report::check(($stored['texto_titulo'] ?? '') === '1º Centro de Telemática de Área' && ($stored['cookies_mensagem'] ?? '') === 'Usamos cookies d\'água e "aspas". <b>Concorda?</b>', 'estilo do template: os demais valores são preservados ao salvar');
        $site->get('/administrator/index.php?option=com_templates&task=style.cancel&' . $site->token() . '=1');
    }

    // ---- Formulários dos módulos: abrem, mostram rótulos traduzidos e gravam sem perder nada
    $labels = [
        'modAniver' => ['Quais aniversariantes exibir', 'Onde cadastrar os aniversariantes'],
        'modLinks'  => ['Título da seção', 'Texto do link', 'Globo (site)', 'Outro ícone (informar a classe)'],
        'modPopup'  => ['Exibir o popup', 'Fechar sozinho', 'Comportamento'],
        'modInsta'  => ['Perfil do Instagram', 'Acesso ao Instagram'],
        'modLeia'   => ['Destino do botão', 'Categoria de artigos (todas as notícias da categoria)', 'Como levar para a página com todas as notícias'],
        'modVideos' => ['Endereço do vídeo no YouTube', 'Movimento automático', 'Aparência'],
    ];

    foreach ($labels as $key => $texts) {
        $id     = $ids[$key];
        $module = (string) $site->value("SELECT module FROM jos_modules WHERE id = $id");
        $before = json_decode((string) $site->value("SELECT params FROM jos_modules WHERE id = $id"), true);
        $page   = $site->get('/administrator/index.php?option=com_modules&task=module.edit&id=' . $id);
        $miss   = array_filter($texts, static fn (string $text): bool => !has($page['body'], $text));
        $raw    = preg_match('/\b' . strtoupper($module) . '_[A-Z_]{3,}\b/', $page['body'], $m) ? $m[0] : '';

        Report::check(!$miss && $raw === '' && !errorsInBody($page['body']), "$module: formulário de configuração com rótulos em português", implode(', ', $miss) . " $raw " . implode('; ', errorsInBody($page['body'])));

        // Nomes amigáveis das posições do template aparecem no campo "Posição"
        if ($key === 'modAniver') {
            Report::check(has($page['body'], '>Conteúdo: acima [content-top]<') && has($page['body'], '>Menu principal: itens [menu-body]<') && !has($page['body'], '[content-top] [content-top]'), 'campo Posição mostra os nomes amigáveis das posições do template');
        }

        $form = HtmlForm::fromHtml($page['body'], 'module-form');

        if ($form === null) {
            Report::check(false, "$module: formulário #module-form encontrado");
            continue;
        }

        $result = submit($site, $form, 'module.apply');
        $after  = json_decode((string) $site->value("SELECT params FROM jos_modules WHERE id = $id"), true);
        $lost   = [];

        foreach ($before as $name => $value) {
            // O formulário grava números como texto; o que importa é o valor
            if (normalize($after[$name] ?? null) !== normalize($value)) {
                $lost[] = $name . ': ' . json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . ' → ' . json_encode($after[$name] ?? null, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
        }

        Report::check(joomlaErrors($result['body']) === '' && !$lost, "$module: abrir e salvar pelo formulário preserva a configuração", joomlaErrors($result['body']) . ' ' . implode(' | ', array_slice($lost, 0, 3)));
        $site->get('/administrator/index.php?option=com_modules&task=module.cancel&' . $site->token() . '=1');
    }

    // ---- Layouts alternativos com nome amigável (módulo de menu do núcleo)
    $menuModule = (int) $site->value("SELECT id FROM jos_modules WHERE module = 'mod_menu' AND position = 'menu-body' ORDER BY id LIMIT 1");
    $page       = $site->get('/administrator/index.php?option=com_modules&task=module.edit&id=' . $menuModule);
    $names      = ['Menu principal (multinível) - posição menu-body', 'Links do menu - posição menu-links', 'Logos do menu - posição menu-logos', 'Acesso Rápido - posição menuacesso', 'Mapa do site - posição menumapa', 'Cartões (cards) - posições de conteúdo'];
    $miss       = array_filter($names, static fn (string $name): bool => !has($page['body'], $name));
    Report::check(!$miss, 'campo Layout do módulo de menu mostra os layouts do template com nomes amigáveis', implode(', ', $miss));
    $site->get('/administrator/index.php?option=com_modules&task=module.cancel&' . $site->token() . '=1');

    // ---- Item de menu do PagTesouro: parâmetros com rótulos
    $page = $site->get('/administrator/index.php?option=com_menus&task=item.edit&id=' . $ids['menuGru']);
    Report::check(has($page['body'], 'Texto de introdução') && has($page['body'], 'Formulário de pagamento (GRU)') && !errorsInBody($page['body']), 'item de menu do PagTesouro: tipo e opções com rótulos em português');
    $site->get('/administrator/index.php?option=com_menus&task=item.cancel&' . $site->token() . '=1');

    $page = $site->get('/administrator/index.php?option=com_menus&task=item.edit&id=' . $ids['menuAniver']);
    Report::check(has($page['body'], 'Quais aniversariantes exibir') && has($page['body'], 'Lista de aniversariantes') && !errorsInBody($page['body']), 'item de menu de Aniversariantes: tipo e opções com rótulos em português');
    $site->get('/administrator/index.php?option=com_menus&task=item.cancel&' . $site->token() . '=1');

    checkPhpLog($site, 'telas do administrador');
}

// =============================================================================================
// Verificações do site
// =============================================================================================

function checkFrontend(Context $c, array $ids, string $label = 'Site público'): void
{
    $site = $c->site;

    Report::context($label);

    $bodies = checkPages($site, [
        ['página inicial', '/', 200, ['1º Centro de Telemática de Área', 'tema-azul-main', 'Ouvidoria e Acesso à Informação']],
        ['blog da categoria (com_content)', '/index.php/noticias', 200, ['Primeira notícia de teste', 'Todas as notícias', 'br-breadcrumb']],
        ['artigo (com_content)', '/index.php/sobre', 200, ['Primeira notícia de teste', 'Texto completo da notícia 1']],
        ['artigo aberto pelo endereço não amigável', '/index.php?option=com_content&view=article&id=' . $ids['article2'] . '&catid=' . $ids['category'], 200, ['Segunda notícia de teste', 'Texto completo da notícia 2']],
        ['tag (com_tags)', '/index.php/operacoes', 200, ['Primeira notícia de teste']],
        ['resultado da busca (com_finder)', '/index.php?option=com_finder&view=search&q=telematica', 200, ['notícia de teste']],
        ['lista pública de aniversariantes', '/index.php/aniversariantes', 200, ['Fulano de Hoje', 'Beltrano Editado', 'aniversariantes-hoje']],
        ['formulário público do PagTesouro', '/index.php/emitir-gru', 200, ['pagtesouroForm', 'Orientações de <strong>preenchimento</strong>', '52121 - 160001 (1º CTA)', 'com_pagtesouro.formulario']],
        ['página de erro 404 com o visual do template', '/index.php/pagina-que-nao-existe', 404, ['Estamos constrangidos em te ver por aqui', '1º Centro de Telemática de Área', 'error-search']],
    ]);

    $home = $bodies['página inicial'];

    // ---- Template
    Report::check(substr_count($home, '<title>') === 1, 'template: um único <title> na página', (string) substr_count($home, '<title>'));
    Report::check(str_contains($home, 'src="/images/joomla_black.png"') && !str_contains($home, 'joomlaImage://'), 'template: imagem do gerenciador de mídia sai com caminho limpo');
    Report::check(preg_match('/new cookieNoticeJS\((\{.*?\})\);/s', $home, $m) === 1 && (json_decode($m[1], true)['noticeText'] ?? '') === 'Usamos cookies d\'água e "aspas". <b>Concorda?</b>', 'template: aviso de cookies aceita aspas e apóstrofos na mensagem');
    Report::check(str_contains($home, 'orgaoDestinatario=26&amp;tipo=1'), 'template: links do Fala.BR com o código SIORG');
    Report::check(str_contains($home, 'https://www.instagram.com/perfil_da_om') && str_contains($home, 'https://www.youtube.com/@canal_da_om'), 'template: redes sociais configuradas aparecem');
    Report::check(str_contains($home, 'id="navigation"') && !str_contains($home, 'header-navigation2'), 'template: menu vertical sem o script do menu horizontal');
    Report::check(str_contains($home, '<strong>Texto legal de teste</strong>') && str_contains($home, '<em>Rodapé do menu</em>'), 'template: textos do rodapé e do menu');

    // ---- Layouts do template para módulos do núcleo
    $markers = [
        'menu principal multinível (menuprincipal)' => 'menu-folder',
        'links do menu (linksmenu)'                 => 'fa-external-link-square-alt',
        'acesso rápido (acessorapido)'              => 'Acesso Rápido',
        'mapa do site (mapadosite)'                 => 'br-item header',
        'cartões (cards)'                           => 'br-card h-fixed hover',
        'busca (mod_finder)'                        => 'id="main-searchbox"',
        'destaques (mod_articles_news)'             => 'newsflash-container',
        'últimas (mod_articles_latest)'             => 'latestnews-item',
    ];

    foreach ($markers as $name => $marker) {
        Report::check(str_contains($home, $marker), "layout do template: $name");
    }

    Report::check(substr_count($home, 'joomla_black.png') >= 4, 'layouts do template: banners e logos do menu exibem as imagens');

    // ---- Módulos do pacote
    Report::check(str_contains($home, 'Aniversariantes do mês') && preg_match('~birthday-data birthday-today">\s*3º Sgt Fulano de Hoje - ' . gmdate('d/m') . ' 🎉~u', $home) === 1, 'mod_aniver: aniversariante de hoje com data e destaque');
    Report::check(preg_match('~Fulano de Hoje.*Beltrano Editado|Beltrano Editado.*Fulano de Hoje~s', $home) === 1 && !str_contains($home, 'Sicrano'), 'mod_aniver: lista só os aniversariantes do mês');
    Report::check(str_contains($home, 'Links Úteis') && str_contains($home, 'href="https://www.eb.mil.br" class="link-link" target="_blank" rel="noopener noreferrer"') && str_contains($home, 'fas fa-globe tema-azul-third'), 'mod_links: cartão com ícone, tema e nova aba');
    Report::check(str_contains($home, 'fas fa-anchor tema-azul-third') && preg_match('~href="/index\.php\?option=com_content&amp;view=featured" class="link-link">~', $home) === 1, 'mod_links: ícone personalizado e link interno na mesma aba');
    Report::check(!str_contains($home, 'javascript:alert') && !str_contains($home, 'Bloqueado'), 'mod_links: endereço "javascript:" é descartado');
    Report::check(str_contains($home, 'id="mod-imagempopup-' . $ids['modPopup'] . '"') && str_contains($home, 'data-segundos="15"') && str_contains($home, 'data-frequencia="dia"') && str_contains($home, '<p>Texto do <strong>popup</strong></p>'), 'mod_imagempopup: popup com temporizador e frequência');
    Report::check(preg_match('~<a href="https://www\.eb\.mil\.br"><img class="img-pop" src="/images/joomla_black\.png" alt="Cartaz">~', $home) === 1, 'mod_imagempopup: imagem com link e texto alternativo');
    Report::check(str_contains($home, 'data-instgrm-permalink="https://www.instagram.com/perfil_da_om/"') && str_contains($home, 'https://www.instagram.com/embed.js') && !str_contains($home, 'embedista'), 'mod_instafeed: embed oficial, sem script de terceiros');
    Report::check(preg_match('~<a href="/index\.php/noticias">\s*<div class="br-button br-button-readmore">\s*Ver todas as notícias~', $home) === 1, 'mod_readmorenews: botão leva à categoria de notícias com URL amigável');
    Report::check(str_contains($home, 'Vídeos da OM') && substr_count($home, 'youtube.com/embed/dQw4w9WgXcQ') === 2 && substr_count($home, 'youtube.com/embed/9bZkp7q19f0') === 2 && !str_contains($home, 'nao-e-youtube'), 'mod_videosiframe: vídeos (watch e youtu.be) em carrossel, endereço inválido ignorado');
    Report::check(str_contains($home, '--video-duracao: 12s') && str_contains($home, 'loading="lazy"'), 'mod_videosiframe: velocidade configurada e carregamento sob demanda');

    // ---- PagTesouro: dados enviados ao navegador
    $gru = $bodies['formulário público do PagTesouro'];
    $ok  = false;

    if (preg_match_all('~<script type="application/json" class="joomla-script-options[^"]*"[^>]*>(.*?)</script>~s', $gru, $blocks)) {
        foreach ($blocks[1] as $block) {
            $data = json_decode($block, true)['com_pagtesouro.formulario'] ?? null;

            if ($data) {
                $codes = array_column($data['uasgs'][0]['servicos'] ?? [], 'codigo');
                sort($codes);
                $ok = count($data['uasgs']) === 1 && $data['uasgs'][0]['orgao'] === '52121' && $codes === ['16014', '16015'] && str_starts_with($data['portal'], 'https://pagtesouro.tesouro.gov.br/');
            }
        }
    }

    Report::check($ok, 'PagTesouro: página entrega a UASG e só os serviços publicados');
    Report::check(str_contains($gru, 'data-unica="1"') && str_contains($gru, 'selected'), 'PagTesouro: UASG única já vem selecionada');

    // ---- Arquivos estáticos referenciados existem
    $assets = [];

    foreach ([$home, $gru] as $html) {
        if (preg_match_all('~(?:href|src)="(/media/(?:templates/site/govbr-ds|mod_[a-z]+|com_[a-z]+)/[^"?#]+)~', $html, $found)) {
            $assets = array_merge($assets, $found[1]);
        }
    }

    $assets = array_values(array_unique($assets));
    $broken = array_filter($assets, static fn (string $asset): bool => $site->get($asset)['status'] !== 200);
    Report::check(count($assets) >= 12 && !$broken, 'todos os ' . count($assets) . ' arquivos CSS/JS/imagem referenciados existem', implode(', ', $broken));

    checkPhpLog($site, 'páginas do site');
}

/** Variações de configuração do template que mudam o HTML gerado. */
function checkTemplateVariants(Context $c, array $ids): void
{
    $site   = $c->site;
    $stored = json_decode((string) $site->value('SELECT params FROM jos_template_styles WHERE id = ' . $ids['style']), true);

    Report::context('Variações do template');

    $apply = static function (array $changes) use ($site, $stored, $ids): string {
        $site->sql('UPDATE jos_template_styles SET params = ' . q(json_encode($changes + $stored, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) . ' WHERE id = ' . $ids['style']);

        return $site->get('/')['body'];
    };

    $home = $apply(['tipo-menu' => '1', 'tema' => '', 'largura' => '1', 'cor_footer' => '1', 'cookies_aviso' => '0', 'redes_sociais' => '0', 'componente_atendimentocidadao' => '0', 'title_page' => 'Título fixo do teste', 'logo_comlink' => '2', 'logo_link' => 'https://www.eb.mil.br']);

    Report::check(!errorsInBody($home), 'menu horizontal, tema verde, largura fluida e rodapé claro: página sem erros', implode('; ', errorsInBody($home)));
    Report::check(str_contains($home, 'id="header-navigation2"') && str_contains($home, "getElementById('menu-box-horizontal')") && substr_count($home, 'id="header-navigation2"') === 1, 'menu horizontal: botão único e script presente');
    Report::check(str_contains($home, 'tema--main') && str_contains($home, 'container-fluid') && str_contains($home, 'br-footer inverted'), 'tema verde, largura fluida e rodapé claro aplicados');
    Report::check(!str_contains($home, 'cookieNoticeJS') && !str_contains($home, 'social-network') && !str_contains($home, 'ouvidoria-acessoinformacao'), 'cookies, redes sociais e ouvidoria desligados somem da página');
    Report::check(preg_match('~<title>Título fixo do teste</title>~', $home) === 1 && substr_count($home, '<title>') === 1, 'título fixo substitui o título da página sem duplicar');
    Report::check(str_contains($home, '<a href="https://www.eb.mil.br"><img src="/images/joomla_black.png"'), 'logo com link personalizado');

    // Estilo nunca salvo (parâmetros vazios): a página precisa funcionar com os padrões
    $site->sql("UPDATE jos_template_styles SET params = '{}' WHERE id = " . $ids['style']);
    $home = $site->get('/')['body'];
    Report::check(!errorsInBody($home) && str_contains($home, 'Template básico') && str_contains($home, '/media/templates/site/govbr-ds/img/logo.svg'), 'estilo recém-instalado (sem nada configurado) funciona com os padrões', implode('; ', errorsInBody($home)));

    $apply([]);
    checkPhpLog($site, 'variações do template');
}

// =============================================================================================
// Cenários
// =============================================================================================

/** Instalação limpa, uso completo e desinstalação. Com $portuguese, o painel e o site ficam em pt-BR. */
function scenarioFresh(Context $c, ?string $coreVersion, bool $portuguese = false): void
{
    $site = $c->site;

    Report::context('Preparação');
    prepare($site);

    if ($coreVersion !== null) {
        coreUpdate($c, $coreVersion);
    }

    $joomla6 = version_compare($site->version(), '6.0.0', '>=');

    Report::context('Instalação do pacote');
    installPackage($c, $c->package, 'pacote instalado pela linha de comando do Joomla');
    checkInstalled($c, $c->version());

    $sites = $site->sql("SELECT s.name FROM jos_update_sites s JOIN jos_update_sites_extensions x ON x.update_site_id = s.update_site_id JOIN jos_extensions e ON e.extension_id = x.extension_id WHERE s.location LIKE '%astatonn/ids-joomla-eb%'");
    Report::check(count($sites) === 1 && $sites[0][0] === 'IDS Joomla EB', 'um único site de atualização registrado (o do pacote)', json_encode($sites, JSON_UNESCAPED_UNICODE));
    Report::check($site->value('SELECT COUNT(*) FROM jos_schemas WHERE version_id = ' . q($c->version())) === '2', 'versão do esquema de banco registrada para os dois componentes');
    checkPhpLog($site, 'instalação');

    if ($portuguese) {
        Report::context('Idioma do painel e do site: português do Brasil');
        enablePortuguese($c);
    }

    $ids = seedSite($c);
    $site->adminLogin();
    exerciseComponents($c);
    checkFrontend($c, $ids);

    // O link "javascript:" serviu para testar o descarte na exibição; o formulário (corretamente) o recusaria.
    $links = json_decode((string) $site->value('SELECT params FROM jos_modules WHERE id = ' . $ids['modLinks']), true);
    unset($links['links']['links2']);
    // Ao salvar, o próprio Joomla completa caminhos internos com a raiz do site
    $links['links']['links1']['url'] = '/index.php?option=com_content&view=featured';
    $site->sql('UPDATE jos_modules SET params = ' . q(json_encode($links, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) . ' WHERE id = ' . $ids['modLinks']);

    checkAdminScreens($c, $ids);
    checkFrontend($c, $ids, 'Site público depois de salvar tudo pelos formulários');
    checkTemplateVariants($c, $ids);

    if ($joomla6) {
        Report::context('Joomla 6 sem o plugin de compatibilidade');
        $site->sql("UPDATE jos_extensions SET enabled = 0 WHERE type = 'plugin' AND folder = 'behaviour' AND element = 'compat6'");
        $site->cli('cache:clean');
        checkFrontend($c, $ids, 'Site público com o plugin "Backward Compatibility 6" desativado');
        $site->adminLogin();
        checkPages($site, [
            ['administrador: Aniversariantes sem o plugin de compatibilidade', '/administrator/index.php?option=com_aniversariantes', 200, ['Fulano de Hoje']],
            ['administrador: PagTesouro sem o plugin de compatibilidade', '/administrator/index.php?option=com_pagtesouro&view=servicos', 200, ['Taxa de inscrição']],
        ]);
        checkPhpLog($site, 'sem o plugin de compatibilidade');
        $site->sql("UPDATE jos_extensions SET enabled = 1 WHERE type = 'plugin' AND folder = 'behaviour' AND element = 'compat6'");
    }

    Report::context('Reinstalação por cima e desinstalação');
    installPackage($c, $c->package, 'reinstalar o mesmo pacote por cima (method="upgrade")');
    checkInstalled($c, $c->version());
    Report::check($site->value("SELECT COUNT(*) FROM jos_aniversariantes_") === '2' && $site->value('SELECT COUNT(*) FROM jos_pagtesouro_servicos') === '3', 'reinstalar não apaga os dados dos componentes');
    Report::check($site->get('/')['status'] === 200, 'site no ar depois da reinstalação');

    // Volta o template padrão do Joomla para poder remover o nosso
    $site->sql("UPDATE jos_template_styles SET home = '0' WHERE client_id = 0; UPDATE jos_template_styles SET home = '1' WHERE template = 'cassiopeia' AND client_id = 0 LIMIT 1;");
    [$code, $output] = $site->cli('extension:remove ' . extensionId($site, 'package', 'pkg_ids_joomla_eb'));
    settle($c);

    $left   = (int) $site->value("SELECT COUNT(*) FROM jos_extensions WHERE element IN ('pkg_ids_joomla_eb', '" . TEMPLATE . "', 'com_aniversariantes', 'com_pagtesouro', '" . implode("', '", OUR_MODULES) . "')");
    $tables = (int) $site->value("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name IN ('jos_aniversariantes_', 'jos_pagtesouro_uasgs', 'jos_pagtesouro_servicos')");
    [, $dirs] = $site->tryExec('ls -d templates/' . TEMPLATE . ' media/templates/site/govbr-ds components/com_aniversariantes components/com_pagtesouro administrator/components/com_aniversariantes administrator/components/com_pagtesouro modules/mod_aniver modules/mod_links media/com_pagtesouro media/mod_imagempopup 2>/dev/null');

    Report::check($code === 0 && $left === 0, 'desinstalar o pacote remove as 10 extensões', "restaram $left; " . trim(substr($output, -200)));
    Report::check($tables === 0 && trim($dirs) === '', 'desinstalar remove tabelas e pastas', "tabelas: $tables; pastas: " . trim($dirs));
    Report::check($site->get('/')['status'] === 200, 'site no ar depois da desinstalação');
    checkPhpLog($site, 'reinstalação e desinstalação');
}

/** Dados de um site "antigo": pacote 1.0.0 em uso, com módulos configurados no formato antigo. */
function seedLegacySite(Context $c): array
{
    $site = $c->site;

    $styleParams = ['texto_titulo' => 'OM de Teste', 'texto_subtitulo' => 'Subtítulo antigo', 'tema' => 'vermelho', 'largura' => '0', 'tipo-menu' => '0', 'tipomenu' => '0', 'cor_footer' => '0', 'logo_comlink' => '0', 'cabecalhomenu' => '1',
        'redes_sociais' => '1', 'redes_instagram' => 'https://www.instagram.com/om_antiga', 'componente_atendimentocidadao' => '1', 'atendimentocidadao_siorg' => '26', 'cookies_aviso' => '0', 'imagem_footer_brasil' => '1', 'imagem_footer_acesso' => '1',
        'texto_licenca' => 'Rodapé antigo'];

    $legacy = [
        'mod_aniver'       => ['content-top', ['show-birthdays' => '1']],
        'mod_links'        => ['content-bottom', ['compact-links' => '0', 'section-name' => 'Links Antigos', 'link-amount' => '3',
            'url-link-1' => 'https://www.eb.mil.br', 'name-link-1' => 'Exército', 'icon-link-1' => 'fas fa-star',
            'url-link-2' => 'https://www.gov.br', 'name-link-2' => 'Gov.br', 'icon-link-2' => 'fas fa-dragon',
            'url-link-3' => 'https://www.defesa.gov.br', 'name-link-3' => 'Defesa', 'icon-link-3' => '',
            'url-link-4' => 'https://nao-deve-aparecer.example', 'name-link-4' => 'Sobra', 'icon-link-4' => '']],
        'mod_videosiframe' => ['content-bottom', ['video-amount' => '2', 'video-link-1' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'video-link-2' => 'https://www.youtube.com/watch?v=9bZkp7q19f0', 'video-link-3' => 'https://www.youtube.com/watch?v=aaaaaaaaaaa', 'video-width' => '480', 'video-height' => '270']],
        'mod_readmorenews' => ['content-top', ['showbuttonreademore' => 'yes', 'menu-readmore' => '101']],
        'mod_imagempopup'  => ['main-bottom', ['imagem' => 'images/joomla_black.png', 'temporizador' => '1', 'segundos' => '7', 'popuptext' => '<p>Aviso antigo</p>']],
        'mod_instafeed'    => ['main-bottom', ['profile' => 'om_antiga']],
    ];

    $sql = "UPDATE jos_template_styles SET home = '0' WHERE client_id = 0;"
        . " UPDATE jos_template_styles SET home = '1', params = " . q(json_encode($styleParams, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) . ' WHERE template = ' . q(TEMPLATE) . ';';

    foreach ($legacy as $module => [$position, $params]) {
        $sql .= ' UPDATE jos_modules SET published = 1, access = 1, showtitle = 0, position = ' . q($position) . ', params = ' . q(json_encode($params, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) . ' WHERE module = ' . q($module) . ';'
            . ' INSERT IGNORE INTO jos_modules_menu (moduleid, menuid) SELECT id, 0 FROM jos_modules WHERE module = ' . q($module) . ';';
    }

    $sql .= " INSERT INTO jos_aniversariantes_ (nome, aniversario, pg) VALUES ('Antigo de Hoje', CONCAT('1990-', DATE_FORMAT(CURDATE(), '%m-%d')), '3º Sgt'), ('Antigo do Mês', CONCAT('1985-', DATE_FORMAT(CURDATE(), '%m'), '-', IF(DAY(CURDATE()) = 20, '21', '20')), 'Cap');"
        . " INSERT INTO jos_pagtesouro_uasgs (orgao, ug, descricao, state) VALUES ('52121', '160999', 'UASG Antiga', 1);"
        . " INSERT INTO jos_pagtesouro_servicos (uasg_id, codigo, descricao, state) VALUES (LAST_INSERT_ID(), '77001', 'Serviço Antigo', 1);";

    $site->sql($sql);

    return [
        'ids'   => array_column($site->sql("SELECT CONCAT(type, '/', element), extension_id FROM jos_extensions WHERE element IN ('pkg_ids_joomla_eb', '" . TEMPLATE . "', 'com_aniversariantes', 'com_pagtesouro', '" . implode("', '", OUR_MODULES) . "')"), 1, 0),
        'style' => $styleParams,
    ];
}

/** Baixa o pacote 1.0.0 publicado (o que está em produção nos sites) e o instala. */
function installLegacy(Context $c): void
{
    installPackage($c, cached(RELEASE_1_0_0, $c->cacheDir), 'pacote 1.0.0 (release publicada) instalado');
    Report::check(installedVersion($c->site, 'package', 'pkg_ids_joomla_eb') === '1.0.0', 'pacote na versão 1.0.0');
}

/** Confere um site que veio da 1.0.0 depois de receber a versão nova. */
function checkUpgraded(Context $c, array $legacy, string $label): void
{
    $site = $c->site;

    Report::context($label);
    checkInstalled($c, $c->version());

    $now     = array_column($site->sql("SELECT CONCAT(type, '/', element), extension_id FROM jos_extensions WHERE element IN ('pkg_ids_joomla_eb', '" . TEMPLATE . "', 'com_aniversariantes', 'com_pagtesouro', '" . implode("', '", OUR_MODULES) . "')"), 1, 0);
    $changed = array_diff_assoc($legacy['ids'], $now);
    Report::check(!$changed && count($now) === 10, 'as extensões foram atualizadas no lugar (mesmos identificadores, nada duplicado)', json_encode($changed));

    $style = json_decode((string) $site->value('SELECT params FROM jos_template_styles WHERE template = ' . q(TEMPLATE)), true);
    Report::check($style == $legacy['style'] && $site->value('SELECT home FROM jos_template_styles WHERE template = ' . q(TEMPLATE)) === '1', 'configurações do estilo do template preservadas e template continua padrão');

    Report::check($site->value('SELECT COUNT(*) FROM jos_aniversariantes_') === '2' && $site->value("SELECT CONCAT(orgao, '/', ug, '/', descricao) FROM jos_pagtesouro_uasgs") === '52121/160999/UASG Antiga' && $site->value('SELECT codigo FROM jos_pagtesouro_servicos') === '77001', 'dados de Aniversariantes e PagTesouro preservados');

    $links = json_decode((string) $site->value("SELECT params FROM jos_modules WHERE module = 'mod_links'"), true);
    Report::check(
        array_column($links['links'] ?? [], 'nome') === ['Exército', 'Gov.br', 'Defesa'] && !isset($links['link-amount'], $links['url-link-1'])
        && ($links['links']['links0']['icone'] ?? '') === 'fas fa-star' && ($links['links']['links1']['icone'] ?? '') === 'custom' && ($links['links']['links1']['icone_custom'] ?? '') === 'fas fa-dragon'
        && ($links['section-name'] ?? '') === 'Links Antigos',
        'mod_links: os 3 links numerados viraram lista (ícone fora da lista vira "Outro ícone")',
        json_encode($links, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
    );

    $videos = json_decode((string) $site->value("SELECT params FROM jos_modules WHERE module = 'mod_videosiframe'"), true);
    Report::check(count($videos['videos'] ?? []) === 2 && !isset($videos['video-amount'], $videos['video-link-1']) && (string) ($videos['video-width'] ?? '') === '480', 'mod_videosiframe: os 2 vídeos numerados viraram lista', json_encode($videos, JSON_UNESCAPED_SLASHES));

    // Arquivos das versões antigas que não podem sobrar
    $obsolete = [
        'administrator/components/com_aniversariantes/src/Service/Html/ANIVERSARIANTES.php', 'administrator/components/com_aniversariantes/src/Field', 'administrator/components/com_aniversariantes/presets',
        'components/com_aniversariantes/src/Field', 'components/com_aniversariantes/src/Controller/AniversarianteController.php', 'components/com_aniversariantes/forms',
        'administrator/components/com_pagtesouro/pagtesouro.php', 'administrator/components/com_pagtesouro/controller.php', 'administrator/components/com_pagtesouro/views', 'administrator/components/com_pagtesouro/models',
        'administrator/components/com_pagtesouro/tables', 'administrator/components/com_pagtesouro/helpers', 'components/com_pagtesouro/pagtesouro.php', 'components/com_pagtesouro/views', 'components/com_pagtesouro/controllers',
        'language/pt-BR/pt-BR.com_pagtesouro.ini', 'administrator/language/pt-BR/pt-BR.com_pagtesouro.sys.ini',
        'modules/mod_aniver/mod_aniver.php', 'modules/mod_links/mod_links.php', 'modules/mod_imagempopup/mod_imagempopup.php', 'modules/mod_readmorenews/mod_readmorenews.php',
        'modules/mod_instafeed/mod_instafeed.php', 'modules/mod_instafeed/js', 'modules/mod_videosiframe/mod_videosiframe.php', 'modules/mod_videosiframe/js', 'modules/mod_videosiframe/css',
        'media/com_aniversariantes/js',
    ];
    [, $found] = $site->tryExec('ls -d ' . implode(' ', $obsolete) . ' 2>/dev/null');
    Report::check(trim($found) === '', 'arquivos obsoletos das versões 1.x removidos do servidor', str_replace("\n", ', ', trim($found)));

    [, $php] = $site->tryExec("grep -rlE 'use Joomla.CMS.MVC.Model.DatabaseAwareTrait|defined\(.JPATH_PLATFORM|JHtml::|JFactory::|JText::' components/com_aniversariantes components/com_pagtesouro administrator/components/com_aniversariantes administrator/components/com_pagtesouro modules/mod_aniver modules/mod_links templates/" . TEMPLATE . ' 2>/dev/null');
    Report::check(trim($php) === '', 'nenhum arquivo instalado usa APIs removidas do Joomla 6', str_replace("\n", ', ', trim($php)));

    $sites    = $site->sql("SELECT e.element, s.location FROM jos_update_sites s JOIN jos_update_sites_extensions x ON x.update_site_id = s.update_site_id JOIN jos_extensions e ON e.extension_id = x.extension_id WHERE e.element IN ('pkg_ids_joomla_eb', '" . TEMPLATE . "', 'com_aniversariantes', 'com_pagtesouro', '" . implode("', '", OUR_MODULES) . "')");
    $children = array_filter($sites, static fn (array $row): bool => $row[0] !== 'pkg_ids_joomla_eb');
    $creator  = array_filter($sites, static fn (array $row): bool => str_contains($row[1], 'component-creator'));
    Report::check(count($sites) >= 1 && !$children && !$creator, 'sites de atualização: só o do pacote (o do Component Creator foi removido)', json_encode($sites, JSON_UNESCAPED_SLASHES));

    // Páginas
    $bodies = checkPages($site, [['página inicial depois da atualização', '/', 200, ['OM de Teste', 'tema-vermelho-main', 'Links Antigos', 'https://www.instagram.com/om_antiga']]]);
    $home   = $bodies['página inicial depois da atualização'];

    Report::check(preg_match('~birthday-today">\s*3º Sgt Antigo de Hoje - ' . gmdate('d/m') . ' 🎉~u', $home) === 1 && str_contains($home, 'Cap Antigo do Mês'), 'mod_aniver continua listando os aniversariantes cadastrados');
    Report::check(str_contains($home, '>Exército<') && str_contains($home, 'fas fa-dragon tema-vermelho-third') && str_contains($home, '>Defesa<') && !str_contains($home, 'nao-deve-aparecer'), 'mod_links exibe os mesmos links de antes');
    Report::check(substr_count($home, 'youtube.com/embed/') === 4 && str_contains($home, 'width="480"'), 'mod_videosiframe exibe os mesmos vídeos de antes');
    Report::check(str_contains($home, 'news-readmore') && str_contains($home, 'Leia Mais'), 'mod_readmorenews continua exibindo o botão para o item de menu escolhido');
    Report::check(str_contains($home, '<p>Aviso antigo</p>') && str_contains($home, 'data-segundos="7"'), 'mod_imagempopup mantém texto e temporizador');
    Report::check(str_contains($home, 'instagram.com/om_antiga/'), 'mod_instafeed mantém o perfil');

    checkPages($site, [
        ['link antigo do PagTesouro (index.php?option=com_pagtesouro&view=formulario)', '/index.php?option=com_pagtesouro&view=formulario', 200, ['pagtesouroForm', 'UASG Antiga']],
        ['link antigo de Aniversariantes (index.php?option=com_aniversariantes)', '/index.php?option=com_aniversariantes&view=aniversariantes', 200, ['Antigo de Hoje']],
    ]);

    $site->adminLogin();
    checkPages($site, [
        ['administrador: painel inicial', '/administrator/index.php', 200, []],
        ['administrador: Aniversariantes', '/administrator/index.php?option=com_aniversariantes', 200, ['Antigo de Hoje', 'Antigo do Mês']],
        ['administrador: PagTesouro (UASGs)', '/administrator/index.php?option=com_pagtesouro', 200, ['UASG Antiga']],
        ['administrador: PagTesouro (serviços)', '/administrator/index.php?option=com_pagtesouro&view=servicos', 200, ['Serviço Antigo', '77001']],
    ]);

    checkPhpLog($site, $label);
}

/** O caminho recomendado: atualizar o pacote no Joomla 5.4 e só depois ir para o Joomla 6. */
function scenarioUpgrade(Context $c, string $joomla6First, string $joomla6Latest): void
{
    $site = $c->site;

    Report::context('Site antigo: Joomla 5.4.1 com o pacote 1.0.0');
    prepare($site);
    installLegacy($c);
    $legacy = seedLegacySite($c);
    Report::check($site->get('/')['status'] === 200, 'site antigo funcionando no Joomla 5.4.1');

    Report::context('Atualização do pacote ainda no Joomla 5.4.1');
    $site->mark();
    installPackage($c, $c->package, 'pacote novo instalado por cima do 1.0.0');
    checkUpgraded($c, $legacy, 'Depois de atualizar o pacote (Joomla 5.4.1)');

    Report::context("Atualização do Joomla para $joomla6First");
    coreUpdate($c, $joomla6First);
    checkUpgraded($c, $legacy, "Depois de atualizar o Joomla para $joomla6First");

    if ($joomla6Latest !== $joomla6First) {
        Report::context("Atualização do Joomla para $joomla6Latest");
        coreUpdate($c, $joomla6Latest);
        checkUpgraded($c, $legacy, "Depois de atualizar o Joomla para $joomla6Latest");
    }
}

/** O caso do relato: Joomla atualizado para o 6 com o pacote 1.0.0 ainda instalado. */
function scenarioRecovery(Context $c, string $joomla6): void
{
    $site = $c->site;

    Report::context('Site antigo: Joomla 5.4.1 com o pacote 1.0.0');
    prepare($site);
    installLegacy($c);
    $legacy = seedLegacySite($c);
    Report::check($site->get('/')['status'] === 200, 'site antigo funcionando no Joomla 5.4.1');

    Report::context("Reprodução do erro: Joomla atualizado para $joomla6 sem atualizar o pacote");
    coreUpdate($c, $joomla6);
    $home   = $site->get('/');
    $errors = implode("\n", $site->phpErrors());
    Report::check($home['status'] === 500, 'o site passa a responder erro 500', 'HTTP ' . $home['status']);
    Report::check(preg_match('~Trait "Joomla\\\\+CMS\\\\+MVC\\\\+Model\\\\+DatabaseAwareTrait" not found in \S+com_aniversariantes/src/Service/Html/ANIVERSARIANTES\.php on line 25~', $errors) === 1, 'o log mostra exatamente o erro relatado (Trait DatabaseAwareTrait not found … ANIVERSARIANTES.php linha 25)', substr($errors, 0, 300));
    Report::check($site->value("SELECT enabled FROM jos_extensions WHERE type = 'plugin' AND element = 'compat6'") === '1', 'o plugin "Backward Compatibility 6" está ativo e mesmo assim o erro acontece');

    Report::context('Paliativo imediato: despublicar o módulo de aniversariantes');
    $site->sql("UPDATE jos_modules SET published = 0 WHERE module = 'mod_aniver'");
    Report::check($site->get('/')['status'] === 200, 'com o módulo despublicado o site volta a abrir');
    $site->sql("UPDATE jos_modules SET published = 1 WHERE module = 'mod_aniver'");
    Report::check($site->get('/')['status'] === 500, 'republicado, o erro volta (confirma a causa)');

    Report::context('Correção definitiva: instalar o pacote novo pelo painel do Joomla');
    $site->adminLogin();
    $page = $site->get('/administrator/index.php?option=com_installer&view=install');
    Report::check($page['status'] === 200 && !has($page['body'], 'name="passwd"'), 'o administrador continua acessível com o site quebrado (tela Extensões: Instalar)');

    $result   = $site->http('POST', '/administrator/index.php?option=com_installer&view=install', [
        'install_package' => new CURLFile($c->package, 'application/zip', 'pkg_ids_joomla_eb.zip'),
        'installtype'     => 'upload',
        'task'            => 'install.install',
        $site->token()    => '1',
    ]);
    $messages = joomlaMessages($result['body']);
    Report::check(joomlaFailures($result['body']) === '' && str_contains(json_encode($messages, JSON_UNESCAPED_UNICODE), 'atualizado com sucesso'), 'envio do pacote pelo painel conclui com a mensagem de sucesso', joomlaErrors($result['body']));
    settle($c);
    $site->mark();

    Report::check($site->get('/')['status'] === 200, 'o site volta a responder 200');
    checkUpgraded($c, $legacy, "Site recuperado (Joomla $joomla6)");
}

/** Avisos de atualização: servidor de atualização local que imita o GitHub. */
function scenarioUpdateNotices(Context $c, string $joomla6): void
{
    $site    = $c->site;
    $server  = 'idseb-it-updates';
    $version = $c->version();

    Report::context('Servidor de atualização de teste (cópia de updates/ e dist/)');
    prepare($site);

    $c->docker->remove($server);
    $c->docker->mustRun(['run', '-d', '--name', $server, '--network', $c->network, '--entrypoint', 'sh', $site->image, '-c', 'mkdir -p /srv && exec php -S 0.0.0.0:80 -t /srv']);
    $c->extraContainers[] = $server;

    $put = static function (string $local, string $remote) use ($c, $server): void {
        $c->docker->mustRun(['exec', '-i', $server, 'sh', '-c', 'mkdir -p ' . escapeshellarg(dirname($remote)) . ' && cat > ' . escapeshellarg($remote)], $local);
    };

    foreach (glob($c->root . '/dist/*.zip') as $zip) {
        $put($zip, '/srv/dist/' . basename($zip));
    }

    // Os XML são os do repositório; só o endereço de download passa a apontar para o servidor local.
    foreach (glob($c->root . '/updates/*.xml') as $xml) {
        $tmp = tempnam(sys_get_temp_dir(), 'idseb');
        file_put_contents($tmp, str_replace(
            ['https://github.com/astatonn/ids-joomla-eb/releases/download/v' . $version . '/', 'https://raw.githubusercontent.com/astatonn/ids-joomla-eb/master/updates/'],
            ["http://$server/dist/", "http://$server/updates/"],
            file_get_contents($xml)
        ));
        $put($tmp, '/srv/updates/' . basename($xml));
        unlink($tmp);
    }

    [, $probe] = $site->tryExec("php -r 'echo strlen((string) @file_get_contents(\"http://$server/updates/ids-joomla-eb-updates.xml\"));'");
    Report::check((int) $probe > 500, 'servidor de atualização acessível a partir do site');

    $redirect = static function () use ($site, $server): void {
        $site->sql("UPDATE jos_update_sites SET enabled = 1, last_check_timestamp = 0, location = REPLACE(location, 'https://raw.githubusercontent.com/astatonn/ids-joomla-eb/master/updates/', 'http://$server/updates/') WHERE location LIKE '%astatonn/ids-joomla-eb%'");
    };

    // ------------------------------------------------------------------ pacote
    Report::context('Site com o pacote 1.0.0: o Joomla avisa que existe versão nova');
    installLegacy($c);
    $legacy = seedLegacySite($c);
    $redirect();

    [$code, $output] = $site->cli('update:extensions:check');
    $pending         = $site->sql("SELECT u.update_id, u.element, u.type, u.version FROM jos_updates u WHERE u.element LIKE '%ids_joomla_eb%' OR u.element LIKE 'mod_%' OR u.element LIKE 'com_%'");
    Report::check($code === 0 && count($pending) === 1 && $pending[0][1] === 'pkg_ids_joomla_eb' && $pending[0][3] === $version, "verificação de atualizações encontra o pacote $version (um único aviso)", json_encode($pending) . ' ' . trim(substr($output, -200)));

    $site->adminLogin();
    $page = $site->get('/administrator/index.php?option=com_installer&view=update');
    Report::check(has($page['body'], 'IDS Joomla EB (pacote completo)') && has($page['body'], $version) && !errorsInBody($page['body']), 'tela Sistema → Atualizar → Extensões lista o pacote com a versão nova');

    // Aviso no painel inicial (ícone rápido de atualizações de extensões)
    $quick = $site->get('/administrator/index.php?option=com_installer&view=update&task=update.ajax&' . $site->token() . '=1&eid=0&skip=0');
    $list  = json_decode($quick['body'], true);
    Report::check(is_array($list) && in_array('pkg_ids_joomla_eb', array_column($list, 'element'), true), 'painel inicial: o aviso "atualizações disponíveis" inclui o pacote', substr($quick['body'], 0, 200));

    // Changelog exibido pelo Joomla
    $log = $site->get('/administrator/index.php?option=com_installer&task=manage.loadChangelogRaw&eid=' . extensionId($site, 'package', 'pkg_ids_joomla_eb') . '&source=update&format=raw');
    Report::check($log['status'] === 200 && str_contains($log['body'], 'DatabaseAwareTrait') && !errorsInBody($log['body']), 'botão Changelog mostra as novidades da versão', substr(strip_tags($log['body']), 0, 200));

    // Atualizar pelo próprio Joomla
    $form = HtmlForm::fromHtml($page['body'], 'adminForm');
    $form->set('cid[]', $pending[0][0])->set('boxchecked', '1');
    $result = submit($site, $form, 'update.update');
    settle($c);
    $text   = json_encode(joomlaMessages($result['body']), JSON_UNESCAPED_UNICODE);
    Report::check(joomlaFailures($result['body']) === '' && str_contains($text, 'atualizado com sucesso'), 'botão Atualizar baixa e instala o pacote sem erros', joomlaFailures($result['body']) . ' ' . substr($text, 0, 300));
    Report::check(stripos($text, 'checksum') === false, 'o checksum do arquivo baixado confere com o anunciado (nenhum aviso de checksum)', substr($text, 0, 300));
    $site->mark();
    checkUpgraded($c, $legacy, 'Depois de atualizar pelo aviso do Joomla');
    Report::check($site->value("SELECT COUNT(*) FROM jos_updates WHERE element = 'pkg_ids_joomla_eb'") === '0', 'depois de atualizar, o aviso some');

    // ------------------------------------------------------------------ verificação antes do Joomla 6
    Report::context("Verificação pré-atualização do Joomla $joomla6");
    $redirect();
    $site->adminLogin();
    $pkg   = extensionId($site, 'package', 'pkg_ids_joomla_eb');
    $check = $site->get('/administrator/index.php?option=com_joomlaupdate&task=update.fetchExtensionCompatibility&extension-id=' . $pkg . '&joomla-target-version=' . $joomla6 . '&joomla-current-version=' . $site->version() . '&extension-version=' . $version . '&' . $site->token() . '=1');
    $data  = json_decode($check['body'], true)['data'] ?? null;
    // state 1 = há versão compatível com o Joomla de destino; resultGroup 3 = a versão instalada já é compatível
    Report::check(is_array($data) && (int) ($data['upgradeCompatibilityStatus']['state'] ?? 0) === 1 && ($data['upgradeCompatibilityStatus']['compatibleVersion'] ?? '') === $version && (int) ($data['resultGroup'] ?? 0) === 3, "o Joomla reconhece o pacote como compatível com o Joomla $joomla6", substr($check['body'], 0, 400));

    // ------------------------------------------------------------------ extensão avulsa
    Report::context('Extensão instalada avulsa (sem o pacote): recebe o próprio aviso');
    $site->sql("UPDATE jos_template_styles SET home = '0' WHERE client_id = 0; UPDATE jos_template_styles SET home = '1' WHERE template = 'cassiopeia' AND client_id = 0 LIMIT 1;");
    $site->cli('extension:remove ' . $pkg);
    settle($c);
    Report::check(installedVersion($site, 'module', 'mod_links') === null, 'pacote removido para o teste avulso');

    // Versão "antiga" do módulo, gerada a partir da atual só trocando o número da versão
    $old = sys_get_temp_dir() . '/idseb-mod_links-old-' . getmypid() . '.zip';
    copy($c->root . '/packages/mod_links.zip', $old);
    $zip = new ZipArchive();
    $zip->open($old);
    $zip->addFromString('mod_links.xml', str_replace('<version>' . $version . '</version>', '<version>3.0.9</version>', $zip->getFromName('mod_links.xml')));
    $zip->close();
    installPackage($c, $old, 'módulo Links 3.0.9 instalado sozinho');
    unlink($old);

    $own = $site->sql("SELECT s.name, s.location FROM jos_update_sites s JOIN jos_update_sites_extensions x ON x.update_site_id = s.update_site_id JOIN jos_extensions e ON e.extension_id = x.extension_id WHERE e.element = 'mod_links'");
    Report::check(count($own) === 1 && str_ends_with($own[0][1], '/updates/mod_links-updates.xml'), 'a extensão avulsa registra o próprio site de atualização', json_encode($own, JSON_UNESCAPED_SLASHES));

    $redirect();
    $site->cli('update:extensions:check');
    $pending = $site->sql("SELECT update_id, element, version FROM jos_updates WHERE element = 'mod_links'");
    Report::check(count($pending) === 1 && $pending[0][2] === $version, "o Joomla avisa que o módulo Links tem a versão $version", json_encode($pending));

    $site->adminLogin();
    $page = $site->get('/administrator/index.php?option=com_installer&view=update');
    $form = HtmlForm::fromHtml($page['body'], 'adminForm');
    $form->set('cid[]', $pending[0][0] ?? '0')->set('boxchecked', '1');
    $result = submit($site, $form, 'update.update');
    settle($c);
    $text = json_encode(joomlaMessages($result['body']), JSON_UNESCAPED_UNICODE);
    Report::check(joomlaFailures($result['body']) === '' && stripos($text, 'checksum') === false && installedVersion($site, 'module', 'mod_links') === $version, 'atualização avulsa do módulo concluída com checksum conferido', joomlaFailures($result['body']) . ' ' . substr($text, 0, 300));

    checkPhpLog($site, 'avisos de atualização');
}
