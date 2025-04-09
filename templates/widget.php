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
</div>