<?php

class PostData {

    private static $baseString;

    public static function savePost($str) {
        self::$baseString = (string) $str;
        return (mb_convert_encoding(htmlentities(self::$baseString), 'UTF-8', 'ASCII'));
    }

    public static function showPost($str) {
        self::$baseString = (string) $str;
        return (html_entity_decode(utf8_decode(self::$baseString)));
    }

    public static function parseStr($str) {
        self::$baseString = html_entity_decode((string) $str);
        self::removeXss();
        self::removeTags();
        self::clearPhp();
        self::clearEmpty();
        return (trim(self::$baseString));
    }

    private static function removeTags() {
        $tagText = preg_replace('/<\/?\w(?:[^"\'>]|"[^"]*"|\'[^\']*\')*>/', null, self::$baseString);
        self::$baseString = $tagText;
    }

    private static function removeXss() {
        $script = preg_replace('/<script[^>]*>([\S\s]*?)<\/script>/', null, self::$baseString);
        self::$baseString = $script;
    }

    private static function clearPhp() {
        self::$baseString = str_replace([
            '<?php', '<!--?', '<!--?=', '<?=', '?>',
            '"', '\'', '´', '`', '<', '>', '\\'
        ], null, self::$baseString);
    }

    private static function clearEmpty() {
        $lines = preg_replace('/\r?\n/', null, self::$baseString);
        $spaces = preg_replace('/ {2,}/', null, $lines);
        self::$baseString = trim($spaces);
    }

}
