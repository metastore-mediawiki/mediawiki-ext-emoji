<?php

/**
 * Class MW_EXT_Emoji
 * ------------------------------------------------------------------------------------------------------------------ */

class MW_EXT_Emoji {

	/**
	 * Clear DATA (escape html).
	 *
	 * @param $string
	 *
	 * @return string
	 * -------------------------------------------------------------------------------------------------------------- */

	private static function clearData( $string ) {
		$outString = htmlspecialchars( trim( $string ), ENT_QUOTES );

		return $outString;
	}

	/**
	 * Get configuration parameters.
	 *
	 * @param $getData
	 *
	 * @return mixed
	 * @throws ConfigException
	 * -------------------------------------------------------------------------------------------------------------- */

	private static function getConfig( $getData ) {
		$context   = new RequestContext();
		$getConfig = $context->getConfig()->get( $getData );

		return $getConfig;
	}

	/**
	 * Register tag function.
	 *
	 * @param Parser $parser
	 *
	 * @return bool
	 * @throws MWException
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
	 * @throws ConfigException
	 * -------------------------------------------------------------------------------------------------------------- */

	public static function onRenderTag( Parser $parser, $id = '', $size = '' ) {
		// Argument: ID.
		$getID = self::clearData( $id ?? '' ?: '' );
		$outID = self::getConfig( 'ScriptPath' ) . '/extensions/MW_EXT_Emoji/storage/images/' . mb_strtolower( $getID ) . '.svg';

		// Argument: size.
		$getSize = self::clearData( $size ?? '' ?: '' );
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
		$out->addModuleStyles( array( 'ext.mw.emoji.styles' ) );

		return true;
	}
}
