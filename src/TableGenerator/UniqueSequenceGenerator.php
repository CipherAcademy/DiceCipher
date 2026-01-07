<?php

namespace DiceCipher\TableGenerator;

class UniqueSequenceGenerator {
    private array $uniqueSequences = [];
    private RandomSequenceGenerator $generator;
    private string $outputFile;

    public function __construct(int $size, string $outputFile) {
        $this->generator = new RandomSequenceGenerator($size);
        $this->outputFile = $outputFile;
    }

    public function generateAndStore(int $numSequences): void {
        // Clear the file if it exists
        if (file_exists($this->outputFile)) {
            file_put_contents($this->outputFile, '');
        }

        while (count($this->uniqueSequences) < $numSequences) {
            $sequence = $this->generator->generate(); // Returns string
            // Convert string to array for normalization
            $sequenceArray = explode(',', $sequence);
            $normalized = SequenceNormalizer::normalize($sequenceArray);

            if (!isset($this->uniqueSequences[$normalized])) {
                $this->uniqueSequences[$normalized] = true;
                // Write the string directly to file
                file_put_contents($this->outputFile, $sequence . PHP_EOL, FILE_APPEND);
            }
        }

        echo "Generated " . count($this->uniqueSequences) . " unique sequences in '{$this->outputFile}'.\n";
    }
}
