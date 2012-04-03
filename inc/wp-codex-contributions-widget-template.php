<?php
/**
 * Widget Template.
 *
 * @since     0.3
 */
?>

<?php if ( isset( $items ) ) : ?>
	<ul>
	<?php foreach ( (array) $items as $item ) : ?>
		<?php if ( $item['ticket'] ) { ?>
		<li><?php printf( __( '[%1$s] for %2$s' ),
			'<a href="' . esc_url( $item['link'] ) . '">' . esc_html( $item['changeset'] ) . '</a>',
			'<a href="' . esc_url( 'http://core.trac.wordpress.org/ticket/' . $item['ticket'] ) . '">' . esc_html( '#' . $item['ticket'] ) . '</a>'
		); ?></li>
		<?php } else { ?>
		<li><?php printf( __( '[%1$s]' ),
			'<a href="' . esc_url( $item['link'] ) . '">' . esc_html( $item['changeset'] ) . '</a>'
		); ?></li>
		<?php } ?>
	<?php endforeach; ?>
	</ul>
	<p>
		<a href="<?php echo esc_url( 'http://codex.wordpress.org/Special:Contributions/' . $user ); ?>">
			<?php
				if ( $total == 2 ) {
					print( "View both changes in the Codex." );
				} else {
					printf( _n( "View the change in the Codex.", "View all %d changes in the Codex.", $total ), $total );
				}
			?>
		</a>
	</p>
<?php endif; ?>