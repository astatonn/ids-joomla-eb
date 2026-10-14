/**
 * IDS Joomla EB - com_pagtesouro
 * Formulário público: monta o endereço da GRU no portal PagTesouro e o abre em nova aba.
 * Não envia nenhum dado para o site: tudo acontece no navegador.
 *
 * (C) 2025-2026 Lucas Lima e colaboradores - GNU GPL v2 ou posterior
 */
(function () {
  'use strict';

  function iniciar() {
    var form = document.getElementById('pagtesouroForm');

    if (!form || !window.Joomla || typeof Joomla.getOptions !== 'function') {
      return;
    }

    var opcoes = Joomla.getOptions('com_pagtesouro.formulario') || {};
    var uasgs = opcoes.uasgs || [];
    var portal = opcoes.portal || '';
    var selectUasg = document.getElementById('pagtesouro-uasg');
    var selectServico = document.getElementById('pagtesouro-servico');
    var caixaErro = document.getElementById('pagtesouro-erro');

    function texto(chave, padrao) {
      var valor = Joomla.Text ? Joomla.Text._(chave) : '';

      return valor && valor !== chave ? valor : padrao;
    }

    function uasgSelecionada() {
      var id = parseInt(selectUasg.value, 10);

      return uasgs.filter(function (uasg) { return uasg.id === id; })[0] || null;
    }

    function servicoSelecionado() {
      var uasg = uasgSelecionada();
      var id = parseInt(selectServico.value, 10);

      if (!uasg) {
        return null;
      }

      return uasg.servicos.filter(function (servico) { return servico.id === id; })[0] || null;
    }

    function mostrarErro(mensagem) {
      caixaErro.textContent = mensagem;
      caixaErro.hidden = false;
      caixaErro.scrollIntoView({ block: 'nearest' });
    }

    function limparErro() {
      caixaErro.textContent = '';
      caixaErro.hidden = true;
    }

    function preencherServicos() {
      var uasg = uasgSelecionada();
      var servicos = uasg ? uasg.servicos : [];

      selectServico.innerHTML = '';

      var vazio = document.createElement('option');
      vazio.value = '';
      vazio.textContent = uasg && !servicos.length
        ? texto('COM_PAGTESOURO_SEM_SERVICOS', 'Nenhum serviço cadastrado para esta UASG')
        : texto('COM_PAGTESOURO_SELECT_SERVICE', '- Selecione o Serviço -');
      selectServico.appendChild(vazio);

      servicos.forEach(function (servico) {
        var opcao = document.createElement('option');
        opcao.value = String(servico.id);
        opcao.textContent = servico.codigo + ' - ' + servico.descricao;
        selectServico.appendChild(opcao);
      });

      // Com um único serviço, ele já vem escolhido
      if (servicos.length === 1) {
        selectServico.value = String(servicos[0].id);
      }

      selectServico.disabled = !servicos.length;
    }

    selectUasg.addEventListener('change', function () {
      limparErro();
      preencherServicos();
    });

    form.addEventListener('submit', function (evento) {
      evento.preventDefault();
      limparErro();

      var uasg = uasgSelecionada();
      var servico = servicoSelecionado();
      var nome = document.getElementById('pagtesouro-nome').value.trim();
      // Mantém letras: o CNPJ alfanumérico usa letras maiúsculas além dos dígitos
      var cpfCnpj = document.getElementById('pagtesouro-cpfcnpj').value.replace(/[^0-9a-z]/gi, '').toUpperCase();
      var referencia = document.getElementById('pagtesouro-referencia').value.trim();
      var valor = document.getElementById('pagtesouro-valor').value.trim().replace(',', '.');

      if (!uasg || !servico || !nome || !cpfCnpj || !referencia || !valor) {
        mostrarErro(texto('COM_PAGTESOURO_ERROR_FILL_ALL_FIELDS', 'Por favor, preencha todos os campos obrigatórios.'));

        return;
      }

      if (!/^\d{11}$/.test(cpfCnpj) && !/^[0-9A-Z]{12}\d{2}$/.test(cpfCnpj)) {
        mostrarErro(texto('COM_PAGTESOURO_ERROR_CPF_CNPJ', 'Informe um CPF (11 dígitos) ou CNPJ (14 caracteres) válido.'));

        return;
      }

      if (!(parseFloat(valor) > 0)) {
        mostrarErro(texto('COM_PAGTESOURO_ERROR_VALOR', 'Informe um valor maior que zero.'));

        return;
      }

      var parametros = [
        ['orgao', uasg.orgao],
        ['ug', uasg.ug],
        ['servico', servico.codigo],
        ['nomeContribuinte', nome],
        ['cpfCnpjContribuinte', cpfCnpj],
        ['numeroReferencia', referencia],
        ['valorPrincipal', valor]
      ].map(function (par) {
        return par[0] + '=' + encodeURIComponent(par[1]);
      }).join('&');

      window.open(portal + '?' + parametros, '_blank', 'noopener');
    });

    // Estado inicial (inclusive quando só existe uma UASG e ela já vem selecionada)
    preencherServicos();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', iniciar);
  } else {
    iniciar();
  }
})();
