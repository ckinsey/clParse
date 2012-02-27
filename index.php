<?php
    require('header.php');
    require('settings.php');
    require('clParse.php');

    $parser = new clParse($settings);
    
    switch(@$_GET['filter']){
        case "hidden":
            $query = "SELECT * FROM entries WHERE is_read = -2 ORDER BY date DESC;";
            break;
        case "flagged":
            $query = "SELECT * FROM entries WHERE is_read <> -2 AND is_flagged = 1 ORDER BY is_read DESC, date DESC;";
            break;
        default:
            $query = "SELECT * FROM entries WHERE is_read <> -2 AND is_flagged <> 1 ORDER BY is_read DESC, is_flagged DESC, date DESC;";
            break;
    }
    
    if($parser->run_query($query))
        $response = $parser->get_db_response();
        
?>

<table>

<?php
    
    $count = 0;
    while($row = mysql_fetch_assoc($response)){ 
        $rowclass = $parser->_get_row_class($row['is_read']);
        if($count % 2)
            $rowclass .= " even";
            
        if($row['is_flagged'])
            $rowclass .= " flagged";
            
        $count++;
    ?>
        
        <tr class="<?php echo $rowclass; ?>">
            
            <td width="125"><?php echo date('D, M d', strtotime($row['date'])); ?></td>
            
            <td><a href="entry.php?id=<?php echo $row['id']; ?>"><?php echo $parser->_strip($row['title']); ?></a></td>
            
            <td><?php echo $parser->_strip(substr($row['body'], 0, 64)); ?></td>
            
            
        </tr>
        
<?php } ?>

</table>

<?php require('footer.php'); ?>