<?php
// API URL
$api_url = "http://localhost:3000/students";

// --------------------
// GET request
// --------------------
$response = file_get_contents($api_url);
$students = json_decode($response, true);

echo "<h2>Students from Express API</h2>";
echo "<ul>";
foreach ($students as $student) {
    echo "<li>ID: {$student['id']}, Name: {$student['name']}, Email: {$student['email']}</li>";
}
echo "</ul>";

// --------------------
// POST request (add new student)
// --------------------
$data = [
    "id" => 4,
    "name" => "Khushi Kapadia",
    "email" => "kk12@gmail.com"
];

$options = [
    "http" => [
        "header"  => "Content-Type: application/json\r\n",
        "method"  => "POST",
        "content" => json_encode($data),
    ]
];

$context  = stream_context_create($options);
$result = file_get_contents($api_url, false, $context);

if ($result === FALSE) { 
    echo "Error posting data."; 
} else {
    echo "<pre>POST Response: " . $result . "</pre>";
}
?>
