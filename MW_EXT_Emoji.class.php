<?php

namespace MediaWiki\Extension\MW_EXT_Emoji;

use OutputPage, Parser, RequestContext, Skin;
use MediaWiki\Extension\MW_EXT_Core\MW_EXT_Core;

/**
 * Class MW_EXT_Emoji
 * ------------------------------------------------------------------------------------------------------------------ */
class MW_EXT_Emoji {

	/**
	 * Register tag function.
	 *
	 * @param Parser $parser
	 *
	 * @return bool
	 * @throws \MWException
	 * -------------------------------------------------------------------------------------------------------------- */

	public static function onParserFirstCallInit( Parser $parser ) {
		$parser->setFunctionHook( 'emoji', __CLASS__ . '::onRenderTag' );

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
	 * -------------------------------------------------------------------------------------------------------------- */

	public static function onRenderTag( Parser $parser, $id = '', $size = '' ) {
		// Argument: ID.
		$getID = MW_EXT_Core::outClear( $id ?? '' ?: '' );
		$outID = MW_EXT_Core::getConfig( 'ScriptPath' ) . '/extensions/MW_EXT_Emoji/storage/images/' . MW_EXT_Core::outConvert( $getID ) . '.svg';

		// Argument: size.
		$getSize = MW_EXT_Core::outClear( $size ?? '' ?: '' );
		$outSize = empty( $getSize ) ? '' : ' width: ' . $getSize . 'em; height: ' . $getSize . 'em;';

		// Out HTML.
		$outHTML = '<span style="background-image: url(' . $outID . ');' . $outSize . '" class="mw-ext-emoji"></span>';

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
	 * -------------------------------------------------------------------------------------------------------------- */

	public static function onBeforePageDisplay( OutputPage $out, Skin $skin ) {
		$out->addModuleStyles( [ 'ext.mw.emoji.styles' ] );

		return true;
	}
}
