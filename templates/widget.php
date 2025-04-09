<?php
/**
 * Template for widgets on the Shop Maestro Dashboard.
 *
 * @var string $title
 * @var string $content
 * @var bool   $draggable
 * @var bool   $resizable
 * @var int    $columns
 * @var int    $rows
 */
?>

<div class="widget" style="grid-column: span 2">
	<div class="widget__header">
		<h2 class="widget__title"><?php echo esc_html( $title ); ?></h2>
	</div>
	<div class="widget__content widget__content--with-padding">
		<?php echo apply_filters( 'the_content', $content ); ?>
	</div>
	<svg class="widget__resize-icon" xmlns="http://www.w3.org/2000/svg" width="800" height="800" fill="none"
		 viewBox="0 0 24 24">
		<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
			  d="m21 15-6 6m6-13L8 21"/>
	</svg>
</div>