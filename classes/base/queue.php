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
 * This class creates queue using an indexed array.
 *
 * @package Collection
 * @category Queue
 * @version 2011-12-31
 *
 * @abstract
 */
abstract class Base_Queue extends Vector {

    /**
     * This function initializes the class.
     *
     * @access public
     * @param mixed $variable                   a collection or an array
     * @return Queue                            an instance of this class
     * @throws Kohana_InvalidArgument_Exception indicates that variable must be an array
     *                                          or a collection
     */
    public function __construct($variable = NULL) {
        parent::__construct($variable);
    }

    /**
     * This function dequeues the element at the head of the queue.
     *
     * @access public
     * @return mixed                            the element dequeued
     * @throws Kohana_EmptyCollection_Exception indicates that no more elements are
     *                                          in the queue
     */
    public function dequeue() {
        if (!parent::is_empty()) {
            $element = array_shift($this->elements);
            $this->count--;
            return $element;
        }
        throw new Kohana_EmptyCollection_Exception('Message: Unable to dequeue an element from the queue. Reason: Collection contains no elements.', array());
    }

    /**
     * This function enqueues an element onto the queue.
     *
     * @access public
     * @param mixed $element                    the element to be enqueued
     * @return boolean                          whether the element was added
     */
    public function enqueue($element) {
        $result = parent::add_element($element);
        return $result;
    }

    /**
     * This function returns the element at the head of the queue, but does not remove it.
     *
     * @access public
     * @return mixed                            the element at the head of the queue
     * @throws Kohana_EmptyCollection_Exception indicates that no more elements are
     *                                          in the queue
     */
    public function peek() {
        if (!parent::is_empty()) {
            $element = $this->elements[0];
            return $element;
        }
        throw new Kohana_EmptyCollection_Exception('Message: Unable to peek at next element in the queue. Reason: Collection contains no elements.', array());
    }

}
?>