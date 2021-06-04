<?php
$contact = [];
$key = 0;
if (!empty($userData->ui_website)) {
    $key++;
    $contact[$key]['name'] = 'WebSite';
    $contact[$key]['icon'] = 'earth';
    $contact[$key]['link'] = $userData->ui_website;
}
if (!empty($userData->ui_mail)) {
    $key++;
    $contact[$key]['name'] = 'E-Mail';
    $contact[$key]['icon'] = 'at-sign';
    $contact[$key]['link'] = $userData->ui_mail;
}
if (!empty($userData->ui_git)) {
    $key++;
    $contact[$key]['name'] = 'GitHub';
    $contact[$key]['icon'] = 'github';
    $contact[$key]['link'] = $userData->ui_git;
}
if (!empty($userData->ui_face)) {
    $key++;
    $contact[$key]['name'] = 'Facebook';
    $contact[$key]['icon'] = 'facebook2';
    $contact[$key]['link'] = $userData->ui_face;
}
if (!empty($userData->ui_insta)) {
    $key++;
    $contact[$key]['name'] = 'Instagram';
    $contact[$key]['icon'] = 'instagram';
    $contact[$key]['link'] = $userData->ui_insta;
}
if (!empty($userData->ui_twit)) {
    $key++;
    $contact[$key]['name'] = 'Twitter';
    $contact[$key]['icon'] = 'twitter';
    $contact[$key]['link'] = $userData->ui_twit;
}
if (!empty($userData->ui_tube)) {
    $key++;
    $contact[$key]['name'] = 'Youtube';
    $contact[$key]['icon'] = 'youtube';
    $contact[$key]['link'] = $userData->ui_tube;
}
if (!empty($userData->ui_whats)) {
    $key++;
    $contact[$key]['name'] = 'WhatsApp';
    $contact[$key]['icon'] = 'whatsapp';
    $contact[$key]['link'] = $userData->ui_whats;
}

if ($key >= 1) {
    foreach ($contact as $value) {
        if ($value['name'] == 'WhatsApp') {
            ?>
            <div class="padding-all-prop">
                <i class="icon-<?= $value['icon'] ?>"></i>
                <?= $value['name'] ?>
                <p class="font-small italic break"><?= $value['link'] ?></p>
            </div>
            <?php
        } else {
            ?>
            <div class="padding-all-prop">
                <a href="<?= $value['link'] ?>" target="_blank">
                    <i class="icon-<?= $value['icon'] ?>"></i>
                    <?= $value['name'] ?>
                </a>
                <p class="font-small italic break"><?= $value['link'] ?></p>
            </div>
            <?php
        }
    }
} else {
    ?>
    <span class="italic font-small">Não há informações disponíveis</span>
    <?php
}
