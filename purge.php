<?php 
    require("settings.php");
    require("clParse.php");

    $parser = new clParse($settings);
    
    if($parser->run_query("DELETE FROM entries WHERE 1;"))
        header("Location: index.php");
?>