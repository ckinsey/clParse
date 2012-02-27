<?php
    require('header.php');
    require('settings.php');
    require('clParse.php');
    $parser = new clParse($settings);
    
    if($parser->run_query('SELECT * FROM entries WHERE id='.$parser->esc($_GET['id']).';'))
        $response = $parser->get_db_response();
    $row = mysql_fetch_assoc($response);   
    
    if($row['is_read'] == 0)
        $response = $parser->run_query('UPDATE entries SET is_read = -1 WHERE id = '.$parser->esc($_GET['id']).';');

?>

        <div class="entry_wrapper">        
            <div class="hide_button" style="float:right;">
              
                <?php if($row['is_read'] == -2): ?>
                    <input type="button" value="Un-hide This Entry" onclick="window.location='unhide.php?id=<?=$row['id']?>'"/>
                <?php else: ?>
                    <input type="button" value="Hide This Entry" onclick="window.location='hide.php?id=<?=$row['id']?>'"/>
                <?php endif;?>
                <br/>
                <?php if($row['is_flagged']): ?>
                    <input type="button" value="Un-flag This Entry" onclick="window.location='unflag.php?id=<?=$row['id']?>'"/>
                <?php else: ?>
                    <input type="button" value="Flag This Entry" onclick="window.location='flag.php?id=<?=$row['id']?>'"/>                
                <?php endif;?>
            </div>
          
         
            <h2><?php echo $row['title']." - ".$row['date']; ?></h2>
            <div>
                <a href="<?=$row['about']?>" target="_blank"><?php echo $row['about']?></a>
            </div>
            <p>
                <?php echo $row['body']; ?>
            </p>
            
        </div>
    
  <?php require('footer.php'); ?>