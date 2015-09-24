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

use \System\Collections\IEachItemContext;
use \System\Collections\IEnumerable;


function actionFunc($x, IEachItemContext $ctx) {
    if ($ctx->isFirst()) {
        $ctx->result($x);
        return;
    }

    $ctx->result($ctx->result() + $x);
}

class ActionClass {
    public function __invoke($x, IEachItemContext $ctx) {
        actionFunc($x, $ctx);
    }
}

/**
 * @see \System\Collection\IEnumerable::each()
 *
 * @author Marcel Joachim Kloubert <marcel.kloubert@gmx.net>
 */
class EachTests extends TestCaseBase {
    /**
     * Creates the actions for the tests.
     *
     * @return array The actions.
     */
    protected function createActions() : array {
        return [
            function ($x, IEachItemContext $ctx) {
                actionFunc($x, $ctx);
            },
            'actionFunc',
            '\actionFunc',
            array($this, 'actionMethod1'),
            array(static::class, 'actionMethod2'),
            new ActionClass(),
            '$x, $ctx => actionFunc($x, $ctx)',
            '$x, $ctx => \actionFunc($x, $ctx)',
            '($x, $ctx) => actionFunc($x, $ctx)',
            '($x, $ctx) => \actionFunc($x, $ctx)',
            '$x, $ctx => { actionFunc($x, $ctx); }',
            '$x, $ctx => { \actionFunc($x, $ctx); }',
            '($x, $ctx) => { actionFunc($x, $ctx); }',
            '($x, $ctx) => { \actionFunc($x, $ctx); }',
            '$x, $ctx => {
actionFunc($x, $ctx);
}',
            '$x, $ctx => {
\actionFunc($x, $ctx);
}',
            '($x, $ctx) => {
actionFunc($x, $ctx);
}',
            '($x, $ctx) => {
\actionFunc($x, $ctx);
}',
        ];
    }

    public function actionMethod1($x, IEachItemContext $ctx) {
        actionFunc($x, $ctx);
    }

    public static function actionMethod2($x, IEachItemContext $ctx) {
        actionFunc($x, $ctx);
    }

    public function test1() {
        foreach ($this->createActions() as $action) {
            foreach (static::sequenceListFromArray([1, 2, 3, 4, 5]) as $seq) {
                /* @var IEnumerable $seq */

                $result = $seq->each($action, false);

                $this->assertFalse(false === $result);
                $this->assertTrue(15 === $result);
            }
        }
    }

    public function test2() {
        foreach ($this->createActions() as $action) {
            foreach (static::sequenceListFromArray([]) as $seq) {
                /* @var IEnumerable $seq */

                $result = $seq->each($action, false);

                $this->assertTrue(false === $result);
            }
        }
    }
}
