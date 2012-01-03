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
 * This class specifies the underlying functions for a collection.
 *
 * @package Collection
 * @category Collection
 * @version 2012-01-03
 *
 * @abstract
 */
abstract class Base_Collection extends Kohana_Object implements Countable, Iterator {

    /**
     * This variable stores the elements in the collection.
     *
     * @access protected
     * @var array
     */
    protected $elements = array();

    /**
     * This variable stores the pointer position.
     *
     * @access protected
     * @var integer
     */
    protected $pointer = 0;

    /**
     * This variable stores the number of elements in the collection.
     *
     * @access protected
     * @var integer
     */
    protected $count = 0;

    /**
     * This function initializes the class.
     *
     * @access public
     * @abstract
     * @param mixed $variable                   a collection or an array
     * @return Collection                       an instance of this class
     */
    public abstract function __construct($variable = NULL);

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
     * This function will remove all elements from the collection.
     *
     * @access public
     * @return boolean                          whether all elements were removed
     */
    public function clear() {
        $this->elements = array();
        $this->count = 0;
        return TRUE;
    }

    /**
     * This function returns the number of elements in the collection.
     *
     * @access public
     * @return integer                          the number of elements
     */
    public function count() {
        return $this->count;
    }

    /**
     * This function returns the current element that is pointed at by the iterator.
     *
     * @access public
     * @return mixed                            the current element
     */
    public function current() {
        $element = current($this->elements);
        return $element;
    }

    /**
     * This function determines whether the specified variable is equivalent
     * to the collection.
     *
     * @access public
     * @param mixed $variable                   the variable to be evaluated
     * @return boolean                          whether the specified variable is
     *                                          equivalent to the collection
     */
    public function equals($variable) {
        return ( (string) serialize($this) == (string) serialize($variable));
    }

    /**
     * This function determines whether there are any elements in the collection.
     *
     * @access public
     * @return boolean                          whether the collection is empty
     */
    public function is_empty() {
        return ($this->count == 0);
    }

    /**
     * This function returns the current key that is pointed at by the iterator.
     *
     * @access public
     * @return scaler                           the key on success or NULL on failure
     */
    public function key() {
        $key = key($this->elements);
        return $key;
    }

    /**
     * This function will iterate to the next element.
     *
     * @access public
     */
    public function next() {
        next($this->elements);
        $this->pointer++;
    }

    /**
     * This function will retain only those elements not in the specified array.
     *
     * @access public
     * @abstract
     * @param array $array                      an array of elements that are to be retained
     * @return boolean                          whether any elements were retained
     */
    public abstract function retain_array(Array $array);

    /**
     * This function will retain only those elements not in the specified collection.
     *
     * @access public
     * @abstract
     * @param Collection $collection            a collection of elements that are to be retained
     * @return boolean                          whether any elements were retained
     */
    public abstract function retain_collection(Collection $collection);

    /**
     * This function will resets the iterator.
     *
     * @access public
     */
    public function rewind() {
        reset($this->elements);
        $this->pointer = 0;
    }

    /**
     * This function determines whether all elements have been iterated through.
     *
     * @access public
     * @return boolean                          whether iterator is still valid
     */
    public function valid() {
        $result = !is_null($this->key());
        return $result;
    }

    /**
     * This function prints out the collection.
     *
     * @access public
     */
    public function write() {
        var_dump($this->as_array());
    }

}
?>