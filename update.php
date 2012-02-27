<?php
    require('settings.php');
    require('clParse.php');

    $parser = new clParse($settings);
    $parser->parse_listings();
    
    header("Location: index.php");
?>
