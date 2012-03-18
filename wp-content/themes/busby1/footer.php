<footer>

<div id="footer">
<?php if ( ! dynamic_sidebar( 'footer' ) ) : ?>
<?php endif; ?>

<div id="copyright">
<p><a rel="license" href="http://creativecommons.org/licenses/by/3.0/"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by/3.0/80x15.png" /></a> <?php bloginfo('name'); ?>
<?php up_footer(); ?> 
</div>

</div>
</footer>
</div> <!--! end of #container -->

  <!--[if lt IE 7 ]>
    <script src="<?php bloginfo('template_url'); ?>/js/libs/dd_belatedpng.js"></script>
    <script>DD_belatedPNG.fix("img, .png_bg"); // Fix any <img> or .png_bg bg-images. Also, please read goo.gl/mZiyb </script>
  <![endif]-->
<?php wp_footer(); ?>
</body>
</html>
