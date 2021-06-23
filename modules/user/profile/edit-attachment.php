<?php
require_once (__DIR__ . '/../../../system/config.php');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="row-pad">
    <div class="col-threequarter over-not">
        <h2 class="gunship">Anexos Enviados</h2>
    </div>
    <div class="col-quarter">
        <button class="btn-default button-block shadow-on-hover" onclick="smUser.cancelEdit()">
            <i class="icon-esc"></i> Voltar
        </button>
    </div>
</div>
<hr />

<div class="box-x-900 margin-auto">
    <p class="list margin-left">Enviar Anexo</p>
    <div id="upload-error" class="alert-danger patern-bg fade-in hide"></div>
    <div class="margin-top">
        <form
            id="upload-file"
            action=""
            method="POST"
            enctype="multipart/form-data"
            class="margin-top align-center"
            onsubmit="return (false);">
            <input id="file-send" name="upload" type="file" accept="*" class="hide" />
            <div class="row">
                <div class="float-left">
                    <label for="file-send" class="btn-info text-white font-large box-y-50" style="line-height: 27px">
                        <i class="icon-archive"></i>
                    </label>
                </div>
                <div class="over-not">
                    <label id="file-name" for="photo-send" class="input-default"></label>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="margin-top">
    <p class="list margin-left font-medium">Anexos Enviados</p>
    <div class="align-center">

        <div class="card box-x-300 line-block">
            <div class="align-left">
                <p><span class="bold">Arquivo:</span><span class="italic">AAAAA</span></p>
                <p><span class="bold">Tamanho:</span><span class="italic">AAAAA</span></p>
                <p><span class="bold">Enviado:</span><span class="italic">AAAAA</span></p>
            </div>
            <div class="padding-all">
                <img src="lib/image/attachments.png" alt="" class="box-xy-50" />
            </div>
        </div>

    </div>
</div>

<div id="upload-status"></div>

<script>
    smUser.uploadFile('<?= (int) $config->store->uploadSize ?>');
</script>

