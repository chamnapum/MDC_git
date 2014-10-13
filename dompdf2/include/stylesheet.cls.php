<?php
/**
 * DOMPDF - PHP5 HTML to PDF renderer
 *
 * File: $RCSfile: stylesheet.cls.php,v $
 * Created on: 2004-06-01
 *
 * Copyright (c) 2004 - Benj Carson <benjcarson@digitaljunkies.ca>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this library in the file LICENSE.LGPL; if not, write to the
 * Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 * 02111-1307 USA
 *
 * Alternatively, you may distribute this software under the terms of the
 * PHP License, version 3.0 or later.  A copy of this license should have
 * been distributed with this file in the file LICENSE.PHP .  If this is not
 * the case, you can obtain a copy at http://www.php.net/license/3_0.txt.
 *
 * The latest version of DOMPDF might be available at:
 * http://www.dompdf.com/
 *
 * @link http://www.dompdf.com/
 * @copyright 2004 Benj Carson
 * @author Benj Carson <benjcarson@digitaljunkies.ca>
 * @contributor Helmut Tischer <htischer@weihenstephan.org>
 * @package dompdf
 *
 * Changes
 * @contributor Helmut Tischer <htischer@weihenstephan.org>
 * @version 0.5.1.htischer.20090507
 * - Specifity of css selector chain was too small because leading whitespace
 *   to be counted as number of elements was removed
 * - On parsing css properties accept and register !important attribute
 * - Add optional debug output
 * @version 20090610
 * - _parse_properties on style property name and value remove augmenting superflous
 *   space for consistent parsing, in particular combined values like background
 */

/* $Id: stylesheet.cls.php 360 2011-02-15 19:33:52Z fabien.menager $ */

/**
 * The location of the default built-in CSS file.
 * {@link Stylesheet::DEFAULT_STYLESHEET}
 */
define('__DEFAULT_STYLESHEET', DOMPDF_LIB_DIR . DIRECTORY_SEPARATOR . "res" . DIRECTORY_SEPARATOR . "html.css");

/**
 * The master stylesheet class
 *
 * The Stylesheet class is responsible for parsing stylesheets and style
 * tags/attributes.  It also acts as a registry of the individual Style
 * objects generated by the current set of loaded CSS files and style
 * elements.
 *
 * @see Style
 * @package dompdf
 */
class Stylesheet {
  
  /**
   * The location of the default built-in CSS file.
   */
  const DEFAULT_STYLESHEET = __DEFAULT_STYLESHEET; 

  /**
   * Array of currently defined styles
   * @var array
   */
  private $_styles;

  /**
   * Base protocol of the document being parsed
   * Used to handle relative urls.
   * @var string
   */
  private $_protocol;

  /**
   * Base hostname of the document being parsed
   * Used to handle relative urls.
   * @var string
   */
  private $_base_host;

  /**
   * Base path of the document being parsed
   * Used to handle relative urls.
   * @var string
   */
  private $_base_path;

  /**
   * The style defined by @page rules
   * @var Style
   */
  private $_page_style;

  /**
   * List of loaded files, used to prevent recursion
   * @var array
   */
  private $_loaded_files;

  /**
   * Accepted CSS media types
   * List of types and parsing rules for future extensions:
   * http://www.w3.org/TR/REC-html40/types.html
   *   screen, tty, tv, projection, handheld, print, braille, aural, all
   * The following are non standard extensions for undocumented specific environments.
   *   static, visual, bitmap, paged, dompdf
   * Note, even though the generated pdf file is intended for print output,
   * the desired content might be different (e.g. screen or projection view of html file).
   * Therefore allow specification of content by dompdf setting DOMPDF_DEFAULT_MEDIA_TYPE.
   * If given, replace media "print" by DOMPDF_DEFAULT_MEDIA_TYPE.
   * (Previous version $ACCEPTED_MEDIA_TYPES = $ACCEPTED_GENERIC_MEDIA_TYPES + $ACCEPTED_DEFAULT_MEDIA_TYPE)
   */
  static $ACCEPTED_DEFAULT_MEDIA_TYPE = "print";
  static $ACCEPTED_GENERIC_MEDIA_TYPES = array("all", "static", "visual", "bitmap", "paged", "dompdf");

  /**
   * The class constructor.
   *
   * The base protocol, host & path are initialized to those of
   * the current script.
   */
  function __construct() {
    $this->_styles = array();
    $this->_loaded_files = array();
    list($this->_protocol, $this->_base_host, $this->_base_path) = explode_url($_SERVER["SCRIPT_FILENAME"]);
    $this->_page_style = null;
  }
  
  /**
   * Class destructor
   */
  function __destruct() {
    clear_object($this);
  }

  /**
   * Set the base protocol
   *
   * @param string $proto
   */
  function set_protocol($proto) { $this->_protocol = $proto; }

  /**
   * Set the base host
   *
   * @param string $host
   */
  function set_host($host) { $this->_base_host = $host; }

  /**
   * Set the base path
   *
   * @param string $path
   */
  function set_base_path($path) { $this->_base_path = $path; }

  /**
   * Return the base protocol for this stylesheet
   *
   * @return string
   */
  function get_protocol() { return $this->_protocol; }

  /**
   * Return the base host for this stylesheet
   *
   * @return string
   */
  function get_host() { return $this->_base_host; }

  /**
   * Return the base path for this stylesheet
   *
   * @return string
   */
  function get_base_path() { return $this->_base_path; }
  
  /**
   * Return the page style
   *
   * @return Style
   */
  function get_page_style() { return $this->_page_style; }

  /**
   * Add a new Style object to the stylesheet
   *
   * add_style() adds a new Style object to the current stylesheet, or
   * merges a new Style with an existing one.
   *
   * @param string $key   the Style's selector
   * @param Style $style  the Style to be added
   */
  function add_style($key, Style $style) {
    if (!is_string($key))
      throw new DOMPDF_Exception("CSS rule must be keyed by a string.");

    if ( isset($this->_styles[$key]) )
      $this->_styles[$key]->merge($style);
    else
      $this->_styles[$key] = clone $style;
  }

  /**
   * lookup a specifc Style object
   *
   * lookup() returns the Style specified by $key, or null if the Style is
   * not found.
   *
   * @param string $key   the selector of the requested Style
   * @return Style
   */
  function lookup($key) {
    if ( !isset($this->_styles[$key]) )
      return null;

    return $this->_styles[$key];
  }

  /**
   * create a new Style object associated with this stylesheet
   *
   * @param Style $parent The style of this style's parent in the DOM tree
   * @return Style
   */
  function create_style(Style $parent = null) {
    return new Style($this, $parent);
  }

  /**
   * load and parse a CSS string
   *
   * @param string $css
   */
  function load_css(&$css) { $this->_parse_css($css); }


  /**
   * load and parse a CSS file
   *
   * @param string $file
   */
  function load_css_file($file) {
    global $_dompdf_warnings;

    // Prevent circular references
    if ( isset($this->_loaded_files[$file]) )
      return;

    $this->_loaded_files[$file] = true;
    $parsed_url = explode_url($file);

    list($this->_protocol, $this->_base_host, $this->_base_path, $filename) = $parsed_url;

    if ( !DOMPDF_ENABLE_REMOTE &&
         ($this->_protocol != "" && $this->_protocol !== "file://") ) {
      record_warnings(E_USER_WARNING, "Remote CSS file '$file' requested, but DOMPDF_ENABLE_REMOTE is false.", __FILE__, __LINE__);
      return;
    }

    // Fix submitted by Nick Oostveen for aliased directory support:
    if ( $this->_protocol == "" )
      $file = $this->_base_path . $filename;
    else
      $file = build_url($this->_protocol, $this->_base_host, $this->_base_path, $filename);

    set_error_handler("record_warnings");
    $css = file_get_contents($file);
    restore_error_handler();

    if ( $css == "" ) {
      record_warnings(E_USER_WARNING, "Unable to load css file $file", __FILE__, __LINE__);;
      return;
    }

    $this->_parse_css($css);
  }

  /**
   * @link http://www.w3.org/TR/CSS21/cascade.html#specificity}
   *
   * @param string $selector
   * @return int
   */
  private function _specificity($selector) {
    // http://www.w3.org/TR/CSS21/cascade.html#specificity
    // ignoring the ":" pseudoclass modifyers
    // also ignored in _css_selector_to_xpath

    $a = ($selector === "!style attribute") ? 1 : 0;

    $b = min(mb_substr_count($selector, "#"), 255);

    $c = min(mb_substr_count($selector, ".") +
             mb_substr_count($selector, "["), 255);

    $d = min(mb_substr_count($selector, " ") + 
             mb_substr_count($selector, ">") +
             mb_substr_count($selector, "+"), 255);

    //If a normal element name is at the begining of the string,
    //a leading whitespace might have been removed on whitespace collapsing and removal
    //therefore there might be one whitespace less as selected element names
    //this can lead to a too small specificity
    //see _css_selector_to_xpath

    if ( !in_array($selector[0], array(" ", ">", ".", "#", "+", ":", "[")) ) {
    	$d++;
    }

    if (DEBUGCSS) {
      /*DEBUGCSS*/	print "<pre>\n";
      /*DEBUGCSS*/	printf("_specificity(): 0x%08x \"%s\"\n", ($a << 24) | ($b << 16) | ($c << 8) | ($d), $selector);
      /*DEBUGCSS*/	print "</pre>";
    }

    return ($a << 24) | ($b << 16) | ($c << 8) | ($d);
  }

  /**
   * converts a CSS selector to an XPath query.
   *
   * @param string $selector
   * @return string
   */
  private function _css_selector_to_xpath($selector, $first_pass = false) {

    // Collapse white space and strip whitespace around delimiters
//     $search = array("/\\s+/", "/\\s+([.>#+:])\\s+/");
//     $replace = array(" ", "\\1");
//     $selector = preg_replace($search, $replace, trim($selector));

    // Initial query (non-absolute)
    $query = "//";
    
    // Will contain :before and :after if they must be created
    $pseudo_elements = array();

    // Parse the selector
    //$s = preg_split("/([ :>.#+])/", $selector, -1, PREG_SPLIT_DELIM_CAPTURE);

    $delimiters = array(" ", ">", ".", "#", "+", ":", "[");

    // Add an implicit * at the beginning of the selector 
    // if it begins with an attribute selector
    if ( $selector[0] === "[" )
      $selector = "*$selector";
      
    // Add an implicit space at the beginning of the selector if there is no
    // delimiter there already.
    if ( !in_array($selector[0], $delimiters) )
      $selector = " $selector";

    $tok = "";
    $len = mb_strlen($selector);
    $i = 0;

    while ( $i < $len ) {

      $s = $selector[$i];
      $i++;

      // Eat characters up to the next delimiter
      $tok = "";
      $in_attr = false;
      
      while ($i < $len) {
        $c = $selector[$i];
        $c_prev = $selector[$i-1];
        
        if ( !$in_attr && in_array($c, $delimiters) )
          break;
          
        if ( $c_prev === "[" ) {
          $in_attr = true;
        }
        
        $tok .= $selector[$i++];
      }

      switch ($s) {

      case " ":
      case ">":
        // All elements matching the next token that are direct children of
        // the current token
        $expr = $s === " " ? "descendant" : "child";

        if ( mb_substr($query, -1, 1) !== "/" )
          $query .= "/";

        if ( !$tok )
          $tok = "*";

        $query .= "$expr::$tok";
        $tok = "";
        break;

      case ".":
      case "#":
        // All elements matching the current token with a class/id equal to
        // the _next_ token.

        $attr = $s === "." ? "class" : "id";

        // empty class/id == *
        if ( mb_substr($query, -1, 1) === "/" )
          $query .= "*";

        // Match multiple classes: $tok contains the current selected
        // class.  Search for class attributes with class="$tok",
        // class=".* $tok .*" and class=".* $tok"

        // This doesn't work because libxml only supports XPath 1.0...
        //$query .= "[matches(@$attr,\"^${tok}\$|^${tok}[ ]+|[ ]+${tok}\$|[ ]+${tok}[ ]+\")]";

        // Query improvement by Michael Sheakoski <michael@mjsdigital.com>:
        $query .= "[contains(concat(' ', @$attr, ' '), concat(' ', '$tok', ' '))]";
        $tok = "";
        break;

      case "+":
        // All sibling elements that folow the current token
        if ( mb_substr($query, -1, 1) !== "/" )
          $query .= "/";

        $query .= "following-sibling::$tok";
        $tok = "";
        break;

      case ":":
        // Pseudo-classes
        switch ($tok) {

        case "first-child":
          $query .= "[1]";
          $tok = "";
          break;

        case "last-child":
          $query .= "[not(following-sibling::*)]";
          $tok = "";
          break;

        case "link":
          $query .= "[@href]";
          $tok = "";
          break;
          
        case "first-line": // TODO
        case "first-letter": // TODO
        
        // N/A
        case "active":
        case "visited":
          $query .= "[@dummy]";
          $tok = "";
          break;

        /* Pseudo-elements */
        case "before":
        case "after":
          if ( $first_pass )
            $pseudo_elements[$tok] = $tok;
          else
            $query .= "/*[@$tok]";
            
          $tok = "";
          break;

        case "empty":
          $query .= "[not(*) and not(normalize-space())]";
          $tok = "";
          break;
          
        case "disabled":
        case "checked":
          $query .= "[@$tok]";
          $tok = "";
          break;
          
        case "enabled":
          $query .= "[not(@disabled)]";
          $tok = "";
          break;
        }

        break;

      case "[":
        // Attribute selectors.  All with an attribute matching the following token(s)
        $attr_delimiters = array("=", "]", "~", "|", "$", "^", "*");
        $tok_len = mb_strlen($tok);
        $j = 0;

        $attr = "";
        $op = "";
        $value = "";

        while ( $j < $tok_len ) {
          if ( in_array($tok[$j], $attr_delimiters) )
            break;
          $attr .= $tok[$j++];
        }

        switch ( $tok[$j] ) {

        case "~":
        case "|":
        case "$":
        case "^":
        case "*":
          $op .= $tok[$j++];

          if ( $tok[$j] !== "=" )
            throw new DOMPDF_Exception("Invalid CSS selector syntax: invalid attribute selector: $selector");

          $op .= $tok[$j];
          break;

        case "=":
          $op = "=";
          break;

        }

        // Read the attribute value, if required
        if ( $op != "" ) {
          $j++;
          while ( $j < $tok_len ) {
            if ( $tok[$j] === "]" )
              break;
            $value .= $tok[$j++];
          }
        }

        if ( $attr == "" )
          throw new DOMPDF_Exception("Invalid CSS selector syntax: missing attribute name");

        $value = trim($value, "\"'");
        
        switch ( $op ) {

        case "":
          $query .=  "[@$attr]";
          break;

        case "=":
          $query .= "[@$attr=\"$value\"]";
          break;

        case "~=":
          // FIXME: this will break if $value contains quoted strings
          // (e.g. [type~="a b c" "d e f"])
          $values = explode(" ", $value);
          $query .=  "[";

          foreach ( $values as $val )
            $query .= "@$attr=\"$val\" or ";

          $query = rtrim($query, " or ") . "]";
          break;

        case "|=":
          $values = explode("-", $value);
          $query .= "[";

          foreach ( $values as $val )
            $query .= "starts-with(@$attr, \"$val\") or ";

          $query = rtrim($query, " or ") . "]";
          break;

        case "$=":
          $query .= "[substring(@$attr, string-length(@$attr)-".(strlen($value) - 1).")=\"$value\"]";
          break;
          
        case "^=":
          $query .= "[starts-with(@$attr,\"$value\")]";
          break;
          
        case "*=":
          $query .= "[contains(@$attr,\"$value\")]";
          break;
        }

        break;
      }
    }
    $i++;

//       case ":":
//         // Pseudo selectors: ignore for now.  Partially handled directly
//         // below.

//         // Skip until the next special character, leaving the token as-is
//         while ( $i < $len ) {
//           if ( in_array($selector[$i], $delimiters) )
//             break;
//           $i++;
//         }
//         break;

//       default:
//         // Add the character to the token
//         $tok .= $selector[$i++];
//         break;
//       }

//    }


    // Trim the trailing '/' from the query
    if ( mb_strlen($query) > 2 )
      $query = rtrim($query, "/");

    return array("query" => $query, "pseudo_elements" => $pseudo_elements);
  }

  /**
   * applies all current styles to a particular document tree
   *
   * apply_styles() applies all currently loaded styles to the provided
   * {@link Frame_Tree}.  Aside from parsing CSS, this is the main purpose
   * of this class.
   *
   * @param Frame_Tree $tree
   */
  function apply_styles(Frame_Tree $tree) {

    // Use XPath to select nodes.  This would be easier if we could attach
    // Frame objects directly to DOMNodes using the setUserData() method, but
    // we can't do that just yet.  Instead, we set a _node attribute_ in
    // Frame->set_id() and use that as a handle on the Frame object via
    // Frame_Tree::$_registry.

    // We create a scratch array of styles indexed by frame id.  Once all
    // styles have been assigned, we order the cached styles by specificity
    // and create a final style object to assign to the frame.

    // FIXME: this is not particularly robust...

    $styles = array();
    $xp = new DOMXPath($tree->get_dom());
    
    // Add generated content
    foreach ($this->_styles as $selector => $style) {
      if (strpos($selector, ":before") === false && 
          strpos($selector, ":after") === false) continue;
      
      $query = $this->_css_selector_to_xpath($selector, true);
      
      // Retrieve the nodes
      $nodes = @$xp->query($query["query"]);
      if ($nodes == null) {
        record_warnings(E_USER_WARNING, "The CSS selector '$selector' is not valid", __FILE__, __LINE__);
        continue;
      }
      
      foreach ($nodes as $i => $node) {
        foreach ($query["pseudo_elements"] as $pos) {
          if (($src = $this->_image($style->content)) !== "none") {
            $new_node = $node->ownerDocument->createElement("img_generated");
            $new_node->setAttribute("src", $src);
          }
          else {
            $new_node = $node->ownerDocument->createElement("dompdf_generated");
          }
          $new_node->setAttribute($pos, $pos);
          
          $tree->insert_node($node, $new_node, $pos);
        }
      }
    }
    
    // Apply all styles in stylesheet
    foreach ($this->_styles as $selector => $style) {
      $query = $this->_css_selector_to_xpath($selector);

      // Retrieve the nodes
      $nodes = @$xp->query($query["query"]);
      if ($nodes == null) {
        record_warnings(E_USER_WARNING, "The CSS selector '$selector' is not valid", __FILE__, __LINE__);
        continue;
      }

      foreach ($nodes as $node) {
        // Retrieve the node id
        if ( $node->nodeType != XML_ELEMENT_NODE ) // Only DOMElements get styles
          continue;

        $id = $node->getAttribute("frame_id");

        // Assign the current style to the scratch array
        $spec = $this->_specificity($selector);
        $styles[$id][$spec][] = $style;
      }
    }

    // Now create the styles and assign them to the appropriate frames.  (We
    // iterate over the tree using an implicit Frame_Tree iterator.)
    $root_flg = false;
    foreach ($tree->get_frames() as $frame) {
      // pre_r($frame->get_node()->nodeName . ":");
      if ( !$root_flg && $this->_page_style ) {
        $style = $this->_page_style;
        $root_flg = true;
      } else
        $style = $this->create_style();

      // Find nearest DOMElement parent
      $p = $frame;
      while ( $p = $p->get_parent() )
        if ($p->get_node()->nodeType == XML_ELEMENT_NODE )
          break;

      // Styles can only be applied directly to DOMElements; anonymous
      // frames inherit from their parent
      if ( $frame->get_node()->nodeType != XML_ELEMENT_NODE ) {
        if ( $p )
          $style->inherit($p->get_style());
        $frame->set_style($style);
        continue;
      }

      $id = $frame->get_id();

      // Handle HTML 4.0 attributes
      Attribute_Translator::translate_attributes($frame);

      // Locate any additional style attributes
      if ( ($str = $frame->get_node()->getAttribute("style")) !== "" ) {
        // Destroy CSS comments
        $str = preg_replace("'/\*.*?\*/'si", "", $str);
        
        $spec = $this->_specificity("!style attribute");
        $styles[$id][$spec][] = $this->_parse_properties($str);
      }

      // Grab the applicable styles
      if ( isset($styles[$id]) ) {

        $applied_styles = $styles[ $frame->get_id() ];

        // Sort by specificity
        ksort($applied_styles);

        if (DEBUGCSS) {
          $debug_nodename = $frame->get_node()->nodeName;
          print "<pre>\n[$debug_nodename\n";
          foreach ($applied_styles as $spec => $arr) {
            printf("specificity: 0x%08x\n",$spec);
            foreach ($arr as $s) {
              print "[\n";
              $s->debug_print();
              print "]\n";
            }
          }
        }
        
        // Merge the new styles with the inherited styles
        foreach ($applied_styles as $arr) {
          foreach ($arr as $s)
            $style->merge($s);
        }
      }

      // Inherit parent's styles if required
      if ( $p ) {

        if (DEBUGCSS) {
          print "inherit:\n";
          print "[\n";
          $p->get_style()->debug_print();
          print "]\n";
        }

        $style->inherit( $p->get_style() );
      }

      if (DEBUGCSS) {
        print "DomElementStyle:\n";
        print "[\n";
        $style->debug_print();
        print "]\n";
        print "/$debug_nodename]\n</pre>";
      }

      /*DEBUGCSS print: see below different print debugging method
      pre_r($frame->get_node()->nodeName . ":");
      echo "<pre>";
      echo $style;
      echo "</pre>";*/
      $frame->set_style($style);

    }

    // We're done!  Clean out the registry of all styles since we
    // won't be needing this later.
    foreach ( array_keys($this->_styles) as $key ) {
      $this->_styles[$key] = null;
      unset($this->_styles[$key]);
    }

  }


  /**
   * parse a CSS string using a regex parser
   *
   * Called by {@link Stylesheet::parse_css()}
   *
   * @param string $str
   */
  private function _parse_css($str) {

    $str = trim($str);
    
    // Destroy comments and remove HTML comments
    $css = preg_replace(array(
      "'/\*.*?\*/'si", 
      "/^<!--/",
      "/-->$/"
    ), "", $str);

    // FIXME: handle '{' within strings, e.g. [attr="string {}"]

    // Something more legible:
    $re =
      "/\s*                                   # Skip leading whitespace                             \n".
      "( @([^\s]+)\s+([^{;]*) (?:;|({)) )?    # Match @rules followed by ';' or '{'                 \n".
      "(?(1)                                  # Only parse sub-sections if we're in an @rule...     \n".
      "  (?(4)                                # ...and if there was a leading '{'                   \n".
      "    \s*( (?:(?>[^{}]+) ({)?            # Parse rulesets and individual @page rules           \n".
      "            (?(6) (?>[^}]*) }) \s*)+?  \n".
      "       )                               \n".
      "   })                                  # Balancing '}'                                \n".
      "|                                      # Branch to match regular rules (not preceeded by '@')\n".
      "([^{]*{[^}]*}))                        # Parse normal rulesets\n".
      "/xs";

    if ( preg_match_all($re, $css, $matches, PREG_SET_ORDER) === false )
      // An error occured
      throw new DOMPDF_Exception("Error parsing css file: preg_match_all() failed.");

    // After matching, the array indicies are set as follows:
    //
    // [0] => complete text of match
    // [1] => contains '@import ...;' or '@media {' if applicable
    // [2] => text following @ for cases where [1] is set
    // [3] => media types or full text following '@import ...;'
    // [4] => '{', if present
    // [5] => rulesets within media rules
    // [6] => '{', within media rules
    // [7] => individual rules, outside of media rules
    //
    //pre_r($matches);
    foreach ( $matches as $match ) {
      $match[2] = trim($match[2]);

      if ( $match[2] !== "" ) {
        // Handle @rules
        switch ($match[2]) {

        case "import":
          $this->_parse_import($match[3]);
          break;

        case "media":
          $acceptedmedia = self::$ACCEPTED_GENERIC_MEDIA_TYPES;
          if ( defined("DOMPDF_DEFAULT_MEDIA_TYPE") ) {
            $acceptedmedia[] = DOMPDF_DEFAULT_MEDIA_TYPE;
          } else {
            $acceptedmedia[] = self::$ACCEPTED_DEFAULT_MEDIA_TYPE;
          }
          if ( in_array(mb_strtolower(trim($match[3])), $acceptedmedia ) ) {
            $this->_parse_sections($match[5]);
          }
          break;

        case "page":
          //This handles @page to be applied to page oriented media
          //Note: This has a reduced syntax:
          //@page { margin:1cm; color:blue; }
          //Not a sequence of styles like a full.css, but only the properties
          //of a single style, which is applied to the very first "root" frame before
          //processing other styles of the frame.
          //Working properties:
          // margin (for margin around edge of paper)
          // font-family (default font of pages)
          // color (default text color of pages)
          //Non working properties:
          // border
          // padding
          // background-color
          //Todo:Reason is unknown
          //Other properties (like further font or border attributes) not tested.
          //If a border or background color around each paper sheet is desired,
          //assign it to the <body> tag, possibly only for the css of the correct media type.

          // If the page has a name, skip the style.
          if ($match[3] !== "")
            return;

          // Store the style for later...
          if ( is_null($this->_page_style) )
            $this->_page_style = $this->_parse_properties($match[5]);
          else
            $this->_page_style->merge($this->_parse_properties($match[5]));
          break;

        case "font-face":
          $this->_parse_font_face($match[5]);
          break;
          
        default:
          // ignore everything else
          break;
        }

        continue;
      }

      if ( $match[7] !== "" )
        $this->_parse_sections($match[7]);

    }
  }

  /* See also style.cls Style::_image(), refactoring?, works also for imported css files */
  protected function _image($val) {
    $DEBUGCSS=DEBUGCSS;
    
    if ( mb_strpos($val, "url") === false ) {
      $path = "none"; //Don't resolve no image -> otherwise would prefix path and no longer recognize as none
    }
    else {
      $val = preg_replace("/url\(['\"]?([^'\")]+)['\"]?\)/","\\1", trim($val));

      // Resolve the url now in the context of the current stylesheet
      $parsed_url = explode_url($val);
      if ( $parsed_url["protocol"] == "" && $this->get_protocol() == "" ) {
        if ($parsed_url["path"][0] === '/' || $parsed_url["path"][0] === '\\' ) {
          $path = $_SERVER["DOCUMENT_ROOT"].'/';
        } else {
          $path = $this->get_base_path();
        }
        $path .= $parsed_url["path"] . $parsed_url["file"];
        $path = realpath($path);
        // If realpath returns FALSE then specifically state that there is no background image
        // FIXME: Is this causing problems for imported CSS files? There are some './none' references when running the test cases.
        if (!$path) { $path = 'none'; }
      } else {
        $path = build_url($this->get_protocol(),
                          $this->get_host(),
                          $this->get_base_path(),
                          $val);
      }
    }
    if ($DEBUGCSS) {
      print "<pre>[_image\n";
      print_r($parsed_url);
      print $this->get_protocol()."\n".$this->get_base_path()."\n".$path."\n";
      print "_image]</pre>";;
    }
    return $path;
  }

  /**
   * parse @import{} sections
   *
   * @param string $url  the url of the imported CSS file
   */
  private function _parse_import($url) {
    $arr = preg_split("/[\s\n,]/", $url,-1, PREG_SPLIT_NO_EMPTY);
    $url = array_shift($arr);
    $accept = false;

    if ( count($arr) > 0 ) {

      $acceptedmedia = self::$ACCEPTED_GENERIC_MEDIA_TYPES;
      if ( defined("DOMPDF_DEFAULT_MEDIA_TYPE") ) {
        $acceptedmedia[] = DOMPDF_DEFAULT_MEDIA_TYPE;
      } else {
        $acceptedmedia[] = self::$ACCEPTED_DEFAULT_MEDIA_TYPE;
      }
              
      // @import url media_type [media_type...]
      foreach ( $arr as $type ) {
        if ( in_array(mb_strtolower(trim($type)), $acceptedmedia) ) {
          $accept = true;
          break;
        }
      }

    } else {
      // unconditional import
      $accept = true;
    }

    if ( $accept ) {
      // Store our current base url properties in case the new url is elsewhere
      $protocol = $this->_protocol;
      $host = $this->_base_host;
      $path = $this->_base_path;
      
      // $url = str_replace(array('"',"url", "(", ")"), "", $url);
      // If the protocol is php, assume that we will import using file://
      // $url = build_url($protocol == "php://" ? "file://" : $protocol, $host, $path, $url);
      // Above does not work for subfolders and absolute urls.
      // Todo: As above, do we need to replace php or file to an empty protocol for local files?
      
      $url = $this->_image($url);
      
      $this->load_css_file($url);

      // Restore the current base url
      $this->_protocol = $protocol;
      $this->_base_host = $host;
      $this->_base_path = $path;
    }

  }
  
  /**
   * parse @font-face{} sections
   * http://www.w3.org/TR/css3-fonts/#the-font-face-rule
   * 
   * @param string $str CSS @font-face rules
   * @return Style
   */
  private function _parse_font_face($str) {
    $descriptors = $this->_parse_properties($str);
    
    preg_match_all("/(url|local)\s*\([\"\']?([^\"\'\)]+)[\"\']?\)\s*(format\s*\([\"\']?([^\"\'\)]+)[\"\']?\))?/i", $descriptors->src, $src);
    
    $sources = array();
    foreach($src[0] as $i => $value) {
      $sources[] = array(
        "local"  => strtolower($src[1][$i]) === "local",
        "uri"    => $src[2][$i],
        "format" => $src[4][$i],
      );
    }
    
    //@todo download font file, ttf2afm, etc
  }

  /**
   * parse regular CSS blocks
   *
   * _parse_properties() creates a new Style object based on the provided
   * CSS rules.
   *
   * @param string $str  CSS rules
   * @return Style
   */
  private function _parse_properties($str) {
    $properties = preg_split("/;(?=(?:[^\(]*\([^\)]*\))*(?![^\)]*\)))/", $str);

    if (DEBUGCSS) print '[_parse_properties';

    // Create the style
    $style = new Style($this);
    foreach ($properties as $prop) {
      // If the $prop contains an url, the regex may be wrong
      // @todo: fix the regex so that it works everytime
      /*if (strpos($prop, "url(") === false) {
        if (preg_match("/([a-z-]+)\s*:\s*[^:]+$/i", $prop, $m))
          $prop = $m[0];
      }*/
      //A css property can have " ! important" appended (whitespace optional)
      //strip this off to decode core of the property correctly.
      //Pass on in the style to allow proper handling:
      //!important properties can only be overridden by other !important ones.
      //$style->$prop_name = is a shortcut of $style->__set($prop_name,$value);.
      //If no specific set function available, set _props["prop_name"]
      //style is always copied completely, or $_props handled separately
      //Therefore set a _important_props["prop_name"]=true to indicate the modifier

      /* Instead of short code, prefer the typical case with fast code
	  $important = preg_match("/(.*?)!\s*important/",$prop,$match);
      if ( $important ) {
      	$prop = $match[1];
      }
      $prop = trim($prop);
      */
      if (DEBUGCSS) print '(';
 	  $important = false;
      $prop = trim($prop);
      if (substr($prop,-9) === 'important') {
      	$prop_tmp = rtrim(substr($prop,0,-9));
      	if (substr($prop_tmp,-1) === '!') {
      		$prop = rtrim(substr($prop_tmp,0,-1));
      		$important = true;
      	}
      }

      if ($prop == "") {
        if (DEBUGCSS) print 'empty)';
        continue;
      }

      $i = mb_strpos($prop, ":");
      if ( $i === false ) {
        if (DEBUGCSS) print 'novalue'.$prop.')';
        continue;
      }

      $prop_name = rtrim(mb_strtolower(mb_substr($prop, 0, $i)));
      $value = ltrim(mb_substr($prop, $i+1));
      if (DEBUGCSS) print $prop_name.':='.$value.($important?'!IMPORTANT':'').')';
      //New style, anyway empty
      //if ($important || !$style->important_get($prop_name) ) {
      //$style->$prop_name = array($value,$important);
      //assignment might be replaced by overloading through __set,
      //and overloaded functions might check _important_props,
      //therefore set _important_props first.
      if ($important) {
        $style->important_set($prop_name);
      }
      //For easier debugging, don't use overloading of assignments with __set
      $style->$prop_name = $value;
      //$style->props_set($prop_name, $value);
    }
    if (DEBUGCSS) print '_parse_properties]';

    return $style;
  }

  /**
   * parse selector + rulesets
   *
   * @param string $str  CSS selectors and rulesets
   */
  private function _parse_sections($str) {
    // Pre-process: collapse all whitespace and strip whitespace around '>',
    // '.', ':', '+', '#'

    $patterns = array("/[\\s\n]+/", "/\\s+([>.:+#])\\s+/");
    $replacements = array(" ", "\\1");
    $str = preg_replace($patterns, $replacements, $str);

    $sections = explode("}", $str);
    if (DEBUGCSS) print '[_parse_sections';
    foreach ($sections as $sect) {
      $i = mb_strpos($sect, "{");

      $selectors = explode(",", mb_substr($sect, 0, $i));
      if (DEBUGCSS) print '[section';
      $style = $this->_parse_properties(trim(mb_substr($sect, $i+1)));
      
      // Assign it to the selected elements
      foreach ($selectors as $selector) {
        $selector = trim($selector);

        if ($selector == "") {
          if (DEBUGCSS) print '#empty#';
          continue;
        }
        if (DEBUGCSS) print '#'.$selector.'#';
        //if (DEBUGCSS) { if (strpos($selector,'p') !== false) print '!!!p!!!#'; }

        $this->add_style($selector, $style);
      }
      if (DEBUGCSS) print 'section]';
    }
    if (DEBUGCSS) print '_parse_sections]';
  }

  /**
   * dumps the entire stylesheet as a string
   *
   * Generates a string of each selector and associated style in the
   * Stylesheet.  Useful for debugging.
   *
   * @return string
   */
  function __toString() {
    $str = "";
    foreach ($this->_styles as $selector => $style)
      $str .= "$selector => " . $style->__toString() . "\n";

    return $str;
  }
}
