<?php

/**
 *  LINQ concept for PHP
 *  Copyright (C) 2015  Marcel Joachim Kloubert <marcel.kloubert@gmx.net>
 *    
 *    This library is free software; you can redistribute it and/or
 *    modify it under the terms of the GNU Lesser General Public
 *    License as published by the Free Software Foundation; either
 *    version 3.0 of the License, or (at your option) any later version.
 *    
 *    This library is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 *    Lesser General Public License for more details.
 *    
 *    You should have received a copy of the GNU Lesser General Public
 *    License along with this library.
 */


namespace System\Collections\Generic;

/**
 * A basic sequence.
 * 
 * @author Marcel Joachim Kloubert <marcel.kloubert@gmx.net>
 */
abstract class EnumerableBase implements IEnumerable {
    /**
     * (non-PHPdoc)
     * @see \System\Collections\Generic\IEnumerable::all()
     */
    public function all($predicate) {
    	$this->checkForFunctionOrThrow($predicate, 1, false);
    	
        while ($this->valid()) {
            $i = $this->current();
            $this->next();
            
            if (!$predicate($i)) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * (non-PHPdoc)
     * @see \System\Collections\Generic\IEnumerable::any()
     */
    public function any($predicate = null) {
    	$this->checkForFunctionOrThrow($predicate);
    	
        $predicate = self::toPredeciateSafe($predicate);
        
        while ($this->valid()) {
            $i = $this->current();
            $this->next();
            
            if ($predicate($i)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Checks if an object/value is a function and throws an exception
     * if not.
     * 
     * @param mixed $obj The object/value to check.
     * @param integer $argCount The required arguments.
     * @param boolean $ignoreNull Ignore (null) references or not.
     */
    protected function checkForFunctionOrThrow($obj,
    		                                   $argCount = 1,
    		                                   $ignoreNull = true) {
    	if (is_null($obj)) {
    		if ($ignoreNull) {
    			return;
    		}
    	}	
    	else {
    		if (is_callable($obj)) {
    			// OK, seems to be a function
    			
    			$r = new \ReflectionFunction($obj);
    			if (count($r->getParameters()) == $argCount) {
    				// has the right number of arguments
    				// so anything is OK
    				
    		        return;
    			}
    		}
    	}
    	
    	$this->throwException(sprintf('Function with %s arguments required!',
    			                      $argCount));
    }
    
    /**
     * (non-PHPdoc)
     * @see \System\Collections\Generic\IEnumerable::contains()
     */
    public function contains($item) {
        while ($this->valid()) {
            $i = $this->current();
            $this->next();
            
            if ($item == $i) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * (non-PHPdoc)
     * @see \System\Collections\Generic\IEnumerable::concat()
     */
    public function concat($iterator) {
        if (is_array($iterator)) {
            $iterator = new \ArrayIterator($iterator);
        }
        
        return static::toEnumerable($this->concatInner($iterator));
    }
    
    private function concatInner($iterator) {
        // first this elements
        while ($this->valid()) {
            yield $this->current();
            
            $this->next();
        }
        
        // now other elements
        while ($iterator->valid()) {
            yield $iterator->current();
            
            $iterator->next();
        }
    }
    
    /**
     * (non-PHPdoc)
     * @see \Countable::count()
     */
    public function count() {
        $result = 0;
        while ($this->valid()) {
            ++$result;
            
            $this->next();
        }    
        
        return $result;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Iterator::current()
     */
    public abstract function current();
    
    /**
     * (non-PHPdoc)
     * @see \System\Collections\Generic\IEnumerable::firstOrDefault()
     */
    public final function firstOrDefault($predicate = null, $defValue = null) {
    	$this->checkForFunctionOrThrow($predicate);
    	
        $predicate = self::toPredeciateSafe($predicate);
        
        $result = $defValue;
        while ($this->valid()) {
            $i = $this->current();

            $this->next();
            
            if ($predicate($i)) {
                $result = $i;
                break;
            }
        }
        
        return $result;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Iterator::key()
     */
    public abstract function key();

    /**
     * (non-PHPdoc)
     * @see \System\Collections\Generic\IEnumerable::lastOrDefault()
     */
    public final function lastOrDefault($predicate = null, $defValue = null) {
    	$this->checkForFunctionOrThrow($predicate);
    	
        $predicate = self::toPredeciateSafe($predicate);
        
        $result = $defValue;
        while ($this->valid()) {
            $i = $this->current();
            if ($predicate($i)) {
                $result = $i;
            }

            $this->next();
        }
        
        return $result;
    }
    
    private function leftOrRight($func, $defValue = null) {
        $result = $defValue;
        
        $isFirst = true;
        while ($this->valid()) {
            $item = $this->current();
            
            if ($isFirst) {
                $result = $item;
                $isFirst = false;
            }
            else {
                $result = $func($item, $result);
            }
            
            $this->next();
        }
        
        return $result;
    }
    
    /**
     * (non-PHPdoc)
     * @see \System\Collections\Generic\IEnumerable::max()
     */
    public function max($defValue = null) {
        return $this->leftOrRight(function($left, $right) {
            return max($left, $right);
        }, $defValue);
    }
    
    /**
     * (non-PHPdoc)
     * @see \System\Collections\Generic\IEnumerable::min()
     */
    public function min($defValue = null) {
        return $this->leftOrRight(function($left, $right) {
            return min($left, $right);
        }, $defValue);
    }
    
    /**
     * (non-PHPdoc)
     * @see \Iterator::next()
     */
    public abstract function next();

    /**
     * (non-PHPdoc)
     * @see \System\Collections\Generic\IEnumerable::reset()
     */
    public final function reset() {
    	$this->rewind();
    }
    
    /**
     * (non-PHPdoc)
     * @see \Iterator::rewind()
     */
    public abstract function rewind();
    
    /**
     * (non-PHPdoc)
     * @see \System\Collections\Generic\IEnumerable::select()
     */
    public function select($selector) {
    	$this->checkForFunctionOrThrow($selector);
    	
        return static::toEnumerable($this->selectInner($selector));
    }
    
    private function selectInner($selector) {
    	while ($this->valid()) {
    		yield $selector($this->current());
    	
    		$this->next();
    	}
    }
    
    /**
     * (non-PHPdoc)
     * @see \System\Collections\Generic\IEnumerable::selectMany()
     */
    public function selectMany($selector) {
    	$this->checkForFunctionOrThrow($selector);
    	
        return static::toEnumerable($this->selectManyInner($selector));
    }
    
    private function selectManyInner($selector) {
        while ($this->valid()) {
            $items = $selector($this->current());
            foreach ($items as $i) {
                yield $i;
            }
        
            $this->next();
        }
    }
    
    /**
     * (non-PHPdoc)
     * @see \System\Collections\Generic\IEnumerable::skip()
     */
    public function skip($count) {
    	if ($count < 0) {
    		$this->throwException('count value is invalid!');
    	}
    	
        return static::toEnumerable($this->skipInner($count));
    }
    
    private function skipInner($count) {
        while ($this->valid()) {
            $i = $this->current();
            $this->next();
            
            if ($count > 0) {
                --$count;
                continue;
            }

            yield $i;
        }
    }

    /**
     * (non-PHPdoc)
     * @see \System\Collections\Generic\IEnumerable::sum()
     */
    public final function sum($defValue = null) {
    	$result = $defValue;
    	
    	$isFirst = true;
    	while ($this->valid()) {
    		$i = $this->current();
    		
    		if ($isFirst) {
    			$result = $i;
    			$isFirst = false;	
    		}
    		else {
    			$result += $i;
    		}
    		
    		$this->next();
    	}
    	
    	return $result;
    }
    
    /**
     * (non-PHPdoc)
     * @see \System\Collections\Generic\IEnumerable::take()
     */
    public function take($count) {
    	if ($count < 0) {
    		$this->throwException('count value is invalid!');
    	}
    	
        return static::toEnumerable($this->takeInner($count));
    }
    
    private function takeInner($count) {
        while ($this->valid()) {
            if ($count <= 0) {
                break;
            }

            yield $this->current();
            --$count;
            
            $this->next();
        }
    }
    
    /**
     * Throws an exception for that sequence.
     * 
     * @param string $message The message.
     * @param number $code The code.
     * @param string $previous The inner/previous exception.
     * 
     * @throws EnumerableException The thrown exception.
     */
    protected function throwException($message = null,
			                          $code = 0,
			                          $previous = null) {
    	throw new EnumerableException($this,
    			                      $message, $code, $previous);
    }
    
    /**
     * (non-PHPdoc)
     * @see \System\Collections\Generic\IEnumerable::toArray()
     */
    public function toArray() {
        $result = array();
        while ($this->valid()) {
            $result[] = $this->current();
            
            $this->next();
        }
        
        return $result;
    }
    
    /**
     * (non-PHPdoc)
     * @see \System\Collections\Generic\IEnumerable::toDictionary()
     */
    public function toDictionary($keySelector = null) {
    	$this->checkForFunctionOrThrow($keySelector, 2);
    	
        if (is_null($keySelector)) {
            $keySelector = function($orgKey, $item) {
                return $orgKey;
            };
        }
        
        $result = array();
        while ($this->valid()) {
            $i = $this->current();
            $k = $keySelector($this->key(), $i);
            
            $result[$k] = $i;
                
            $this->next();
        }
        
        return $result;
    }

    /**
     * Wraps an object to a sequence.
     *
     * @param mixed $input The input value/object.
     *
     * @return \System\Collections\Generic\IEnumerable The wrapped object.
     */
    protected static function toEnumerable($input) {
    	return false;
    }
    
    private static function toPredeciateSafe($predicate, $defValue = true) {
        if (is_null($predicate)) {
            $predicate = function($i) use ($defValue) {
                return $defValue;
            };
        }
        
        return $predicate;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Iterator::valid()
     */
    public abstract function valid();
    
    /**
     * (non-PHPdoc)
     * @see \System\Collections\Generic\IEnumerable::where()
     */
    public function where($predicate) {
    	$this->checkForFunctionOrThrow($predicate);
    	
        return static::toEnumerable($this->whereInner($predicate));
    }
    
    private function whereInner($predicate) {
        while ($this->valid()) {
            $i = $this->current();
            
            if ($predicate($i)) {
                yield $i;
            }
            
            $this->next();
        }
    }
}