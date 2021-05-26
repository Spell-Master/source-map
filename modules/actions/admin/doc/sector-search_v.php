<?php
echo ("<script>smTools.modal.showX();</script>"); // APAGAR ISSO DEPOIS DA PRODUÇÃO
require_once (__DIR__ . '/../../../../system/config.php');
sleep((int) $config->length->colldown);

$post = GlobalFilter::filterPost();

var_dump($post);
/* 
object(stdClass)#7 (2) { ["form_id"]=> string(13) "search-sector" 
 * ["search"]=> string(8) "fdfdfdfd" } 
 */

