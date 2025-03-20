<?php

namespace DiceCipher\TableGenerator;

class SequenceNormalizer {
    public static function normalize(array $sequence): string {
        $minRotation = $sequence;
        $len = count($sequence);

        for ($i = 1; $i < $len; $i++) {
            $rotated = array_merge(array_slice($sequence, $i), array_slice($sequence, 0, $i));
            if ($rotated < $minRotation) {
                $minRotation = $rotated;
            }
        }

        return implode(",", $minRotation);
    }
}
