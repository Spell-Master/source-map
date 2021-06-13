<div class="row">
    <div class="col-twothird col-fix">
        <button class="btn-success button-block shadow-on-hover text-white" id="confirm-cut">
            <i class="icon-image-compare"></i>
            Cortar & Salvar
        </button>
    </div>
    <div class="col-third col-fix">
        <div class="row">
            <div class="col-half col-fix align-right">
                <button class="btn-default box-xy-50 radius-circle shadow-on-hover font-large" style="padding: 0" id="scalePlus" data-model="plus">+</button>
            </div>
            <div class="col-half col-fix align-right">
                <button class="btn-default box-xy-50 radius-circle shadow-on-hover font-large" style="padding: 0" id="scaleMinus" data-model="minus">-</button>
            </div>
        </div>
    </div>
    <div class="col-single align-center margin-top" id="img-w">
        <img alt="" id="cut-img" />
    </div>
</div>

<form id="cut-photo" onsubmit="return false;">
    <textarea name="base64" id="base64" value="" class="hide"></textarea>
</form>


<script>
    smTools.modal.showX();
    var $cut = new photoCut(),
    $imgSrc = localStorage.getItem('user-photo'),
    $confirm = document.getElementById('confirm-cut'),
    $cutImg = document.getElementById('cut-img'),
    $limitW = document.getElementById('img-w').offsetWidth,
    $plus = document.getElementById('scalePlus'),
    $minus = document.getElementById('scaleMinus');

    $cutImg.setAttribute('style', 'max-width:' + Math.ceil($limitW - 20) + 'px');

    $cutImg.src = $imgSrc;
    smTools.imgCut.init('cut-img');

    $plus.addEventListener('mousedown', $cut.scalePress, false);
    $plus.addEventListener('mouseup', $cut.scaleDrop, false);
    $minus.addEventListener('mousedown', $cut.scalePress, false);
    $minus.addEventListener('mouseup', $cut.scaleDrop, false);

    $plus.addEventListener('touchstart', $cut.scalePress, false);
    $plus.addEventListener('touchend', $cut.scaleDrop, false);
    $minus.addEventListener('touchstart', $cut.scalePress, false);
    $minus.addEventListener('touchend', $cut.scaleDrop, false);

    $confirm.addEventListener('click', $cut.savePhoto, false);
</script>