# Guia de atualização — IDS Joomla EB 3.1.0 e Joomla 6

Este guia cobre três situações:

1. [Vou atualizar o Joomla 5 para o Joomla 6](#vou-atualizar-o-joomla-5-para-o-joomla-6)
2. [Meu site saiu do ar depois de atualizar para o Joomla 6](#meu-site-saiu-do-ar-depois-de-atualizar-para-o-joomla-6)
3. [O que muda ao sair das versões 1.x/2.x](#o-que-muda-ao-sair-das-versões-1x2x)

Todos os caminhos abaixo são exercitados pelos testes automatizados do repositório
(`php tests/integration/run.php`), em sites Joomla reais.

## Requisitos da versão 3.1.0

| | Mínimo | Observação |
|---|---|---|
| Joomla | 5.4 | Sites em Joomla 4.x ou 5.0–5.3 devem primeiro atualizar o Joomla para a 5.4 |
| PHP | 8.1 | O Joomla 6 exige PHP 8.3 |
| Banco | MySQL 8.0 / MariaDB 10.4 | |

## Vou atualizar o Joomla 5 para o Joomla 6

A ordem correta é **pacote primeiro, Joomla depois**:

1. Faça backup dos arquivos e do banco de dados.
2. Com o site ainda no **Joomla 5.4**, atualize o IDS Joomla EB para a **3.1.0**:
   - pelo aviso em **Sistema → Atualizar → Extensões**, ou
   - em **Sistema → Instalar → Extensões → Instalar da URL**, colando
     `https://github.com/astatonn/ids-joomla-eb/releases/latest/download/pkg_ids_joomla_eb.zip`.
3. Confira o site e o painel.
4. Atualize o Joomla para a versão 6 em **Sistema → Atualizar → Joomla**. Na verificação
   pré-atualização, o pacote *IDS Joomla EB* deve aparecer como compatível.

Não é necessário ativar o plugin *Behaviour - Backward Compatibility 6* para este pacote.

## Meu site saiu do ar depois de atualizar para o Joomla 6

### Como reconhecer

O site público mostra erro 500 e o log do servidor (Apache/PHP) registra:

```
PHP Fatal error:  Trait "Joomla\CMS\MVC\Model\DatabaseAwareTrait" not found in
.../administrator/components/com_aniversariantes/src/Service/Html/ANIVERSARIANTES.php on line 25
```

**Causa:** o componente *Aniversariantes* das versões 1.x/2.x usa uma classe que o Joomla 6
removeu. Ele é carregado em toda página que exibe o módulo *Seção de Aniversariantes do Mês*, por
isso o site inteiro cai. O plugin de compatibilidade do Joomla 6 não cobre essa classe, então
ativá-lo não resolve.

O **painel administrativo continua funcionando**: os dois caminhos abaixo são feitos por ele.

### Paliativo imediato (1 minuto)

Em **Conteúdo → Módulos do site**, despublique o módulo *Seção de Aniversariantes do Mês*
(`mod_aniver`). O site volta a abrir na hora, sem a lista de aniversariantes.

### Correção definitiva

Em **Sistema → Instalar → Extensões**, instale a versão 3.1.0:

- aba **Instalar da URL**:
  `https://github.com/astatonn/ids-joomla-eb/releases/latest/download/pkg_ids_joomla_eb.zip`
- ou aba **Enviar arquivo de pacote**, com o `pkg_ids_joomla_eb.zip` baixado da
  [última release](https://github.com/astatonn/ids-joomla-eb/releases/latest).

Ao terminar aparece a mensagem *IDS Joomla EB 3.1.0 atualizado com sucesso* e o site volta ao ar.
Se tiver despublicado o módulo de aniversariantes, publique-o novamente. Cadastros, módulos e as
opções do template continuam como estavam.

### Se nem o painel abrir

Com acesso ao servidor, na pasta do Joomla:

```bash
php cli/joomla.php extension:install --url=https://github.com/astatonn/ids-joomla-eb/releases/latest/download/pkg_ids_joomla_eb.zip
```

Sem acesso ao terminal, despublique o módulo direto no banco (troque `jos_` pelo prefixo das suas
tabelas) e depois instale a 3.1.0 pelo painel:

```sql
UPDATE jos_modules SET published = 0 WHERE module = 'mod_aniver';
```

## O que muda ao sair das versões 1.x/2.x

A atualização é feita no lugar: as extensões mantêm os mesmos identificadores e nada precisa ser
reinstalado ou reconfigurado.

### Preservado

- Cadastro de aniversariantes, UASGs e serviços do PagTesouro (as tabelas não mudam).
- Todas as opções do estilo do template e o template como padrão.
- Módulos publicados, posições e configurações.
- Itens de menu e links existentes, inclusive `index.php?option=com_pagtesouro&view=formulario`.

### Convertido automaticamente

| Módulo | Antes | Agora |
|--------|-------|-------|
| **Links** | "Quantidade de links" + campos *Link 1* a *Link 12* | Lista de links: botão **+** para adicionar, setas para ordenar. Ícones fora da lista pronta viram *Outro ícone* com a classe preservada |
| **Vídeos** | "Quantidade de vídeos" + campos *Link 1* a *Link 12* | Lista de vídeos, sem limite fixo |

### Nomes novos na lista de módulos

| Antes | Agora |
|-------|-------|
| Seção de Aniversariantes do Mês | IDS Gov - Aniversariantes |
| Seção de Links | IDS Gov - Links |
| Imagem PopUp | IDS Gov - Popup (imagem ou aviso) |
| Módulo de Feed do Instagram | IDS Gov - Instagram |
| Leia Mais | IDS Gov - Botão Leia Mais |
| Módulo para exibição de vídeos em carrossel | IDS Gov - Vídeos (YouTube) |

Os módulos que você já criou continuam com o título que você deu a eles.

### Vale conferir depois de atualizar

- **Opções do template:** a aba única *Avançado* virou seis abas (Identidade, Aparência, Menu,
  Rodapé, Redes sociais, Cidadão e cookies). Os valores são os mesmos.
- **Redes sociais:** os endereços agora precisam começar com `https://`. Ao salvar o estilo, o
  Joomla completa os que estiverem sem.
- **Aniversariantes:** a lista do mês passou a ser ordenada pelo dia, e "hoje" segue o fuso horário
  do site (**Sistema → Configuração global → Servidor → Fuso horário**).
- **Popup:** com *Fechar sozinho* desligado, o popup não fecha mais por conta própria. Há também a
  opção de exibi-lo só uma vez por visita ou por dia.
- **Instagram:** o script de terceiros (embedista.com) foi removido; fica só o embed oficial.
- **PagTesouro:** o CPF/CNPJ é validado antes de abrir o PagTesouro (11 dígitos ou 14 caracteres).
- **Personalizações de layout:** se você alterou arquivos dos módulos diretamente em
  `modules/mod_.../`, elas são substituídas. O jeito suportado de personalizar agora é criar um
  layout alternativo em `templates/SEU_TEMPLATE/html/mod_.../`.

## Avisos de atualização

A partir da 3.1.0, o Joomla avisa no painel quando houver versão nova (veja
[Atualizações no README](README.md#atualizações)). As tags `v2.0.0` e `v3.0.0` do repositório nunca
tiveram release: quem viu um aviso de atualização que falhava ao baixar estava vendo esse problema,
resolvido agora.

## Problemas comuns

**"Maximum PHP file upload size is too small" ao enviar o pacote.**
O arquivo tem cerca de 8 MB e o padrão do PHP é 2 MB. Use **Instalar da URL** ou aumente
`upload_max_filesize` e `post_max_size` no `php.ini`.

**O aviso de atualização não aparece.**
O Joomla precisa estar na versão 5.4 ou superior e o servidor precisa acessar
`raw.githubusercontent.com` e `github.com`. Em **Sistema → Atualizar → Extensões**, use
**Verificar atualizações**. Em **Sistema → Atualizar → Sites de atualização**, confira se
*IDS Joomla EB* está ativo.

**O servidor não tem acesso à internet.**
Baixe o `pkg_ids_joomla_eb.zip` em outra máquina e use **Enviar arquivo de pacote**. Os avisos
automáticos dependem do acesso do servidor ao GitHub (direto ou por proxy, configurado em
**Sistema → Configuração global → Servidor**).

**"O pacote IDS Joomla EB requer Joomla 5.4 ou superior".**
Atualize antes o Joomla para a 5.4 (a última versão da série 5) e repita a instalação.

## Ajuda

Abra uma [issue](https://github.com/astatonn/ids-joomla-eb/issues/new) informando as versões do
Joomla, do PHP e do pacote, o que aconteceu e, se houver, a linha de erro do log do servidor. A
comunidade também está no [Discord](https://discord.gg/tfUnXT3QcD).
