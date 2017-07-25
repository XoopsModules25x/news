<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team, Kazumi Ono (AKA onokazu)
 */

/**
 * A tree structures with {@link XoopsObject}s as nodes
 *
 * @package              kernel
 * @subpackage           core
 *
 * @author               Kazumi Ono    <onokazu@xoops.org>
 * @copyright        (c) XOOPS Project (https://xoops.org)
 */
class MyXoopsObjectTree
{
    /**#@+
     * @access    private
     */
    public $_parentId;
    public $_myId;
    public $_rootId = null;
    public $tree    = array();
    public $_objects;
    /**#@-*/

    /**
     * Constructor
     *
     * @param array  $objectArr Array of {@link XoopsObject}s
     * @param string $myId      field name of object ID
     * @param string $parentId  field name of parent object ID
     * @param string $rootId    field name of root object ID
     **/
    public function __construct(&$objectArr, $myId, $parentId, $rootId = null)
    {
        //$this->db = xoopsDatabaseFactory::getDatabaseConnection();
        $this->_objects  =& $objectArr;
        $this->_myId     = $myId;
        $this->_parentId = $parentId;
        if (isset($rootId)) {
            $this->_rootId = $rootId;
        }
        $this->_initialize();
    }

    /**
     * Initialize the object
     *
     * @access    private
     **/
    public function _initialize()
    {
        foreach (array_keys($this->_objects) as $i) {
            $key1                         = $this->_objects[$i]->getVar($this->_myId);
            $this->tree[$key1]['obj']     = $this->_objects[$i];
            $key2                         = $this->_objects[$i]->getVar($this->_parentId);
            $this->tree[$key1]['parent']  = $key2;
            $this->tree[$key2]['child'][] = $key1;
            if (isset($this->_rootId)) {
                $this->tree[$key1]['root'] = $this->_objects[$i]->getVar($this->_rootId);
            }
        }
    }

    /**
     * Get the tree
     *
     * @return array Associative array comprising the tree
     **/
    public function &getTree()
    {
        return $this->tree;
    }

    /**
     * returns an object from the tree specified by its id
     *
     * @param string $key ID of the object to retrieve
     *
     * @return object Object within the tree
     **/
    public function &getByKey($key)
    {
        return $this->tree[$key]['obj'];
    }

    /**
     * returns an array of all the first child object of an object specified by its id
     *
     * @param string $key ID of the parent object
     *
     * @return array Array of children of the parent
     **/
    public function getFirstChild($key)
    {
        $ret = array();
        if (isset($this->tree[$key]['child'])) {
            foreach ($this->tree[$key]['child'] as $childkey) {
                $ret[$childkey] = $this->tree[$childkey]['obj'];
            }
        }

        return $ret;
    }

    /**
     * returns an array of all child objects of an object specified by its id
     *
     * @param string $key ID of the parent
     * @param array  $ret (Empty when called from client) Array of children from previous recursions.
     *
     * @return array Array of child nodes.
     **/
    public function getAllChild($key, $ret = array())
    {
        if (isset($this->tree[$key]['child'])) {
            foreach ($this->tree[$key]['child'] as $childkey) {
                $ret[$childkey] = $this->tree[$childkey]['obj'];
                $children       = $this->getAllChild($childkey, $ret);
                foreach (array_keys($children) as $newkey) {
                    $ret[$newkey] = $children[$newkey];
                }
            }
        }

        return $ret;
    }

    /**
     * returns an array of all parent objects.
     * the key of returned array represents how many levels up from the specified object
     *
     * @param string $key     ID of the child object
     * @param array  $ret     (empty when called from outside) Result from previous recursions
     * @param int    $uplevel (empty when called from outside) level of recursion
     *
     * @return array Array of parent nodes.
     **/
    public function getAllParent($key, $ret = array(), $uplevel = 1)
    {
        if (isset($this->tree[$key]['parent']) && isset($this->tree[$this->tree[$key]['parent']]['obj'])) {
            $ret[$uplevel] = $this->tree[$this->tree[$key]['parent']]['obj'];
            $parents       = $this->getAllParent($this->tree[$key]['parent'], $ret, $uplevel + 1);
            foreach (array_keys($parents) as $newkey) {
                $ret[$newkey] = $parents[$newkey];
            }
        }

        return $ret;
    }

    /**
     * Make options for a select box from
     *
     * @param string $fieldName   Name of the member variable from the
     *                            node objects that should be used as the title for the options.
     * @param string $selected    Value to display as selected
     * @param int    $key         ID of the object to display as the root of select options
     * @param string $ret         (reference to a string when called from outside) Result from previous recursions
     * @param string $prefix_orig String to indent items at deeper levels
     * @param string $prefix_curr String to indent the current item
     *
     * @return void
    @access private
     */
    public function _makeSelBoxOptions($fieldName, $selected, $key, &$ret, $prefix_orig, $prefix_curr = '')
    {
        if ($key > 0) {
            $value = $this->tree[$key]['obj']->getVar($this->_myId);
            $ret   .= '<option value=\'' . $value . '\'';
            if ($value == $selected) {
                $ret .= ' selected';
            }
            $ret         .= '>' . $prefix_curr . $this->tree[$key]['obj']->getVar($fieldName) . '</option>';
            $prefix_curr .= $prefix_orig;
        }
        if (isset($this->tree[$key]['child']) && !empty($this->tree[$key]['child'])) {
            foreach ($this->tree[$key]['child'] as $childkey) {
                $this->_makeSelBoxOptions($fieldName, $selected, $childkey, $ret, $prefix_orig, $prefix_curr);
            }
        }
    }

    /**
     * Make a select box with options from the tree
     *
     * @param string  $name           Name of the select box
     * @param string  $fieldName      Name of the member variable from the node objects that should be used as the title for the options.
     * @param string  $prefix         String to indent deeper levels
     * @param string  $selected       Value to display as selected
     * @param bool    $addEmptyOption Set TRUE to add an empty option with value "0" at the top of the hierarchy
     * @param integer $key            ID of the object to display as the root of select options
     * @param string  $additional
     *
     * @return string HTML select box
     */
    public function makeSelBox(
        $name,
        $fieldName,
        $prefix = '-',
        $selected = '',
        $addEmptyOption = false,
        $key = 0,
        $additional = ''
    ) {
        $ret = "<select id='" . $name . "' name='" . $name . "'";
        if ($additional !== '') {
            $ret .= $additional;
        }
        $ret .= '>';
        if (false != $addEmptyOption) {
            $ret .= '<option value=\'0\'>----</option>';
        }
        $this->_makeSelBoxOptions($fieldName, $selected, $key, $ret, $prefix);

        return $ret . '</select>';
    }

    /**
     * Internal function used by makeTreeAsArray
     * @param        $fieldName
     * @param        $key
     * @param        $ret
     * @param        $prefix_orig
     * @param string $prefix_curr
     */
    public function _recursiveMakeTreeAsArray($fieldName, $key, &$ret, $prefix_orig, $prefix_curr = '')
    {
        if ($key > 0) {
            $value       = $this->tree[$key]['obj']->getVar($this->_myId);
            $ret[$value] = $prefix_curr . $this->tree[$key]['obj']->getVar($fieldName);
            $prefix_curr .= $prefix_orig;
        }
        if (isset($this->tree[$key]['child']) && !empty($this->tree[$key]['child'])) {
            foreach ($this->tree[$key]['child'] as $childkey) {
                $this->_recursiveMakeTreeAsArray($fieldName, $childkey, $ret, $prefix_orig, $prefix_curr);
            }
        }
    }

    /**
     * Identical function as makeSelBox but returns an array
     *
     * @param string  $fieldName Name of the member variable from the node objects that should be used as the title for the options.
     * @param string  $prefix    String to indent deeper levels
     * @param integer $key       ID of the object to display as the root of select options
     * @param null    $empty
     *
     * @return array key = object ID, value = $fieldName
     */
    public function makeTreeAsArray($fieldName, $prefix = '-', $key = 0, $empty = null)
    {
        $ret = array();
        if ($empty != null) {
            $ret[0] = $empty;
        }
        $this->_recursiveMakeTreeAsArray($fieldName, $key, $ret, $prefix);

        return $ret;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $sel_id
     * @param unknown_type $order
     * @param unknown_type $parray
     * @param unknown_type $r_prefix
     *
     * @return mixed
     */
    //      function getChildTreeArray($sel_id = 0, $order = "", $parray = array(), $r_prefix = "")
    //      {
    //          $sel_id = (int)($sel_id);
    //          $sql = "SELECT * FROM " . $this->table . " WHERE " . $this->pid . "=" . $sel_id . "";
    //          if ($order != "") {
    //              $sql .= " ORDER BY $order";
    //          }
    //          $result = $this->db->query($sql);
    //          $count = $this->db->getRowsNum($result);
    //          if ($count == 0) {
    //              return $parray;
    //          }
    //          while ($row = $this->db->fetchArray($result)) {
    //              $row['prefix'] = $r_prefix . ".";
    //              array_push($parray, $row);
    //              $parray = $this->getChildTreeArray($row[$this->id], $order, $parray, $row['prefix']);
    //          }
    //          return $parray;
    //      }
}
