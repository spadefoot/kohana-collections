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
 * This class creates hash map using an associated array.
 *
 * @package Collection
 * @category HashMap
 * @version 2012-01-03
 *
 * @abstract
 */
abstract class Base_HashMap extends Collection {

    /**
     * This function initializes the class.
     *
     * @access public
     * @param mixed $variable                   a collection or an array
     * @return HashMap                          an instance of this class
     * @throws Kohana_InvalidArgument_Exception indicates that variable must be an array
     *                                          or a collection
     */
    public function __construct($variable = NULL) {
        if ( ! is_null($variable)) {
            if (is_object($variable) && ($variable instanceof Collection)) {
                $this->put_collection($variable);
            }
            else if (is_array($variable)) {
                $this->put_array($variable);
            }
            else {
                throw new Kohana_InvalidArgument_Exception('Message: Unable to initialize class. Reason: :type is of the wrong data type.', array(':type' => gettype($variable)));
            }
        }
    }

    /**
     * This function returns the value associated with the specified key.
     *
     * @access public
     * @param scaler $key                       the key
     * @return mixed                            the element associated with the specified key
     * @throws Kohana_InvalidArgument_Exception indicates that key must be either an integer
     *                                          or a string
     * @throws Kohana_KeyNotFound_Exception     indicates that key could not be found
     */
    public function get_element($key) {
        if (is_integer($key) || is_string($key)) {
            if (array_key_exists($key, $this->elements)) {
                $element = $this->elements[$key];
                return $element;
            }
            throw new Kohana_KeyNotFound_Exception('Message: Unable to get element. Reason: Key :key does not exist.', array(':key' => $key));
        }
        throw new Kohana_InvalidArgument_Exception('Message: Unable to get element. Reason: :type is of the wrong data type.', array(':type' => gettype($key)));
    }

    /**
     * This function returns an array of all keys in the collection.
     *
     * @access public
     * @return array                            an array of all keys in the collection
     */
    public function get_keys() {
        $keys = array_keys($this->elements);
        return $keys;
    }

    /**
     * The function returns an array of all values in the collection.
     *
     * @access public
     * @return array                            an array of all values in the collection
     */
    public function get_values() {
        $values = array_values($this->elements);
        return $values;
    }

    /**
     * The function determines whether the specified key exists in the collection.
     *
     * @access public
     * @param scaler $key                       the key to be tested
     * @return boolean                          whether the specified key exists
     */
    public function has_key($key) {
        if (is_integer($key) || is_string($key)) {
            $result = array_key_exists($key, $this->elements);
            return $result;
        }
        return FALSE;
    }

    /**
     * This function determines whether the specified value exists in the collection.
     *
     * @access public
     * @param mixed $value                      the value to be tested
     * @return boolean                          whether the specified value exists
     */
    public function has_value($value) {
        foreach ($this->elements as $element) {
            if ( (string) serialize($value) == (string) serialize($element)) {
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * This function puts the key/value mapping to the collection.
     *
     * @access public
     * @param scaler $key                       the key to be mapped
     * @param mixed $value                      the value to be mapped
     * @return boolean                          whether the key/value pair was set
     * @throws Kohana_InvalidArgument_Exception indicates that key must be either an integer
     *                                          or a string
     */
    public function put_element($key, $value) {
        if (is_integer($key) || is_string($key)) {
            $this->elements[$key] = $value;
            $this->count++;
            return TRUE;
        }
        throw new Kohana_InvalidArgument_Exception('Message: Unable to put element. Reason: :type is of the wrong data type.', array(':type', gettype($key)));
    } 

    /**
     * This function puts all of the key/value mappings into the collection.
     *
     * @access public
     * @param array $array                      the array to be mapped
     * @return boolean                          whether any key/value pairs were set
     * @throws Kohana_InvalidArgument_Exception indicates that a key in the passed array
     *                                          is not an integer or a string
     */
    public function put_array(Array $array) {
        if ( ! empty($array)) {
            foreach ($array as $key => $value) {
                $this->put_element($key, $value);
            }
            return TRUE;
        }
        return FALSE;
    }

    /**
     * This function puts all of the key/value mappings into the collection.
     *
     * @access public
     * @param Collection $collection            the collection to be mapped
     * @return boolean                          whether any key/value pairs were set
     * @throws Kohana_InvalidArgument_Exception indicates that a key in the passed collection
     *                                          is not an integer or a string
     */
    public function put_collection(Collection $collection) {
        $result = $this->put_array($collection->as_array());
        return $result;
    }

    /**
     * This function removes the key/value mapping with the specified key from the collection.
     *
     * @access public
     * @param scaler $key                       the key
     * @return boolean                          whether the key/value pair was removed
     */
    public function remove_key($key) {
        if (is_integer($key) || is_string($key)) {
            if (array_key_exists($key, $this->elements)) {
                unset($this->elements[$key]);
                $this->count--;
                return TRUE;
            }
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
        $elements = array();
        $count = 0;
        foreach ($array as $element) {
            foreach ($this->elements as $key => $value) {
                if ( (string) serialize($value) == (string) serialize($element)) {
                    $elements[$key] = $value;
                    $count++;
                }
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
        $result = $this->retain_array($collection->as_array());
        return $result;
    }

    /**
     * This function will retain only those elements that match the specified element.
     *
     * @access public
     * @param mixed $element                    the element to be retained
     * @return boolean                          whether any elements were retained
     */
    public function retain_element($element) {
        $elements = array();
        $count = 0;
        foreach ($this->elements as $key => $value) {
            if ( (string) serialize($value) == (string) serialize($element)) {
                $elements[$key] = $value;
                $count++;
            }
        }
        $this->elements = $elements;
        $this->count = $count;
        return ($this->count > 0);
    }

    /**
     * This function will retain only those elements that match the specified value.
     *
     * @access public
     * @param mixed $value                      the value to be retained
     * @return boolean                          whether any elements were retained
     */
    public function retain_value($value) {
        $result = $this->retain_element($value);
        return $result;
    }

}
?>