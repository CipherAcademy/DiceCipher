<?php

require __DIR__ . '/../vendor/autoload.php';

use DiceCipher\TableGenerator\UniqueSequenceGenerator;

$generator = new UniqueSequenceGenerator(40, "sequences.txt");
$generator->generateAndStore(1000);
