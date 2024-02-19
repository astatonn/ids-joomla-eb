# Template Padrão Digital de Governo para Joomla! (govbr-ds-joomla)
Template para o gerenciador de conteudos Joomla! baseado no Padrão Digital de Governo instituido pela [Portaria Nº 540, de 8 de setembro de 2020 - Secretaria de Governo/Presidência da República](https://www.in.gov.br/en/web/dou/-/portaria-n-540-de-8-de-setembro-de-2020-276907456).

## Sobre o template
Este template foi criado a partir das especificações disponíveis em https://www.gov.br/ds/, tendo em vista a compatibilidade com a atual versão do Joomla! (4.3.2, neste momento)

## Elementos implementados

Componentes:
* com_content.article: Visualização de artigos individuais
* com_content.category: Visualização estilo _blog_ para categorias
* com_finder.search: Visualização de resultados de busca
* com_tags.tag: Visualização de lista de artigos com a _tag_ específica

Módulos:
* mod_articles_latest: Visualização de artigos em texto (Últimas Notícias)
* mod_articles_news: Visualização de artigos com imagens (Destaques)
* mod_banners: Visualização de _banners_ estilo carrossel
* mod_breadcrumbs: Visualização da trilha de navegação
* mod_finder: Visualização do formulário de busca
* mod_menu: Visualizações de menus em _layouts_ diversos: Acesso Rápido, Cards, Mapa do Site, Menu Principal, Menu Principal (Área de Links) e Menu Principal (Área de Logos)

Layouts:
* joomla.pagination: Paginação utilizada para navegar em listas de itens

## Parâmetros
Parâmetros de estilo configurados na área "Avançado" do tema:
* Largura: Fixa ou Fluída. Deixa o conteúdo fixo ou expande para preencher todas as laterais da tela. [Referência](https://www.gov.br/ds/components/menu?tab=desenvolvedor)
* Menu Principal: Flutuante ou Empurrando. Quando flutuante, exibe o menu com elevação em relação ao conteúdo da página. No outro caso, o menu empurra o conteúdo da página na lateral esquerda. [Referência](https://www.gov.br/ds/components/menu?tab=desenvolvedor)
* Cor do rodapé: escuro ou claro. [Referência](https://www.gov.br/ds/components/footer?tab=desenvolvedor)
* Logo: marca exibida no cabeçalho. [Referência](https://www.gov.br/ds/components/header?tab=desenvolvedor)
* Logo com link?: habilita link na logo do cabeçalho, podendo direcionar para a página inicial ou para endereço personalizado.
* Logo (Rodapé): marca exibida no rodapé. [Referência](https://www.gov.br/ds/components/footer?tab=desenvolvedor)
* Título: identifica o site, sistema ou aplicativo. [Referência](https://www.gov.br/ds/components/header?tab=desenvolvedor)
* Subtítulo: reforça a identificação de uma categoria associada ao título da página, ou descrição do mesmo. [Referência](https://www.gov.br/ds/components/header?tab=desenvolvedor)
* Assinatura: identifica uma subcategoria ou descrição relacionada à marca. [Referência](https://www.gov.br/ds/components/header?tab=desenvolvedor)
* Cabeçalho do menu principal: Opções para exibir logo e título, apenas logo ou apenas título. [Referência](https://www.gov.br/ds/components/header?tab=desenvolvedor)
* Exibir Redes Sociais: bloco "Redes Sociais", exibido no menu lateral e no rodapé da página. [Referência](https://www.gov.br/ds/components/menu?tab=desenvolvedor)
* Informações legais (Rodapé do menu): texto exibido no rodapé do menu. [Referência](https://www.gov.br/ds/components/menu?tab=desenvolvedor)
* Componente Atendimento ao Cidadão: habilita a exibição do bloco "Atendimento ao Cidadão", com referências ao Fala.BR. [Referência](https://www.gov.br/governodigital/pt-br/legislacao/gov-br/gov_br_manual_de_diretrizes.pdf)
  * Órgão SIORG: quando o componente "Atendimento ao Cidadão estiver ativado, indica o órgão que será preenchido automaticamente no Fala.BR. A lista de órgãos está disponível no [Siorg Cidadão](https://siorg.gov.br/siorg-cidadao-webapp/resources/app/consulta-estrutura.html).
* Aviso de _cookies_: habilita a exibição da barra de aviso sobre uso de _cookies_. [Referência](https://www.gov.br/ds/components/cookiebar?tab=desenvolvedor)
  * Mensagem sobre _cookies_: texto exibido ao usuário na barra de aviso. [Referência](https://www.gov.br/ds/components/cookiebar?tab=desenvolvedor)
  * Botão Leia Mais: habilita botão secundário contendo link para a política de _cookies_ do site. [Referência](https://www.gov.br/ds/components/cookiebar?tab=desenvolvedor)
    * Título do botão Leia Mais: texto do botão secundário. [Referência](https://www.gov.br/ds/components/cookiebar?tab=desenvolvedor)
    * Link do botão Leia Mais: link do botão secundário. [Referência](https://www.gov.br/ds/components/cookiebar?tab=desenvolvedor)
  * Título do botão Ciente: texto do botão primário. [Referência](https://www.gov.br/ds/components/cookiebar?tab=desenvolvedor)
* Exibir logo Brasil: logo do Governo Federal exibida no rodapé da página. Pode ser desativada em período de defeso eleitoral. [Referência](https://www.gov.br/ds/components/footer?tab=desenvolvedor)
  * Logo Brasil: logo do Governo Federal, caso necessário alteração.
* Exibir logo Acesso à Informação: logo para acesso ao site Acesso à Informação. [Referência](https://www.gov.br/ds/components/footer?tab=desenvolvedor)
* Informações legais: texto apresentado no rodapé da página. [Referência](https://www.gov.br/ds/components/footer?tab=desenvolvedor)

## Posições
* menuacesso: Menu de Acesso, exibido no cabeçalho. É obrigatório o uso do layout "acessorapido" no módulo.
* search: Área para exibição do módulo Busca Inteligente (finder).
* menu-body: Menu Principal (Conteúdo). É obrigatório o uso do layout "menuprincipal" nos módulos para itens multiníveis. Utilizar o layout "menuprincipal-nivel1" para exibição de itens de nível 1. Opcionalmente pode-se adicionar um ícone da biblioteca FontAwesome 5 Free para o menu, preenchendo o campo "Classe do Menu".
* menu-logos: Menu Principal (Logos). É obrigatório o uso do layout "logosmenu" no módulo. É obrigatório o preenchimento do campo "Imagem do Link" nos itens de menu.
* menu-links: Menu Principal (Links). É obrigatório o uso do layout "linksmenu" no módulo. Opcionalmente pode-se adicionar um ícone da biblioteca FontAwesome 5 Free para os itens de menu, preenchendo o campo "Classe de ícones de link".
* main-top: Área para exibição de módulos no início da página, após o cabeçalho.
* breadcrumbs: Área para exibição do módulo Navegação Estrutural (breadcrumbs).
* content-top: Área para exibição de módulos acima do conteúdo.
* content-bottom: Área para exibição de módulos abaixo do conteúdo.
* main-bottom: Área para exibição de módulos ao final da página, antes do rodapé.
* menumapa: Mapa do Site. É obrigatório o uso do layout "mapadosite" nos módulos.
* error-search: Área para exibição do módulo Busca Inteligente (finder) na página de erro. É obrigatório o uso do layout "busca-erro" no módulo.
* debug: Área para exibição de módulos quando a depuração está habilitada no Joomla.

## Demonstração
Este template está atualmente em uso em alguns dos sites listados abaixo:

## Instalação
A versão estável mais recente é disponibilizada na área de [_Releases_](https://github.com/marceloalencar/govbr-ds-joomla/releases).

Utilize a funcionalidade para [baixar o código em formato zip](https://github.com/marceloalencar/govbr-ds-joomla/archive/refs/heads/master.zip) para obter a versão em desenvolvimento.

## Contribuições
Este espaço está aberto a contribuições da comunidade. Sinta-se livre para enviar _pull requests_ ou relatar falhas ou sugestões.
