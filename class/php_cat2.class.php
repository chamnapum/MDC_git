<?php
if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {exit('No direct access!');}
/*
    Php_Cat Mysql Nested Category Software.
    Copyright (C) 2008  Ersin GÜVENÇ.

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

  ***********************************************************************************************************
  *
  *  @filename: php_cat.class.php
  *  Php version PHP5.
  *  Mysql version 4 and Newer
  *  @author Ersin Güvenç <eguvenc@gmail.com>
  *  @license: GPL
  *  @Revision: 48
  *  @version $Id: php_cat.class.php  2008-07-13 11:24:27
  *  @version: v.0.0.3 @alpha 2
  *
  ************************************************************************************************************
*/
require "../config.inc.php";
/*
  #	Constant: Standard Table Definitions
*/
if (!defined ('TABLE_NAME')) define ('TABLE_NAME', 'category');
if (!defined ('CAT_ID')) define ('CAT_ID', 'cat_id');
if (!defined ('PARENT_ID')) define ('PARENT_ID', 'parent_id');
if (!defined ('CAT_NAME')) define ('CAT_NAME', 'cat_name');
if (!defined ('CAT_ORDER')) define ('CAT_ORDER', 'order');
if (!defined ('DSC')) define ('DSC', 'dsc');
if (!defined ('TYPE')) define ('TYPE', 'type');
if (!defined ('CAT_LINK')) define ('CAT_LINK', 'cat_link');
if (!defined ('LEFT')) define ('LEFT', 'lft');
if (!defined ('RIGHT')) define ('RIGHT', 'rgt');
//if (!defined ('TOP')) define ('TOP', 'tp'); //deprecated !

//catch errors.. you can customize exception classes.look at php5 manual.
Class cat_Exception extends Exception {
    function __toString(){
    $msg = "<center>";
    $msg.= "<div style=\"padding:4px;width:400px;background-color:#FFFFCC;border:1px solid #CCFF66;text-align:left;\">";
    $msg.= "<b>".__CLASS__ . ": [Error]:</b>&nbsp;". $this->getMessage()."<br /><b>[Line]:</b>&nbsp;".$this->getLine()."\n";
    $msg.="</div>";
    $msg.="</center>";
    return $msg;
    }
}
//feedback for php_cat actions..
Class feedback {
    static function add($feedback){
    $msg = "<center>";
    $msg.= "<div style=\"padding:4px;width:400px;background-color:#FFFFCC;border:1px solid #CCFF66;text-align:left;\">";
    $msg.= "<b>Info:</b>&nbsp;". $feedback."<br />";
    $msg.="</div>";
    $msg.="</center>";
    echo $msg;
    }
}

Class php_cat
{
     /**
	 *	Define your path separator style.
     *  home > forum > member
	 */
    public $separator = "&nbsp; / &nbsp;";
     /**
	 *  list_cat() method returns to associative array.
     *  @access: private.
     */
    private $list_cat_row = array();
    /**
	 *  path() method returns to associative array.
     *  @access: private.
     */
    private $path_row = array();
    /**
	 *  top_cat() method returns to associative array.
     *  @access: private.
     */
    private $top_row = array();
    /**
	 *  map() method returns to associative array.
     *  @access: private.
     */
    private $map_row = array();
    /**
	 *  children() method returns to associative array.
     *  @access: private.
     */
    private $child_row = array();
     /**
	 *  Create Search Engine friendly links from cat_names..
     *  example : ..?cat=portable-devices-2
     */
    public $seo = false;  //boolean
     /**
	 *  Allowed options variables.. option - default value.
     *  @access: private.
     */
    private $allowed_options = array(
        'separator',
        'area',
        'seo'
        //'menu'
        );
    /**
	 *  if params value not set default values will be set.
     *  @access: private.
     */
    private $default_parameters = array(
        'separator'=>'&nbsp; / &nbsp;',
        'area'=>'admin',
        'seo'=>true
        );

     /**
	 *  Choose working area administrator or client.
     *  @access: private.
     */
    private $area = "client"; //or admin

    /**
	*
	*	@Method:		__construct
    *	@Access:	    public
	*	@Parameters:	1
	*	@Param-1:		Array().
	*	@Description:   Set variables if key exists in allowed options.
	*/
    public function __construct($params = array())
    {
      //Auto set variables & factory...
      $this->factory($params);
    }
    /**
	*
	*	@Method:	    factory.
    *	@Access:	    public
	*	@Parameters:	1
	*	@Param-1:		Array().
	*	@Description:   Auto Set variables if key exists in allowed options.
	*/
    public function factory($params)
    {
    try{
      //Auto set variables...
      if(empty($params))
      $params = $this->default_parameters;
      foreach ($params as $key => $value) {
        if (in_array(strtolower($key), $this->allowed_options,true) && (!is_null($value))):
            $this->{strtolower($key)} = $value;
            else:
                throw new cat_Exception("
                You supply a wrong parameter or
                null value please check
                parameters
                or look
                at the
                documentation.".__FUNCTION__.'error!');
            endif;
        }

      switch ($this->area) {
        case 'client':
            if($this->seo)
            //blabla...
            break;

        case 'admin':
            if($this->seo){
            require_once "seo.class.inc.php";
            require_once "actions.class.php";
            }else{
            //require_once "seo.class.inc.php";
            require_once "actions.class.php";
            }
            break;
        }

    }catch(Exception $e) {echo $e;}
    } //end function

    /**
	*	@Method:        version
	*/
	public function version()
	{
		return 'Php Cat 0.0.3 @alpha 2';
	}
    /**
	*
	*	@Method:		add_cat
    *	@Access:	    public
	*	@Parameters:    1
	*	@Param-1:		data - array() - pointer .. WHERE = 'cat_name' or WHERE = 'parent_id'
	*	@Description:   Add New Category (add a not existing category).
	*/
    public function add_cat($data){
    try{
      if (empty($data['new_name'])) throw new cat_Exception("New category name empty!");
      $params['seo'] = $this->seo;
      Actions::getInstance()->set_params($params);
      Actions::getInstance()->{__FUNCTION__}($data);
    }catch(Exception $e) {echo $e;}
    }
    /**
	*
	*	@Method:		add_subcat
    *	@Access:	    public
	*	@Parameters:	1
	*	@Param-1:		data - array() - pointer .. WHERE = 'cat_name' or WHERE = 'parent_id'
	*	@Description:   Add New Category (add a not existing category).
	*/
    public function add_subcat($data){
    try{
      if (empty($data['new_name'])) throw new cat_Exception("New category name empty!");
      $params['seo'] = $this->seo;
      Actions::getInstance()->set_params($params);
      Actions::getInstance()->{__FUNCTION__}($data);
    }catch(Exception $e) {echo $e;}
    }
    /**
	*
	*	@Method:		del_cat
    *	@Access:	    public
	*	@Parameters:	1
    *   @Param-1:		data - array()
	*	@Description:   Delete current category and all children.
	*/
    public function del_cat($data){
    try{
      Actions::getInstance()->{__FUNCTION__}($data);
    }catch(Exception $e) {echo $e;}
    }
    /**
	*
	*	@Method:		update_cat
    *	@Access:	    public
	*	@Parameters:	1
    *   @Param-1:		data - array()
	*	@Description:   Delete one category not their children.
	*/
    public function update_cat($data){
		//print_r($data);
    try{
      if (empty($data['new_name'])) throw new cat_Exception("Category name empty!");
	  //$data['order'];
      $params['seo'] = $this->seo;
      Actions::getInstance()->set_params($params);
      Actions::getInstance()->{__FUNCTION__}($data);
    }catch(Exception $e) {echo $e;}
    }

    ######################
    # LIST CATEGORY SQL
    # You can create data store procudure from original sql.
    ######################
    /*
    SELECT node.cat_name, node.cat_link, node.cat_id, node.dsc, (COUNT( parent.cat_name ) - ( sub_tree.depth +1 )) AS depth
    FROM category AS node, category AS parent, category AS sub_parent,
    (SELECT node.cat_name, (COUNT( parent.cat_name ) -1) AS depth
    FROM category AS node, category AS parent
    WHERE node.lft BETWEEN parent.lft AND parent.rgt
    AND node.cat_name = '$parent'
    GROUP BY node.cat_name
    ORDER BY node.lft) AS sub_tree WHERE node.lft BETWEEN parent.lft AND parent.rgt AND node.lft
    BETWEEN sub_parent.lft AND sub_parent.rgt AND sub_parent.cat_name = sub_tree.cat_name
    GROUP BY node.cat_name
    HAVING depth >=0
    ORDER BY node.lft
    */
     /**
	*
	*	@Method:		list_cat
    *	@Access:	    public
    *   @param-1:       data -  array()
	*	@Description:   List children in associative arrays with action.
	*/
    public function list_cat($data,$type)
    {
    try{ //exception start
      if($this->seo): $node_cat_link = ',node.'.CAT_LINK; else: $node_cat_link = ""; endif;
      $depth = 1; //category level..

      if(array_key_exists('cat_id',$data)) {
          $parent = $data['cat_id'];
          $index = sprintf("AND node.%s = '%d'",CAT_ID,$parent);  //"AND node.cat_id = '$parent'"
          //$having_depth = ">= 1";
          if($data['cat_id'] == "" || $data['cat_id'] == 0){
          $index = sprintf("AND node.%s = '%d'",PARENT_ID,0);           //"AND node.parent_id = '0'"; //Get top categories...
          //$index = "AND node.lft = '1'";
          //$index = "AND node.rgt=(SELECT MAX(rgt) FROM category)";
          $having_depth = "< 1";
          }else{
          $having_depth = "= ".$depth;
          }
      }elseif(array_key_exists('cat_name',$data)){
          $parent = $data['cat_name'];
          $index = sprintf("AND node.%s = '%s'",CAT_NAME,$parent);
          //...
          if($data['cat_name'] == "") {
          $index = sprintf("AND node.%s = '%d'",PARENT_ID,0);           //"AND node.parent_id = '0'"; //Get top categories...
          //$index = "AND node.lft = '1'";
          //$index = "AND node.rgt=(SELECT MAX(rgt) FROM category)";
          $having_depth = "< 1";
          }else{
          $having_depth = "= ".$depth;  //">=1"
          }
      }elseif(array_key_exists('cat_link',$data)){
          $parent = $data['cat_link'];
          $index = sprintf("AND node.%s = '$parent'",CAT_LINK,$parent);
          //...
          if($data['cat_link'] == ""){
          $index = sprintf("AND node.%s = '%d'",PARENT_ID,0);           //"AND node.parent_id = '0'"; //Get top categories...
          //$index = "AND node.lft = '1'";
          //$index = "AND node.rgt=(SELECT MAX(rgt) FROM category)";
          $having_depth = "< 1";
          }else{
          $having_depth = "= ".$depth;
          }
      }elseif(array_key_exists('order',$data)){
          $parent = $data['order'];
          $index = sprintf("AND node.%s = '$parent'",CAT_ORDER,$parent);
          //...
          if($data['order'] == ""){
          $index = sprintf("AND node.%s = '%d'",PARENT_ID,0);           //"AND node.parent_id = '0'"; //Get top categories...
          //$index = "AND node.lft = '1'";
          //$index = "AND node.rgt=(SELECT MAX(rgt) FROM category)";
          $having_depth = "< 1";
          }else{
          $having_depth = "= ".$depth;
          }
      }
      $sql = sprintf("SELECT node.%s %s,node.%s, node.%s, node.%s, (COUNT( parent.%s ) - ( sub_tree.depth +1 )) AS depth
      FROM %s AS node, %s AS parent, %s AS sub_parent,
      (SELECT node.%s, (COUNT( parent.%s ) -1) AS depth
      FROM %s AS node, %s AS parent
      WHERE node.%s BETWEEN parent.%s AND parent.%s
      %s AND node.type='".$type."'
      GROUP BY node.%s
      ORDER BY node.%s) AS sub_tree WHERE node.%s BETWEEN parent.%s AND parent.%s AND node.%s
      BETWEEN sub_parent.%s AND sub_parent.%s AND sub_parent.%s = sub_tree.%s
      GROUP BY node.%s
      HAVING depth %s
      ORDER BY node.%s",
      CAT_NAME,$node_cat_link,CAT_ID,DSC,CAT_ORDER,CAT_NAME,TABLE_NAME,TABLE_NAME,TABLE_NAME,CAT_ID,
                                                           //group by cat_name problem fixed.
      CAT_ID,TABLE_NAME,TABLE_NAME,LEFT,LEFT,RIGHT,$index,CAT_ID,LEFT,LEFT,LEFT,RIGHT,LEFT,
      LEFT,RIGHT,CAT_ID,CAT_ID,
      //group by cat_name problem fixed.
      CAT_ID,$having_depth,CAT_ORDER);
	  //echo $sql;
      $query = mysql_query($sql); 
	  if(!$query) throw new cat_Exception(mysql_error());
	  
      while($row = mysql_fetch_assoc($query)){
      $this->list_cat_row[] = $row;
      }
      return $this->list_cat_row;
      //exception end
      }catch(Exception $e) {echo $e;}
    }

    ######################
    # CHILDREN SQL
    ######################
    /*
    SELECT node.cat_name, (COUNT(parent.cat_name) - (sub_tree.depth + 1)) AS depth
    FROM category AS node,
    category AS parent,
    category AS sub_parent,
    (SELECT node.cat_name, (COUNT(parent.cat_name) - 1) AS depth
    FROM category AS node,
    category AS parent
    WHERE node.lft BETWEEN parent.lft AND parent.rgt
    AND node.cat_name = 'PORTABLE ELECTRONICS'
    GROUP BY node.cat_name
    ORDER BY node.lft) AS sub_tree
    WHERE node.lft BETWEEN parent.lft AND parent.rgt
        AND node.lft BETWEEN sub_parent.lft AND sub_parent.rgt
        AND sub_parent.cat_name = sub_tree.cat_name
    GROUP BY node.cat_name
    HAVING depth = 1  // for show current cat <=1
    ORDER BY node.lft;
    */
     /**
	*
	*	@Method:		children
    *	@Access:	    public
    *   @param-1:       data -  array()
	*	@Description:   Get children of the requested category.
	*/
    public function children($data)
    {
    try{
      if(array_key_exists('cat_id',$data)) {
      $parent = $data['cat_id'];
      $constant = "CAT_ID";
      }elseif(array_key_exists('cat_name',$data)){
      $parent = $data['cat_name'];
      $constant = "CAT_NAME";
      }elseif(array_key_exists('cat_link',$data)){
      $parent = $data['cat_link'];
      $constant = "CAT_LINK";
      }
      $sql = sprintf("SELECT node.%s,node.%s,node.%s, (COUNT(parent.%s) - (sub_tree.depth + 1)) AS depth
      FROM %s AS node,
      %s AS parent,
      %s AS sub_parent,
      (SELECT node.%s, (COUNT(parent.%s) - 1) AS depth
      FROM %s AS node,
      %s AS parent
      WHERE node.%s BETWEEN parent.%s AND parent.%s
      AND node.%s = '%s'
      GROUP BY node.%s
      ORDER BY node.%s) AS sub_tree
      WHERE node.%s BETWEEN parent.%s AND parent.%s
          AND node.%s BETWEEN sub_parent.%s AND sub_parent.%s
          AND sub_parent.%s = sub_tree.%s
      GROUP BY node.%s
      HAVING depth = 1
      ORDER BY node.order",CAT_NAME,CAT_LINK,CAT_ID,CAT_ID,TABLE_NAME,TABLE_NAME,TABLE_NAME,
      CAT_ID,CAT_ID,TABLE_NAME,TABLE_NAME,LEFT,LEFT,RIGHT,constant($constant),$parent,
      CAT_ID,LEFT,LEFT,LEFT,RIGHT,LEFT,LEFT,RIGHT,CAT_ID,CAT_ID,CAT_ID,LEFT);
       /* return associative array*/
      $query = mysql_query($sql); if(!$query) throw new cat_Exception(mysql_error());
      while($row = mysql_fetch_assoc($query)){
      $this->child_row[] = $row;
      }
      return $this->child_row;
      //exception end
      }catch(Exception $e) {echo $e;}
    }

    ######################
    # PATH SQL
    ######################
    /*
    SELECT parent.cat_name
    FROM category AS node,
    category AS parent
    WHERE node.lft BETWEEN parent.lft AND parent.rgt
    AND node.cat_name = 'FLASH'
    ORDER BY parent.lft;
    */
    /**
	*
	*	@Method:		path
    *	@Access:	    public
    *   @param-1:       data - array()
	*	@Description:   Get current single path.
	*/
    public function path($data)
    {
    try{
      if(array_key_exists('cat_id',$data)) {
      $node = $data['cat_id'];
      $constant = "CAT_ID";
      }elseif(array_key_exists('cat_name',$data)){
      $node = $data['cat_name'];
      $constant = "CAT_NAME";
      }elseif(array_key_exists('cat_link',$data)){
      $node = $data['cat_link'];
      $constant = "CAT_LINK";
      }
      $sql = sprintf("SELECT parent.%s,parent.%s,parent.%s
      FROM %s AS node,
      %s AS parent
      WHERE node.%s BETWEEN parent.%s AND parent.%s
      AND node.%s = '%s'
      ORDER BY parent.%s",CAT_NAME,CAT_LINK,CAT_ID,TABLE_NAME,TABLE_NAME,
      LEFT,LEFT,RIGHT,constant($constant),$node,'order');
      /* return associative array*/
      $query = mysql_query($sql); if(!$query) throw new cat_Exception(mysql_error());
      while($row = mysql_fetch_assoc($query)){
      $this->path_row[] = $row;
      }
      return $this->path_row;
      //exception end
      }catch(Exception $e) {echo $e;}
    }
    ######################
    # TOP CATEGORY SQL
    ######################
    /*
    SELECT * FROM category WHERE parent_id='-1';
    */
    /**
	*
	*	@Method:		top_cat
    *	@Access:	    public
    *   @param-1:       data - array()
	*	@Description:   Get top categories  - with (0).
	*/
    public function top_cat($data)
    {
    try{
      $sql = sprintf("SELECT * FROM %s WHERE %s='%d'",TABLE_NAME,PARENT_ID,0);
      $query = mysql_query($sql); if(!$query) throw new cat_Exception(mysql_error());
      while($row = mysql_fetch_assoc($query)){
        $this->top_row[] = $row;
      }
      return $this->top_row;
      //exception end
      }catch(Exception $e) {echo $e;}
    }
     /**
	*
	*	@Method:		top_cat
    *	@Access:	    public
    *   @param-1:       data - array()
	*	@Description:   return the last biggest category id.
	*/
    ######################
    # MAX CATEGORY SQL
    ######################
    /*
    SELECT cat_id,cat_link,cat_name, MAX(rgt) AS max_c FROM category
    WHERE parent_id='-1' GROUP BY cat_name ORDER BY max_c DESC LIMIT 1
    */
    public function max_cat()
    {
    try{
      $sql = sprintf("SELECT %s,%s,%s, MAX(%s) AS max_c FROM %s
      WHERE %s='%d' GROUP BY %s ORDER BY max_c DESC LIMIT 1",CAT_ID,
      CAT_LINK,CAT_NAME,RIGHT,TABLE_NAME,PARENT_ID,0,CAT_NAME);
      $query = mysql_query($sql); if(!$query) throw new cat_Exception(mysql_error());
      $row = mysql_fetch_assoc($query);
      return $row;
    //exception end
    }catch(Exception $e) {echo $e;}
    }
    ######################
    # MAP CATEGORY SQL
    ######################
    /*
    SELECT node.cat_name, node.cat_link, node.cat_id, node.dsc,
    (COUNT(parent.cat_name) - 1) AS depth
    FROM category AS node,
    category AS parent
    WHERE node.lft BETWEEN parent.lft AND parent.rgt
    GROUP BY node.cat_name
    ORDER BY node.lft
    */
     /**
	*
	*	@Method:		map
    *	@Access:	    public
	*	@Description:   index all categories into array.
	*/
    public function map()
    {
    try{
      $sql = sprintf("SELECT node.%s, node.%s, node.%s, node.%s,
      (COUNT(parent.%s) - 1) AS depth
      FROM %s AS node,
      %s AS parent
      WHERE node.%s  BETWEEN parent.%s AND parent.%s
      GROUP BY node.%s
      ORDER BY parent.order, node.%s",CAT_NAME,CAT_LINK,CAT_ID,DSC,CAT_NAME,TABLE_NAME,
      TABLE_NAME,LEFT,LEFT,RIGHT,CAT_NAME,'order');
      $query = mysql_query($sql); if(!$query) throw new cat_Exception(mysql_error());

      while($row = mysql_fetch_assoc($query)){
      $this->map_row[] = $row;
      }
	  //die(print_r($this->map_row));
      return $this->map_row;
      //exception end
      }catch(Exception $e) {echo $e;}
    }
	
	public function map2()
    {
    try{
      $sql2 =sprintf("SELECT node.%s, node.%s, node.%s, node.%s, node.order, node.parent_id,
      (COUNT(parent.%s) - 1) AS depth
      FROM %s AS node,
      %s AS parent
      WHERE
	  node.type='1' AND
	  node.%s BETWEEN parent.%s AND parent.%s   
      GROUP BY node.%s
      ORDER BY node.%s",CAT_NAME,CAT_LINK,CAT_ID,DSC,CAT_NAME,TABLE_NAME,
      TABLE_NAME,LEFT,LEFT,RIGHT,CAT_NAME,'order');
	  //echo $sql2;
	  
	  
      $query2 = mysql_query($sql2); if(!$query2) throw new cat_Exception(mysql_error());

      while($row2 = mysql_fetch_assoc($query2)){
      $this->map_row2[] = $row2;
      }
      return $this->map_row2;
      //exception end
      }catch(Exception $e) {echo $e;}
    }
	
	public function map3()
    {
    try{
      $sql2 =sprintf("SELECT node.%s, node.%s, node.%s, node.%s, node.order, node.parent_id,
      (COUNT(parent.%s) - 1) AS depth
      FROM %s AS node,
      %s AS parent
      WHERE
	  node.type='0' AND
	  node.%s BETWEEN parent.%s AND parent.%s   
      GROUP BY node.%s
      ORDER BY node.%s",CAT_NAME,CAT_LINK,CAT_ID,DSC,CAT_NAME,TABLE_NAME,
      TABLE_NAME,LEFT,LEFT,RIGHT,CAT_NAME,'order');
	  //echo $sql2;
	  
	  
      $query2 = mysql_query($sql2); if(!$query2) throw new cat_Exception(mysql_error());

      while($row2 = mysql_fetch_assoc($query2)){
      $this->map_row2[] = $row2;
      }
      return $this->map_row2;
      //exception end
      }catch(Exception $e) {echo $e;}
    }
	
	public function map4()
    {
    try{
      $sql2 =sprintf("SELECT node.%s, node.%s, node.%s, node.%s, node.order, node.parent_id,
      (COUNT(parent.%s) - 1) AS depth
      FROM %s AS node,
      %s AS parent
      WHERE
	  node.type='3' AND
	  node.%s BETWEEN parent.%s AND parent.%s   
      GROUP BY node.%s
      ORDER BY node.%s",CAT_NAME,CAT_LINK,CAT_ID,DSC,CAT_NAME,TABLE_NAME,
      TABLE_NAME,LEFT,LEFT,RIGHT,CAT_NAME,'order');
	  //echo $sql2;
	  
	  
      $query2 = mysql_query($sql2); if(!$query2) throw new cat_Exception(mysql_error());

      while($row2 = mysql_fetch_assoc($query2)){
      $this->map_row2[] = $row2;
      }
      return $this->map_row2;
      //exception end
      }catch(Exception $e) {echo $e;}
    }
    ######################
    # DEPTH CATEGORY SQL
    ######################
    /*
    SELECT node.cat_name, node.cat_link, node.cat_id,
    (COUNT(parent.cat_name) - 1) AS depth
    FROM category AS node,
    category AS parent
    WHERE node.lft BETWEEN parent.lft AND parent.rgt
    AND node.cat_name = 'FLASH'
    GROUP BY node.cat_name
    ORDER BY node.lft
    */
     /**
	*
	*	@Method:		depth
    *	@Access:	    public
	*	@Description:   get depth of the current category.
	*/
    public function depth($data)
    {
    try{
      if(array_key_exists('cat_id',$data)) {
      $node = $data['cat_id'];
      $constant = "CAT_ID";
      }elseif(array_key_exists('cat_name',$data)){
      $node = $data['cat_name'];
      $constant = "CAT_NAME";
      }elseif(array_key_exists('cat_link',$data)){
      $node = $data['cat_link'];
      $constant = "CAT_LINK";
      }
      $sql = sprintf("SELECT node.%s, node.%s, node.%s,
      (COUNT(parent.%s) - 1) AS depth
      FROM %s AS node,
      %s AS parent
      WHERE node.%s BETWEEN parent.%s AND parent.%s
      AND node.%s = '%s'
      GROUP BY node.%s
      ORDER BY node.%s",CAT_NAME,CAT_LINK,CAT_ID,CAT_NAME,TABLE_NAME,
      TABLE_NAME,LEFT,LEFT,RIGHT,constant($constant),$node,CAT_NAME,'order');
      $query = mysql_query($sql); if(!$query) throw new cat_Exception(mysql_error());
      $row = mysql_fetch_assoc($query);
      return $row;
      //exception end
      }catch(Exception $e) {echo $e;}
    }

    /**
	*
	*	@Method:		fetch_num
    *	@Access:	    public
	*	@Description:   check database have any record or not
	*/
    public function fetch_num(){
    try{
      $sql = sprintf("SELECT * FROM %s",TABLE_NAME);
      $query = mysql_query($sql); if(!$query) throw new cat_Exception(mysql_error());
      return mysql_num_rows($query);
      }catch(Exception $e) {echo $e;}
    }

} // end of the php_cat class...

?>
