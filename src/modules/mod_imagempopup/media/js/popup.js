/**
 * IDS Joomla EB - mod_imagempopup
 * Abre o popup ao carregar a página, respeitando a frequência configurada, e cuida do
 * fechamento: botão, tecla Esc, clique fora da janela e temporizador.
 *
 * (C) 2024-2026 Henrique Michels Voicolesco, Lucas Lima e colaboradores - GNU GPL v2 ou posterior
 */
(function () {
  'use strict';

  function hoje() {
    var d = new Date();

    return d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDate();
  }

  // Devolve o armazenamento do navegador, ou null se estiver bloqueado (modo privado, políticas...).
  function armazenamento(tipo) {
    try {
      var area = window[tipo];
      area.setItem('__mod_imagempopup__', '1');
      area.removeItem('__mod_imagempopup__');

      return area;
    } catch (erro) {
      return null;
    }
  }

  function iniciar(popup) {
    var frequencia = popup.getAttribute('data-frequencia') || 'sempre';
    var chave = 'mod_imagempopup.' + popup.id + '.' + (popup.getAttribute('data-assinatura') || '');
    var area = frequencia === 'sessao' ? armazenamento('sessionStorage')
      : frequencia === 'dia' ? armazenamento('localStorage')
        : null;

    if (area) {
      var visto = area.getItem(chave);

      if (frequencia === 'sessao' && visto) {
        return;
      }

      if (frequencia === 'dia' && visto === hoje()) {
        return;
      }
    }

    var botao = popup.querySelector('[data-popup-fechar]');
    var contador = popup.querySelector('[data-popup-contador]');
    var segundos = parseInt(popup.getAttribute('data-segundos'), 10) || 0;
    var focoAnterior = document.activeElement;
    var intervalo = null;

    function fechar() {
      if (intervalo) {
        window.clearInterval(intervalo);
      }

      popup.hidden = true;
      document.removeEventListener('keydown', aoTeclar);

      if (focoAnterior && typeof focoAnterior.focus === 'function') {
        focoAnterior.focus();
      }
    }

    function aoTeclar(evento) {
      if (evento.key === 'Escape') {
        fechar();
      }
    }

    popup.hidden = false;

    if (area) {
      area.setItem(chave, frequencia === 'dia' ? hoje() : '1');
    }

    if (botao) {
      botao.addEventListener('click', fechar);
      botao.focus();
    }

    document.addEventListener('keydown', aoTeclar);

    if (popup.getAttribute('data-fechar-fora') === '1') {
      popup.addEventListener('click', function (evento) {
        if (evento.target === popup) {
          fechar();
        }
      });
    }

    if (segundos > 0) {
      intervalo = window.setInterval(function () {
        segundos -= 1;

        if (contador) {
          contador.textContent = String(segundos);
        }

        if (segundos <= 0) {
          fechar();
        }
      }, 1000);
    }
  }

  function iniciarTodos() {
    Array.prototype.forEach.call(document.querySelectorAll('.mod-imagempopup.overlay'), iniciar);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', iniciarTodos);
  } else {
    iniciarTodos();
  }
})();
