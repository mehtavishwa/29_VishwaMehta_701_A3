<?php
// Function to list files recursively
function listFilesRecursively($dir)
{
    // Scan current directory
    $items = scandir($dir);
    echo "<ul>";
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue; // Skip current and parent
        $path = $dir . "/" . $item;
        if (is_dir($path)) {
            echo "<li><strong>[Folder]</strong> $item</li>";
            // Recursive call for subfolder
            listFilesRecursively($path);
        } else {
            echo "<li>$item</li>";
        }
    }
    echo "</ul>";
}

// Directory to scan (change this to your folder)
$directory = "C:/xampp/htdocs/phpAssignment/shoppingcart";

echo "<h2>Files in Directory (including subfolders): $directory</h2>";
listFilesRecursively($directory);
?>
