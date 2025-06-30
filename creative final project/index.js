//index.js
import express from "express";
import cors from "cors";
import mongoose from "mongoose";
import bcrypt from "bcrypt";

const app = express();
app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(cors());


mongoose.connect("mongodb+srv://selina:qwaszx@cluster0.vduftu5.mongodb.net/auth", {
  useNewUrlParser: true,
  useUnifiedTopology: true
}).then(() => {
  console.log("Connected to DB"); 
}).catch((err) => {
  console.error("Connection failed", err); 
});

//course schema
const courseSchema = new mongoose.Schema({
    courseName: String,
    numberOfCredits: Number,
    grade: String,
    semester: String,
    year: Number
});


//user schema 
const userSchema = new mongoose.Schema({
    name: String,
    email: String,
    password: String,
    courses: [courseSchema]
})


const User = new mongoose.model("User", userSchema)

//routes routes
app.post("/Login", async (req, res) => {
    res.setHeader('Content-Type', 'application/json');
    const { email, password } = req.body;
    try {
        const user = await User.findOne({ email: email });
        if (user) {
            const match = await bcrypt.compare(password, user.password);
            if (match) {
                res.send({ message: "Login successful", user: user });
            } else {
                res.send({ message: "Wrong credentials" });
            }
        } else {
            res.send("User not registered");
        }
    } catch (err) {
        console.error(err);
        res.status(500).send("Internal Server Error");
    }
});


app.post("/Register", async (req, res) => {
    res.setHeader('Content-Type', 'application/json');
    const { name, email, password } = req.body;
    try {
        const existingUser = await User.findOne({ email: email });
        if (existingUser) {
            res.send({ message: "User already exists" });
        } else {
            const hashedPassword = await bcrypt.hash(password, 10);
            const newUser = new User({ name, email, password: hashedPassword });
            await newUser.save();
            res.send({ message: "Registration successful" });
        }
    } catch (err) {
        console.error(err);
        res.status(500).send("Internal Server Error");
    }
});

app.post("/addCourse", async (req, res) => {
    console.log(req.body);  // Log to see what data is coming in
    const { userId, courseName, numberOfCredits, grade, semester, year } = req.body;
    try {
        const user = await User.findById(userId);
        if (!user) {
            return res.status(404).send({ message: "User not found" });
        }
        user.courses.push({ courseName, numberOfCredits, grade, semester, year });
        await user.save();
        res.send({ message: "Course added successfully" });
    } catch (err) {
        console.error(err);
        res.status(500).send("Internal Server Error");
    }
});

app.get("/courses", async (req, res) => {
    const { userId } = req.query; // Assuming you send userId as a query parameter
    try {
        const user = await User.findById(userId).populate('courses');
        if (!user) {
            return res.status(404).send({ message: "User not found" });
        }
        res.send(user.courses); // Send only the courses
    } catch (err) {
        console.error(err);
        res.status(500).send({ message: "Internal Server Error" });
    }
});

app.get("/getClassmates", async (req, res) => {
    const { courseName, year, semester } = req.query;

    try {
        const users = await User.find({
            "courses.courseName": courseName,
            "courses.year": Number(year),
            "courses.semester": semester
        }, 'name email courses.$');

        if (users.length === 0) {
            return res.status(404).json({ message: "No classmates found." });
        }

        res.json(users);
    } catch (error) {
        console.error(error);
        res.status(500).send("Internal Server Error");
    }
});

app.get('/getAverageGrade', async (req, res) => {
    const { courseName } = req.query;
    try {
        const users = await User.find({ "courses.courseName": courseName }, 'courses.$');
        let totalGrade = 0;
        let count = 0;

        users.forEach(user => {
            user.courses.forEach(course => {
                if (course.courseName === courseName) {
                    totalGrade += convertGradeToPoints(course.grade);
                    count++;
                }
            });
        });

        const average = count > 0 ? (totalGrade / count).toFixed(2) : 0;
        res.json({ average });
    } catch (error) {
        console.error(error);
        res.status(500).send("Internal Server Error");
    }
});

function convertGradeToPoints(grade) {
    const gradeToPoint = {
        'A': 4.0, 'A-': 3.7, 'B+': 3.3, 'B': 3.0, 'B-': 2.7,
        'C+': 2.3, 'C': 2.0, 'C-': 1.7, 'D+': 1.3, 'D': 1.0, 'D-': 0.7, 'F': 0.0
    };
    return gradeToPoint[grade] || 0; // Default to 0 if grade is not found
}

app.listen(6969, () => {
    console.log("Server started on port 6969");
});