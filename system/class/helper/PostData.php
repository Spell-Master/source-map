<?php

class PostData {

    public static function savePost($str) {
        return (mb_convert_encoding(htmlentities($str), 'UTF-8', 'ASCII'));
    }

    public static function showPost($str) {
        return (html_entity_decode(utf8_decode($str)));
    }

}
