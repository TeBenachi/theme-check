<?php
/**
 * Check if:
 * Theme or author URI refers to _s
 * The readme is a copy of _s
 * footer credit link refers to _s
 */
class UnderscoresCheck implements themecheck {
	protected $error = array();

	function check( $php_files, $css_files, $other_files ) {

		$ret = true;
		global $data;

		checkcount();
		if ( ! empty( $data['AuthorURI'] ) || ! empty( $data['URI'] ) ) {

			if ( stripos( $data['URI'], 'underscores.me' ) || stripos( $data['AuthorURI'], 'underscores.me' ) ) {
				$this->error[] .= __( '<span class="tc-lead tc-required">' . __( 'REQUIRED', 'theme-check' ) . '</span>: ' . __( 'Using underscores.me as Theme URI or Autor URI is not allowed.', 'theme-check' ) );
				$ret = false;
			}
		}

		checkcount();
		foreach ( $other_files as $file_path => $file_content ) {
			$filename = tc_filename( $file_path );
			if ( preg_match( "/Hi. I'm a starter theme called `_s`, or `underscores`, if you like./", $file_content) ) {
				$error = "/Hi. I'm a starter theme called `_s`, or `underscores`, if you like./";
				$grep = tc_preg( $error, $file_path );
				$this->error[] = sprintf( '<span class="tc-lead tc-required">' . __( 'REQUIRED', 'theme-check' ) . '</span>: ' . __( 'Found a copy of Underscores. See %1$s. Update the files for your own theme.', 'theme-check' ),
				'<strong>' . $filename . '</strong>' ) . $grep;
				$ret = false;
			}
		}

		/**
		 * There is a big risk for false positives here. Instead, this could be limited to footer.php,
		 * since we are looking for clones of underscores.
		 */
		checkcount();
		foreach ( $php_files as $file_path => $file_content ) {
			$filename = tc_filename( $file_path );
			if ( preg_match( '/Underscores.me/', $file_content) ) {
				$error = '/Underscores.me/';
				$grep = tc_preg( $error, $file_path );
				$this->error[] = sprintf( '<span class="tc-lead tc-required">' . __( 'REQUIRED', 'theme-check' ) . '</span>: ' . __( 'Found a copy of Underscores. See %1$s. Update the files for your own theme.', 'theme-check' ),
				'<strong>' . $filename . '</strong>' ) . $grep;
				$ret = false;
			}
		}

		return $ret;
	}

	function getError() { return $this->error; }
}
$themechecks[] = new UnderscoresCheck;