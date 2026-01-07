<?php

namespace DiceCipher;

class DiceRollCounter {
    private array $currentRoll;
    private bool $finished;

    public function __construct() {
        $this->currentRoll = [1, 1, 1, 1, 1];
        $this->finished = false;
    }

    /**
     * Get the current dice roll as a comma-separated string
     */
    public function getCurrent(): string {
        return implode(',', $this->currentRoll);
    }

    /**
     * Get the current dice roll as an array
     */
    public function getCurrentArray(): array {
        return $this->currentRoll;
    }

    /**
     * Advance to the next dice roll
     * Returns true if successful, false if we've reached the end (6,6,6,6,6)
     */
    public function next(): bool {
        if ($this->finished) {
            return false;
        }

        // Check if we're at the end
        if ($this->currentRoll === [6, 6, 6, 6, 6]) {
            $this->finished = true;
            return false;
        }

        // Increment from right to left (like counting)
        for ($i = 4; $i >= 0; $i--) {
            if ($this->currentRoll[$i] < 6) {
                $this->currentRoll[$i]++;
                // Reset all dice to the right to 1
                for ($j = $i + 1; $j < 5; $j++) {
                    $this->currentRoll[$j] = 1;
                }
                return true;
            }
        }

        $this->finished = true;
        return false;
    }

    /**
     * Reset to the beginning
     */
    public function reset(): void {
        $this->currentRoll = [1, 1, 1, 1, 1];
        $this->finished = false;
    }

    /**
     * Check if we've reached the end
     */
    public function isFinished(): bool {
        return $this->finished;
    }
}

