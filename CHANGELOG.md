# Changelog

Todas as mudanças relevantes do projeto ficam registradas aqui.

O formato segue o [Keep a Changelog](https://keepachangelog.com/pt-BR/1.1.0/) e o projeto usa
[Versionamento Semântico](https://semver.org/lang/pt-BR/). Todas as extensões do pacote usam o
mesmo número de versão.

> As seções de cada versão (`### Corrigido`, `### Adicionado`, `### Alterado`, `### Removido`,
> `### Segurança`, `### Notas`) alimentam o botão **Changelog** do Joomla: o `build/build.php`
> gera `updates/changelog.xml` a partir deste arquivo. Escreva um item por linha.

## [3.1.0] - 2026-10-14

Primeira versão realmente compatível com o Joomla 6, testada em Joomla 5.4.1, 5.4.10, 6.0.1 e 6.2.0,
em inglês e com o pacote de idioma pt-BR oficial.

### Corrigido

- Aniversariantes: erro fatal no Joomla 6 que derrubava o site inteiro (`Trait "Joomla\CMS\MVC\Model\DatabaseAwareTrait" not found`). O componente foi reescrito sem nenhuma classe removida do Joomla.
- Aniversariantes: tela branca no Joomla 6 quando o plugin de compatibilidade estava desativado (o código dependia da constante `JPATH_PLATFORM`).
- Aniversariantes: a lista do mês passou a ser ordenada pelo dia do aniversário, e não pelo ano de nascimento.
- Aniversariantes: "hoje" agora segue o fuso horário configurado no site, e não o horário UTC do servidor.
- PagTesouro: o pacote da versão 3.0.0 não instalava (faltavam pastas declaradas no manifesto) e a versão 1.0.0 não funcionava no Joomla 6 (usava `JHtml`, `JFactory` e `JText`). O componente foi refeito por completo.
- Todas as extensões: voltaram a declarar `method="upgrade"`. Sem isso o Joomla recusava reinstalar ou atualizar uma extensão já instalada.
- Atualizações: os endereços de download apontavam para arquivos que nunca foram publicados (erro 404) e o template era anunciado com o identificador errado, então nenhum aviso de atualização funcionava.
- Template: erro de JavaScript em todas as páginas quando o menu era vertical, título duplicado no HTML e página de erro que dependia de arquivos externos (CDN).
- Template: a página de erro falhava (`Class "Text" not found`) ao exibir exceções encadeadas no modo de depuração.
- Template: imagens escolhidas no gerenciador de mídia agora funcionam em qualquer página, inclusive com URLs amigáveis.
- Template: a página de erro tinha um cabeçalho diferente do restante do site, sem as cores do tema. Agora usa o mesmo cabeçalho, menu e rodapé das demais páginas.
- Links: aviso de PHP exibido na página de erro (o módulo não encontrava o tema de cores do template).
- Links: o quarto link aparecia no formulário mesmo quando só três eram pedidos.
- Popup: com o temporizador desligado o popup ainda fechava sozinho.

### Adicionado

- Avisos de atualização dentro do Joomla: no painel inicial, em Sistema → Atualizar → Extensões e na verificação pré-atualização do Joomla 6, com checksum SHA-256/SHA-512 e botão Changelog.
- Template: as opções do estilo foram organizadas em seis abas (Identidade, Aparência, Menu, Rodapé, Redes sociais, Cidadão e cookies), com descrições, exemplos e campos que só aparecem quando fazem sentido.
- Template: nomes amigáveis para as posições de módulo e para os layouts alternativos de menu, banners e busca.
- Módulos: todos aparecem na lista de tipos de módulo com o prefixo "IDS Gov" e uma descrição de uso.
- Links: os links agora são uma lista que cresce conforme a necessidade (antes eram 12 campos fixos), com ícones escolhidos por nome e opção de abrir na mesma aba. Os links já cadastrados são convertidos automaticamente.
- Vídeos: lista de vídeos sem limite fixo, aceita links youtu.be, Shorts e transmissões ao vivo, velocidade ajustável e opção de carrossel parado. Os vídeos já cadastrados são convertidos automaticamente.
- Botão Leia Mais: o destino pode ser uma categoria de artigos (todas as notícias), um item de menu ou um endereço, e o texto do botão é configurável.
- Aniversariantes (módulo): escolha do período (mês, hoje ou próximos dias), título configurável e opção de ocultar o módulo quando não houver aniversariantes.
- Aniversariantes (componente): filtros por mês e por posto/graduação, ordenação pelo calendário e tipo de item de menu "Lista de aniversariantes".
- PagTesouro: tipo de item de menu "Formulário de pagamento (GRU)", publicação/despublicação de UASGs e serviços, permissões por grupo e seleção automática quando há uma única UASG ou serviço.
- Popup: frequência de exibição (sempre, uma vez por visita ou uma vez por dia), fechamento pela tecla Esc e por clique fora da janela, link na imagem e texto alternativo. O clique fora e os botões do editor foram sugeridos por @DUGELO no PR #13.
- Código-fonte de todas as extensões em `src/`, script de build reprodutível e testes automatizados de instalação e atualização em Joomla 5.4 e 6.

### Alterado

- Requisitos: Joomla 5.4 ou superior e PHP 8.1 ou superior (o Joomla 6 exige PHP 8.3).
- Aniversariantes: a data de nascimento é digitada no formato brasileiro (dd/mm/aaaa), qualquer que seja o idioma do painel.
- Formulários: os campos de sim/não mostram "Sim" e "Não" (o pacote pt-BR oficial do Joomla exibe "Nunca" no lugar de "Não").
- PagTesouro: o formulário público não faz mais requisições ao servidor; os serviços de cada UASG são carregados junto com a página.
- PagTesouro: o CPF/CNPJ é validado antes de abrir o PagTesouro e o CNPJ alfanumérico é aceito.
- Módulos: reescritos na estrutura nativa do Joomla 5/6, o que permite criar layouts alternativos pelo template.
- Instagram: usa somente o script oficial do Instagram e aceita o perfil com @ ou o endereço completo.
- Pacote: com o pacote instalado, o Joomla mostra um único aviso de atualização (o do pacote) em vez de um por extensão.

### Removido

- Instagram: script de terceiros (embedista.com) que era carregado em todas as páginas com o módulo.
- Template: botão "Envie um Feedback" da página de erro, que não tinha nenhuma ação.
- Aniversariantes: arquivos gerados pelo Component Creator que não eram usados (campos, presets, web services de exemplo).

### Notas

- Quem está no Joomla 5.4 deve instalar esta versão ANTES de atualizar o Joomla para a versão 6.
- Quem já atualizou para o Joomla 6 e está com o site fora do ar encontra o passo a passo de recuperação no arquivo MIGRATION.md do repositório.
- O plugin "Behaviour - Backward Compatibility 6" não é mais necessário para estas extensões.

## [3.0.0] e [2.0.0] - 2025-10-27 (não publicadas)

Estas versões existiram apenas no repositório (tags `v2.0.0` e `v3.0.0`), sem release. Elas
alteraram manifestos e documentação, mas o código não chegou a ser portado para o Joomla 6: o
componente Aniversariantes continuava usando uma classe removida, o PagTesouro "3.0" estava
incompleto e os endereços de atualização apontavam para arquivos inexistentes. Foram substituídas
pela 3.1.0.

## [1.0.0] - 2025-04-08

Primeira versão distribuída como pacote único, para Joomla 4 e 5.

- Template IDS Gov baseado no Padrão Digital de Governo, com temas verde, azul, amarelo e vermelho.
- Componentes Aniversariantes e PagTesouro.
- Módulos Aniversariantes, Popup de imagem, Feed do Instagram, Links, Leia Mais e Vídeos.

[3.1.0]: https://github.com/astatonn/ids-joomla-eb/releases/tag/v3.1.0
[1.0.0]: https://github.com/astatonn/ids-joomla-eb/releases/tag/1.0.0
