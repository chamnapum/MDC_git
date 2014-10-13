<?php
if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {exit('No direct access!');}

/**************************************
* @filename: actions.class.php
* @author: Ersin Güvenç
* This class is part of the php_cat class.
*/

//Purely static class...
class Actions extends php_cat
{
  static $instance;

  public function __construct() {}
  //this object can not copy except getInstance() method.
  private function __clone() {}

  //prevent duplication of memory using by Singleton Pattern.
  public static function getInstance()
  {
     if(! (self::$instance instanceof self))
     {
      self::$instance = new self();
     }
    return self::$instance;
  }

  //set php_cat construct parameters
  public function set_params($params){
    $this->seo = $params['seo'];
    //..
  }
    /**
	*
	*	@Method:		id_exists
    *	@Access:	    protected
	*	@Parameters:	1
	*	@Param-1:		data['cat_id']
	*	@Description:   Check id exist or not exist in database.
	*/
  protected function id_exists($cat_id){
    $sql = sprintf("SELECT %s FROM %s WHERE %s='%d'",CAT_NAME,TABLE_NAME,CAT_ID,$cat_id);
    $query = mysql_query($sql); if(!$query) throw new cat_Exception(mysql_error());
    if(mysql_num_rows($query) == 0):
    return false; else: return true; endif;
  }
  ######################
  # ADD CATEGORY SQL
  # You can create data store procudure from original sql.
  ######################
  /*
  INSERT INTO category (cat_name,parent_id,dsc,cat_link,lft,rgt) VALUES ('%s','%d','%s','%s','1','2');
  */
  /*
  LOCK TABLE category WRITE;
  SELECT @myRight := rgt FROM category WHERE cat_id = '$cat_id';
  UPDATE category SET rgt = rgt + 2 WHERE rgt > @myRight;
  UPDATE category SET lft = lft + 2 WHERE lft > @myRight;
  INSERT INTO category(cat_name,parent_id,dsc,cat_link,lft,rgt) VALUES('$new_catname','0','$dsc','$link',
  @myRight + 1,@myRight + 2);
  UNLOCK TABLES;
  */
    /**
	*
	*	@Method:		add_cat
    *	@Access:	    public
	*	@Parameters:	1
	*	@Param-1:		data - array - cat_id,new_name,parent_id
	*	@Description:   Add New Category (add a not existing category).
	*/
  public function add_cat($data)
  {
    if(!array_key_exists('new_name',$data)) throw new cat_Exception("Please provide a associative array
     that contain new category name (new_name).");
    if(!array_key_exists('cat_id',$data)) throw new cat_Exception("Please provide cat_id!");
    if(!array_key_exists('parent_id',$data)) throw new cat_Exception("Please provide parent_id!");
    if(!array_key_exists('type',$data)) throw new cat_Exception("Please provide type!");
	
      $parent = (int)$data['cat_id'];
      $constant = "CAT_ID";

      $level = $this->depth(array('cat_id'=>$parent));  //if cat_id level (depth)  == 0 parent_id must be = 0
      //print_r($level);
      if($level['depth'] == 0){
      $parent_id = 0;
      }else{
      $parent_id = $data['parent_id'];
      }

    switch ($this->seo) { case 0: $link = ""; //seo false.
    break;
    case 1:         //seo true.
        #### create seo insert link.
        $seo = Seo::getInstance();
        $link = $seo->insert($data['new_name']);
        #### create seo insert link.
        break;
    }

     //if no any record in database !
    if($this->fetch_num() == 0){
    $FIRST_CAT = sprintf("INSERT INTO %s (%s,%s,%s,%s,%s,%s,%s,%s) VALUES ('%s','%d','%s','%s','%s','%s','1','2')",
    TABLE_NAME,CAT_NAME,PARENT_ID,DSC,'category.order',CAT_LINK,TYPE,LEFT,RIGHT,$data['new_name'],0,$data['dsc'],$data['order'],$link,$data['type']);
    $query = mysql_query($FIRST_CAT); if(!$query) throw new cat_Exception(mysql_error());
	//print_r($query);
    }else{

    $LOCK_TABLE = sprintf("LOCK TABLE %s WRITE ",TABLE_NAME); //lock tables in current mysql user session.
    $SELECT = sprintf("SELECT @myRight := %s FROM %s WHERE %s = '%s'",RIGHT,TABLE_NAME,constant($constant),$parent);
    $UPDATE_RIGTH = sprintf("UPDATE %s SET %s = %s + 2 WHERE %s > @myRight",TABLE_NAME,RIGHT,RIGHT,RIGHT);
    $UPDATE_LEFT = sprintf("UPDATE %s SET %s = %s + 2 WHERE %s > @myRight",TABLE_NAME,LEFT,LEFT,LEFT);
    $INSERT = sprintf("INSERT INTO %s (%s,%s,%s,%s,%s,%s,%s,%s) VALUES ('%s','%d','%s','%s','%s','%s',@myRight + 1,@myRight + 2)",TABLE_NAME,
    CAT_NAME,PARENT_ID,DSC,'category.order',CAT_LINK,TYPE,LEFT,RIGHT,$data['new_name'],$parent_id,$data['dsc'],$data['order'],$link,$data['type']);
    $UNLOCK_TABLE = "UNLOCK TABLES";
    //echo $SELECT."\n".$UPDATE_RIGTH."\n".$UPDATE_LEFT."\n".$INSERT."\n";

    //echo $INSERT;
    if(!mysql_query($LOCK_TABLE)) throw new cat_Exception(mysql_error());
    if(!mysql_query($SELECT)) throw new cat_Exception(mysql_error());
    if(!mysql_query($UPDATE_RIGTH)) throw new cat_Exception(mysql_error());
    if(!mysql_query($UPDATE_LEFT)) throw new cat_Exception(mysql_error());
    if(!mysql_query($INSERT)) throw new cat_Exception(mysql_error());
    if(!mysql_query($UNLOCK_TABLE)) throw new cat_Exception(mysql_error());
    }

    feedback::add('New category added!');

  }
  ######################
  # ADD SUB CATEGORY SQL
  # You can create data store procudure from original sql.
  ######################
   /*
  LOCK TABLE category WRITE;
  SELECT @myLeft := lft FROM category
  WHERE name = '$cat_name';
  UPDATE category SET rgt = rgt + 2 WHERE rgt > @myLeft;
  UPDATE category SET lft = lft + 2 WHERE lft > @myLeft;
  INSERT INTO category(cat_name, parent_id, lft, rgt) VALUES('$new_cat_name', parent_id, @myLeft + 1, @myLeft + 2);
  UNLOCK TABLES;
  */
    /**
	*
	*	@Method:		add_subcat
    *	@Access:	    public
	*	@Parameters:	1
	*	@Param-1:		data - array - pointer .. new_name,cat_id
	*	@Description:   Add sub category inside a existing category.if category has no children use add_subcat
	*/
  public function add_subcat($data)
  {
    if(!array_key_exists('new_name',$data)) throw new cat_Exception("Please provide a associative array
     that contain new category name (new_name).");
    if(!array_key_exists('cat_id',$data)) throw new cat_Exception("Please provide cat_id!");
    //if(!array_key_exists('parent_id',$data)) throw new cat_Exception("Please provide parent_id!");

    $parent = (int)$data['cat_id'];
    $constant = "CAT_ID";

    switch ($this->seo) { case 0: $link = ""; //seo false.
    break;
    case 1:         //seo true.
        #### create seo insert link.
        $seo = Seo::getInstance();
        $link = $seo->insert($data['new_name']);
        #### create seo insert link.
        break;
    }

    $LOCK_TABLE = sprintf("LOCK TABLE %s WRITE",TABLE_NAME); //lock tables in current user session.
    $SELECT = sprintf("SELECT @myLeft := %s FROM %s WHERE %s = '%s'",LEFT,TABLE_NAME,constant($constant),$parent);
    $UPDATE_RIGTH = sprintf("UPDATE %s SET %s = %s + 2 WHERE %s > @myLeft",TABLE_NAME,RIGHT,RIGHT,RIGHT);
    $UPDATE_LEFT = sprintf("UPDATE %s SET %s = %s + 2 WHERE %s > @myLeft",TABLE_NAME,LEFT,LEFT,LEFT);
    $INSERT = sprintf("INSERT INTO %s (%s,%s,%s,%s,%s,%s,%s,%s) VALUES ('%s','%s','%s','%s','%s','%s',@myLeft + 1,@myLeft + 2)",
    TABLE_NAME,CAT_NAME,PARENT_ID,DSC,'category.order',CAT_LINK,TYPE,LEFT,RIGHT,$data['new_name'],$parent,$data['dsc'],$data['order'],$link,$data['type']);
    $UNLOCK_TABLE = "UNLOCK TABLES";

    if(!mysql_query($LOCK_TABLE)) throw new cat_Exception(mysql_error());
    if(!mysql_query($SELECT)) throw new cat_Exception(mysql_error());
    if(!mysql_query($UPDATE_RIGTH)) throw new cat_Exception(mysql_error());
    if(!mysql_query($UPDATE_LEFT)) throw new cat_Exception(mysql_error());
    if(!mysql_query($INSERT)) throw new cat_Exception(mysql_error());
    if(!mysql_query($UNLOCK_TABLE)) throw new cat_Exception(mysql_error());

    feedback::add('New Subcategory added!');
  }
      ######################
    # UPDATE CATEGORY SQL
    ######################
    /*
    UPDATE category SET cat_name='$new_name',dsc='$dsc',cat_link='$link'
    WHERE cat_id='$current_id'
    */
    /**
	*
	*	@Method:		update_cat
    *	@Access:	    public
	*	@Parameters:	1
	*	@Param-1:		data - array - pointer .. WHERE = 'cat_name' or cat_id
	*	@Description:   Update cat_name,dsc,cat_link fields on current category.
	*/
   public function update_cat($data)
   {
   //print_r($data);
    if(!array_key_exists('new_name',$data)) throw new cat_Exception("Please provide a new category name (new_name).");
    if(!array_key_exists('cat_id',$data)) throw new cat_Exception('Please provide a cat_id !');

    $current_id = (int)$data['cat_id'];
    $constant = "CAT_ID";
    //if not exist id in database.
    if(!$this->id_exists($data['cat_id'])) throw new cat_Exception("This category ID not found in database!");

    switch ($this->seo) {
    case 0: $link = ""; //seo false.
    break;
    case 1:         //seo true.
        #### create seo update link.
        $seo = Seo::getInstance();
        $link = $seo->update($data['new_name'],$current_id);
        #### create seo update link.
        break;
    }
    //echo $link;

    $UPDATE = sprintf("UPDATE %s SET %s='%s',%s='%s',%s='%s' WHERE %s='%s'",
    TABLE_NAME,CAT_NAME,$data['new_name'],'category.order',$data['order'],CAT_LINK,$link,constant($constant),$current_id);
	//echo $UPDATE;
    if(!mysql_query($UPDATE)) throw new cat_Exception(mysql_error());

    feedback::add('Category updated!');
   }
    /**
    *
    *	@Method:		del_cat
    *	@Access:	    public
    *	@Parameters:	1
    *	@Param-1:		data - array - pointer .. WHERE = 'cat_name' or cat_id
    *	@Description:   Delete current category and their children.
    */
       ######################
    # DELETE CATEGORY SQL
    # You can create data store procudure from original sql.
    ######################
    /*
     LOCK TABLE category WRITE;
     SELECT @myLeft := lft, @myRight := rgt, @myWidth := rgt - lft + 1
     FROM category WHERE name = 'TELEVISIONS';
     DELETE FROM category WHERE lft BETWEEN @myLeft AND @myRight;
     UPDATE category SET rgt = rgt - @myWidth WHERE rgt > @myRight;
     UPDATE category SET lft = lft - @myWidth WHERE lft > @myRight;
     UNLOCK TABLES;
    */
    public function del_cat($data)
    {
      if(!array_key_exists('cat_id',$data)) throw new cat_Exception('Please provide a cat_id !');

      $parent = (int)$data['cat_id'];
      $constant = "CAT_ID";
      //if not exist id in database.
      if(!$this->id_exists($data['cat_id'])) throw new cat_Exception("This category ID not found in database!");

		/*echo"<script>alert('".$data['cat_id']."');</script>";*/
      $LOCK_TABLE = sprintf("LOCK TABLE %s WRITE",TABLE_NAME); //lock tables in current user session.
      $SELECT = sprintf("SELECT @myLeft := %s, @myRight := %s, @myWidth := %s - %s + 1 FROM %s WHERE %s = '%s'",
      LEFT,RIGHT,RIGHT,LEFT,TABLE_NAME,constant($constant),$parent);
      $DELETE = sprintf("DELETE FROM %s WHERE %s BETWEEN @myLeft AND @myRight",TABLE_NAME,LEFT);
	  
      $UPDATE_RIGTH = sprintf("UPDATE %s SET %s = %s - @myWidth WHERE %s > @myRight",TABLE_NAME,RIGHT,RIGHT,RIGHT);
      $UPDATE_LEFT = sprintf("UPDATE %s SET %s = %s - @myWidth WHERE %s > @myRight",TABLE_NAME,LEFT,LEFT,LEFT);
      $UNLOCK_TABLE = "UNLOCK TABLES";

      //echo $LOCK_TABLE.$SELECT.$DELETE.$UPDATE_RIGTH.$UPDATE_LEFT.$UNLOCK_TABLE;
      if(!mysql_query($LOCK_TABLE)) throw new cat_Exception(mysql_error());
      if(!mysql_query($SELECT)) throw new cat_Exception(mysql_error());
      if(!mysql_query($DELETE)) throw new cat_Exception(mysql_error());
      if(!mysql_query($UPDATE_RIGTH)) throw new cat_Exception(mysql_error());
      if(!mysql_query($UPDATE_LEFT)) throw new cat_Exception(mysql_error());
      if(!mysql_query($UNLOCK_TABLE)) throw new cat_Exception(mysql_error());

      feedback::add('Category deleted!');
    }

} // end class..

?>