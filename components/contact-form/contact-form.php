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
?>
<form action="#" method="post" novalidate class="w-full">
  <div class="grid md:grid-cols-2 gap-x-6 gap-y-5 ">

    <input
      type="text"
      name="nombre"
      placeholder="Nombre completo*"
      class="bg-[#F4F4F4] rounded-full px-7 py-4 text-xl text-dark placeholder:text-dark/45 outline-none focus:ring-3 focus:ring-dark w-full"
    />

    <input
      type="email"
      name="correo"
      placeholder="Correo electrónico*"
      class="bg-[#F4F4F4] rounded-full px-7 py-4 text-xl text-dark placeholder:text-dark/45 outline-none focus:ring-3 focus:ring-dark w-full"
    />

    <input
      type="tel"
      name="telefono"
      placeholder="Teléfono"
      class="md:col-span-2 bg-[#F4F4F4] rounded-full px-7 py-4 text-xl text-dark placeholder:text-dark/45 outline-none focus:ring-3 focus:ring-dark w-full"
    />

    <div class="relative md:col-span-2" data-subject-dropdown>
      <button
        type="button"
        data-subject-trigger
        class="w-full bg-[#F4F4F4] rounded-full px-7 py-4 text-xl text-dark flex items-center justify-between gap-2 cursor-pointer hover:bg-[#ECECEC] transition-colors outline-none focus:ring-3 focus:ring-dark"
      >
        <span data-subject-label class="text-dark/45">Asunto*</span>
        <?php echo get_icon('chevron-down', 'h-8 w-8 text-dark transition-transform duration-200 pointer-events-none'); ?>
      </button>
      <div
        data-subject-list
        class="hidden absolute left-0 right-0 top-full mt-2 z-50 bg-[#F8F8F8] rounded-xl shadow-2xl overflow-hidden max-h-80 overflow-y-auto"
        role="listbox"
      >
        <div class="flex flex-col">
          <label class="flex items-center gap-4 py-4 px-6 border-b-3 border-[#EDEDED] cursor-pointer text-xl text-dark hover:bg-gray-200 rounded transition-colors">
            <input type="radio" name="asunto" value="" data-subject-radio data-subject-label-text="Asunto*" class="peer sr-only" checked>
            <span class="w-5 h-5 rounded-full border-2 border-dark flex items-center justify-center shrink-0 peer-checked:border-secondary peer-checked:[&>span]:scale-100">
              <span class="w-2.5 h-2.5 rounded-full bg-secondary scale-0 transition"></span>
            </span>
            <span class="font-normal peer-checked:font-bold peer-checked:text-secondary">Ninguno</span>
          </label>
          <?php foreach ($contact_subjects as $subject): ?>
            <label class="flex items-center gap-4 py-4 px-6 border-b-3 border-[#EDEDED] last:border-0 cursor-pointer text-xl text-dark hover:bg-gray-200 rounded transition-colors">
              <input type="radio" name="asunto" value="<?php echo esc_attr($subject); ?>" data-subject-radio data-subject-label-text="<?php echo esc_attr($subject); ?>" class="peer sr-only">
              <span class="w-5 h-5 rounded-full border-2 border-dark flex items-center justify-center shrink-0 peer-checked:border-secondary peer-checked:[&>span]:scale-100">
                <span class="w-2.5 h-2.5 rounded-full bg-secondary scale-0 transition"></span>
              </span>
              <span class="font-normal peer-checked:font-bold peer-checked:text-secondary"><?php echo esc_html($subject); ?></span>
            </label>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <div class="md:col-span-2 relative">
      <textarea
        name="mensaje"
        placeholder="Mensaje*"
        maxlength="100"
        rows="6"
        class="bg-[#F4F4F4] rounded-2xl px-7 py-4 pb-12 text-xl text-dark placeholder:text-dark/45 outline-none focus:ring-3 focus:ring-dark w-full resize-none"
      ></textarea>
      <div class="absolute right-5 bottom-4 flex items-center gap-2 text-sm text-dark/60">
        <span>0/100</span>
        <?php echo get_icon('paperclip', 'h-4 w-4'); ?>
      </div>
    </div>

  </div>

  <label class="flex items-start gap-3 mt-4 text-2xl text-[#8C8C8C] cursor-pointer">
    <input type="checkbox" name="privacidad" class=" h-6.5 w-6.5 accent-dark" />
    <span>He leído y acepto la política de privacidad</span>
  </label>

  <button
    type="submit"
    class="mt-8 w-full bg-dark text-white py-4 rounded-xl text-xl font-medium hover:opacity-90 transition cursor-pointer"
  >
    Enviar mensaje
  </button>
</form>

<script>
(function () {
  const root = document.querySelector('[data-subject-dropdown]');
  if (!root) return;
  const trigger = root.querySelector('[data-subject-trigger]');
  const list = root.querySelector('[data-subject-list]');
  const arrow = trigger.querySelector('svg');
  const radios = root.querySelectorAll('[data-subject-radio]');
  const label = root.querySelector('[data-subject-label]');

  const open = () => {
    list.classList.remove('hidden');
    if (arrow) arrow.classList.add('rotate-180');
  };
  const close = () => {
    list.classList.add('hidden');
    if (arrow) arrow.classList.remove('rotate-180');
  };
  const toggle = () => list.classList.contains('hidden') ? open() : close();

  trigger.addEventListener('click', toggle);
  document.addEventListener('click', (e) => {
    if (!root.contains(e.target)) close();
  });
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') close();
  });

  radios.forEach((radio) => {
    radio.addEventListener('change', () => {
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
</script>
