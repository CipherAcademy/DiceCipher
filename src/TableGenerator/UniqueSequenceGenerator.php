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
        while (count($this->uniqueSequences) < $numSequences) {
            $sequence = $this->generator->generate();
            $normalized = SequenceNormalizer::normalize($sequence);

            if (!isset($this->uniqueSequences[$normalized])) {
                $this->uniqueSequences[$normalized] = true;
                file_put_contents($this->outputFile, implode(",", $sequence) . PHP_EOL, FILE_APPEND);
            }
        }

        echo "Generated " . count($this->uniqueSequences) . " unique sequences in '{$this->outputFile}'.\n";
    }
}
