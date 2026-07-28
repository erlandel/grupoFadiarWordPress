<?php
/* Template Name: Sobre Nosotros */
get_header();

$about_id = 0;
$about_posts = get_posts(array(
    'post_type'      => 'about_us',
    'posts_per_page' => 1,
    'post_status'    => 'publish',
    'fields'         => 'ids',
    'orderby'        => 'date',
    'order'          => 'ASC',
));
if (!empty($about_posts)) {
    $about_id = (int) $about_posts[0];
}

$page_title = $about_id ? (string) gf_get_field('about_page_title', $about_id) : '';
if ($page_title === '') {
    $page_title = 'Grupo Fadiar – Innovación y compromiso social';
}
?>

<div class="mx-30 mt-7">
  <div class="flex ">
    <p><a href="<?php echo home_url('/'); ?>"><?php echo esc_html(gf_e('noticias.breadcrumb_home')); ?></a></p>
    <svg class="h-6 w-6 mx-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
    <p><?php echo esc_html(gf_e('header.menu.about')); ?></p>
  </div>
  <div id="metrics" class="scroll-mt-24 mt-7">
    <h1 class="text-4xl font-bold text-dark"><?php echo esc_html($page_title); ?></h1>
  </div>
</div>

<div><?php get_template_part('components/metrics/metrics', null, array('post_id' => $about_id)); ?></div>
<div><?php get_template_part('components/our-story/our-story'); ?></div>
<div><?php get_template_part('components/corporate-pillars/corporate-pillars'); ?></div>

<?php get_footer(); ?>
