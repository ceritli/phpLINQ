<?php

//  LICENSE: GPL 3 - https://www.gnu.org/licenses/gpl-3.0.txt
//  
//  s. https://github.com/mkloubert/phpLINQ


require_once './bootstrap.inc.php';


$pageTitle = 'intersect()';

// example #1
$examples[] = new Example();
$examples[0]->sourceCode = 'use \\System\\Linq\\Enumerable;
	
$seq1 = Enumerable::fromValues(1, 2, 3, 4, 5);
$seq2 = Enumerable::fromValues(3, 2);
$seq3 = Enumerable::fromValues(6, 7);
$seq4 = Enumerable::createEmpty();


$showSequence = function($seq) {
	foreach ($seq as $item) {
		echo "{$item}\n";
	}
};


echo "seq1 + seq2:\n";
$showSequence($seq1->intersect($seq2));
		
echo "\n";
		
echo "seq1 + seq3:\n";
$showSequence($seq1->reset()
		           ->intersect($seq3));

echo "\n";
		
echo "seq1 + seq4:\n";
$showSequence($seq1->reset()
		           ->intersect($seq4));
';


require_once './shutdown.inc.php';