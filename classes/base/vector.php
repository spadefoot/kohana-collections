<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Copyright 2011 Spadefoot
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
 * This class creates a vector using an indexed array.
 *
 * @package Collection
 * @category Vector
 * @version 2011-12-31
 *
 * @abstract
 */
abstract class Base_Vector extends Collection {

    /**
     * This function initializes the class.
     *
     * @access public
     * @param mixed $variable                   a collection or an array
     * @return Vector                           an instance of this class
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
                throw new Kohana_InvalidArgument_Exception('Message: Unable to initialize class. Reason: :type is of the wrong data type.', array(':type' => gettype($variable)));
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
        $this->elements[] = $element;
        $this->count++;
        return TRUE;
    }

    /**
     * This function returns the element at the the specified index.
     *
     * @access public
     * @param integer $index                    the index of the element
     * @return mixed                            the element at the specified index
     * @throws Kohana_InvalidArgument_Exception indicates that index must be an integer
     * @throws Kohana_OutOfBounds_Exception     indicates that no element exists at the
     *                                          specified index
     */
    public function get_element($index) {
        if (is_integer($index)) {
            if (isset($this->elements[$index])) {
                $element = $this->elements[$index];
                return $element;
            }
            throw new Kohana_OutOfBounds_Exception('Message: Unable to get element. Reason: Invalid index specified', array(':index' => $index));
        }
        throw new Kohana_InvalidArgument_Exception('Message: Unable to get element. Reason: :type is of the wrong data type.', array(':type' => gettype($index)));
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
        $result = ($this->index_of($element) >= 0);
        return $result;
    }

    /**
     * This function returns the index of the specified element should it exist within the collection.
     *
     * @access public
     * @param mixed $element                    the element to be located
     * @return integer                          the index of the element if it exists within
     *                                          the collection; otherwise, a value of -1
     */
    public function index_of($element) {
        foreach ($this->elements as $index => $value) {
            if (serialize($value) == serialize($element)) {
                return $index;
            }
        }
        return -1;
    }

    /**
     * This function inserts an element at the specified index.
     *
     * @access public
     * @param integer $index                    the index where the element will be inserted at
     * @param mixed $element                    the element to be inserted
     * @return boolean                          whether the element was inserted
     * @throws Kohana_InvalidArgument_Exception indicates that index must be an integer
     * @throws Kohana_OutOfBounds_Exception     indicates that no element exists at the
     *                                          specified index
     *
     * @see http://www.justin-cook.com/wp/2006/08/02/php-insert-into-an-array-at-a-specific-position/
     */
    public function insert_element($index, $element) {
        if (is_integer($index)) {
            if (($index >= 0) && ($index < $this->count)) {
                array_splice($this->elements, $index, 0, array($element));
                $this->count++;
                return TRUE;
            }
            else if ($index == $this->count) {
                $this->elements[$index] = $element;
                $this->count++;
                return TRUE;
            }
            throw new Kohana_OutOfBounds_Exception('Message: Unable to insert element. Reason: Invalid index specified', array(':index' => $index, ':element' => $element));
        }
        throw new Kohana_InvalidArgument_Exception('Message: Unable to insert element. Reason: :type is of the wrong data type.', array(':type' => gettype($index), ':element' => $element));
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
     * This function removes all elements in the collection that pair up with the specified element.
     *
     * @access public
     * @param mixed $element                    the element to be removed
     * @return boolean                          whether the element was removed
     */
    public function remove_element($element) {
        $count = $this->count;
        while (($index = $this->index_of($element)) >= 0) {
            unset($this->elements[$index]);
            $count--;
        }
        if ($count < $this->count) {
            $this->elements = array_values($this->elements);
            $this->count = $count;
            return TRUE;
        }
        return FALSE;
    }

    /**
     * This function removes the element as the specified index.
     *
     * @access public
     * @param integer $index                    the index of element to be removed
     * @return boolean                          whether the element was removed
     * @throws Kohana_InvalidArgument_Exception indicates that index must be an integer
     * @throws Kohana_OutOfBounds_Exception     indicates that no element exists at the
     *                                          specified index
     */
    public function remove_index($index) {
        if (is_integer($index)) {
            if (isset($this->elements[$index])) {
                unset($this->elements[$index]);
                $this->elements = array_values($this->elements);
                $this->count--;
                return TRUE;
            }
            throw new Kohana_OutOfBounds_Exception('Message: Unable to remove element. Reason: Invalid index specified', array(':index' => $index));
        }
        throw new Kohana_InvalidArgument_Exception('Message: Unable to remove element. Reason: :type is of the wrong data type.', array(':type' => gettype($index)));
    }

    /**
     * This function removes all elements between the specified range.
     *
     * @access public
     * @param integer $beg_index                the beginning index
     * @param integer $end_index                the ending index
     * @return boolean                          whether any elements were removed
     * @throws Kohana_InvalidArgument_Exception indicates that an index must be an integer
     * @throws Kohana_InvalidRange_Exception    indicates that the ending index is less than
     *                                          the beginning index
     * @throws Kohana_OutOfBounds_Exception     indicates that either the beginning index
     *                                          or ending index is beyond the bounds of
     *                                          the array
     */
    public function remove_range($beg_index, $end_index) {
        if (is_integer($beg_index) && is_integer($end_index)) {
            if (isset($this->elements[$beg_index]) && isset($this->elements[$end_index])) {
                if ($beg_index <= $end_index) {
                    for ($i = $beg_index; $i <= $end_index; $i++) {
                        unset($this->elements[$i]);
                        $this->count--;
                    }
                    $this->elements = array_values($this->elements);
                    return TRUE;
                }
                throw new Kohana_InvalidRange_Exception('Message: Unable to remove range. Reason: Invalid range start from :start and ends at :end', array(':start' => $beg_index, ':end' => $end_index));
            }
            throw new Kohana_OutOfBounds_Exception('Message: Unable to remove range. Reason: Invalid index specified', array(':start' => $beg_index, ':end' => $end_index));
        }
        throw new Kohana_InvalidArgument_Exception('Message: Unable to remove range. Reason: Either :start or :end is of the wrong data type.', array(':start' => gettype($beg_index), ':end' => gettype($end_index)));
    }

    /**
     * This function will retain only those elements not in the specified array.
     *
     * @access public
     * @param array $array                      an array of elements that are to be retained
     * @return boolean                          whether any elements were retained
     */
    public function retain_array(Array $array) {
        $elements = array();
        $count = 0;
        foreach ($array as $element) {
            if ($this->has_element($element)) {
                $elements[] = $element;
                $count++;
            }
        }
        $this->elements = $elements;
        $this->count = $count;
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
        while ($this->has_element($element)) {
            $elements[] = $element;
            $count++;
        }
        $this->elements - $elements;
        $this->count = $count;
        return ($this->count > 0);
    }

    /**
     * This function replaces the element at the specified index.
     *
     * @access public
     * @param integer $index                    the index of the element to be set
     * @param mixed $element                    the element to be set
     * @return boolean                          whether the element was set
     * @throws Kohana_InvalidArgument_Exception indicates that index must be an integer
     */
    public function set_element($index, $element) {
        if (is_integer($index)) {
            if (isset($this->elements[$index])) {
                $this->elements[$index] = $element;
                return TRUE;
            }
            else if ($index == $this->count) {
                $this->elements[] = $element;
                $this->count++;
                return TRUE;
            }
            return FALSE;
        }
        throw new Kohana_InvalidArgument_Exception('Message: Unable to set element. Reason: :type is of the wrong data type.', array(':type' => gettype($index), ':element' => $element));
    }

}
?>