<?php

//  LICENSE: GPL 3 - https://www.gnu.org/licenses/gpl-3.0.txt
//  
//  s. https://github.com/mkloubert/phpLINQ


require_once './bootstrap.inc.php';


$pageTitle = 'buildWhile()';


// example #1
$examples[] = new Example();
$examples[0]->sourceCode = 'use \\System\\Linq\\Enumerable;


// build 10 random values
$seq = Enumerable::buildWhile(function($ctx) {
                                  //  $ctx->addItem  => add item or not (is TRUE by default)
                                  //  $ctx->cancel   => cancel operation or not (is TRUE by default)
                                  //  $ctx->count    => the number of items to create (this value can be updated)
                                  //  $ctx->index    => zero based index
                                  //  $ctx->isFirst  => indicates if this is the first element or not
                                  //  $ctx->items    => the reference to the array that currently contains
                                  //                    all previously build items
                                  //  $ctx->newKey   => the custom key for the new item
                                  //  $ctx->nextVal  => the value of prevVal for the next element
                                  //                    (is set to NULL at the beginning of each call)
                                  //  $ctx->prevVal  => the value of nextVal from the previous call
                                  //  $ctx->tag      => a value that can be defined for all invocations
                                  //                    of that callable / function
                                  //                    it is not resetted

                                  $ctx->cancel = $ctx->index > 10;
                                  if (!$ctx->cancel) {
                                      // define custom key
                                      $ctx->newKey = strval("KEY::" . $ctx->index);

                                      // return the item to add
                                      return mt_rand();
                                  }
                              });


foreach ($seq as $key => $item) {
    echo "{$key} => {$item}\n";
}
';


require_once './shutdown.inc.php';