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
 * This class provides a set of helper function for working with sets.
 *
 * @package Collection
 * @category Set
 * @version 2012-01-03
 *
 * @see http://docs.oracle.com/javase/tutorial/collections/interfaces/set.html
 *
 * @abstract
 */
abstract class Base_Set extends Kohana_Object {

    /**
     * This function returns the cardinality of the specified hash set.
     *
     * @access public
     * @static
     * @param HashSet $set                      the hash set to be evaluated
     * @return integer                          the cardinality of the specified
     *                                          hash set
     */
    public static function cardinality(HashSet $set) {
        return $set->count();
    }

    /**
     * This function returns the cartesian product of the specified hash sets.
     *
     * @access public
     * @static
     * @param HashSet $sets                     the hash sets to be evaluated
     * @return HashSet                          the cartesian product of the specified
     *                                          hash sets
     *
     * @see http://stackoverflow.com/questions/714108/cartesian-product-of-arbitrary-sets-in-java
     */
    public static function cartesian_product(/*HashSet... sets*/) {
        if (func_num_args() < 2) {
            throw new Kohana_InvalidArgument_Exception('Message: Unable to perform evaluation. Reason: At least two sets must be passed.');
        }
        $sets = func_get_args();
        return self::_cartesian_product(0, $sets);
    }

    /**
     * This function acts as a helper to finding the cartesian product of the specified
     * hash sets.
     *
     * @access protected
     * @static
     * @param integer $index                    the index 
     * @param HashSet $sets                     the hash sets to be evaluated
     * @return HashSet                          the cartesian product of the specified
     *                                          hash sets
     *
     * @see http://stackoverflow.com/questions/714108/cartesian-product-of-arbitrary-sets-in-java
     */
    protected static function _cartesian_product($index, $sets) {
        $hashset = new HashSet();
        if ($index == count($sets)) {
            $hashset->add_element(new HashSet());
        }
        else {
            foreach ($sets[$index] as $object) {
                $cartesian_product = self::_cartesian_product($index + 1, $sets);
                foreach ($cartesian_product as $set) {
                    $set->add_element($object);
                    $hashset->add_element($set);
                }
            }
        }
        return $hashset;
    }

    /**
     * This function returns a hash set which represents the (asymmetric) difference between
     * the two specified sets.
     *
     * @access public
     * @static
     * @param HashSet $s1                       the first set
     * @param HashSet $s2                       the second set
     * @return HashSet                          a hash set which represents the (asymmetric)
     *                                          difference of the two specified sets
     */
    public static function difference(HashSet $s1, HashSet $s2) {
        $s0 = new HashSet($s1);
        $s0->remove_collection($s2);
        return $s0;
    }

    /**
     * This function returns a hash set which represents the intersection between the two
     * specified sets.
     *
     * @access public
     * @static
     * @param HashSet $s1                       the first set
     * @param HashSet $s2                       the second set
     * @return HashSet                          a hash set which represents the intersection
     *                                          of the two specified sets
     */
    public static function intersection(HashSet $s1, HashSet $s2) {
        $s0 = new HashSet($s1);
        $s0->retain_collection($s2);
        return $s0;
    }

    /**
     * This function returns whether the second hash set is a subset of the first hash
     * set.
     *
     * @access public
     * @static
     * @param HashSet $s1                       the first set
     * @param HashSet $s2                       the second set
     * @return HashSet                          whether the second hash set is a
     *                                          subset of the first hash set
     */
    public static function is_subset(HashSet $s1, HashSet $s2) {
        return $s1->has_collection($s2);
    }

    /**
     * This function returns whether the second hash set is a superset of the first hash
     * set.
     *
     * @access public
     * @static
     * @param HashSet $s1                       the first set
     * @param HashSet $s2                       the second set
     * @return HashSet                          whether the second hash set is a
     *                                          superset of the first hash set
     */
    public static function is_superset(HashSet $s1, HashSet $s2) {
        return $s2->has_collection($s1);
    }

    /**
     * This function returns the power set of the specified set.
     *
     * @access public
     * @static
     * @param HashSet $set                      the hash set to be used
     * @return HashSet                          the power set
     *
     * @see http://rosettacode.org/wiki/Power_Set
     */
    public static function powerset(HashSet $set) {
        $powerset = new HashSet();
        $powerset->add_element(new HashSet());
        foreach ($set as $element) {
            $hashset = new HashSet();
            foreach ($powerset as $subset) {
                $hashset->add_element($subset);
                $temp = new HashSet($subset);
                $temp->add_element($element);
                $hashset->add_element($temp);
            }
            $powerset = $hashset;
        }
        return $powerset;
    }

    /**
     * This function returns a hash set which represents the symmetric difference between
     * the two specified sets.
     *
     * @access public
     * @static
     * @param HashSet $s1                       the first set
     * @param HashSet $s2                       the second set
     * @return HashSet                          a hash set which represents the symmetric
     *                                          difference of the two specified sets
     */
    public static function symmetric_difference(HashSet $s1, HashSet $s2) {
        $s0 = new HashSet($s1);
        $s0->add_collection($s2);
        $tmp = new HashSet($s1);
        $tmp->retain_collection($s2);
        $s0->remove_collection($tmp);
        return $s0;
    }

    /**
     * This function returns a hash set which represents the union of the two specified
     * sets.
     *
     * @access public
     * @static
     * @param HashSet $s1                       the first set
     * @param HashSet $s2                       the second set
     * @return HashSet                          a hash set which represents the union
     *                                          of the two specified sets
     */
    public static function union(HashSet $s1, HashSet $s2) {
        $s0 = new HashSet($s1);
        $s0->add_collection($s2);
        return $s0;
    }

}
?>