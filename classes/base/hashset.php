<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Copyright 2011-2012 Spadefoot
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * This class creates hash set using an associated array.
 *
 * @package Collection
 * @category HashSet
 * @version 2012-01-02
 *
 * @abstract
 */
abstract class Base_HashSet extends Collection {

    /**
     * This function initializes the class.
     *
     * @access public
     * @param mixed $variable                   a collection or an array
     * @return HashSet                          an instance of this class
     * @throws Kohana_InvalidArgument_Exception indicates that variable must be an array
     *                                          or a collection
     */
    public function __construct($variable = NULL) {
        if (!is_null($variable)) {
            if (is_object($variable) && ($variable instanceof Collection)) {
                $this->add_collection($variable);
            }
            else if (is_array($variable)) {
                $this->add_array($variable);
            }
            else {
                throw new Kohana_InvalidArgument_Exception('Wrong data type specified.', array(':type', gettype($variable)));
            }
        }
    }

    /**
     * This function will add the elements in the specified array to the collection.
     *
     * @access public
     * @param array $array                      the array to be added
     * @return boolean                          whether any elements were added
     */
    public function add_array(Array $array) {
        $result = FALSE;
        if (!empty($array)) {
            foreach ($array as $element) {
                if ($this->add_element($element)) {
                    $result = TRUE;
                }
            }
        }
        return $result;
    }

    /**
     * This function will add the elements in the specified collection to the collection.
     *
     * @access public
     * @param Collection $collection            the collection to be added
     * @return boolean                          whether any elements were added
     */
    public function add_collection(Collection $collection) {
        $result = $this->add_array($collection->as_array());
        return $result;
    }

    /**
     * This function will add the element specified.
     *
     * @access public
     * @param mixed $element                    the element to be added
     * @return boolean                          whether the element was added
     */
    public function add_element($element) {
        $hash_code = self::hash_code($element);
        if ( ! isset($this->elements[$hash_code])) {
            $this->elements[$hash_code] = $element;
            $this->count++;
        }
        return TRUE;
    }

    /**
     * This function returns the collection as an array.
     *
     * @access public
     * @return array                            an array of the elements
     */
    public function as_array() {
        return $this->elements;
    }

    /**
     * This function determines whether all elements in the specified array are contained
     * within the collection.
     *
     * @access public
     * @param array $array                      the array to be tested
     * @return boolean                          whether all elements are contained within
     *                                          the collection
     */
    public function has_array($array) {
        if (!empty($array)) {
            foreach ($array as $element) {
                if (!$this->has_element($element)) {
                    return FALSE;
                }
            }
            return TRUE;
        }
        return FALSE;
    }

    /**
     * This function determines whether all elements in the specified collection are contained
     * within the collection.
     *
     * @access public
     * @param Collection $collection            the collection to be tested
     * @return boolean                          whether all elements are contained within
     *                                          the collection
     */
    public function has_collection(Collection $collection) {
        $result = $this->has_array($collection->as_array());
        return $result;
    }

    /**
     * This function determines whether the specified element is contained within the
     * collection.
     *
     * @access public
     * @param mixed $element                    the element to be tested
     * @return boolean                          whether the specified element is contained
     *                                          within the collection
     */
    public function has_element($element) {
        $hash_code = self::hash_code($element);
        $result = isset($this->elements[$hash_code]);
        return $result;
    }

    /**
     * This function removes all elements in the collection that pair up with an element in the
     * specified array.
     *
     * @access public
     * @param array $array                      the array of elements to be removed
     * @return boolean                          whether any elements were removed
     */
    public function remove_array(Array $array) {
        $count = $this->count;
        foreach ($array as $element) {
            while (($index = $this->index_of($element)) >= 0) {
                unset($this->elements[$index]);
                $count--;
            }
        }
        if ($count < $this->count) {
            $this->elements = array_values($this->elements);
            $this->count = $count;
            return TRUE;
        }
        return FALSE;
    }

    /**
     * This function removes all elements in the collection that pair up with an element in the
     * specified collection.
     *
     * @access public
     * @param Collection $collection            the collection of elements to be removed
     * @return boolean                          whether any elements were removed
     */
    public function remove_collection(Collection $collection) {
        $result = $this->remove_array($collection->as_array());
        return $result;
    }

    /**
     * This function removes the specified element in the collection if found.
     *
     * @access public
     * @param mixed $element                    the element to be removed
     * @return boolean                          whether the element was removed
     */
    public function remove_element($element) {
        $hash_code = self::hash_code($element);
        if (isset($this->elements[$hash_code])) {
            unset($this->elements[$hash_code]);
            $this->count--;
            return TRUE;
        }
        return FALSE;
    }

    /**
     * This function will retain only those elements not in the specified array.
     *
     * @access public
     * @param array $array                      an array of elements that are to be retained
     * @return boolean                          whether any elements were retained
     */
    public function retain_array(Array $array) {
        foreach ($array as $element) {
            $this->retain_element($element);
        }
        return ($this->count > 0);
    }

    /**
     * This function will retain only those elements not in the specified collection.
     *
     * @access public
     * @param Collection $collection            a collection of elements that are to be retained
     * @return boolean                          whether any elements were retained
     */
    public function retain_collection(Collection $collection) {
        $result = $this->retain_collection($collection->as_array());
        return $result;
    }

    /**
     * This function will retain only those elements contained in the specified collection.
     *
     * @access public
     * @param $mixed $element                   the element that is to be retained
     * @return boolean
     */
    public function retain_element($element) {
        $elements = array();
        $count = 0;
        $hash_code = self::hash_code($element);
        if (isset($this->elements[$hash_code])) {
            $elements[$hash_code] = $this->elements[$hash_code];
            $count++;
        }
        $this->elements - $elements;
        $this->count = $count;
        return ($this->count > 0);
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * This function generates the hash code for the specified element.
     *
     * @access protected
     * @param mixed $element                    the element to be hashed
     * @return string                           the hash code the specified element
     */
    protected static function hash_code($element) {
        $hash_code = (is_object($element)) ? spl_object_hash($element) : md5(serialize($element));
        $hash_code = gettype($element) . ':' . $hash_code;
        return $hash_code;
    }

}
?>