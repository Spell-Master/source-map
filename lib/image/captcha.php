<?php
/*
 * Image Captcha para a classe Captcha
 */
require_once (__DIR__ . '/../../system/config.php');
$captcha = new Captcha();
$captcha->showImage();
$session->code = $captcha->showCode();
