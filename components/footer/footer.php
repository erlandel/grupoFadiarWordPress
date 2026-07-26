<?php
$section_keys = [
  'footer.section.about',
  'footer.section.community',
  'footer.section.values',
  'footer.section.hr',
];

$link_keys_by_section = [
  ['footer.link.history', 'footer.link.where', 'footer.link.rd', 'footer.link.business', 'footer.link.quality'],
  ['footer.link.schools', 'footer.link.communities'],
  ['footer.link.sustainability', 'footer.link.innovation', 'footer.link.commitment', 'footer.link.teamwork'],
  ['footer.link.leadership', 'footer.link.strategy', 'footer.link.training', 'footer.link.work_with_us', 'footer.link.news'],
];

$legal_keys = [
  'footer.legal.privacy',
  'footer.legal.cookies',
  'footer.legal.terms',
  'footer.legal.refunds',
  'footer.legal.notices',
  'footer.legal.sitemap',
];
?>
<footer class="bg-dark text-white py-12 px-4 md:px-8 xl:px-20">
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 border-b-2 border-white pb-8 mb-6">
    <div class="flex flex-col lg:col-span-1">
      <div>
        <div class="flex items-center mb-5">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logoWithe.svg" alt="Grupo Fadiar Logo" width="150" height="40" />
        </div>
        <p class="text-white text-lg mb-6"><?php echo esc_html(gf_e('footer.help_text')); ?></p>
        <div class="flex gap-8 mb-8">
          <a href="#" aria-label="<?php echo esc_attr(gf_e('footer.social.phone')); ?>">
            <svg class="w-7 h-7 text-white hover:scale-110 transition-transform" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          </a>
          <a href="#" aria-label="<?php echo esc_attr(gf_e('footer.social.instagram')); ?>">
            <svg class="w-7 h-7 text-white hover:scale-110 transition-transform" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 448 512" fill="currentColor"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg>
          </a>
          <a href="#" aria-label="<?php echo esc_attr(gf_e('footer.social.facebook')); ?>">
            <svg class="w-7 h-7 text-white hover:scale-110 transition-transform" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512" fill="currentColor"><path d="M512 256C512 114.6 397.4 0 256 0S0 114.6 0 256C0 376 82.7 476.8 194.2 504.5V334.2H141.4V256h52.8V222.3c0-87.1 39.4-127.5 125-127.5c16.2 0 44.2 3.2 55.7 6.4V172c-6-.6-16.5-1-29.6-1c-42 0-58.2 15.9-58.2 57.2V256h83.6l-14.4 78.2H287V510.1C413.8 494.8 512 386.9 512 256h0z"/></svg>
          </a>
          <a href="#" aria-label="<?php echo esc_attr(gf_e('footer.social.email')); ?>">
            <svg class="w-7 h-7 text-white hover:scale-110 transition-transform" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
          </a>
        </div>
      </div>
    </div>
    <?php for ($i = 0; $i < count($section_keys); $i++): ?>
      <div class="flex flex-col items-start">
        <h4 class="text-secondary text-2xl font-semibold mb-10"><?php echo esc_html(gf_e($section_keys[$i])); ?></h4>
        <ul class="space-y-2">
          <?php foreach ($link_keys_by_section[$i] as $link_key): ?>
            <li>
              <a href="#" class="text-lg hover:text-secondary transition-colors">
                <?php echo esc_html(gf_e($link_key)); ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endfor; ?>
  </div>
  <div class="flex flex-col md:flex-row justify-between items-center text-lg text-white/70">
    <div class="flex flex-wrap justify-center md:justify-start gap-10 mb-4 md:mb-0">
      <?php foreach ($legal_keys as $key): ?>
        <a href="#" class="hover:text-secondary transition-colors">
          <?php echo esc_html(gf_e($key)); ?>
        </a>
      <?php endforeach; ?>
    </div>
    <span class="text-white/60">v1.0</span>
  </div>
</footer>
