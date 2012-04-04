<?php
/**
 * Codex Widget Template.
 *
 * @since     1.2
 */
?>

<?php if ( isset( $items ) ) : ?>
	<ul>
	<?php foreach ( (array) $items as $item ) :
		$link = 'http://codex.wordpress.org/index.php?title=' . $item['title'] . '&oldid=' . $item['revision'];
	?>
		<li><?php printf( __( 'For %1$s' ),
			'<a href="' . esc_url( $link ) . '">' . esc_html( $item['title'] ) . '</a>'
		); ?></li>
	<?php endforeach; ?>
	</ul>
	<p>
		<a href="<?php echo esc_url( 'http://codex.wordpress.org/Special:Contributions/' . ucfirst( $user ) ); ?>">
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