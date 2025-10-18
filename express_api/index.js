const express = require("express");
const cors = require("cors");

const app = express();
app.use(cors()); // allow requests from PHP client
app.use(express.json());

// Sample students data
const students = [
  { id: 1, name: "Bhavesh Jariwala", email: "bhavesh@gmail.com" },
  { id: 2, name: "Rajesh Patel", email: "rp08@gmail.com" },
  { id: 3, name: "Kanishka Shah", email: "kshah@gmail.com" },
];

// GET endpoint to fetch all students
app.get("/students", (req, res) => {
  res.json(students);
});

// POST endpoint to add a student
app.post("/students", (req, res) => {
  const student = req.body;
  students.push(student);
  res.json({ message: "Student added successfully", student });
});

// Start server
const PORT = 3000;
app.listen(PORT, () =>
  console.log(`Express API running on http://localhost:${PORT}`)
);
