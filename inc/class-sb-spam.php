<?php
defined('ABSPATH') OR exit;

class SB_Spam {
    public static $texts = array(
        'Canvas Art Cheap',
        'gucci outlet',
        'Louis Vuitton',
        'smgtv.co.uk',
        'dancebrazil.org',
        'massive demand for this product',
        'дневной заработок',
        'lettersvsnumbers',
        'sivictxixxgn',
        'ealwibidnwfc'
    );

    public static function check($content) {
        $content = SB_PHP::lowercase($content);
        foreach(self::$texts as $text) {
            $text = SB_PHP::lowercase($text);
            if(SB_PHP::is_string_contain($content, $text)) {
                return true;
            }
        }
        return false;
    }
}