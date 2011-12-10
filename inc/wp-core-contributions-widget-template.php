<?php
/**
 * Widget Template.
 *
 * @since     0.2
 */
?>

<?php if ( isset( $items ) ) : ?>
	<ul>
	<?php foreach ( (array) $items as $item ) : ?>
		<li><?php printf( __( '[%1$s] for %2$s' ),
			'<a href="' . esc_url( $item['link'] ) . '">' . esc_html( $item['changeset'] ) . '</a>',
			'<a href="' . esc_url( 'http://core.trac.wordpress.org/ticket/' . $item['ticket'] ) . '">' . esc_html( '#' . $item['ticket'] ) . '</a>'
		); ?></li>
	<?php endforeach; ?>
	</ul>
<?php endif; ?>