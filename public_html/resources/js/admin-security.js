window.sclAdminEscape = function (value) {
  var node = document.createElement('span'); node.textContent = value == null ? '' : String(value); return node.innerHTML.replace(/'/g, '&#39;').replace(/"/g, '&quot;');
};
(function ($) {
  'use strict';
  var meta = document.querySelector('meta[name="csrf-token"]');
  if (!meta) return;
  $.ajaxPrefilter(function (options, original, xhr) {
    var url = new URL(options.url, window.location.href);
    if (url.origin === window.location.origin && !/^(GET|HEAD)$/i.test(options.type)) {
      xhr.setRequestHeader('X-CSRF-Token', meta.content);
    }
  });
  document.addEventListener('submit', function (event) {
    var form = event.target;
    if (form.method.toLowerCase() !== 'post' || new URL(form.action).origin !== location.origin) return;
    var input = form.querySelector('input[name="_csrf"]');
    if (!input) { input = document.createElement('input'); input.type = 'hidden'; input.name = '_csrf'; form.appendChild(input); }
    input.value = meta.content;
  }, true);
  $(document).ajaxError(function (event, xhr) {
    if (xhr.status === 401) window.location.assign('index.php');
    if (xhr.status === 403) window.alert('Operação não permitida. Atualize a página e confira seu acesso.');
  });
})(jQuery);
