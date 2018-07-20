<?php

namespace MediaWiki\Extension\MW_EXT_Emoji;

use OutputPage, Parser, Skin;
use MediaWiki\Extension\MW_EXT_Kernel\MW_EXT_Kernel;

/**
 * Class MW_EXT_Emoji
 */
class MW_EXT_Emoji {

	/**
	 * Get emoji file.
	 *
	 * @param $id
	 *
	 * @return string
	 * @throws \ConfigException
	 */
	private static function getEmoji( $id ) {
		$path = MW_EXT_Kernel::getConfig( 'ScriptPath' ) . '/extensions/MW_EXT_Emoji/storage/images/';
		$id   = MW_EXT_Kernel::outNormalize( $id );
		$out  = $path . $id . '.svg';

		return $out;
	}

	/**
	 * Register tag function.
	 *
	 * @param Parser $parser
	 *
	 * @return bool
	 * @throws \MWException
	 */
	public static function onParserFirstCallInit( Parser $parser ) {
		$parser->setFunctionHook( 'emoji', [ __CLASS__, 'onRenderTag' ] );

		return true;
	}

	/**
	 * Render tag function.
	 *
	 * @param Parser $parser
	 * @param string $id
	 * @param string $size
	 *
	 * @return string
	 * @throws \ConfigException
	 */
	public static function onRenderTag( Parser $parser, $id = '', $size = '' ) {
		// Argument: id.
		$getID = MW_EXT_Kernel::outClear( $id ?? '' ?: '' );
		$outID = self::getEmoji( $getID );

		// Argument: size.
		$getSize = MW_EXT_Kernel::outClear( $size ?? '' ?: '' );
		$outSize = empty( $getSize ) ? '' : ' width: ' . $getSize . 'em; height: ' . $getSize . 'em;';

		// Out HTML.
		$outHTML = '<span style="background-image: url(' . $outID . ');' . $outSize . '" class="mw-ext-emoji navigation-not-searchable"></span>';

		// Out parser.
		$outParser = $parser->insertStripItem( $outHTML, $parser->mStripState );

		return $outParser;
	}

	/**
	 * Load resource function.
	 *
	 * @param OutputPage $out
	 * @param Skin $skin
	 *
	 * @return bool
	 */
	public static function onBeforePageDisplay( OutputPage $out, Skin $skin ) {
		$out->addModuleStyles( [ 'ext.mw.emoji.styles' ] );

		return true;
	}
}
