<div align="center">
<!-- Title: -->
<a href="https://github.com/astatonn/ids-joomla-eb/">
    <img src="https://i.imgur.com/ublEN2h.png" height="100">
  </a>
  <h1><a href="https://github.com/astatonn/ids-joomla-eb">Template Padrão Digital de Governo para Joomla</a><br>Exército Brasileiro</h1>

  <!-- Labels: -->
  <a href="https://github.com/astatonn/ids-joomla-eb/releases/latest">
    <img src="https://img.shields.io/github/v/release/astatonn/ids-joomla-eb?label=vers%C3%A3o" height="20" alt="Versão">
  </a>
  <img src="https://img.shields.io/badge/Joomla-5.4%20%7C%206.x-green.svg" height="20" alt="Joomla 5.4 e 6.x">
  <img src="https://img.shields.io/badge/PHP-8.1%2B-purple.svg" height="20" alt="PHP 8.1 ou superior">
  <a href="https://github.com/astatonn/ids-joomla-eb/actions/workflows/ci.yml">
    <img src="https://github.com/astatonn/ids-joomla-eb/actions/workflows/ci.yml/badge.svg" height="20" alt="Testes">
  </a>
  <a href="https://discord.gg/tfUnXT3QcD">
    <img src="https://img.shields.io/discord/1212095458751549560" height="20" alt="Discord">
  </a>
</div>

Pacote para o [Joomla](https://www.joomla.org) com a identidade visual do Governo Federal
([Padrão Digital de Governo](https://www.gov.br/ds/)) para sites e intranets do Exército Brasileiro:
um template, dois componentes e seis módulos, instalados e atualizados de uma vez só.

> [!IMPORTANT]
> **Vai atualizar o Joomla 5 para o Joomla 6?** Instale a versão **3.1.0** deste pacote **antes**.
> As versões anteriores derrubam o site no Joomla 6 com o erro
> `Trait "Joomla\CMS\MVC\Model\DatabaseAwareTrait" not found`.
>
> **Já atualizou e o site saiu do ar?** O painel administrativo continua funcionando: instale a
> 3.1.0 por ele e o site volta. O passo a passo está em [MIGRATION.md](MIGRATION.md#meu-site-saiu-do-ar-depois-de-atualizar-para-o-joomla-6).

## O que está incluído

| Tipo | Nome no Joomla | Para que serve |
|------|----------------|----------------|
| Template | **IDS Gov - Exército Brasileiro** | Visual gov.br com temas verde, azul, amarelo e vermelho, menu lateral ou horizontal, alto contraste, VLibras, aviso de cookies e bloco de Ouvidoria (Fala.BR) |
| Componente | **Aniversariantes** | Cadastro dos aniversariantes da OM (posto/graduação, nome e data) e página pública com a lista |
| Componente | **PagTesouro** | Cadastro de UASGs e serviços e formulário público que leva o contribuinte ao PagTesouro para emitir a GRU |
| Módulo | **IDS Gov - Aniversariantes** | Aniversariantes do mês, de hoje ou dos próximos dias |
| Módulo | **IDS Gov - Links** | Seção de links em cartões com ícone ou em lista compacta |
| Módulo | **IDS Gov - Popup (imagem ou aviso)** | Janela com imagem e/ou texto ao abrir a página |
| Módulo | **IDS Gov - Instagram** | Perfil do Instagram da OM (embed oficial) |
| Módulo | **IDS Gov - Botão Leia Mais** | Botão para a página com todas as notícias |
| Módulo | **IDS Gov - Vídeos (YouTube)** | Carrossel de vídeos do YouTube |

O template também traz layouts próprios para os módulos e componentes do Joomla: menu (seis
layouts), busca inteligente, trilha de navegação, banners (carrossel), últimas notícias, destaques,
artigos, blog de categoria, tags, resultados da busca e paginação.

## Requisitos

| | Mínimo | Observação |
|---|---|---|
| **Joomla** | 5.4 | Testado em 5.4.1, 5.4.10, 6.0.1 e 6.2.0 |
| **PHP** | 8.1 | O Joomla 6 exige PHP 8.3 |
| **Banco de dados** | MySQL 8.0 ou MariaDB 10.4 | Os componentes Aniversariantes e PagTesouro usam MySQL/MariaDB |

Não é necessário ativar o plugin *Behaviour - Backward Compatibility 6*: o pacote não usa nenhuma
API antiga do Joomla.

## Instalação

No painel administrativo, abra **Sistema → Instalar → Extensões** e escolha uma das formas:

1. **Instalar da URL** (recomendado, não depende do limite de upload do PHP). Cole:

   ```
   https://github.com/astatonn/ids-joomla-eb/releases/latest/download/pkg_ids_joomla_eb.zip
   ```

2. **Enviar arquivo de pacote.** Baixe o `pkg_ids_joomla_eb.zip` da
   [última release](https://github.com/astatonn/ids-joomla-eb/releases/latest) e arraste para a tela.
   O arquivo tem cerca de 8 MB; se o Joomla reclamar do tamanho, aumente `upload_max_filesize` e
   `post_max_size` no PHP ou use a opção 1.

3. **Pela linha de comando**, na pasta do Joomla:

   ```bash
   php cli/joomla.php extension:install --url=https://github.com/astatonn/ids-joomla-eb/releases/latest/download/pkg_ids_joomla_eb.zip
   ```

O *Download ZIP* do GitHub (botão **Code**) também continua sendo um pacote instalável.

Para **atualizar**, instale por cima do mesmo jeito ou use o aviso do próprio Joomla
(veja [Atualizações](#atualizações)). Nada é perdido: cadastros, módulos e as opções do template
são preservados, e as configurações antigas dos módulos Links e Vídeos são convertidas
automaticamente.

## Primeiros passos

1. **Ative o template:** em **Sistema → Estilos de templates do site**, marque
   *IDS Gov - Exército Brasileiro* como padrão e abra o estilo para configurar.
2. **Configure o estilo.** As opções ficam em seis abas:

   | Aba | O que você define |
   |-----|-------------------|
   | **Identidade** | Título, subtítulo, assinatura, logo e o que acontece ao clicar na logo |
   | **Aparência** | Tema de cores (verde, azul, amarelo, vermelho) e largura fixa ou fluida |
   | **Menu** | Menu vertical (lateral) ou horizontal, abertura flutuante ou empurrando, topo e rodapé do menu |
   | **Rodapé** | Cor, logo, selo Acesso à Informação, marca do Governo Federal e texto legal |
   | **Redes sociais** | Instagram, X, Facebook e YouTube |
   | **Cidadão e cookies** | Bloco de Ouvidoria (Fala.BR, com o código SIORG) e barra de aviso de cookies |

3. **Publique os menus.** Crie módulos do tipo *Menu* e escolha a posição e o layout (aba
   *Avançado* do módulo):

   | Posição | Layout do módulo de menu | Resultado |
   |---------|--------------------------|-----------|
   | Menu principal: itens `[menu-body]` | *Menu principal (multinível)* ou *(um nível)* | Menu lateral |
   | Menu principal: links / barra horizontal `[menu-links]` | *Links do menu* | Links no rodapé do menu e barra horizontal |
   | Menu principal: logos `[menu-logos]` | *Logos do menu* | Logos no rodapé do menu (campo *Imagem do link*) |
   | Cabeçalho: Acesso Rápido `[menuacesso]` | *Acesso Rápido* | Atalhos no cabeçalho |
   | Rodapé: mapa do site `[menumapa]` | *Mapa do site* | Colunas de links no rodapé |
   | Conteúdo: acima / abaixo | *Cartões (cards)* | Cartões com ícone |

   Ícones [Font Awesome 5](https://fontawesome.com/v5/search?m=free) podem ser informados no campo
   *Classe do ícone do link* de cada item de menu (por exemplo `fa-newspaper`).

4. **Demais posições:** Cabeçalho: Busca `[search]` (módulo *Busca Inteligente*), Página: trilha de
   navegação `[breadcrumbs]`, Página: topo `[main-top]`, Conteúdo: acima `[content-top]`, Conteúdo:
   abaixo `[content-bottom]`, Página: fim `[main-bottom]` e Página de erro: busca `[error-search]`
   (módulo *Busca Inteligente* com o layout *Busca da página de erro*).

5. **Módulos do pacote:** em **Conteúdo → Módulos do site → Novo**, todos começam com **IDS Gov**.

### Dúvidas frequentes

**Como faço o botão "Leia mais" levar para a página com todas as notícias?**
No módulo *IDS Gov - Botão Leia Mais*, em **Destino do botão**, escolha *Categoria de artigos* e
selecione a categoria das notícias. Publique o módulo logo abaixo do módulo de notícias, na mesma
posição.

**Onde cadastro os aniversariantes?**
Em **Componentes → Aniversariantes**. O módulo *IDS Gov - Aniversariantes* só escolhe o período
(mês, hoje ou próximos dias) e a forma de exibir. Para uma página com a lista, crie um item de menu
do tipo **Aniversariantes → Lista de aniversariantes**.

**Como publico o formulário do PagTesouro?**
Cadastre a UASG e os serviços em **Componentes → PagTesouro** e crie um item de menu do tipo
**PagTesouro → Formulário de pagamento (GRU)**. Com uma única UASG publicada, ela já vem selecionada.

**O módulo do Instagram mostra só um link.**
O conteúdo vem direto do `instagram.com` no navegador do visitante. Em redes onde o Instagram é
bloqueado (comum em intranets) aparece apenas o link para o perfil.

## Atualizações

O Joomla consulta este repositório e **avisa dentro do painel** quando há versão nova:

- no **Painel inicial**, no bloco de notificações (*Extensões: atualizações disponíveis*);
- em **Sistema → Atualizar → Extensões**, com o botão **Changelog** mostrando o que mudou;
- na **verificação pré-atualização** do Joomla (antes de ir para uma nova versão principal), que
  informa se o pacote é compatível com a versão de destino.

Com o pacote instalado aparece **um único aviso**, o do pacote, que atualiza tudo de uma vez. Quem
instalou uma extensão avulsa (só um módulo, por exemplo) recebe o aviso daquela extensão. Os
arquivos baixados têm o checksum SHA-256/SHA-512 conferido pelo Joomla.

Para os avisos funcionarem, o **servidor** precisa conseguir acessar `raw.githubusercontent.com` e
`github.com` (se a rede usa proxy, configure-o em **Sistema → Configuração global → Servidor →
Proxy**). Sites que instalaram a versão 1.0.0 já têm o endereço de atualização registrado e passam
a ver o aviso da 3.1.0 assim que estiverem no Joomla 5.4 ou superior.

Não apareceu o aviso? Em **Sistema → Atualizar → Extensões** clique em **Verificar atualizações**;
se necessário, em **Sistema → Atualizar → Sites de atualização** confira se *IDS Joomla EB* está
ativo e use **Reconstruir**.

## Ambiente local com Docker

```bash
php build/build.php          # opcional: gera dist/pkg_ids_joomla_eb.zip para instalar sozinho
docker compose up --build
```

O site sobe em <http://localhost:8080> (administrador em `/administrator`, usuário `joomla`, senha
`joomla@secured`). Use `JOOMLA_PORT=8081` para outra porta e
`JOOMLA_IMAGE=joomla:5.4-php8.3-apache` para outra versão do Joomla.

## Desenvolvimento

```
src/                   código-fonte de cada extensão (edite aqui)
  template/            IDS Gov - Exército Brasileiro
  components/          com_aniversariantes, com_pagtesouro
  modules/             mod_aniver, mod_imagempopup, mod_instafeed, mod_links, mod_readmorenews, mod_videosiframe
packages/              um ZIP por extensão, gerado a partir de src/ (versionado)
pkg_ids_joomla_eb.xml  manifesto do pacote · script.php · language/
updates/               servidores de atualização e changelog lidos pelo Joomla (gerados)
build/build.php        gera packages/, dist/ e updates/
tests/                 verificações estáticas e testes de integração em Docker
```

```bash
php build/build.php                 # depois de alterar src/: regenera packages/, dist/ e updates/
php tests/lint.php                  # sintaxe, APIs removidas do Joomla 6, manifestos, idiomas, update servers
php tests/integration/run.php       # instala em Joomla 5.4 e 6 reais (Docker) e testa site e painel
php tests/integration/run.php --list
```

Os testes de integração cobrem: instalação limpa (Joomla 5.4.1, 5.4.10, 6.0.1 e 6.2.0), atualização
a partir do pacote 1.0.0 seguida da atualização do Joomla para a versão 6, recuperação de um site
quebrado pelo erro do `DatabaseAwareTrait`, e os avisos de atualização (pacote e extensão avulsa).

### Como publicar uma versão

A ordem importa: os sites em produção leem `updates/` direto da branch `master`.

1. Descreva as mudanças no [CHANGELOG.md](CHANGELOG.md) em uma seção `## [X.Y.Z] - AAAA-MM-DD`.
2. `php build/build.php --set-version=X.Y.Z` e faça o commit **em um branch**.
3. `git tag vX.Y.Z && git push origin vX.Y.Z`: o workflow *Release* confere os checksums e publica
   os arquivos na release.
4. **Só depois** leve o commit para a `master`. A partir daí os sites recebem o aviso de atualização,
   e o workflow *Testes* confirma que cada arquivo anunciado existe e tem o checksum declarado.

## Documentação

- [MIGRATION.md](MIGRATION.md): atualização para a 3.1.0, Joomla 6 e recuperação de site fora do ar
- [CHANGELOG.md](CHANGELOG.md): histórico de mudanças
- [Wiki](https://github.com/astatonn/ids-joomla-eb/wiki) · [Issues](https://github.com/astatonn/ids-joomla-eb/issues) · [Discord](https://discord.gg/tfUnXT3QcD)

Este template foi criado a partir das especificações disponíveis em <https://www.gov.br/ds/> e é
baseado na versão do [marceloalencar](https://github.com/marceloalencar).

## Demonstração
Este template está atualmente em uso em alguns dos sites listados abaixo:
<table border="0" align="center">
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
       <img src="https://github.com/astatonn/bras-esEB/blob/main/Brasao%20CI%20Art%20.png" style="border-radius: 50%; border: 3px solid #4CAF50; height: 100px;" alt="Centro de Instrução de Artilharia de Mísseis e Foguetes" />
       <br />
    <a href="http://intranet.ciartmslfgt.eb.mil.br/"><sub><b>Intranet</b></sub></a>
    <br />
    </td>
   <td align="center">
       <img src="https://github.com/astatonn/bras-esEB/blob/main/badmcurado.jpg?raw=true" style="border-radius: 50%; border: 3px solid #4CAF50; height: 100px;" alt="Base Administrativa do Curado" />
       <br />
    <a href="http://intranet.baadmcurado.eb.mil.br/"><sub><b>Intranet</b></sub></a>
    <br />
    </td>
      <td align="center">
       <img src="https://github.com/astatonn/bras-esEB/blob/main/Brasao_3RM.png?raw=true" style="border-radius: 50%; border: 3px solid #4CAF50; height: 100px;" alt="3ª Região Militar" />
       <br />
    <a href="https://3rm.eb.mil.br/"><sub><b>Internet</b></sub></a>
    <br />
    </td>
           <td align="center">
       <img src="https://github.com/astatonn/bras-esEB/blob/main/3rcg-removebg-preview.png?raw=true" style="border-radius: 50%; border: 3px solid #4CAF50; height: 100px;" alt="3º Regimento de Cavalaria de Guardas" />
       <br />
    <a href="http://intranet.3rcg.eb.mil.br/"><sub><b>Intranet</b></sub></a>
    <br />
      <a href="https://3rcg.eb.mil.br/"><sub><b>Internet</b></sub></a>
       <br />  
    </td>
    <td align="center">
       <img src="https://github.com/astatonn/bras-esEB/blob/main/8_cia_com.png?raw=true" style="border-radius: 50%; border: 3px solid #4CAF50; height: 100px;" alt="8ª Companhia de Comunicações" />
       <br />
    <a href="https://8ciacom.eb.mil.br/"><sub><b>Internet</b></sub></a>
    <br />
    </td>
  </tr>
  <tr>
  <td align="center">
       <img src="https://github.com/astatonn/bras-esEB/blob/c72da8295564c779f6a2883bd06ceaf3e60ce365/8rcmec.png" style="border-radius: 50%; border: 3px solid #4CAF50; height: 100px;" alt="8º Regimento de Cavalaria Mecanizado" />
       <br />
    <a href="http://intranet.8rcmec.eb.mil.br/"><sub><b>Intranet</b></sub></a>
    <br />
    </td>
       <td align="center">
       <img src="https://github.com/astatonn/bras-esEB/blob/main/3gaaae.png" style="border-radius: 50%; border: 3px solid #4CAF50; height: 100px;" alt="8º Regimento de Cavalaria Mecanizado" />
       <br />
    <a href="https://3gaaae.eb.mil.br/"><sub><b>Internet</b></sub></a>
    <br />
    </td>
       <td align="center">
       <img src="https://github.com/astatonn/bras-esEB/blob/main/19rcmeclogo.png" style="border-radius: 50%; border: 3px solid #4CAF50; height: 100px;" alt="8º Regimento de Cavalaria Mecanizado" />
       <br />
    <a href="https://19rcmec.eb.mil.br/"><sub><b>Internet</b></sub></a>
    <br />
    </td>
         <td align="center">
       <img src="https://github.com/astatonn/bras-esEB/blob/main/CIBld.png" style="border-radius: 50%; border: 3px solid #4CAF50; height: 100px;" alt="8º Regimento de Cavalaria Mecanizado" />
       <br />
    <a href="https://cibld.eb.mil.br/"><sub><b>Internet</b></sub></a>
    <br />
    </td>
 <td align="center">
       <img src="https://github.com/astatonn/bras-esEB/blob/main/2bimtz_logo.png" style="border-radius: 50%; border: 3px solid #4CAF50; height: 100px;" alt="8º Regimento de Cavalaria Mecanizado" />
       <br />
    <a href="https://intranet.2bimtz.eb.mil.br/"><sub><b>Intranet</b></sub></a>
    <br />
    </td>
       <td align="center">
       <img src="https://github.com/astatonn/bras-esEB/blob/main/13bdaimz.png" style="border-radius: 50%; border: 3px solid #4CAF50; height: 100px;" alt="8º Regimento de Cavalaria Mecanizado" />
       <br />
    <a href="http://intranet.13bdainfmtz.eb.mil.br "><sub><b>Intranet</b></sub></a>
            <br />
         <a href="https://13bdainfmtz.eb.mil.br/"><sub><b>Internet</b></sub></a>   
    <br />
    </td>
        <td align="center">
       <img src="https://github.com/astatonn/bras-esEB/blob/main/logo_52ct.png" style="border-radius: 50%; border: 3px solid #4CAF50; height: 100px;" alt="8º Regimento de Cavalaria Mecanizado" />
       <br />
    <a href="http://intranet.52ct.eb.mil.br "><sub><b>Intranet</b></sub></a>
              <br />
    <a href="http://52ct.eb.mil.br "><sub><b>Internet</b></sub></a>
    <br />
    </td>
        <td align="center">
       <img src="https://github.com/astatonn/bras-esEB/blob/main/BRASO_2_BDA_C.png" style="border-radius: 50%; border: 3px solid #4CAF50; height: 100px;" alt="8º Regimento de Cavalaria Mecanizado" />
       <br />
    <a href="http://intranet.2bdacmec.eb.mil.br"><sub><b>Intranet</b></sub></a>
             <br />
      <a href="http://2bdacmec.eb.mil.br"><sub><b>Internet</b></sub></a>
    <br />
    </td>
  </tr>
    <tr>
  <td align="center">
       <img src="https://github.com/astatonn/bras-esEB/blob/main/1bdacmec.png" style="border-radius: 50%; border: 3px solid #4CAF50; height: 100px;" alt="8º Regimento de Cavalaria Mecanizado" />
       <br />
    <a href="https://www.1bdacmec.eb.mil.br/"><sub><b>Internet</b></sub></a>
    <br />
       <a href="https://intranet.1bdacmec.eb.mil.br"><sub><b>Intranet</b></sub></a>
    <br />
    </td>
        <td align="center">
       <img src="https://github.com/astatonn/bras-esEB/blob/main/logo41ct.jpeg" style="border-radius: 50%; border: 3px solid #4CAF50; height: 100px;" alt="8º Regimento de Cavalaria Mecanizado" />
       <br />
    <a href="https://www.intranet.11ct.eb.mil.br/"><sub><b>Intranet</b></sub></a>
    <br />
    </td>
       <td align="center">
       <img src="https://github.com/astatonn/bras-esEB/blob/main/6bcom-sem_fundo.png" style="border-radius: 50%; border: 3px solid #4CAF50; height: 100px;" alt="8º Regimento de Cavalaria Mecanizado" />
       <br />
    <a href="https://www.6bcom.eb.mil.br/"><sub><b>Internet</b></sub></a>
    <br />
    </td>
       <td align="center">
       <img src="https://github.com/astatonn/bras-esEB/blob/main/logo3bsup.png" style="border-radius: 50%; border: 3px solid #4CAF50; height: 100px;" alt="8º Regimento de Cavalaria Mecanizado" />
       <br />
    <a href="https://www.3bsup.eb.mil.br/"><sub><b>Internet</b></sub></a>
    <br />
    </td>
       <td align="center">
       <img src="https://github.com/astatonn/bras-esEB/blob/main/6RCB.png" style="border-radius: 50%; border: 3px solid #4CAF50; height: 100px;" alt="8º Regimento de Cavalaria Mecanizado" />
       <br />
        <a href="https://www.6rcb.eb.mil.br/"><sub><b>Internet</b></sub></a>
    <br />
           <a href="https://www.intranet.6rcb.eb.mil.br/"><sub><b>Intranet</b></sub></a>
        <br />
    </td>
       <td align="center">
       <img src="https://github.com/astatonn/bras-esEB/blob/main/6cta_logo.png" style="border-radius: 50%; border: 3px solid #4CAF50; height: 100px;" alt="8º Regimento de Cavalaria Mecanizado" />
       <br />
           <a href="https://www.intranet.6cta.eb.mil.br/"><sub><b>Intranet</b></sub></a>
        <br />
    </td>
     <td align="center">
       <img src="https://github.com/astatonn/bras-esEB/blob/main/logo_10rm.png" style="border-radius: 50%; border: 3px solid #4CAF50; height: 100px;" alt="8º Regimento de Cavalaria Mecanizado" />
       <br />
           <a href="https://www.10rm.eb.mil.br/"><sub><b>Internet</b></sub></a>
        <br />
    </td>
     <td align="center">
       <img src="https://github.com/astatonn/bras-esEB/blob/main/logo-cro9.png" style="border-radius: 50%; border: 3px solid #4CAF50; height: 100px;" alt="8º Regimento de Cavalaria Mecanizado" />
       <br />
           <a href="https://www.intranet.cro9.eb.mil.br/"><sub><b>Intranet</b></sub></a>
        <br />
    </td>
  </tr>
       <tr>
  <td align="center">
           <img src="https://github.com/astatonn/bras-esEB/blob/main/7rm.png" style="border-radius: 50%; border: 3px solid #4CAF50; height: 100px;" alt="8º Regimento de Cavalaria Mecanizado" />
       <br />
    <a href="https://7rm.eb.mil.br/"><sub><b>Internet</b></sub></a>
    <br />
    </td>
        <td align="center">
           <img src="https://github.com/astatonn/bras-esEB/blob/main/dsg.png?raw=true" style="border-radius: 50%; border: 3px solid #4CAF50; height: 100px;" alt="8º Regimento de Cavalaria Mecanizado" />
       <br />
    <a href="https://geoportal.eb.mil.br/portal/"><sub><b>Internet</b></sub></a>
    <br />
    </td>
  </tr>
    
</table>

## Contribuições
Este espaço está aberto a contribuições da comunidade. Sinta-se livre para enviar _pull requests_ ou relatar falhas ou sugestões.
Alterações de código devem ser feitas em `src/` (os arquivos em `packages/` são gerados pelo build).

## Contribuidores

Agradecemos a todos que contribuíram para este projeto!
<table border="0" align="center">
  <tr>
    <td align="center">
        <a href="https://github.com/joao-pedro-rdo">
    <img src="https://avatars.githubusercontent.com/u/122635721?v=4" style="border-radius: 50%; border: 3px solid #4CAF50; height: 100px;" alt="João Pedro Ramos" />
    <br />
    <sub><b>João Pedro Ramos</b></sub>
    <br />
    <sub><b>Docker Container</b></sub>
  </a>
    </td>
    <td align="center">
       <a href="https://github.com/HenriqueMichelsVoicolesco">
    <img src="https://avatars.githubusercontent.com/u/50555545?v=4" style="border-radius: 50%; border: 3px solid #4CAF50; height: 100px;" alt="Henrique Michels Voicolesco" />
    <br />
    <sub><b>Henrique Michels Voicolesco</b></sub>
    <br />
    <sub><b>Módulo PopUp</b></sub>
  </a>
    </td>
         <td align="center">
       <a href="https://github.com/vieiranetodev">
    <img src="https://avatars.githubusercontent.com/u/147254493?v=4" style="border-radius: 50%; border: 3px solid #4CAF50; height: 100px;" alt="José Vieira Neto" />
    <br />
    <sub><b>José Vieira Neto</b></sub>
    <br />
    <sub><b>Moderador Discord</b></sub>
  </a>
    </td>
              <td align="center">
       <a href="https://github.com/vicariskr">
    <img src="https://avatars.githubusercontent.com/u/84245596?v=4" style="border-radius: 50%; border: 3px solid #4CAF50; height: 100px;" alt="Gabriel Augusto Vicari" />
    <br />
    <sub><b>Gabriel Augusto Vicari</b></sub>
    <br />
    <sub><b>Documentação (README/Wiki)</b></sub>
  </a>
    </td>
  </tr>
</table>
