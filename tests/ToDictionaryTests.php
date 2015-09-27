<?php

/**********************************************************************************************************************
 * phpLINQ (https://github.com/mkloubert/phpLINQ)                                                                     *
 *                                                                                                                    *
 * Copyright (c) 2015, Marcel Joachim Kloubert <marcel.kloubert@gmx.net>                                              *
 * All rights reserved.                                                                                               *
 *                                                                                                                    *
 * Redistribution and use in source and binary forms, with or without modification, are permitted provided that the   *
 * following conditions are met:                                                                                      *
 *                                                                                                                    *
 * 1. Redistributions of source code must retain the above copyright notice, this list of conditions and the          *
 *    following disclaimer.                                                                                           *
 *                                                                                                                    *
 * 2. Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the       *
 *    following disclaimer in the documentation and/or other materials provided with the distribution.                *
 *                                                                                                                    *
 * 3. Neither the name of the copyright holder nor the names of its contributors may be used to endorse or promote    *
 *    products derived from this software without specific prior written permission.                                  *
 *                                                                                                                    *
 *                                                                                                                    *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, *
 * INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE  *
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, *
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR    *
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,  *
 * WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE   *
 * USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.                                           *
 *                                                                                                                    *
 **********************************************************************************************************************/

use \System\ArgumentException;
use \System\Collections\IDictionary;
use \System\Linq\Enumerable;


function itemValidatorFunc($x) : bool {
    return is_numeric($x);
}

function keyComparerFunc ($x, $y) : bool {
    return 0 === strcasecmp(trim($x), trim($y));
}

function keyValidatorFunc ($x) : bool {
    return is_string($x);
}

class ItemValidatorClass {
    public function __invoke($x) {
        return itemValidatorFunc($x);
    }
}

class KeyComparerClass {
    public function __invoke($x, $y) {
        return keyComparerFunc($x, $y);
    }
}

class KeyValidatorClass {
    public function __invoke($x) {
        return keyValidatorFunc($x);
    }
}

/**
 * @see \System\Collection\IEnumerable::toDictionary()
 *
 * @author Marcel Joachim Kloubert <marcel.kloubert@gmx.net>
 */
class ToDictionaryTests extends TestCaseBase {
    /**
     * Creates the item validators for the tests.
     *
     * @return array The item validators.
     */
    protected function createItemValidators() : array {
        return [
            function ($x) {
                return itemValidatorFunc($x);
            },
            'itemValidatorFunc',
            '\itemValidatorFunc',
            new ItemValidatorClass(),
            array($this, 'itemValidatorMethod1'),
            array(static::class, 'itemValidatorMethod2'),
            '$x => itemValidatorFunc($x)',
            '($x) => itemValidatorFunc($x)',
            '$x => return itemValidatorFunc($x);',
            '($x) => return itemValidatorFunc($x);',
            '$x => { return itemValidatorFunc($x); }',
            '($x) => { return itemValidatorFunc($x); }',
            '$x => {
                 return itemValidatorFunc($x);
             }',
            '($x) => {
                 return itemValidatorFunc($x);
             }',
            '$x => \itemValidatorFunc($x)',
            '($x) => \itemValidatorFunc($x)',
            '$x => return \itemValidatorFunc($x);',
            '($x) => return \itemValidatorFunc($x);',
            '$x => { return \itemValidatorFunc($x); }',
            '($x) => { return \itemValidatorFunc($x); }',
            '$x => {
                 return \itemValidatorFunc($x);
             }',
            '($x) => {
                 return \itemValidatorFunc($x);
             }',
        ];
    }

    /**
     * Creates the key comparers for the tests.
     *
     * @return array The key comparers.
     */
    protected function createKeyComparers() : array {
        return [
            function ($x, $y) {
                return keyComparerFunc($x, $y);
            },
            'keyComparerFunc',
            '\keyComparerFunc',
            new KeyComparerClass(),
            array($this, 'keyComparerMethod1'),
            array(static::class, 'keyComparerMethod2'),
            '$x, $y => keyComparerFunc($x, $y)',
            '($x, $y) => keyComparerFunc($x, $y)',
            '$x, $y => return keyComparerFunc($x, $y);',
            '($x, $y) => return keyComparerFunc($x, $y);',
            '$x, $y => { return keyComparerFunc($x, $y); }',
            '($x, $y) => { return keyComparerFunc($x, $y); }',
            '$x, $y => {
                 return keyComparerFunc($x, $y);
             }',
            '($x, $y) => {
                 return keyComparerFunc($x, $y);
             }',
            '$x, $y => \keyComparerFunc($x, $y)',
            '($x, $y) => \keyComparerFunc($x, $y)',
            '$x, $y => return \keyComparerFunc($x, $y);',
            '($x, $y) => return \keyComparerFunc($x, $y);',
            '$x, $y => { return \keyComparerFunc($x, $y); }',
            '($x, $y) => { return \keyComparerFunc($x, $y); }',
            '$x, $y => {
                 return \keyComparerFunc($x, $y);
             }',
            '($x, $y) => {
                 return \keyComparerFunc($x, $y);
             }',
        ];
    }

    /**
     * Creates the key validators for the tests.
     *
     * @return array The key validators.
     */
    protected function createKeyValidators() : array {
        return [
            function ($x) {
                return keyValidatorFunc($x);
            },
            'keyValidatorFunc',
            '\keyValidatorFunc',
            new KeyValidatorClass(),
            array($this, 'keyValidatorMethod1'),
            array(static::class, 'keyValidatorMethod2'),
            '$x => keyValidatorFunc($x)',
            '($x) => keyValidatorFunc($x)',
            '$x => return keyValidatorFunc($x);',
            '($x) => return keyValidatorFunc($x);',
            '$x => { return keyValidatorFunc($x); }',
            '($x) => { return keyValidatorFunc($x); }',
            '$x => {
                 return keyValidatorFunc($x);
             }',
            '($x) => {
                 return keyValidatorFunc($x);
             }',
            '$x => \keyValidatorFunc($x)',
            '($x) => \keyValidatorFunc($x)',
            '$x => return \keyValidatorFunc($x);',
            '($x) => return \keyValidatorFunc($x);',
            '$x => { return \keyValidatorFunc($x); }',
            '($x) => { return \keyValidatorFunc($x); }',
            '$x => {
                 return \keyValidatorFunc($x);
             }',
            '($x) => {
                 return \keyValidatorFunc($x);
             }',
        ];
    }

    public function itemValidatorMethod1($x) {
        return itemValidatorFunc($x);
    }

    public static function itemValidatorMethod2($x) {
        return itemValidatorFunc($x);
    }

    public function keyComparerMethod1($x, $y) {
        return keyComparerFunc($x, $y);
    }

    public static function keyComparerMethod2($x, $y) {
        return keyComparerFunc($x, $y);
    }

    public function keyValidatorMethod1($x) {
        return keyValidatorFunc($x);
    }

    public static function keyValidatorMethod2($x) {
        return keyValidatorFunc($x);
    }

    public function test1() {
        $dict = Enumerable::create(['a' => 11, 'b' => 2.0, 'C' => '3'])
                          ->toDictionary();

        $this->assertEquals(3, count($dict));

        $this->assertTrue(isset($dict['a']));
        $this->assertFalse(isset($dict['A']));
        $this->assertSame(11, $dict['a']);

        $this->assertTrue(isset($dict['b']));
        $this->assertFalse(isset($dict['B']));
        $this->assertSame(2.0, $dict['b']);

        $this->assertTrue(isset($dict['C']));
        $this->assertFalse(isset($dict['c']));
        $this->assertSame('3', $dict['C']);

        $this->checkForExpectedValues($dict->asEnumerable(), [11, 2.0, '3']);
    }

    public function testItemValidators() {
        $keyValidatorList   = $this->createKeyValidators();
        $keyValidatorList[] = null;

        foreach ($keyValidatorList as $keyValidator) {
            foreach ($this->createItemValidators() as $itemValidator) {
                try {
                    $dict1 = Enumerable::create(['a' => 111, 'b' => 2.7, 'C' => '33three'])
                                       ->toDictionary(null, $keyValidator, $itemValidator);
                }
                catch (ArgumentException $ex) {
                    $thrownEx1 = $ex;
                }

                $this->assertFalse(isset($dict1));
                $this->assertTrue(isset($thrownEx1));
                $this->assertInstanceOf(ArgumentException::class, $thrownEx1);

                try {
                    $dict2 = Enumerable::create(['a' => 111, 'B' => 2.7, 'c' => '33'])
                                       ->toDictionary(null, $keyValidator, $itemValidator);
                }
                catch (ArgumentException $ex) {
                    $thrownEx2 = $ex;
                }

                $this->assertTrue(isset($dict2));
                $this->assertFalse(isset($thrownEx2));
                $this->assertInstanceOf(IDictionary::class, $dict2);
                $this->assertEquals(3, count($dict2));
            }
        }
    }

    public function testKeyComparer() {
        foreach ($this->createKeyComparers() as $keyComparer) {
            $dict = Enumerable::create(['a' => 111, 'b' => 2.7, 'C' => '33three'])
                              ->toDictionary($keyComparer);

            $this->assertEquals(3, count($dict));

            $this->assertTrue(isset($dict['a']));
            $this->assertTrue(isset($dict['A']));
            $this->assertSame(111, $dict['a']);
            $this->assertSame(111, $dict['A']);

            $this->assertTrue(isset($dict['b']));
            $this->assertTrue(isset($dict['B']));
            $this->assertSame(2.7, $dict['b']);
            $this->assertSame(2.7, $dict['B']);

            $this->assertTrue(isset($dict['C']));
            $this->assertTrue(isset($dict['c']));
            $this->assertSame('33three', $dict['c']);
            $this->assertSame('33three', $dict['C']);

            $this->checkForExpectedValues($dict->asEnumerable(), [111, 2.7, '33three']);
        }
    }

    public function testKeyValidators() {
        foreach ($this->createKeyValidators() as $keyValidator) {
            try {
                $dict1 = Enumerable::create(['a' => 111, 'b' => 2.7, 'C' => '33three'])
                                   ->toDictionary(null, $keyValidator);
            }
            catch (ArgumentException $ex) {
                $thrownEx1 = $ex;
            }

            $this->assertTrue(isset($dict1));
            $this->assertFalse(isset($thrownEx1));
            $this->assertInstanceOf(IDictionary::class, $dict1);
            $this->assertEquals(3, count($dict1));

            $this->checkForExpectedValues($dict1->keys()->asResettable(),
                                          ['a', 'b', 'C']);
            $this->checkForExpectedValues($dict1->values()->asResettable(),
                                          [111, 2.7, '33three']);

            try {
                $dict2 = Enumerable::create(['a' => 111, 2 => 3, 'C' => '33three'])
                                   ->toDictionary(null, $keyValidator);
            }
            catch (ArgumentException $ex) {
                $thrownEx2 = $ex;
            }

            $this->assertFalse(isset($dict2));
            $this->assertTrue(isset($thrownEx2));
            $this->assertInstanceOf(ArgumentException::class, $thrownEx2);
        }
    }
}
