# Template Padrão Digital de Governo para Joomla Exército Brasileiro!
Template para o gerenciador de conteudos Joomla! baseado no Padrão Digital de Governo instituido pela [Portaria Nº 540, de 8 de setembro de 2020 - Secretaria de Governo/Presidência da República](https://www.in.gov.br/en/web/dou/-/portaria-n-540-de-8-de-setembro-de-2020-276907456).

## Sobre o template
Este template foi criado a partir das especificações disponíveis em https://www.gov.br/ds/, tendo em vista a compatibilidade com a atual versão do Joomla! (5.0.3, neste momento)

## Requerimentos
### Software
- PhP 8.1.0 ou superior

### Databases
- MySQL 8.9.13 ou superior
- MariaDB 10.4.0 ou superior
- PostgreSQL 12.0 ou superior

### Web Server
- Apache 2.4
- Nginx 1.21 ou superior
- Microsoft IIS 10

## Elementos implementados
Novidades:
* ✨docker-compose: Agora você pode levantar um container docker com joomla rodando, dispensando os passos de instalção manual. Veja mais detalhes em [Docker: Configuração do Joomla com Docker](#docker-configuração-do-joomla-com-docker).
* ✨Articles Newsflash: Agora o módulo de últimas notícias tem o cabeçalho baseado na categoria tornando dinâmica a criação do módulo. 


Features:
* ✨mod_links: Permite a associação de ícones acima dos textos usando font-awesome (ref.: https://www.w3schools.com/icons/fontawesome5_icons_communication.asp)
* 🔧mod_links: Corrigido o problema de instalação do módulo
* 🔥✨ids-joomla-eb: Nova feature de temas personalizados (verde, azul, vermelho e amarelo)


## TODO LIST
- [ ] pop-up: Criar módulo de pop-up
- [ ] Plugin: Criar plugin semelhante ao Tabs
- [X] Menu lateral: Criar estilo collapse (IN PROGRESS)
- [ ] Integração: Implementar funcionalidade com o Phoca Download

      
Componentes:
* com_content.article: Visualização de artigos individuais
* com_content.category: Visualização estilo _blog_ para categorias
* com_finder.search: Visualização de resultados de busca
* com_tags.tag: Visualização de lista de artigos com a _tag_ específica
* com_aniversariantes: Responsável por gerenciar o conteúdo de aniversariantes do mês a ser apresentado no módulo mod_aniversariantes

Módulos:
* mod_articles_latest: Visualização de artigos em texto (Últimas Notícias)
* mod_articles_news: Visualização de artigos com imagens (Destaques)
* mod_banners: Visualização de _banners_ estilo carrossel
* mod_breadcrumbs: Visualização da trilha de navegação
* mod_finder: Visualização do formulário de busca
* mod_menu: Visualizações de menus em _layouts_ diversos: Acesso Rápido, Cards, Mapa do Site, Menu Principal, Menu Principal (Área de Links) e Menu Principal (Área de Logos)
* mod_links: Cria uma seção com apontamentos para links e título personalizado
* mod_aniversariantes: Responsável por apresentar as informações do componente aniversariantes do mês

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
<table border="0">
  <tr>
    <td align="center">
       <img src="https://github.com/astatonn/bras-esEB/blob/main/1cta.png" style="border-radius: 50%; border: 3px solid #4CAF50; height: 100px;" alt="1º Centro de Telemática de Área" />
       <br />
          <a href="http://1cta.eb.mil.br"><sub><b>Internet</b></sub></a>
          <br />
    </td>
    <td align="center">
       <img src="https://github.com/astatonn/bras-esEB/blob/main/agsp_logo.png" style="border-radius: 50%; border: 3px solid #4CAF50; height: 100px;" alt="Arsenal de Guerra de General Câmara" />
       <br />
    <a href="http://intranet.agsp.eb.mil.br/"><sub><b>Intranet</b></sub></a>
    <br />
      <a href="http://agsp.eb.mil.br/"><sub><b>Internet</b></sub></a>
       <br />  
    </td>
         <td align="center">
       <img src="https://github.com/astatonn/bras-esEB/blob/main/logocolog.png" style="border-radius: 50%; border: 3px solid #4CAF50; height: 100px;" alt="Comando Logístico" />
       <br />
    <a href="http://intranet.colog.eb.mil.br/"><sub><b>Intranet</b></sub></a>
    <br />
    </td>
          <td align="center">
       <img src="https://github.com/astatonn/bras-esEB/blob/main/Brasao%20CI%20Art%20.png" style="border-radius: 50%; border: 3px solid #4CAF50; height: 100px;" alt="Comando Logístico" />
       <br />
    <a href="http://intranet.ciartmslfgt.eb.mil.br/"><sub><b>Intranet</b></sub></a>
    <br />
    </td>
  </tr>
</table>



## Instalação
### Baixe e Extraia o Projeto:

- Baixe este projeto em formato .zip pela interface ou no terminal através do comando:
``` curl -LJO "https://github.com/astatonn/ids-joomla-eb/archive/refs/heads/master.zip" ```

- Extraia os arquivos na pasta de sua escolha.
### Instale o template
- Acesse o painel de administrador do Joomla.
- Navegue até "Sistema" -> "Extensões" -> "Gerenciar" -> "Instalar".
- Selecione o arquivo .zip que você baixou e clique em "Enviar Arquivo e Instalar".

### Instale os Componentes e Módulos Individualmente
- Ainda em "Sistema" -> "Extensões" -> "Gerenciar" -> "Instalar", escolha a opção "Instalar de Pasta" (Install from Folder).
- Cole o caminho referente ao local onde você extraiu o projeto para cada um dos componentes e módulos.

#### Exemplo
- Para o componente com_aniversariantes:
```/caminho/para/a/pasta/ids-joomla-eb/componentes/com_aniversariantes```

- Para o módulo mod_aniversariantes:
```/caminho/para/a/pasta/ids-joomla-eb/modules/mod_aniversariantes```

- Para o módulo mod_links:
```/caminho/para/a/pasta/ids-joomla-eb/modules/mod_links```

*Certifique-se de substituir "/caminho/para/a/pasta" pelo caminho correto onde você extraiu os arquivos do projeto.*

## Docker: Configuração do Joomla com Docker 

### Requisitos

- [Docker Engine](https://docs.docker.com/engine/install/) ou [Docker Desktop](https://docs.docker.com/desktop/)
- [Docker Compose](https://docs.docker.com/compose/install/)

### Configuração

1. **Baixe o arquivo Docker Compose**  
   O arquivo `docker-compose.yaml` está disponível no repositório oficial do [Docker Hub Joomla](https://hub.docker.com/_/joomla). 

2. **Edite o arquivo `docker-compose.yaml`**  
   Abra o arquivo `docker-compose.yaml` e personalize as credenciais do banco de dados e do Joomla conforme necessário. Certifique-se de ajustar os seguintes parâmetros:
   
   - Banco de Dados:
     ```yaml
     MYSQL_DATABASE: seu_nome_banco
     MYSQL_USER: seu_usuario
     MYSQL_PASSWORD: sua_senha
     MYSQL_ROOT_PASSWORD: sua_senha_root
     ```
   - Joomla:
     ```yaml
     JOOMLA_DB_HOST: nome_do_servico_db
     JOOMLA_DB_NAME: seu_nome_banco
     JOOMLA_DB_USER: seu_usuario
     JOOMLA_DB_PASSWORD: sua_senha
     ```

3. **Execute o Docker Compose**  
   No diretório onde o arquivo `docker-compose.yaml` está localizado, execute o comando a seguir para iniciar os containers:
   ```bash
   docker-compose up -d
   ```
4. **Acesse o Joomla**
  Se estiver utilizando as portas padrão configuradas no docker-compose.yaml, o Joomla estará disponível em http://localhost:8080. Caso contrário, substitua localhost pelo seu endereço IP ou nome de domínio.

5. **Importar o template**
    Não sera possivel importar o template pelo painel de administrador do Joomla. Para isso, voce deve copiar a pasta do template para o joomla. Para isso, execute o comando abaixo:
    ```bash
    docker cp ids-joomla-eb <id-do-seu-container>:/var/www/html/tmp    
    ```
    Para saber o id do container, execute o comando abaixo:
    ```bash
    docker ps
    ```
    agora acesse localhost:8080/administrator -> System -> Templates -> Site Templates -> Styles -> Configure como default o template IDS Gov...

## Contribuições
Este espaço está aberto a contribuições da comunidade. Sinta-se livre para enviar _pull requests_ ou relatar falhas ou sugestões.

## Contribuidores

Agradecemos a todos que contribuíram para este projeto!
<table border="0">
  <tr>
    <td align="center">
        <a href="https://github.com/joao-pedro-rdo">
    <img src="https://avatars.githubusercontent.com/u/122635721?v=4" style="border-radius: 50%; border: 3px solid #4CAF50; height: 100px;" alt="João Pedro" />
    <br />
    <sub><b>João Pedro Ramos</b></sub>
    <br />
    <sub><b>Docker Container</b></sub>
  </a>
    </td>
    <td align="center">
       <a href="https://github.com/HenriqueMichelsVoicolesco">
    <img src="https://avatars.githubusercontent.com/u/50555545?v=4" style="border-radius: 50%; border: 3px solid #4CAF50; height: 100px;" alt="João Pedro" />
    <br />
    <sub><b>Henrique Michels Voicolesco</b></sub>
    <br />
    <sub><b>Módulo PopUp</b></sub>
  </a>
    </td>
         <td align="center">
       <a href="https://github.com/vieiranetodev">
    <img src="https://avatars.githubusercontent.com/u/147254493?v=4" style="border-radius: 50%; border: 3px solid #4CAF50; height: 100px;" alt="João Pedro" />
    <br />
    <sub><b>José Vieira Neto</b></sub>
    <br />
    <sub><b>Moderador Discord</b></sub>
  </a>
    </td>
  </tr>
</table>

</div>
