<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<div class="post">
<header>
<h2 class="posttitle"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'toolbox' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
</header>

<div class="postcontent">
<?php the_content(); ?>

</div>

</article><!-- #post-<?php the_ID(); ?> -->
