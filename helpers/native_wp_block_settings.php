<?php


/*--------------------------------
	Helper functions for getting values from block native WP settings like colors, spacing and such
--------------------------------*/
/**
 * getBackgroundColorFromBlock
 *
 * @param  mixed $block
 * @return void
 */
function getBackgroundColorFromBlock($block) {
	// Color from palette
	if(!empty($block["backgroundColor"])) {
		return "var(--wp--preset--color--" . $block["backgroundColor"] . ")";
	}
	// Custom HEX color
	else if(!empty($block["style"]["color"]["background"])) {
		return $block["style"]["color"]["background"];
	}
	else {
		return "transparent";
	}
}


/**
 * getSpacing
 *
 * @param  mixed $block
 * @param  string $type
 * @return string
 */
function getSpacing($block, $type): string {
	// If this block has enabled spacing in editor
	if( isset($block["style"]) && is_array($block["style"])
		&& isset($block["style"]["spacing"][$type])
		) {
		$spacing = $block["style"]["spacing"][$type];

		return "
			$type-top: {$spacing["top"]};
			$type-bottom: {$spacing["bottom"]};
			$type-left: {$spacing["left"]};
			$type-right: {$spacing["right"]};
		";
	}
	else {
		return "$type: 0";
	}
}


/**
 * bit_convertPresetVars
 *
 * @param  mixed $block
 * @return void
 */
function bit_convertSpacingPresetVars($preset) {
	/* 
		this function converts strings like "var:preset|spacing|30" 
		to "var(--wp--preset--spacing--30)"
	*/

	// remove "var:"
	$cutPreset = str_replace("var:", "", $preset);

	// Divide into parts by "|" char
	$parts = explode("|", $cutPreset);

	// Setup result by combining parts and wrapping it in "var()"
	$res = "var(--wp";
	foreach ($parts as $part) {
		$res .= "--" . $part;
	}
	$res .= ")";

	return $res;

}