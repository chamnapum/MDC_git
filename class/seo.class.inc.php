<?php
if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {exit('No direct access!');}
/*
  ************************************************************************************************************
  *
  *  Seo - Link Factory Class
  *  @filename: seo.class.php
  *  Php version PHP5.
  *  Mysql Version 4 and newer.
  *  Revision : 53
  *  version: 0.0.3 alpha
  *  @author: Ersin Güvenç <eguvenc@gmail.com>  (C) 2008.
  *  @license http://opensource.org/licenses/lgpl-license.php GNU Lesser General Public License Version 2.1
  *
  ************************************************************************************************************
*/
//catch all error.. you can customize exception class.look at php5 manual.
Class Seo_Exception extends Exception {
    function __toString(){
    return __CLASS__ . ": [Error]: {$this->getMessage()} [Line]: {$this->getLine()}\n";
    }
}
//Purely static class..
Class Seo {

    static $instance;
    //this class only using by getInstace() method.
    private function __construct() {}

    //this object can not copy except getInstance() method.
    private function __clone() {}

    //lets prevent duplication of memory using by Singleton.
    public static function getInstance(){
       if(! (self::$instance instanceof self))
       {
        self::$instance = new self();
       }
       return self::$instance;
    }
    //table configuration....
    public $table = array('TABLE_NAME'=> TABLE_NAME,    //table name
                          'ID'=> CAT_ID,  //table id field name
                          'FIELD'=> CAT_NAME,    //which field you want convert to link.
                          'FIELD_LINK'=> CAT_LINK //link field name
                          );
    /**
    *  Main subject link variable, we set incoming link this variable.
    *  @access: private
    */
    private $subject = "";
    /**
    *  All unknown and whitespace characters will replace with this.
    *  @access: private - static
    */
    private static $replace_with_character = "-"; //default '-', or you can choose any character like "__" OR "~~"

    /**
    *    @Method:        init_empty_control - static -boolean
    *    @Access:        private
    *    @Description:   Empty control for each $this->table[].
    */
    private function init_empty_control(){
        foreach($this->table as $val){
        if(empty($val))
        throw new Seo_Exception('TABLE DATA can\'t be empty.
        Please provide table information.
        Seo class '.__FUNCTION__.' error!');
        }
        return true;
    }
    /**
    * Silly version function.*/
    public function version(){ return "Seo Link Factory 0.0.3 alpha";}
    /**
      *
      *    @Method:        _set_link
      *    @Access:        private
      *    @Parameters:    1
      *    @Param-1:        String.
      *    @Description:   Set main subject variable.
      */
    private function _set_link($subject){
     $this->subject = mysql_real_escape_string($subject);
    }
    /**
      *
      *    @Method:        _get_link
      *    @Access:        private
      *    @Parameters:    1
      *    @Param-1:        String.
      *    @Description:   Get main subject variable.
      */
    private function _get_link(){
        return $this->subject;
    }
    /**
      *
      *    @Method:        replace_data - static
      *    @Access:        public
      *    @Parameters:    1
      *    @Param-1:        Array().
      *    @Description:   Strlower string and convert string to UTF-8, we replace some special characters.
      */
    public static function replace_data($data){
      $trimmed = ltrim(rtrim($data));
      $convert = mb_convert_case($trimmed, MB_CASE_LOWER, "UTF-8");
      //Enter special characters in your language (patterns - replace)..
      /*
      '/€/','/£/','/¥/','$','#','&','%',
      */
      $patterns = array('/Á/','/É/','/Í/','/Ó/','/Ú/',
                        '/À/','/È/','/Ì/','/Ò/','/Ù/',
                        '/á/','/é/','/í/','/ó/','/ú/','/ý/',
                        '/à/','/è/','/ì/','/ò/','/ù/',
                        '/â/','/ê/','/î/','/ô/','/û/',
                        '/Â/','/Ê/','/Î/','/Ô/','/Û/',
                        '/ä/','/ë/','/ï/','/ö/','/ü/','/ÿ/',
                        '/Ä/','/Ë/','/Ï/','/Ö/','/Ü/','/Ÿ/',
                        '/ã/','/õ/','/ñ/','/å/','/ø/','/š/',
                        '/Ã/','/Õ/','/Ñ/','/Å/','/Ø/','/Š/',
                        '/ç/','/&#287;/','/&#305;/','/ö/','/&#351;/','/ü/');

      $replace  = array( 'a' , 'e' , 'i' , 'o' , 'u' ,
                         'a' , 'e' , 'i' , 'o' , 'u' ,
                         'a' , 'e' , 'i' , 'o' , 'u' ,'y',
                         'a' , 'e' , 'i' , 'o' , 'u' ,
                         'a' , 'e' , 'i' , 'o' , 'u' ,
                         'a' , 'e' , 'i' , 'o' , 'u' ,
                         'a' , 'e' , 'i' , 'o' , 'u' ,'y',
                         'a' , 'e' , 'i' , 'o' , 'u' ,'y',
                         'a' , 'o' , 'n' , 'a' , 'q' ,'s',
                         'a' , 'o' , 'n' , 'a' , 'q' ,'s',
                         'c' , 'g' , 'i' , 'o' , 's' ,'u');

      return preg_replace($patterns,$replace,$convert);
    }
     /**
      *
      *    @Method:        clean_str - static.
      *    @Access:        public
      *    @Parameters:    1
      *    @Param-1:        String
      *    @Description:   Get only between a-z characters from replaced data.
      */
    public static function clean_str($str){
    try{
        if(empty($str))
        throw new Seo_Exception('String can\'t be empty.Seo class '.__FUNCTION__.' error!');
        return ereg_replace("[^a-zA-Z0-9_]", self::$replace_with_character ,Seo::replace_data($str));
    }catch(Exception $e) {echo $e;}
    }
     /**
      *
      *    @Method:        query_link
      *    @Access:        public
      *    @Parameters:    1
      *    @Param-1:       String
      *    @Description:   Query mysql database, check for existing seo links.
      */
    ######################
    # QUERY LINK SQL (ORIGINAL SQL)
    ######################
    /*
    SELECT link FROM articles WHERE link='blabla' OR link REGEXP '^($link).*(-).*[(0-9)\d]$'
    */
    public function query_link($pure_link){
    if(empty($pure_link))
      throw new Seo_Exception('Query_link variable can\'t be empty.Seo class '.__FUNCTION__.' error!');
        $link = mysql_real_escape_string($pure_link);
        $sql = sprintf("SELECT %s FROM %s WHERE %s='%s' OR %s REGEXP '^($link).*(%s).*[(0-9)\d]$'",
        $this->table['FIELD_LINK'],
        $this->table['TABLE_NAME'],
        $this->table['FIELD_LINK'],
        $link,
        $this->table['FIELD_LINK'],
        self::$replace_with_character);

        $query = mysql_query($sql);
        if(!$query) throw new seo_Exception(mysql_error());
        $num = mysql_num_rows($query);
        //echo $num;
        if($num == 1) {
        $total = $num++;
        $new_link = $link.self::$replace_with_character.$total;
        //$link = $this->increase_link($link,$total);
        return $new_link;
        }elseif($num > 1){

        //find the most big number and increase one.
          $match_row = array();
          $cat_row = array();
          $max_row = array();
          $i = 0; $j = 0;
          while($row = mysql_fetch_assoc($query)){
          $cat_row[] = $row[$this->table['FIELD_LINK']];
          $pattern = sprintf("/%s(\d+)$/",self::$replace_with_character);
          preg_match($pattern, $cat_row[$i++],$match_row[]);
          $max_row[] = $match_row[$j++][1];
          }
          //print_r($max_row);
          $total = max($max_row) + 1;
          //print_r($match_row);
        $new_link = $link.self::$replace_with_character.$total;
        return $new_link;

        }else{
            return $link;
        }
    }
     /**
      *
      *    @Method:        insert
      *    @Access:        public
      *    @Parameters:    1
      *    @Param-1:        String
      *    @Description:   Build new seo link for insert to mysql database.
      */
    public function insert($subject){
    try {
      $this->init_empty_control();
      $this->_set_link($subject);
      $link = Seo::clean_str($this->_get_link());
      $new_link = $this->query_link($link);
      return $new_link;
      }catch(Exception $e) {echo $e;}

    }
     /**
      *
      *    @Method:        update
      *    @Access:        public
      *    @Parameters:    2
      *    @Param-1:        String
      *    @Param-2:        Integer
      *    @Description:   Update existing link stored in mysql database.
      */
     ######################
    # UPDATE SQL (ORIGINAL SQL)
    ######################
    /*
    SELECT link FROM articles WHERE article_id='$id'
    UPDATE articles SET link='' WHERE article='$id'
    */
    public function update($subject,$id){
    try {
      $this->init_empty_control();
      if(empty($id))
      throw new Seo_Exception('Update id can\'t be empty.Seo class '.__FUNCTION__.' error!');
      $this->_set_link($subject);
      $sql = sprintf("SELECT %s FROM %s WHERE %s='%d'",
      $this->table['FIELD_LINK'],
      $this->table['TABLE_NAME'],
      $this->table['ID'],
      $id);
      //echo $sql;
      $query = mysql_query($sql);
      if(!$query) throw new seo_Exception(mysql_error());
      $row = mysql_fetch_assoc($query);
      /*
      $update_sql = sprintf("UPDATE %s SET %s='' WHERE %s='%d'",
      $this->table['TABLE_NAME'],
      $this->table['FIELD_LINK'],
      $this->table['ID'],
      $id);
      if(!mysql_query($update_sql)) throw new seo_Exception(mysql_error());
      */
      $link = Seo::clean_str($this->_get_link());
      $pattern = sprintf("<(%s).*(%s).*[0-9]>",$link,self::$replace_with_character);
      if (preg_match($pattern,$row[$this->table['FIELD_LINK']])) {
      $new_link = $row[$this->table['FIELD_LINK']];
      }elseif($link == $row[$this->table['FIELD_LINK']]){
      $new_link = $link;
      }else{
      $new_link = $this->query_link($link);
      }
      //update problem fixed !!
      return $new_link;
      }catch(Exception $e) {echo $e;}

    }
    /**
      *
      *    @Method:        reset_table
      *    @Access:        public
      *    @Description:   Clean all link data with space (' ') on current table.
      */
    ###  WARNING ALL DATA WILL DELETE ON THE CURRENT TABLE!! ##
    ######################
    # RESET TABLE SQL (ORIGINAL SQL)
    ######################
    /*
    UPDATE articles SET link=''
    */
    public function reset_table(){
        $this->init_empty_control();
        $sql = sprintf("UPDATE %s SET %s=''",$this->table['TABLE_NAME'],$this->table['FIELD_LINK']);
        if(!mysql_query($sql)) throw new seo_Exception(mysql_error());
    }
     /**
      *
      *    @Method:        restore_all
      *    @Access:        public
      *    @Description:   if any error occur you can restore all data on current table.(Recover all link)
      */
    ######################
    # RESTORE ALL SQL (ORIGINAL SQL)
    ######################
    /*
    SELECT title,link,article_id FROM articles
    UPDATE articles SET link='$link' WHERE article_id='id' LIMIT 1
    */
    public function restore_all(){
    try{
        $this->init_empty_control();
        $this->reset_table(); //reset all data..
        $select_sql = sprintf("SELECT %s,%s,%s FROM %s",
        $this->table['FIELD'],
        $this->table['FIELD_LINK'],
        $this->table['ID'],
        $this->table['TABLE_NAME']);
        //..
        $query = mysql_query($select_sql);
        if(!$query) throw new seo_Exception(mysql_error());

        while($obj = mysql_fetch_object($query)){
        $link = $this->update(
        $obj->{$this->table['FIELD']},
        $obj->{$this->table['ID']}
        );

        $id = $obj->{$this->table['ID']};
        //..
        $update_sql = sprintf("UPDATE %s SET %s='%s' WHERE
        %s='$id' LIMIT 1",
        $this->table['TABLE_NAME'],
        $this->table['FIELD_LINK'],
        $link,
        $this->table['ID'],
        $this->table['ID']);
        if(!mysql_query($update_sql)) throw new seo_Exception(mysql_error());
        } //end while.

        $this->show_table();
        echo "Restoration succesfuly completed!";
    }catch(Exception $e) {echo $e;}

    }

    /**
      *
      *    @Method:        show_table
      *    @Access:        public
      *    @Description:   show current table
      */
    ######################
    # SHOW TABLE SQL (ORIGINAL SQL)
    ######################
    /*
    SELECT title,link,article_id FROM articles
    */
    public function show_table(){
        /**/
        $this->init_empty_control();
        $select_sql = sprintf("SELECT %s,%s,%s FROM %s",
        $this->table['FIELD'],
        $this->table['FIELD_LINK'],
        $this->table['ID'],
        $this->table['TABLE_NAME']);

        echo "<table cellpadding=\"4\" cellspacing=\"0\" border=\"1\">";
        echo "<tr>";
        echo "<th>{$this->table['FIELD']}</th><th>{$this->table['FIELD_LINK']}</th>";
        echo "</tr>";

        $query = mysql_query($select_sql);
        if(!$query) throw new seo_Exception(mysql_error());

        while($row = mysql_fetch_assoc($query)){
        echo "<tr>";
        echo "<td>";
        echo $row[$this->table['FIELD']];
        echo "</td>";
        echo "<td>";
        echo $row[$this->table['FIELD_LINK']];
        echo "</td>";
        echo "</tr>";
        }
        echo "</table>";
    }


} // end of the class..

/*
    private function num_equal_link($new_link){
       $sql = "SELECT cat_link FROM category WHERE cat_link='$new_link'";
       $query = mysql_query($sql);
       if(!$query) throw new seo_Exception(mysql_error());
       return mysql_num_rows($query);
    }
    private function increase_link($link,$total){
        $new_link = $link.self::$replace_with_character.$total;
        if($this->num_equal_link($new_link) > 0){
          $count = $total++;
          $new_link = "";
          $new_link = $link.self::$replace_with_character.$count;
          if($this->num_equal_link($new_link) > 0){
          $this->increase_link($link,$count);
          }
        }
        return $new_link;
    }
*/
?>