<?php
// Display PHP upload configuration
echo "<h2>PHP Upload Configuration</h2>";
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Setting</th><th>Value</th></tr>";
echo "<tr><td>upload_max_filesize</td><td>" . ini_get('upload_max_filesize') . "</td></tr>";
echo "<tr><td>post_max_size</td><td>" . ini_get('post_max_size') . "</td></tr>";
echo "<tr><td>max_file_uploads</td><td>" . ini_get('max_file_uploads') . "</td></tr>";
echo "<tr><td>memory_limit</td><td>" . ini_get('memory_limit') . "</td></tr>";
echo "<tr><td>max_execution_time</td><td>" . ini_get('max_execution_time') . " seconds</td></tr>";
echo "<tr><td>max_input_time</td><td>" . ini_get('max_input_time') . " seconds</td></tr>";
echo "</table>";

echo "<br><h3>Summary:</h3>";
echo "<ul>";
echo "<li><strong>Maximum file size per upload:</strong> " . ini_get('upload_max_filesize') . "</li>";
echo "<li><strong>Maximum POST data size:</strong> " . ini_get('post_max_size') . "</li>";
echo "<li><strong>Maximum number of files per upload:</strong> " . ini_get('max_file_uploads') . "</li>";
echo "</ul>";

echo "<p><em>Note: The actual upload limit is the smaller of upload_max_filesize and post_max_size.</em></p>";
?>
