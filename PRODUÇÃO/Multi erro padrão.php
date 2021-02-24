<?php

if ($select->error() || $selectB->error()) {
    $error = "";
    $error .= (($select->error() !== null) ? '<p>' . $select->error() . '</p>' : null);
    $error .= (($selectB->error() !== null) ? '<p>' . $selectB->error() . '</p>' : null);
    throw new ConstException($error, ConstException::SYSTEM_ERROR);
}