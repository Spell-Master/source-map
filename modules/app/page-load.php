<?php
SeoData::breadCrumbs($url);

$pageData = (object) [];

foreach ($appResult as $key => $appValue) {
    if ($appValue->a_link == $appPage) {
        $pageData->a_hash = $appValue->a_hash;
        $pageData->a_title = $appValue->a_title;
        $pageData->a_content = $appValue->a_content;
        $pageData->a_date = $appValue->a_date;
        $pageData->a_version = $appValue->a_version;
    }
}

var_dump($pageData);
