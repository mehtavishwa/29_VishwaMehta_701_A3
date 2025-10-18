<?php
include 'db.php';

// Query all students from database
$result = $conn->query("SELECT * FROM students");

if ($result->num_rows > 0) {
    // Create a new XML document
    $xml = new DOMDocument("1.0", "UTF-8");
    $xml->formatOutput = true;

    // Root element
    $students = $xml->createElement("students");
    $xml->appendChild($students);

    // Loop through database rows
    while ($row = $result->fetch_assoc()) {
        $student = $xml->createElement("student");

        $id = $xml->createElement("id", $row['id']);
        $name = $xml->createElement("name", $row['name']);
        $email = $xml->createElement("email", $row['email']);
        $age = $xml->createElement("age", $row['age']);

        $student->appendChild($id);
        $student->appendChild($name);
        $student->appendChild($email);
        $student->appendChild($age);

        $students->appendChild($student);
    }

    // Save XML to file
    $xml->save("new_students.xml");

    echo "<b>Data exported successfully!</b><br>";
    echo "File created: new_students.xml<br>";
} else {
    echo "No records found in students table.";
}

$conn->close();
?>
