<?php

namespace DiceCipher\TableGenerator;

class RandomAlphabetGenerator {
    private array $alphabet;

    public function __construct(array $alphabet) {
        $this->alphabet = $alphabet;
    }

    public function generate(): array {
        $characters = $this->alphabet;
        $shuffled = [];

        while (!empty($characters)) {
            $index = random_int(0, count($characters) - 1);
            $shuffled[] = $characters[$index];
            array_splice($characters, $index, 1);
        }

        return $shuffled;
    }
}

