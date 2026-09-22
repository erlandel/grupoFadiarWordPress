<?php

// Carga las opciones de asunto administrables para el selector del formulario.
$contact_subject_posts = get_posts(array(
    'post_type'      => 'contact_subject',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
));
$contact_subjects = array();
foreach ($contact_subject_posts as $post) {
    $contact_subjects[] = array(
        'id'    => $post->ID,
        'title' => $post->post_title,
    );
}
unset($post);

// Lee el resultado del envío y conserva la URL para el redireccionamiento.
$status = isset($_GET['contact']) ? sanitize_key($_GET['contact']) : '';
$redirect_to = esc_url(add_query_arg(null, null));
?>

<?php // Avisos de éxito o error después del envío. ?>
<?php if ($status === 'ok'): ?>
  <div data-auto-dismiss class="mb-5 flex items-center justify-between rounded-xl border border-green-400 bg-green-100 p-3 text-sm text-green-800 transition-opacity duration-500 sm:mb-6 sm:p-4 sm:text-base">
    <span><?php gf_render_e('contact.success'); ?></span>
    <a href="<?php echo esc_url(remove_query_arg('contact')); ?>"
       class="text-green-800/60 hover:text-green-800 ml-4 text-lg leading-none">&times;</a>
  </div>
<?php elseif ($status === 'error'): ?>
  <div data-auto-dismiss class="mb-5 flex items-center justify-between rounded-xl border border-red-400 bg-red-100 p-3 text-sm text-red-800 transition-opacity duration-500 sm:mb-6 sm:p-4 sm:text-base">
    <span><?php gf_render_e('contact.error'); ?></span>
    <a href="<?php echo esc_url(remove_query_arg('contact')); ?>"
       class="text-red-800/60 hover:text-red-800 ml-4 text-lg leading-none">&times;</a>
  </div>
<?php endif; ?>

<!-- Formulario principal y campos ocultos para el endpoint de WordPress. -->
<form id="contactForm"
      action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
      method="post" novalidate class="w-full">
  <input type="hidden" name="action" value="grupofadiar_contact">
  <input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>">

  <!-- Honeypot antispam: debe permanecer vacío y oculto para usuarios. -->
  <textarea name="website" class="hidden" tabindex="-1"
            autocomplete="off" aria-hidden="true"></textarea>

  <div class="grid gap-x-4 gap-y-4 md:grid-cols-2 md:gap-x-6 md:gap-y-6">

    <!-- Datos personales: nombre y correo se organizan en columnas desde tablet. -->
    <div class="relative">
      <input type="text" name="nombre" placeholder="<?php echo esc_attr(gf_e('contact.name')); ?>"
             class="w-full rounded-full bg-[#F4F4F4] px-5 py-3.5 text-sm text-dark outline-none focus:ring-3 focus:ring-dark sm:px-6 sm:py-4 sm:text-base xl:px-7 xl:py-4 data-[invalid=true]:ring-3 data-[invalid=true]:ring-red-500"/>
      <p data-error class="hidden text-sm text-red-500 mt-1 ml-2"><?php gf_render_e('contact.name_error'); ?></p>
    </div>

    <div class="relative">
      <input type="email" name="correo" placeholder="<?php echo esc_attr(gf_e('contact.email')); ?>"
             class="w-full rounded-full bg-[#F4F4F4] px-5 py-3.5 text-sm text-dark outline-none focus:ring-3 focus:ring-dark sm:px-6 sm:py-4 sm:text-base xl:px-7 xl:py-4 data-[invalid=true]:ring-3 data-[invalid=true]:ring-red-500"/>
      <p data-error class="hidden text-sm text-red-500 mt-1 ml-2"><?php gf_render_e('contact.email_error'); ?></p>
    </div>

    <!-- Selector de país y teléfono, incluido como componente reutilizable. -->
    <?php include __DIR__ . '/phone-input.php'; ?>

    <!-- Selector de asunto con opciones dinámicas y validación propia. -->
    <div class="relative md:col-span-2" data-subject-dropdown>
      <button type="button" data-subject-trigger
               class="flex h-12 w-full cursor-pointer items-center justify-between gap-2 rounded-full bg-[#F4F4F4] px-5 text-sm text-dark outline-none transition-colors hover:bg-[#ECECEC] focus:ring-3 focus:ring-dark sm:h-14 sm:px-6 xl:h-14 xl:px-7 data-[invalid=true]:ring-3 data-[invalid=true]:ring-red-500">
         <span data-subject-label class="text-sm text-dark/45"><?php gf_render_e('contact.subject'); ?></span>
         <?php echo get_icon('chevron-down', 'h-6 w-6 text-dark transition-transform duration-200 pointer-events-none sm:h-7 sm:w-7 xl:h-8 xl:w-8'); ?>
      </button>
      <div data-subject-list
            class="absolute left-0 right-0 top-full z-50 mt-2 hidden max-h-[60vh] overflow-y-auto rounded-xl bg-[#F8F8F8] shadow-2xl sm:max-h-80"
           role="listbox">
        <div class="flex flex-col">
          <?php foreach ($contact_subjects as $subject_data):
            $subject_title = gf_get_post_title($subject_data['id']);
          ?>
            <label class="flex cursor-pointer items-center gap-4 rounded border-b-3 border-[#EDEDED] px-6 py-4 text-sm text-dark transition-colors hover:bg-gray-200 last:border-0">
              <input type="radio" name="asunto"
                     value="<?php echo esc_attr($subject_data['title']); ?>"
                     data-subject-radio
                     data-subject-label-text="<?php echo esc_attr($subject_title); ?>"
                     class="peer sr-only">
              <span class="w-4 h-4 sm:w-5 sm:h-5 rounded-full border-2 border-dark flex items-center justify-center shrink-0 peer-checked:border-secondary peer-checked:[&>span]:scale-100">
                <span class="w-2.5 h-2.5 rounded-full bg-secondary scale-0 transition"></span>
              </span>
              <span class="font-normal peer-checked:font-bold peer-checked:text-secondary"><?php echo esc_html($subject_title); ?></span>
            </label>
          <?php endforeach; ?>
        </div>
      </div>
      <p data-error class="hidden text-sm text-red-500 mt-1"><?php gf_render_e('contact.subject_error'); ?></p>
    </div>

    <!-- Mensaje libre y contador de palabras. -->
    <div class="md:col-span-2">
      <div class="relative">
        <textarea name="mensaje" placeholder="<?php echo esc_attr(gf_e('contact.message')); ?>"
                  rows="6" data-word-limit="100"
                   class="w-full resize-none rounded-2xl bg-[#F4F4F4] pb-12 pl-5 pr-16 pt-4 text-sm text-dark outline-none focus:ring-3 focus:ring-dark sm:pl-6 sm:pr-20 sm:text-base xl:pl-7 xl:pr-20 xl:pb-14 data-[invalid=true]:ring-3 data-[invalid=true]:ring-red-500"></textarea>
        <div class="pointer-events-none absolute bottom-5 right-5 flex items-center gap-2 text-xs text-dark/60 sm:bottom-7 sm:right-7 sm:text-sm">
          <span data-counter>0/100</span>
          <?php echo get_icon('paperclip', 'h-4 w-4'); ?>
        </div>
      </div>
      <p data-error class="hidden text-sm text-red-500 mt-1 ml-2"><?php gf_render_e('contact.message_empty'); ?></p>
    </div>

  </div>

  <!-- Consentimiento de privacidad y error asociado. -->
  <div class="relative mt-4">
    <label data-privacidad-wrapper
           class="flex cursor-pointer items-start gap-2 text-sm leading-snug text-[#8C8C8C] sm:text-base">
      <input type="checkbox" name="privacidad" class="mt-0.5 h-5 w-5 shrink-0 accent-dark"/>
      <span class="min-w-0"><?php gf_render_e('contact.privacy'); ?></span>
    </label>
    <p data-error class="hidden text-sm text-red-500 mt-1"><?php gf_render_e('contact.privacy_error'); ?></p>
  </div>

  <!-- Envío del formulario y plantilla del indicador de carga. -->
  <button type="submit" data-submit-btn
           class="mt-5 w-full cursor-pointer rounded-xl bg-dark py-3.5 text-base font-medium text-white transition hover:opacity-90 disabled:cursor-not-allowed sm:py-4 sm:text-lg xl:py-4">
    <span data-submit-text class="inline-flex items-center gap-2"><?php gf_render_e('contact.submit'); ?></span>
  </button>

  <template id="submitSpinnerTmpl">
    <?php echo get_icon('spinner', 'h-5 w-5 animate-spin'); ?>
  </template>
</form>
