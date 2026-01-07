<?php

namespace DiceCipher\TableGenerator;

class RandomSequenceGenerator
{
    private int $size;

    public function __construct(int $size = 40)
    {
        if ($size !== 40) {
            throw new \InvalidArgumentException("Size must be 40 for DiceCipher.");
        }
        $this->size = $size;
    }

    /**
     * Generate a single random permutation of 01–40 as a comma-separated string
     */
    public function generate(): string
    {
        $numbers = range(1, $this->size);

        // Modern Fisher-Yates shuffle using cryptographically secure random_int()
        for ($i = $this->size - 1; $i > 0; $i--) {
            $j = random_int(0, $i);
            [$numbers[$i], $numbers[$j]] = [$numbers[$j], $numbers[$i]];
        }

        // Format as 01,02,...,40
        return implode(',', array_map(fn($n) => sprintf('%02d', $n), $numbers));
    }

    /**
     * Optional: Generate multiple sequences at once (useful for batching)
     */
    public function generateMultiple(int $count): array
    {
        $sequences = [];
        for ($i = 0; $i < $count; $i++) {
            $sequences[] = $this->generate();
        }
        return $sequences;
    }
}