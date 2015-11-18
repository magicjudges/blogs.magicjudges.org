<?php

function language_links() {
	echo '<div class="external-languages">';
	$language_links = get_post_meta( get_the_ID(), '_language_links', true );

	function sort_by_language_key($a, $b) {
		return strcmp($a['language'], $b['language']);
	}

	uasort($language_links, 'sort_by_language_key');

	if ( $language_links && count( $language_links ) > 0 ) {
		_e( 'This article is also available in: ', 'language-links' );
		echo '<ul>';
		foreach ( $language_links as $language_link ) :
			?>
			<li>
				<a href="<?php echo $language_link['url']; ?>" target="_blank" class="<?php echo $language_link['language']; ?>"><?php echo Language_Link::$languages[$language_link['language']]['native']; ?></a>
			</li>

		<?php
		endforeach;
		echo '</ul>';
	}
	echo '</div>';
}

