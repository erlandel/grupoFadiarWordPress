// Selector desplegable de asuntos.
(function () {
  const root = document.querySelector('[data-subject-dropdown]');
  if (!root) return;
  const trigger = root.querySelector('[data-subject-trigger]');
  const list = root.querySelector('[data-subject-list]');
  const arrow = trigger && trigger.querySelector('svg');
  const radios = root.querySelectorAll('[data-subject-radio]');
  const label = root.querySelector('[data-subject-label]');

  const open = function () {
    list.classList.remove('hidden');
    if (arrow) arrow.classList.add('rotate-180');
  };
  const close = function () {
    list.classList.add('hidden');
    if (arrow) arrow.classList.remove('rotate-180');
  };
  const toggle = function () {
    list.classList.contains('hidden') ? open() : close();
  };

  trigger.addEventListener('click', toggle);
  document.addEventListener('click', function (e) {
    if (!root.contains(e.target)) close();
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') close();
  });

  radios.forEach(function (radio) {
    radio.addEventListener('change', function () {
      if (!radio.checked) return;
      const labelText = radio.getAttribute('data-subject-label-text');
      const value = radio.value;
      if (label) {
        label.textContent = labelText;
        if (value === '') {
          label.classList.add('text-dark/45');
        } else {
          label.classList.remove('text-dark/45');
        }
      }
      close();
    });
  });
})();

// Selector de país y composición del teléfono.
(function () {
  const root = document.querySelector('[data-phone-input]');
  if (!root) return;

  const trigger   = root.querySelector('[data-phone-trigger]');
  const list      = root.querySelector('[data-phone-list]');
  const arrow     = trigger.querySelector('svg');
  const flag      = root.querySelector('[data-phone-flag]');
  const code      = root.querySelector('[data-phone-code]');
  const number    = root.querySelector('[data-phone-number]');
  const hidden    = root.querySelector('[data-phone-hidden]');
  const search    = root.querySelector('[data-phone-search]');
  const emptyNote = root.querySelector('[data-phone-empty]');
  const labels    = root.querySelectorAll('[data-country-code]');

  function emit() {
    const digits = number.value.replace(/[^0-9]/g, '');
    hidden.value = code.textContent.trim() + ' ' + digits;
  }

  function open()  { list.classList.remove('hidden'); arrow && arrow.classList.add('rotate-180'); trigger.setAttribute('aria-expanded', 'true'); }
  function close() { list.classList.add('hidden');    arrow && arrow.classList.remove('rotate-180'); trigger.setAttribute('aria-expanded', 'false'); }
  function toggle() { list.classList.contains('hidden') ? open() : close(); }

  trigger.addEventListener('click', toggle);
  document.addEventListener('click', function (e) { if (!root.contains(e.target)) close(); });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });

  function normalize(s) {
    return s.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
  }

  labels.forEach(function (label) {
    label.addEventListener('click', function (e) {
      e.preventDefault();
      flag.textContent = label.getAttribute('data-country-emoji');
      code.textContent = label.getAttribute('data-country-dial');
      const container = root.querySelector('[data-phone-container]');
      if (container) container.setAttribute('data-phone-lengths', label.getAttribute('data-country-lengths'));
      number.value = '';
      close();
      emit();
      clearError();
      number.focus();
    });
  });

  function clearError() {
    const c = root.querySelector('[data-phone-container]');
    if (c) c.removeAttribute('data-invalid');
    const err = root.querySelector('[data-error]');
    if (err) err.classList.add('hidden');
  }

  search.addEventListener('input', function () {
    const q = normalize(search.value);
    let visible = 0;
    labels.forEach(function (l) {
      const name = normalize(l.getAttribute('data-country-name'));
      const show = name.indexOf(q) !== -1;
      l.style.display = show ? '' : 'none';
      if (show) visible++;
    });
    emptyNote.classList.toggle('hidden', visible > 0);
  });

  number.addEventListener('input', function () {
    const v = number.value.replace(/[^0-9]/g, '');
    if (v !== number.value) number.value = v;
    emit();
    const ev = new Event('input', { bubbles: true });
    hidden.dispatchEvent(ev);
  });

  emit();
})();

// Validación del formulario, contador y estado de envío.
(function () {
  const form = document.getElementById('contactForm');
  if (!form) return;

  const S = window.gfStrings || {};
  const rules = [
    { name: 'nombre',    selector: 'input[name="nombre"]',       test: function (v) { return /^\S+(?:\s+\S+){2,}$/.test(v.trim()); },           msg: S['contact.name_error'] || 'Ingresa tu nombre y ambos apellidos',   event: 'input' },
    { name: 'correo',    selector: 'input[name="correo"]',      test: function (v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim()); },   msg: S['contact.email_error'] || 'Correo electrónico no válido',           event: 'input' },
    { name: 'telefono',  selector: 'input[name="telefono"]',    test: function (v) {
      const m = v.trim().match(/^\+?\d{1,4}\s(\d+)$/);
      if (!m) return false;
      const container = document.querySelector('[data-phone-container]');
      if (!container) return false;
      const lengths = (container.getAttribute('data-phone-lengths') || '').split(',');
      return lengths.indexOf(String(m[1].length)) !== -1;
    },         msg: S['contact.phone_error'] || 'Ingresa un número de teléfono válido',   event: 'input',  isPhone: true },
    { name: 'asunto',    selector: 'input[name="asunto"]',      test: function ()  { const c = form.querySelector('input[name="asunto"]:checked'); return c && c.value !== ''; }, msg: S['contact.subject_error'] || 'Selecciona un asunto', event: 'change', isRadio: true },
    { name: 'mensaje',   selector: 'textarea[name="mensaje"]',  test: function (v) { const w = v.trim() ? v.trim().split(/\s+/).length : 0; return w > 0 && w <= 100; },  msg: S['contact.message_limit'] || 'El mensaje no puede exceder las 100 palabras',  event: 'input' },
    { name: 'privacidad', selector: 'input[name="privacidad"]', test: function (_, el) { return el.checked; },                                 msg: S['contact.privacy_error'] || 'Debes aceptar la política de privacidad', event: 'change' },
  ];

  const els = {};
  const errors = {};

  rules.forEach(function (r) {
    if (r.isRadio) {
      const container = document.querySelector('[data-subject-dropdown]');
      els[r.name] = container ? container.querySelector('[data-subject-trigger]') : null;
      errors[r.name] = container ? container.querySelector('[data-error]') : null;
    } else if (r.isPhone) {
      const phoneWrap = document.querySelector('[data-phone-input]');
      els[r.name] = form.querySelector(r.selector);
      errors[r.name] = phoneWrap ? phoneWrap.querySelector('[data-error]') : null;
    } else {
      const el = form.querySelector(r.selector);
      els[r.name] = el;
      errors[r.name] = el ? el.closest('.relative').querySelector('[data-error]') : null;
    }
  });

  function setError(name) {
    if (name === 'privacidad') {
      const w = document.querySelector('[data-privacidad-wrapper]');
      if (w) w.setAttribute('data-invalid', 'true');
    } else if (rules.some(function (r) { return r.name === name && r.isPhone; })) {
      const c = document.querySelector('[data-phone-container]');
      if (c) c.setAttribute('data-invalid', 'true');
    } else if (els[name]) {
      els[name].setAttribute('data-invalid', 'true');
    }
    if (errors[name]) errors[name].classList.remove('hidden');
  }

  function clearError(name) {
    if (name === 'privacidad') {
      const w = document.querySelector('[data-privacidad-wrapper]');
      if (w) w.removeAttribute('data-invalid');
    } else if (rules.some(function (r) { return r.name === name && r.isPhone; })) {
      const c = document.querySelector('[data-phone-container]');
      if (c) c.removeAttribute('data-invalid');
    } else if (els[name]) {
      els[name].removeAttribute('data-invalid');
    }
    if (errors[name]) errors[name].classList.add('hidden');
  }

  function validate(name) {
    const r = rules.filter(function (x) { return x.name === name; })[0];
    if (!r) return true;
    const el = els[name];
    if (!el) return true;
    let valid;
    if (r.isRadio) {
      valid = r.test();
    } else if (name === 'privacidad') {
      valid = el.checked;
    } else {
      valid = r.test(el.value);
    }
    if (valid) clearError(name);
    else setError(name);
    return valid;
  }

  rules.forEach(function (r) {
    if (r.isRadio) {
      form.querySelectorAll('input[name="asunto"]').forEach(function (radio) {
        radio.addEventListener('change', function () { validate('asunto'); });
      });
    } else {
      const el = els[r.name];
      if (el) el.addEventListener(r.event, function () { validate(r.name); });
    }
  });

  const textarea = els.mensaje;
  const counter = document.querySelector('[data-counter]');
  if (textarea && counter) {
    const limit = parseInt(textarea.getAttribute('data-word-limit')) || 100;
    const update = function () {
      const val = textarea.value.trim();
      const words = val ? val.split(/\s+/).length : 0;
      counter.textContent = words + '/' + limit;
      if (words > limit) {
        textarea.setAttribute('data-invalid', 'true');
      } else {
        textarea.removeAttribute('data-invalid');
      }
    };
    textarea.addEventListener('input', update);
    update();
  }

  form.addEventListener('submit', function (e) {
    let ok = true;
    rules.forEach(function (r) {
      if (!validate(r.name)) ok = false;
    });
    if (!ok) {
      e.preventDefault();
      const first = form.querySelector('[data-invalid=true]');
      if (first) {
        const target = first.closest('.relative') || first.closest('[data-subject-dropdown]');
        if (target) target.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
      return;
    }
    const btn = form.querySelector('[data-submit-btn]');
    const text = btn.querySelector('[data-submit-text]');
    const tmpl = document.getElementById('submitSpinnerTmpl');
    text.textContent = '';
    if (tmpl) text.appendChild(tmpl.content.firstElementChild.cloneNode(true));
    text.appendChild(document.createTextNode(' ' + (window.gfStrings['contact.submitting'] || 'Enviando...')));
    btn.disabled = true;
  });
})();

// Cierre automático de los avisos y limpieza de la URL.
(function () {
  const el = document.querySelector('[data-auto-dismiss]');
  if (!el) return;
  setTimeout(function () {
    el.classList.add('opacity-0');
    setTimeout(function () {
      if (el.parentNode) el.parentNode.removeChild(el);
      if (window.history.replaceState) {
        const url = window.location.pathname + window.location.search.replace(/[?&]contact=[^&]*/, '').replace(/^&/, '?');
        if (window.location.hash) url += window.location.hash;
        window.history.replaceState({}, '', url);
      }
    }, 500);
  }, 30000);
})();
