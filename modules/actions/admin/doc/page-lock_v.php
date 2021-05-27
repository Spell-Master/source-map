<?php
echo ("<script>smTools.modal.showX();</script>"); // APAGAR ISSO DEPOIS DA PRODUÇÃO
require_once (__DIR__ . '/../../../../system/config.php');
sleep((int) $config->length->colldown);

$post = GlobalFilter::filterPost();

/*
 * object(stdClass)#7 (2) { 
 * ["form_id"]=> string(15) "target-11111111" 
 * ["hash"]=> string(8) "11111111" } 
 */