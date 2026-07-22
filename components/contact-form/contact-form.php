<?php

$contact_subject_posts = get_posts(array(
    'post_type'      => 'contact_subject',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
));
$contact_subjects = array();
foreach ($contact_subject_posts as $post) {
    $contact_subjects[] = $post->post_title;
}
unset($post);

$status = isset($_GET['contact']) ? sanitize_key($_GET['contact']) : '';
$redirect_to = esc_url(add_query_arg(null, null));
?>

<?php if ($status === 'ok'): ?>
  <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-800 rounded-xl text-lg flex items-center justify-between">
    <span>Mensaje enviado correctamente. Te contactaremos pronto.</span>
    <a href="<?php echo esc_url(remove_query_arg('contact')); ?>"
       class="text-green-800/60 hover:text-green-800 ml-4 text-2xl leading-none">&times;</a>
  </div>
<?php elseif ($status === 'error'): ?>
  <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-800 rounded-xl text-lg flex items-center justify-between">
    <span>Ocurrió un error al enviar el mensaje. Intenta de nuevo.</span>
    <a href="<?php echo esc_url(remove_query_arg('contact')); ?>"
       class="text-red-800/60 hover:text-red-800 ml-4 text-2xl leading-none">&times;</a>
  </div>
<?php endif; ?>

<form id="contactForm"
      action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
      method="post" novalidate class="w-full">
  <input type="hidden" name="action" value="grupofadiar_contact">
  <input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>">
  <textarea name="website" class="hidden" tabindex="-1"
            autocomplete="off" aria-hidden="true"></textarea>

  <div class="grid md:grid-cols-2 gap-x-6 gap-y-6">

    <div class="relative">
      <input type="text" name="nombre" placeholder="Nombre completo*"
             class="bg-[#F4F4F4] rounded-full px-7 py-4 text-xl text-dark placeholder:text-dark/45 outline-none focus:ring-3 focus:ring-dark w-full data-[invalid=true]:ring-3 data-[invalid=true]:ring-red-500"/>
      <p data-error class="hidden text-sm text-red-500 mt-1 ml-2">Ingresa tu nombre y ambos apellidos</p>
    </div>

    <div class="relative">
      <input type="email" name="correo" placeholder="Correo electrónico*"
             class="bg-[#F4F4F4] rounded-full px-7 py-4 text-xl text-dark placeholder:text-dark/45 outline-none focus:ring-3 focus:ring-dark w-full data-[invalid=true]:ring-3 data-[invalid=true]:ring-red-500"/>
      <p data-error class="hidden text-sm text-red-500 mt-1 ml-2">Correo electrónico no válido</p>
    </div>

    <?php include __DIR__ . '/phone-input.php'; ?>

    <div class="relative md:col-span-2" data-subject-dropdown>
      <button type="button" data-subject-trigger
              class="w-full bg-[#F4F4F4] rounded-full px-7 py-4 text-xl text-dark flex items-center justify-between gap-2 cursor-pointer hover:bg-[#ECECEC] transition-colors outline-none focus:ring-3 focus:ring-dark data-[invalid=true]:ring-3 data-[invalid=true]:ring-red-500">
        <span data-subject-label class="text-dark/45">Asunto*</span>
        <?php echo get_icon('chevron-down', 'h-8 w-8 text-dark transition-transform duration-200 pointer-events-none'); ?>
      </button>
      <div data-subject-list
           class="hidden absolute left-0 right-0 top-full mt-2 z-50 bg-[#F8F8F8] rounded-xl shadow-2xl overflow-hidden max-h-80 overflow-y-auto"
           role="listbox">
        <div class="flex flex-col">
          <label class="flex items-center gap-4 py-4 px-6 border-b-3 border-[#EDEDED] cursor-pointer text-xl text-dark hover:bg-gray-200 rounded transition-colors">
            <input type="radio" name="asunto" value=""
                   data-subject-radio data-subject-label-text="Asunto*"
                   class="peer sr-only" checked>
            <span class="w-5 h-5 rounded-full border-2 border-dark flex items-center justify-center shrink-0 peer-checked:border-secondary peer-checked:[&>span]:scale-100">
              <span class="w-2.5 h-2.5 rounded-full bg-secondary scale-0 transition"></span>
            </span>
            <span class="font-normal peer-checked:font-bold peer-checked:text-secondary">Ninguno</span>
          </label>
          <?php foreach ($contact_subjects as $subject): ?>
            <label class="flex items-center gap-4 py-4 px-6 border-b-3 border-[#EDEDED] last:border-0 cursor-pointer text-xl text-dark hover:bg-gray-200 rounded transition-colors">
              <input type="radio" name="asunto"
                     value="<?php echo esc_attr($subject); ?>"
                     data-subject-radio
                     data-subject-label-text="<?php echo esc_attr($subject); ?>"
                     class="peer sr-only">
              <span class="w-5 h-5 rounded-full border-2 border-dark flex items-center justify-center shrink-0 peer-checked:border-secondary peer-checked:[&>span]:scale-100">
                <span class="w-2.5 h-2.5 rounded-full bg-secondary scale-0 transition"></span>
              </span>
              <span class="font-normal peer-checked:font-bold peer-checked:text-secondary"><?php echo esc_html($subject); ?></span>
            </label>
          <?php endforeach; ?>
        </div>
      </div>
      <p data-error class="hidden text-sm text-red-500 mt-1">Selecciona un asunto</p>
    </div>

    <div class="md:col-span-2">
      <div class="relative">
        <textarea name="mensaje" placeholder="Mensaje*"
                  rows="6" data-word-limit="100"
                  class="bg-[#F4F4F4] rounded-2xl pl-7 pr-20 pt-4 pb-14 text-xl text-dark placeholder:text-dark/45 outline-none focus:ring-3 focus:ring-dark w-full resize-none data-[invalid=true]:ring-3 data-[invalid=true]:ring-red-500"></textarea>
        <div class="pointer-events-none absolute right-7 bottom-7 flex items-center gap-2 text-sm text-dark/60">
          <span data-counter>0/100</span>
          <?php echo get_icon('paperclip', 'h-4 w-4'); ?>
        </div>
      </div>
      <p data-error class="hidden text-sm text-red-500 mt-1 ml-2">El mensaje no puede estar vacío</p>
    </div>

  </div>

  <div class="relative mt-4">
    <label data-privacidad-wrapper
           class="flex items-start gap-3 text-2xl text-[#8C8C8C] cursor-pointer">
      <input type="checkbox" name="privacidad" class="h-6.5 w-6.5 accent-dark"/>
      <span>He leído y acepto la política de privacidad</span>
    </label>
    <p data-error class="hidden text-sm text-red-500 mt-1">Debes aceptar la política de privacidad</p>
  </div>

  <button type="submit"
          class="mt-8 w-full bg-dark text-white py-4 rounded-xl text-xl font-medium hover:opacity-90 transition cursor-pointer">
    Enviar mensaje
  </button>
</form>

<script>
(function () {
  var root = document.querySelector('[data-subject-dropdown]');
  if (!root) return;
  var trigger = root.querySelector('[data-subject-trigger]');
  var list = root.querySelector('[data-subject-list]');
  var arrow = trigger && trigger.querySelector('svg');
  var radios = root.querySelectorAll('[data-subject-radio]');
  var label = root.querySelector('[data-subject-label]');

  var open = function () {
    list.classList.remove('hidden');
    if (arrow) arrow.classList.add('rotate-180');
  };
  var close = function () {
    list.classList.add('hidden');
    if (arrow) arrow.classList.remove('rotate-180');
  };
  var toggle = function () {
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
      var labelText = radio.getAttribute('data-subject-label-text');
      var value = radio.value;
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

(function () {
  var root = document.querySelector('[data-phone-input]');
  if (!root) return;

  var trigger   = root.querySelector('[data-phone-trigger]');
  var list      = root.querySelector('[data-phone-list]');
  var arrow     = trigger.querySelector('svg');
  var flag      = root.querySelector('[data-phone-flag]');
  var code       = root.querySelector('[data-phone-code]');
  var number    = root.querySelector('[data-phone-number]');
  var hidden    = root.querySelector('[data-phone-hidden]');
  var search    = root.querySelector('[data-phone-search]');
  var emptyNote = root.querySelector('[data-phone-empty]');
  var labels    = root.querySelectorAll('[data-country-code]');

  function emit() {
    var digits = number.value.replace(/[^0-9]/g, '');
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
      var container = root.querySelector('[data-phone-container]');
      if (container) container.setAttribute('data-phone-lengths', label.getAttribute('data-country-lengths'));
      number.value = '';
      close();
      emit();
      clearError();
      number.focus();
    });
  });

  function clearError() {
    var c = root.querySelector('[data-phone-container]');
    if (c) c.removeAttribute('data-invalid');
    var err = root.querySelector('[data-error]');
    if (err) err.classList.add('hidden');
  }

  search.addEventListener('input', function () {
    var q = normalize(search.value);
    var visible = 0;
    labels.forEach(function (l) {
      var name = normalize(l.getAttribute('data-country-name'));
      var show = name.indexOf(q) !== -1;
      l.style.display = show ? '' : 'none';
      if (show) visible++;
    });
    emptyNote.classList.toggle('hidden', visible > 0);
  });

  number.addEventListener('input', function () {
    var v = number.value.replace(/[^0-9]/g, '');
    if (v !== number.value) number.value = v;
    emit();
    var ev = new Event('input', { bubbles: true });
    hidden.dispatchEvent(ev);
  });

  emit();
})();

(function () {
  var form = document.getElementById('contactForm');
  if (!form) return;

  var rules = [
    { name: 'nombre',    selector: 'input[name="nombre"]',       test: function (v) { return /^\S+(?:\s+\S+){2,}$/.test(v.trim()); },           msg: 'Ingresa tu nombre y ambos apellidos',   event: 'input' },
    { name: 'correo',    selector: 'input[name="correo"]',      test: function (v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim()); },   msg: 'Correo electrónico no válido',           event: 'input' },
    { name: 'telefono',  selector: 'input[name="telefono"]',    test: function (v) {
      var m = v.trim().match(/^\+?\d{1,4}\s(\d+)$/);
      if (!m) return false;
      var container = document.querySelector('[data-phone-container]');
      if (!container) return false;
      var lengths = (container.getAttribute('data-phone-lengths') || '').split(',');
      return lengths.indexOf(String(m[1].length)) !== -1;
    },         msg: 'Ingresa un número de teléfono válido',   event: 'input',  isPhone: true },
    { name: 'asunto',    selector: 'input[name="asunto"]',      test: function ()  { var c = form.querySelector('input[name="asunto"]:checked'); return c && c.value !== ''; }, msg: 'Selecciona un asunto', event: 'change', isRadio: true },
    { name: 'mensaje',   selector: 'textarea[name="mensaje"]',  test: function (v) { var w = v.trim() ? v.trim().split(/\s+/).length : 0; return w > 0 && w <= 100; },  msg: 'El mensaje no puede exceder las 100 palabras',  event: 'input' },
    { name: 'privacidad', selector: 'input[name="privacidad"]', test: function (_, el) { return el.checked; },                                 msg: 'Debes aceptar la política de privacidad', event: 'change' },
  ];

  var els = {};
  var errors = {};

  rules.forEach(function (r) {
    if (r.isRadio) {
      var container = document.querySelector('[data-subject-dropdown]');
      els[r.name] = container ? container.querySelector('[data-subject-trigger]') : null;
      errors[r.name] = container ? container.querySelector('[data-error]') : null;
    } else if (r.isPhone) {
      var phoneWrap = document.querySelector('[data-phone-input]');
      els[r.name] = form.querySelector(r.selector);
      errors[r.name] = phoneWrap ? phoneWrap.querySelector('[data-error]') : null;
    } else {
      var el = form.querySelector(r.selector);
      els[r.name] = el;
      errors[r.name] = el ? el.closest('.relative').querySelector('[data-error]') : null;
    }
  });

  function setError(name) {
    if (name === 'privacidad') {
      var w = document.querySelector('[data-privacidad-wrapper]');
      if (w) w.setAttribute('data-invalid', 'true');
    } else if (rules.some(function (r) { return r.name === name && r.isPhone; })) {
      var c = document.querySelector('[data-phone-container]');
      if (c) c.setAttribute('data-invalid', 'true');
    } else if (els[name]) {
      els[name].setAttribute('data-invalid', 'true');
    }
    if (errors[name]) errors[name].classList.remove('hidden');
  }

  function clearError(name) {
    if (name === 'privacidad') {
      var w = document.querySelector('[data-privacidad-wrapper]');
      if (w) w.removeAttribute('data-invalid');
    } else if (rules.some(function (r) { return r.name === name && r.isPhone; })) {
      var c = document.querySelector('[data-phone-container]');
      if (c) c.removeAttribute('data-invalid');
    } else if (els[name]) {
      els[name].removeAttribute('data-invalid');
    }
    if (errors[name]) errors[name].classList.add('hidden');
  }

  function validate(name) {
    var r = rules.filter(function (x) { return x.name === name; })[0];
    if (!r) return true;
    var el = els[name];
    if (!el) return true;
    var valid;
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
      var el = els[r.name];
      if (el) el.addEventListener(r.event, function () { validate(r.name); });
    }
  });

  var textarea = els.mensaje;
  var counter = document.querySelector('[data-counter]');
  if (textarea && counter) {
    var limit = parseInt(textarea.getAttribute('data-word-limit')) || 100;
    var update = function () {
      var val = textarea.value.trim();
      var words = val ? val.split(/\s+/).length : 0;
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
    var ok = true;
    rules.forEach(function (r) {
      if (!validate(r.name)) ok = false;
    });
    if (!ok) {
      e.preventDefault();
      var first = form.querySelector('[data-invalid=true]');
      if (first) {
        var target = first.closest('.relative') || first.closest('[data-subject-dropdown]');
        if (target) target.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    }
  });
})();
</script>
