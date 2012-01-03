# Collections

This collections module is for the Kohana PHP Framework.  These collections provide more functionality than the SPL
implementation. All classes are iteratable and countable.

## Quick Start Guide:

Below are a few code examples of how to use these classes.

### HashSet

$hashset = new HashSet();
$hashset->add_element($element);
$exists = $hashset->has_element($element);

### HashMap

$hashmap = new HashMap();
$hashmap->put_element($key, $element);
$element = $hashmap->get_element($key);
$remove_key($key);

### Vector

$vector = new Vector();
$vector->add_element($element);
$element = $vector->get_element(0);
$vectory->remove_index(0);

### Queue

$queue = new Queue();
$queue->enqueue($element);
$element = $queue->peek();
$element = $queue->dequeue();

### Stack

$stack = new Stack();
$stack->push($element);
$element = $stack->peek();
$element = $stack->pop();

## License (Apache v2.0)

Copyright 2011-2012 Spadefoot

Licensed under the Apache License, Version 2.0 (the "License"); you may not use these files except in compliance with the License. You may obtain
a copy of the License at:

[http://www.apache.org/licenses/LICENSE-2.0](http://www.apache.org/licenses/LICENSE-2.0)

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations
under the License.
