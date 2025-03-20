<?php

namespace DiceCipher\TableGenerator;

class RandomSequenceGenerator {
    private int $size;

    public function __construct(int $size = 40) {
        $this->size = $size;
    }

    public function generate(): array {
        $numbers = range(1, $this->size);
        $shuffled = [];

        while (!empty($numbers)) {
            $index = random_int(0, count($numbers) - 1);
            $shuffled[] = $numbers[$index];
            array_splice($numbers, $index, 1);
        }

        // Format numbers as two-digit strings
        return array_map(fn($num) => str_pad($num, 2, "0", STR_PAD_LEFT), $shuffled);
    }
}
