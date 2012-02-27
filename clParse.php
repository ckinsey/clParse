<?php
require('MagpieRSS/rss_fetch.inc');

/***************************************************************************
*
* clParse
*
* Aggregates Craigslist entries from a set of RSS URLs given in settings.php
* 
* clParse provides a few tools to help organize a diverse set of Craigslist
* listings by allowing flagging, hiding, and remembering read status.  It is
* helpful in managing listings across multiple cities.
*
* Leverages MagpieRSS to parse Craigslist's RSS feeds
*
***************************************************************************/



class clParse {

    private $db_link;   
    private $urls;
    private $errors;
    private $db_response;
    
    
    public function __construct($s){
        
        //Initialize error array
        $this->errors = array();
    
        //Create database connection from settings
        $this->db_link = mysql_connect($s['db']['host'], $s['db']['user'], $s['db']['password']);
        if(!$this->db_link)
            die(mysql_error());
            
        //select database
        mysql_select_db($s['db']['database'],$this->db_link); 
        
        //Load url list from settings
        $this->urls = $s['urls'];
          
    }
    
    public function __destruct(){
        //Automatically close database connection when object is destroyed
        mysql_close($this->db_link);
    }
    
    public function run_query($q){
        //Run a query on the open connection, set any error messages
        $resp = mysql_query($q, $this->db_link);
        if ($resp){
            $this->db_response = $resp;
            return true;
        }else{
            $this->set_error(mysql_error());
        }
    }
    
    public function get_db_response(){
        //handle MySQL response resource
        return $this->db_response;
    }
    
    public function get_url_list(){
        //handle URLs array
        return $this->urls;
    }
    
    public function esc($string){
        //Escape string using open connection
        return(mysql_real_escape_string($string, $this->db_link));
    }
    
    public function set_error($message){
        //Add error message to internal array, to be used later
        $this->errors[] = $message;
        
        //for now, die on single error message
        die('<h2>A clParse error occured: '.$message.'</h2>');
    }
    
    public function parse_listings(){
        //Use Magpie RSS to parse a Craigslist Entry into the database
    
        $url_list = $this->urls;
        foreach($url_list as $url){
        
            //create magpie object
            $rss = fetch_rss($url);
            foreach($rss->items as $item){
                
                //Check to see if database already contains an identical entry
                if($this->run_query("SELECT * FROM entries WHERE title='".$this->esc($item['title'])."' AND date='".$this->esc(substr(str_replace("T", " ", $item['dc']['date']), 0, -6))."';")){
                    $response = $this->get_db_response();
                    if(mysql_num_rows($response) == 0 ){
                        //insert fresh entry into DB
                        $this->run_query("INSERT INTO entries (title, body, date, about) VALUES('".$this->esc($item['title'])."', '".$this->esc($item['description'])."', '".$this->esc(substr(str_replace("T", " ", $item['dc']['date']), 0, -6))."', '".$this->esc($item['about'])."');");
                    }
                }
            }
            $rss="";
        }    
    }
    
    public function _strip($str){
        //maintenance method to qualify DB text for output to entry list
        return preg_replace('/\n/', '', strip_tags($str, '<strong><b><i>'));
    }
    
    public function _get_row_class($status){
        //maintenance method to set table row classes based on read status
        switch($status){
            case 0:
                $rowclass='unread';
                break;
            case -1:
                $rowclass='read';
                break;
            default:
                $rowclass='unread';
                break;
                
        }
        
        return $rowclass;
    
    }
    
    
    
    
}

?>