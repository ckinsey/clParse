<?php 
    require('clParse.php');
    require('settings.php');
    
    $parser = new clParse($settings);
    
    if($parser->run_query("UPDATE entries SET is_flagged = 1 WHERE id = ".$parser->esc($_GET['id']).";"))
        header("Location: index.php");
?>