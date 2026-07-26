<?php
/**
 * Componente: Phone Input con selector de país
 * Sigue el diseño de input-telefono-diseno.md (sección 10 — versión WordPress).
 *
 * Emite un <input type="hidden" name="telefono"> con formato "+XX 12345678".
 * El input visible (name="telefono_numero") solo acepta dígitos.
 *
 * Requiere: components/contact-form/data/countries.json (generado una vez con tools/).
 */

$countries_json_path = __DIR__ . '/data/countries.json';
$countries = array();
if (is_file($countries_json_path)) {
    $raw = file_get_contents($countries_json_path);
    $decoded = json_decode($raw, true);
    if (is_array($decoded)) {
        $countries = $decoded;
    }
}

function code_to_flag_emoji(string $code): string {
    if (strlen($code) !== 2) return '';
    $a = 0x1F1E6 + (ord($code[0]) - 0x41);
    $b = 0x1F1E6 + (ord($code[1]) - 0x41);
    return mb_chr($a) . mb_chr($b);
}

// País por defecto: Cuba
$default_entry = array('code' => 'CU', 'phoneCode' => '+53');
foreach ($countries as $c) {
    if ($c['code'] === 'CU') {
        $default_entry = $c;
        break;
    }
}

$gf_country_lang = gf_current_lang();
?>
<div class="relative md:col-span-2" data-phone-input>
   <div class="bg-[#F4F4F4] rounded-full flex items-center w-full min-w-0 overflow-hidden
              focus-within:ring-3 focus-within:ring-dark
              data-[invalid=true]:ring-3 data-[invalid=true]:ring-red-500"
        data-phone-container
        data-phone-lengths="<?php echo esc_attr(implode(',', $default_entry['validLengths'] ?? [6,15])); ?>">
    <button type="button"
            data-phone-trigger
            class="flex items-center gap-2 pl-7 pr-3 py-4 outline-none shrink-0 cursor-pointer"
            aria-haspopup="listbox"
            aria-expanded="false">
      <span data-phone-flag class="text-xl leading-none shrink-0"><?php echo code_to_flag_emoji($default_entry['code']); ?></span>
      <span data-phone-code class="text-xl text-dark"><?php echo esc_html($default_entry['phoneCode']); ?></span>
      <?php echo get_icon('chevron-down', 'h-5 w-5 text-dark transition-transform duration-200 pointer-events-none'); ?>
    </button>
    <span class="h-7 w-px bg-dark/15 shrink-0"></span>
    <input type="tel"
           inputmode="numeric"
           pattern="[0-9]*"
           name="telefono_numero"
           placeholder="<?php echo esc_attr(gf_e('contact.phone_placeholder')); ?>"
           data-phone-number
            class="bg-transparent text-xl text-dark placeholder:text-dark/45 outline-none flex-1 min-w-0 pl-4 pr-7 py-4"
           autocomplete="tel-national"/>
  </div>

  <div data-phone-list
       class="hidden absolute left-0 right-0 top-full mt-2 z-50 bg-[#F8F8F8] rounded-xl shadow-2xl overflow-hidden max-h-80 overflow-y-auto"
       role="listbox">
    <div class="sticky top-0 bg-[#F8F8F8] border-b-3 border-[#EDEDED] p-3">
      <input type="text"
             data-phone-search
             placeholder="<?php echo esc_attr(gf_e('contact.phone_country_search')); ?>"
             class="w-full bg-[#EDEDED] rounded-full px-5 py-2 text-lg text-dark outline-none placeholder:text-dark/45"
             autocomplete="off"/>
    </div>
    <div class="flex flex-col">
      <?php foreach ($countries as $c):
        $country_name = $gf_country_lang === 'en' && !empty($c['name_en']) ? $c['name_en'] : $c['name_es'];
      ?>
        <label class="flex items-center gap-3 py-3 px-6 border-b-3 border-[#EDEDED] last:border-0 cursor-pointer text-xl text-dark hover:bg-gray-200 rounded transition-colors"
               data-country-code="<?php echo esc_attr($c['code']); ?>"
               data-country-dial="<?php echo esc_attr($c['phoneCode']); ?>"
               data-country-name="<?php echo esc_attr($country_name); ?>"
               data-country-emoji="<?php echo esc_attr(code_to_flag_emoji($c['code'])); ?>"
               data-country-lengths="<?php echo esc_attr(implode(',', $c['validLengths'] ?? [6,15])); ?>"
               role="option">
          <span class="text-xl leading-none shrink-0"><?php echo code_to_flag_emoji($c['code']); ?></span>
          <span class="font-normal"><?php echo esc_html($country_name); ?></span>
          <span class="ml-auto text-dark/60"><?php echo esc_html($c['phoneCode']); ?></span>
        </label>
      <?php endforeach; ?>
    </div>
    <p data-phone-empty class="hidden text-center py-6 text-dark/60"><?php echo esc_html(gf_e('contact.phone_country_empty')); ?></p>
  </div>

  <input type="hidden"
         name="telefono"
         data-phone-hidden
         value="<?php echo esc_attr($default_entry['phoneCode'] . ' '); ?>"/>
  <p data-error class="hidden text-sm text-red-500 mt-1 ml-2"><?php echo esc_html(gf_e('contact.phone_error')); ?></p>
</div>
