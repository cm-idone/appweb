<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', 'https://rawgit.com/schmich/instascan-builds/master/instascan.min.js']);
$this->dependencies->add(['js', '{$path.js}Dashboard/index.js']);

?>

%{header}%
<main class="unmodbar">
    <div class="sacanner-qr">
        <video id="qr_scanner" style="width:100%;height:300px;border:1px solid red;box-sizing:border-box;"></video>
        <a data-action="qr_scanner_change_front_camera">Cámara frontal</a>
        <a data-action="qr_scanner_change_back_camera">Cámara trasera</a>
    </div>
</main>
