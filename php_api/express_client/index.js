const express = require("express");
const axios = require("axios"); // install axios for HTTP requests
const app = express();
app.use(express.json());

const PHP_API_URL = "http://localhost:8081/phpAssignment/php_api/students.php";

// GET /students - fetch from PHP API
app.get("/students", async (req, res) => {
  try {
    const response = await axios.get(PHP_API_URL);
    res.json(response.data);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
});

// POST /students - send new student to PHP API
app.post("/students", async (req, res) => {
  try {
    const response = await axios.post(PHP_API_URL, req.body);
    res.json(response.data);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
});

const PORT = 4000;
app.listen(PORT, () =>
  console.log(`Express client running on http://localhost:${PORT}`)
);
