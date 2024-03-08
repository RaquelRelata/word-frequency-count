<!DOCTYPE html>
<html>
<head>
    <title>Word Frequency Calculator</title>
</head>
<body>

<h2>Word Frequency Calculator</h2>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <textarea name="text" rows="10" cols="50"></textarea><br>
    <label for="sortOrder">Sort Order:</label>
    <select name="sortOrder">
        <option value="asc">Ascending</option>
        <option value="desc">Descending</option>
    </select>
    <br>
    <label for="displayLimit">Display Limit:</label>
    <input type="number" name="displayLimit" value="10" min="1"><br>
    <input type="submit" name="submit" value="Calculate Frequency">
</form>

<?php
// Function to tokenize text and calculate word frequencies, excluding stop words
function calculateWordFrequency($text) {
    // Check if text is provided
    if (empty($text)) {
        return array(); // Return empty array if text is empty
    }
    
    // Define common stop words
    $stopWords = array("the", "and", "in", "of", "a", "to", "is", "it", "that", "this", "To", "for", "or", "if", "we");
    
    // Tokenize text into words (including punctuation)
    $words = str_word_count($text, 1);
    
    // Remove stop words
    $words = array_diff($words, $stopWords);
    
    // Count occurrences of each word
    $wordCount = array_count_values($words);
    
    return $wordCount;
}

// Function to sort word frequencies by frequency
function sortWordFrequency($wordFrequencies, $sortOrder) {
    if ($sortOrder == "asc") {
        asort($wordFrequencies);
    } elseif ($sortOrder == "desc") {
        arsort($wordFrequencies);
    }
    return $wordFrequencies;
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve text from textarea
    $inputText = isset($_POST['text']) ? $_POST['text'] : '';
    
    // Calculate word frequencies, excluding stop words
    $wordFrequencies = calculateWordFrequency($inputText);
    
    // Retrieve sort order from form
    $sortOrder = isset($_POST['sortOrder']) ? $_POST['sortOrder'] : 'asc';
    
    // Sort word frequencies based on sort order
    $sortedWordFrequencies = sortWordFrequency($wordFrequencies, $sortOrder);
    
    // Retrieve display limit from form
    $displayLimit = isset($_POST['displayLimit']) ? intval($_POST['displayLimit']) : 10;
    
    // Display word frequencies with display limit
    echo "<h3>Word Frequencies (excluding common stop words, displaying top $displayLimit):</h3>";
    if (empty($sortedWordFrequencies)) {
        echo "<p>No valid input provided or no words found.</p>";
    } else {
        echo "<table border='1'>";
        echo "<tr><th>Word</th><th>Frequency</th></tr>";
        $counter = 0;
        foreach ($sortedWordFrequencies as $word => $frequency) {
            if ($counter >= $displayLimit) {
                break;
            }
            echo "<tr><td>$word</td><td>$frequency</td></tr>";
            $counter++;
        }
        echo "</table>";
    }
}
?>

</body>
</html>
