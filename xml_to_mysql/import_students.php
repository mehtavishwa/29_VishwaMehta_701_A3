<?php
include 'db.php';

// Load the XML file
$xml = simplexml_load_file("students.xml") or die("Error: Cannot load students.xml");

foreach ($xml->student as $student) {
    $id = (int)$student->id;
    $name = $conn->real_escape_string($student->name);
    $email = $conn->real_escape_string($student->email);
    $age = (int)$student->age;

    $check = $conn->query("SELECT * FROM students WHERE id = $id");

    if ($check->num_rows == 0) {
        $sql = "INSERT INTO students (id, name, email, age) VALUES ($id, '$name', '$email', $age)";
        if ($conn->query($sql)) {
            echo "Inserted: $name <br>";
        } else {
            echo "Error inserting $name: " . $conn->error . "<br>";
        }
    } else {
        echo "Student ID $id already exists. Skipping...<br>";
    }
}

echo "<hr>Data import complete.";
$conn->close();
?>
