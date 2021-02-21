<?php
/**
 * ********************************************
 * * @Class Captcha
 * * @author Spell-Master (Omar Pautz)
 * * @version 1.0 (2012)
 * ********************************************
 * * @requires
 * - Arquivo de fonte de formato
 * ttf. Consultar o método getImage
 * - Sessão para armazenamento de
 * dados.
 * - Arquivo externo para gerar a imagem
 * ********************************************
 * * Cria código aleatório para bloqueio
 * de spans
 * ********************************************
 */

class Captcha {

    private $background;
    private $captchaCode;

    function __construct() {
        $this->getCode();
    }

    /**
     * ****************************************
     * * Gera o código
     * ****************************************
     */
    public function getCode() {
        $randCode = '';
        $letters = str_split('abcdefghjkmnpqrstuvwxyz123456789ABCDEFGHIJKMNPQRSTUVWWXYZ123456789');
        for ($i = 0; $i < 6; ++$i) {
            $randCode .= $letters[array_rand($letters)];
        }
        $this->captchaCode = $randCode;
        $this->getImage();
        return $this->captchaCode;
    }

    /**
     * ****************************************
     * * Mostra o código
     * ****************************************
     */
    public function showCode() {
        return $this->captchaCode;
    }

    /**
     * ****************************************
     * * Mostra a imagem
     * ****************************************
     */
    public function showImage() {
        header('Content-Type: image/png');
        imagepng($this->background);
        imagedestroy($this->background);
    }

    /**
     * ****************************************
     * * Cria a imagem PNG com o
     * código e aplica efeitos aleatórios
     * ****************************************
     */
    private function getImage() {
        $this->background = imagecreate(190, 50);
        imagecolorallocate($this->background, 255, 255, 255);
        $pos = 34;
        $size = 20;
        $font = __DIR__
                . DIRECTORY_SEPARATOR . '..'
                . DIRECTORY_SEPARATOR . '..'
                . DIRECTORY_SEPARATOR . '..'
                . DIRECTORY_SEPARATOR . 'lib'
                . DIRECTORY_SEPARATOR . 'fonts'
                . DIRECTORY_SEPARATOR . 'gunship.ttf';
        $color1 = imagecolorallocate($this->background, rand(25, 100), rand(25, 100), rand(25, 100));
        $color2 = imagecolorallocate($this->background, rand(50, 200), rand(50, 200), rand(50, 200));
        $shadow1 = imagecolorallocate($this->background, 200, 200, 200);

        foreach (str_split($this->captchaCode, 1) as $i => $letter) {
            imagettftext($this->background, $size + 5, rand(10, 5), ((25 * $i) + 30), $pos, $shadow1, $font, $letter);
            imagettftext($this->background, $size, rand(1, 3), ((30 * $i) + 10), $pos, rand($color1, $color2), $font, $letter);
        }
    }
}
