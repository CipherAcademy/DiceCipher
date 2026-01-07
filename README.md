# DiceCipher

A PHP library for generating and working with DiceCipher sequences - unique randomized sequences of numbers and characters for cryptographic applications.

## Overview

DiceCipher generates two types of unique sequences:
1. **Number sequences**: Random permutations of numbers 01-40
2. **Alphabet sequences**: Random permutations of 40 characters from a master alphabet file

These sequences can then be merged into a combined matrix file for use in cryptographic operations.

## Installation

1. Clone or download this repository
2. Install dependencies using Composer:
   ```bash
   composer install
   ```

## Scripts Overview

### 1. Sequence Generator (`sequence-generator`)

Generates unique randomized sequences of numbers from 01 to 40.

**How it works:**
- Creates random permutations of the numbers 1-40
- Uses normalization to ensure uniqueness (rotates sequences to their lexicographically smallest form)
- Stores sequences in a comma-separated format (e.g., `01,02,03,...,40`)
- Automatically runs bias analysis after generation

**Usage:**
```bash
php bin/sequence-generator <number_of_sequences> <output_file>
```

**Example:**
```bash
php bin/sequence-generator 1000 sequences.txt
```

This will:
- Generate 1000 unique number sequences
- Save them to `output/sequences.txt`
- Automatically run bias analysis and create `output/sequences--bias-report-histogram.txt`

**Note:** All output files are automatically saved to the `output/` directory. The directory will be created if it doesn't exist.

**Output format:**
Each line contains 40 comma-separated numbers:
```
01,02,03,04,05,06,07,08,09,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40
40,39,38,37,36,35,34,33,32,31,30,29,28,27,26,25,24,23,22,21,20,19,18,17,16,15,14,13,12,11,10,09,08,07,06,05,04,03,02,01
...
```

### 2. Alphabet Generator (`alphabet-generator`)

Generates unique randomized sequences of characters from the master alphabet file.

**How it works:**
- Reads 40 characters from `resources/master-alphabet.txt` (lines 1-40)
- Creates random permutations of these characters
- Uses the same normalization logic as the sequence generator to ensure uniqueness
- Stores sequences in a comma-separated format
- Automatically runs bias analysis after generation

**Usage:**
```bash
php bin/alphabet-generator <number_of_sequences> [output_file]
```

**Example:**
```bash
# With custom filename
php bin/alphabet-generator 1000 my-alphabet-sequences.txt
# Creates: output/my-alphabet-sequences.txt and output/my-alphabet-sequences--bias-report-histogram.txt

# With default filename (alphabet-sequences.txt)
php bin/alphabet-generator 1000
# Creates: output/alphabet-sequences.txt and output/alphabet-sequences--bias-report-histogram.txt
```

**Note:** All output files are automatically saved to the `output/` directory. The directory will be created if it doesn't exist.

**Output format:**
Each line contains 40 comma-separated characters:
```
1,2,3,4,5,6,7,8,9,0,@,A,B,C,D,E,F,#,G,H,I,J,K,L,/,M,N,O,P,Q,.,R,S,T,U,V,W,X,Y,Z
Z,Y,X,W,V,U,T,S,R,.,Q,P,O,N,M,/,L,K,J,I,H,G,#,F,E,D,C,B,A,@,0,9,8,7,6,5,4,3,2,1
...
```

**Master Alphabet File:**
The alphabet is read from `resources/master-alphabet.txt`, which should contain exactly 40 characters, one per line (lines 1-40).

### 3. Origin Matrix Generator (`origin-matrix-generator`)

Merges number sequences and alphabet sequences into a combined matrix file with dice roll counts, base 36 numbers, and words from the wordlist.

**How it works:**
1. **Dice Roll Counter**: Generates sequential dice roll combinations from `1,1,1,1,1` to `6,6,6,6,6` (7776 total combinations)
   - Each combination represents 5 dice rolls (each die showing 1-6)
   - Format: comma-separated (e.g., `1,1,1,1,1`, `1,1,1,1,2`, ..., `6,6,6,6,6`)

2. **Base 36 Counter**: Counts from 1 to 7776 in base 36 (0-9, A-Z)
   - Always uses at least 3 characters, padded with leading zeros
   - Examples: `001`, `002`, ..., `00Z`, `010`, ..., `600` (7776 in base 36)

3. **Wordlist Lookup**: Matches each dice roll to a word from `resources/wordlist.txt`
   - Converts comma-separated dice roll (e.g., `1,1,1,1,1`) to non-comma format (`11111`) for lookup
   - If no word is found, the word field is left empty

4. **Merging**: Combines each line from both files in the format:
   ```
   <dice_roll> - <word> - <base36> - <number_sequence> - <alphabet_sequence>
   ```

**Usage:**
```bash
php bin/origin-matrix-generator <sequences_file> <alphabet_sequences_file>
```

**Example:**
```bash
php bin/origin-matrix-generator output/sequences.txt output/alphabet-sequences.txt
```

**Requirements:**
- Both input files must have at least 7776 lines
- The script will error if either file has fewer than 7776 lines
- Input files can be in any location (typically from the `output/` folder)

**Output:**
Creates `output/origin-matrix.txt` with exactly 7776 merged lines.

**Output format:**
```
1,1,1,1,1 - abacus - 001 - 39,04,05,29,03,01,09,06,36,34,38,21,11,37,20,19,17,31,07,24,22,40,10,28,12,32,08,02,15,14,26,25,30,23,27,35,13,16,18,33 - R,Q,X,N,J,M,W,K,2,U,7,V,D,6,5,L,O,I,P,T,E,4,S,9,#,Z,0,1,/,G,H,@,F,Y,3,B,8,.,A,C
1,1,1,1,2 - abdomen - 002 - 15,30,17,03,02,20,13,36,06,32,26,37,40,29,23,22,05,19,14,18,34,35,21,33,38,04,10,11,24,01,28,12,25,31,39,16,09,07,27,08 - W,#,8,6,T,H,.,P,Y,X,0,E,M,/,D,J,F,A,L,7,G,S,R,2,B,9,Q,O,Z,3,V,U,4,@,C,1,5,K,I,N
...
6,6,6,6,6 - zoom - 600 - 24,04,33,14,19,36,27,17,03,23,05,31,29,28,34,16,37,06,21,07,12,01,39,20,08,09,22,40,13,30,11,10,35,02,15,25,32,38,18,26 - D,N,S,P,5,@,T,2,8,#,J,Y,G,E,6,A,7,V,X,C,0,Z,4,W,1,/,O,U,I,Q,M,L,R,.,9,H,3,F,B,K
```

Each line contains:
- **Dice roll**: Sequential combination from 1,1,1,1,1 to 6,6,6,6,6
- **Word**: Word from wordlist.txt corresponding to the dice roll
- **Base 36 count**: Sequential count from 001 to 600 (7776 in base 36)
- **Number sequence**: One line from the sequences file
- **Alphabet sequence**: One line from the alphabet sequences file

### 4. Make Origin Matrix PDF-Ready (`make-origin-matrix-pdf-ready`)

Converts the origin matrix file into a PDF-ready format with special formatting for document generation.

**How it works:**
- Wraps dice roll, base36, and word in asterisks (`*1,1,1,1,1*`, `*001*`, `*word*`)
- Uses em dashes (`–`) as separators between all elements
- Breaks number sequence in the middle (after 20th comma) with backslash: `08, \ 36`
- Adds backslash separators between sequences: `30,31 \ F,3,I`
- Ends each line with backslash and newline
- Adds empty lines between each row for better readability

**Usage:**
```bash
php bin/make-origin-matrix-pdf-ready [origin_matrix_file]
```

**Example:**
```bash
# Use default input file (output/origin-matrix.txt)
php bin/make-origin-matrix-pdf-ready

# Or specify a custom input file
php bin/make-origin-matrix-pdf-ready output/origin-matrix.txt
```

**Output:**
Creates `output/origin-matrix-pdf-ready.txt` with formatted lines.

**Output format:**
```
*1,1,1,1,1* – *001* – *abacus* – 39,04,05,29,03,01,09,06,36,34,38,21,11,37,20,19,17,31,07,24, \ 22,40,10,28,12,32,08,02,15,14,26,25,30,23,27,35,13,16,18,33 \ R,Q,X,N,J,M,W,K,2,U,7,V,D,6,5,L,O,I,P,T,E,4,S,9,#,Z,0,1,/,G,H,@,F,Y,3,B,8,.,A,C \

*1,1,1,1,2* – *002* – *abdomen* – 15,30,17,03,02,20,13,36,06,32,26,37,40,29,23,22,05,19,14,18, \ 34,35,21,33,38,04,10,11,24,01,28,12,25,31,39,16,09,07,27,08 \ W,#,8,6,T,H,.,P,Y,X,0,E,M,/,D,J,F,A,L,7,G,S,R,2,B,9,Q,O,Z,3,V,U,4,@,C,1,5,K,I,N \

...
```

### 5. Bias Analysis Tools

Both generators automatically run bias analysis after creating sequences. You can also run them manually:

**Analyze Number Sequences:**
```bash
# Analyze all numbers (01-40)
php bin/analyze-bias output/sequences.txt all

# Analyze a specific number
php bin/analyze-bias output/sequences.txt 01
```

**Analyze Alphabet Sequences:**
```bash
# Analyze all characters
php bin/analyze-alphabet-bias output/alphabet-sequences.txt all

# Analyze a specific character
php bin/analyze-alphabet-bias output/alphabet-sequences.txt A
```

**Bias Analysis Reports:**
- Creates histogram reports showing position distribution for each number/character
- Highlights positions with significant bias (>20% deviation from expected)
- Includes statistical analysis (chi-square test, standard deviation)
- Saves to: `<filename>--bias-report-histogram.txt` in the same directory as the input file

## Complete Workflow Example

Here's a complete example of generating and merging sequences:

```bash
# 1. Generate number sequences
php bin/sequence-generator 7776 sequences.txt
# Creates: output/sequences.txt and output/sequences--bias-report-histogram.txt

# 2. Generate alphabet sequences
php bin/alphabet-generator 7776 alphabet-sequences.txt
# Creates: output/alphabet-sequences.txt and output/alphabet-sequences--bias-report-histogram.txt

# 3. Generate origin matrix
php bin/origin-matrix-generator output/sequences.txt output/alphabet-sequences.txt
# Creates: output/origin-matrix.txt with 7776 merged lines

# 4. (Optional) Create PDF-ready version
php bin/make-origin-matrix-pdf-ready
# Uses default: output/origin-matrix.txt
# Creates: output/origin-matrix-pdf-ready.txt with formatted lines
```

## Technical Details

### Normalization

Both generators use sequence normalization to ensure uniqueness:
- Rotates each sequence to find its lexicographically smallest rotation
- This ensures that sequences that are rotations of each other are considered duplicates
- Example: `[1,2,3,4]` and `[2,3,4,1]` are normalized to the same sequence

### Uniqueness Guarantee

The generators continue generating sequences until the requested number of unique sequences is reached. If duplicates are found (after normalization), they are discarded and new sequences are generated.

### File Requirements

- **Master Alphabet**: `resources/master-alphabet.txt` must contain exactly 40 characters (one per line, lines 1-40)
- **Wordlist**: `resources/wordlist.txt` should contain 7776 lines with tab-separated dice rolls and words (e.g., `11111	abacus`)
- **Merge Script**: Both input files must have at least 7776 lines
- **Output Directory**: The `output/` directory is automatically created if it doesn't exist. All generated sequence files and bias reports are saved here.

## Project Structure

```
DiceCipher/
├── bin/
│   ├── sequence-generator          # Number sequence generator
│   ├── alphabet-generator         # Alphabet sequence generator
│   ├── origin-matrix-generator    # Generate origin matrix from sequences
│   └── make-origin-matrix-pdf-ready # Convert origin matrix to PDF-ready format
│   ├── analyze-bias               # Analyze number sequence bias
│   └── analyze-alphabet-bias      # Analyze alphabet sequence bias
├── src/
│   ├── TableGenerator/
│   │   ├── RandomSequenceGenerator.php
│   │   ├── RandomAlphabetGenerator.php
│   │   ├── UniqueSequenceGenerator.php
│   │   ├── UniqueAlphabetGenerator.php
│   │   └── SequenceNormalizer.php
│   └── DiceRollCounter.php
├── resources/
│   ├── master-alphabet.txt         # Master alphabet (40 characters)
│   └── wordlist.txt                # Wordlist for dice roll mapping (7776 words)
├── output/                         # Generated sequence files and reports (auto-created)
│   ├── sequences.txt
│   ├── alphabet-sequences.txt
│   └── *--bias-report-histogram.txt
└── composer.json
```

## License

GPL-3.0-or-later

## Author

Árpád Lehel Mátyus - contact@dicecipher.com
