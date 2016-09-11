<?php
/**
 * Jacqueline Framework: strings manipulations
 *
 * @package	jacqueline
 * @since	jacqueline 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Check multibyte functions
if ( ! defined( 'JACQUELINE_MULTIBYTE' ) ) define( 'JACQUELINE_MULTIBYTE', function_exists('mb_strpos') ? 'UTF-8' : false );

if (!function_exists('jacqueline_strlen')) {
	function jacqueline_strlen($text) {
		return JACQUELINE_MULTIBYTE ? mb_strlen($text) : strlen($text);
	}
}

if (!function_exists('jacqueline_strpos')) {
	function jacqueline_strpos($text, $char, $from=0) {
		return JACQUELINE_MULTIBYTE ? mb_strpos($text, $char, $from) : strpos($text, $char, $from);
	}
}

if (!function_exists('jacqueline_strrpos')) {
	function jacqueline_strrpos($text, $char, $from=0) {
		return JACQUELINE_MULTIBYTE ? mb_strrpos($text, $char, $from) : strrpos($text, $char, $from);
	}
}

if (!function_exists('jacqueline_substr')) {
	function jacqueline_substr($text, $from, $len=-999999) {
		if ($len==-999999) { 
			if ($from < 0)
				$len = -$from; 
			else
				$len = jacqueline_strlen($text)-$from;
		}
		return JACQUELINE_MULTIBYTE ? mb_substr($text, $from, $len) : substr($text, $from, $len);
	}
}

if (!function_exists('jacqueline_strtolower')) {
	function jacqueline_strtolower($text) {
		return JACQUELINE_MULTIBYTE ? mb_strtolower($text) : strtolower($text);
	}
}

if (!function_exists('jacqueline_strtoupper')) {
	function jacqueline_strtoupper($text) {
		return JACQUELINE_MULTIBYTE ? mb_strtoupper($text) : strtoupper($text);
	}
}

if (!function_exists('jacqueline_strtoproper')) {
	function jacqueline_strtoproper($text) { 
		$rez = ''; $last = ' ';
		for ($i=0; $i<jacqueline_strlen($text); $i++) {
			$ch = jacqueline_substr($text, $i, 1);
			$rez .= jacqueline_strpos(' .,:;?!()[]{}+=', $last)!==false ? jacqueline_strtoupper($ch) : jacqueline_strtolower($ch);
			$last = $ch;
		}
		return $rez;
	}
}

if (!function_exists('jacqueline_strrepeat')) {
	function jacqueline_strrepeat($str, $n) {
		$rez = '';
		for ($i=0; $i<$n; $i++)
			$rez .= $str;
		return $rez;
	}
}

if (!function_exists('jacqueline_strshort')) {
	function jacqueline_strshort($str, $maxlength, $add='...') {
	//	if ($add && jacqueline_substr($add, 0, 1) != ' ')
	//		$add .= ' ';
		if ($maxlength < 0) 
			return $str;
		if ($maxlength == 0) 
			return '';
		if ($maxlength >= jacqueline_strlen($str)) 
			return strip_tags($str);
		$str = jacqueline_substr(strip_tags($str), 0, $maxlength - jacqueline_strlen($add));
		$ch = jacqueline_substr($str, $maxlength - jacqueline_strlen($add), 1);
		if ($ch != ' ') {
			for ($i = jacqueline_strlen($str) - 1; $i > 0; $i--)
				if (jacqueline_substr($str, $i, 1) == ' ') break;
			$str = trim(jacqueline_substr($str, 0, $i));
		}
		if (!empty($str) && jacqueline_strpos(',.:;-', jacqueline_substr($str, -1))!==false) $str = jacqueline_substr($str, 0, -1);
		return ($str) . ($add);
	}
}

// Clear string from spaces, line breaks and tags (only around text)
if (!function_exists('jacqueline_strclear')) {
	function jacqueline_strclear($text, $tags=array()) {
		if (empty($text)) return $text;
		if (!is_array($tags)) {
			if ($tags != '')
				$tags = explode($tags, ',');
			else
				$tags = array();
		}
		$text = trim(chop($text));
		if (is_array($tags) && count($tags) > 0) {
			foreach ($tags as $tag) {
				$open  = '<'.esc_attr($tag);
				$close = '</'.esc_attr($tag).'>';
				if (jacqueline_substr($text, 0, jacqueline_strlen($open))==$open) {
					$pos = jacqueline_strpos($text, '>');
					if ($pos!==false) $text = jacqueline_substr($text, $pos+1);
				}
				if (jacqueline_substr($text, -jacqueline_strlen($close))==$close) $text = jacqueline_substr($text, 0, jacqueline_strlen($text) - jacqueline_strlen($close));
				$text = trim(chop($text));
			}
		}
		return $text;
	}
}

// Return slug for the any title string
if (!function_exists('jacqueline_get_slug')) {
	function jacqueline_get_slug($title) {
		return jacqueline_strtolower(str_replace(array('\\','/','-',' ','.'), '_', $title));
	}
}

// Replace macros in the string
if (!function_exists('jacqueline_strmacros')) {
	function jacqueline_strmacros($str) {
		return str_replace(array("{{", "}}", "((", "))", "||"), array("<i>", "</i>", "<b>", "</b>", "<br>"), $str);
	}
}

// Unserialize string (try replace \n with \r\n)
if (!function_exists('jacqueline_unserialize')) {
	function jacqueline_unserialize($str) {
		if ( is_serialized($str) ) {
			try {
				$data = unserialize($str);
			} catch (Exception $e) {
				dcl($e->getMessage());
				$data = false;
			}
			if ($data===false) {
				try {
					$data = @unserialize(str_replace("\n", "\r\n", $str));
				} catch (Exception $e) {
					dcl($e->getMessage());
					$data = false;
				}
			}
			//if ($data===false) $data = @unserialize(str_replace(array("\n", "\r"), array('\\n','\\r'), $str));
			return $data;
		} else
			return $str;
	}
}
?>