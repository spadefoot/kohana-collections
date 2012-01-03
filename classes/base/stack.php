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
 * This class creates stack using an indexed array.
 *
 * @package Collection
 * @category Stack
 * @version 2012-01-03
 *
 * @abstract
 */
abstract class Base_Stack extends Vector {

    /**
     * This function initializes the class.
     *
     * @access public
     * @param mixed $variable                   a collection or an array
     * @return Stack                            an instance of this class
     * @throws Kohana_InvalidArgument_Exception indicates that variable must be an array
     *                                          or a collection
     */
    public function __construct($variable = NULL) {
        parent::__construct($variable);
    }

    /**
     * This function returns the element at the top of the stack, but does not remove it.
     *
     * @access public
     * @return mixed                            the element at the top of the stack
     * @throws Kohana_EmptyCollection_Exception indicates that no more elements are
     *                                          on the stack
     */
    public function peek() {
        if (!parent::is_empty()) {
            $element = $this->element[$this->count - 1];
            return $element;
        }
        throw new Kohana_EmptyCollection_Exception('Message: Unable to peek at next element on the stack. Reason: Collection contains no elements.', array());
    }

    /**
     * This function pops the top element off the stack.
     *
     * @access public
     * @return mixed                            the element at the top of the stack
     * @throws Kohana_EmptyCollection_Exception indicates that no more elements are
     *                                          on the stack
     */
    public function pop() {
        if (!parent::is_empty()) {
            $element = array_pop($this->elements);
            $this->count--;
            return $element;
        }
        throw new Kohana_EmptyCollection_Exception('Message: Unable to pop an element off the stack. Reason: Collection contains no elements.', array());
    }

    /**
     * This function pushes an element onto the top of the stack.
     *
     * @access public
     * @param mixed $element                    the element to be pushed onto the stack
     * @return boolean                          whether the element was added
     */
    public function push($element) {
        $result = parent::add_element($element);
        return $result;
    }

}
?>