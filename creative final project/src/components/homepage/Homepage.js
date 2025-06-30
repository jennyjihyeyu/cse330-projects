import React, { useState, useEffect, useCallback } from 'react';
import axios from 'axios';
import { Line } from 'react-chartjs-2';
import 'chart.js/auto';

const Homepage = ({ user }) => {
    const [error, setError] = useState('');

    //course data 
    const [courseData, setCourseData] = useState({
        courseName: '',
        numberOfCredits: '',
        grade: '',
        semester: '',
        year: ''
    });
    const [courses, setCourses] = useState([]); 

    //filter year and semester 
    const [filter, setFilter] = useState({ year: '', semester: '' }); 
   
    //view courses 
    const [showCourses, setShowCourses] = useState(false);
    const [totalCredits, setTotalCredits] = useState(0); 
    const [showTotalCredits, setShowTotalCredits] = useState(false);

    //average grade for class 
    const [courseNameForAverage, setCourseNameForAverage] = useState('');
    const [averageGrade, setAverageGrade] = useState(null);
   
    //for gpa graph
    const [chartData, setChartData] = useState({
        labels: [],
        datasets: [] 
    });

    //for classmate search 
    const [classmateSearch, setClassmateSearch] = useState({
        courseName: '',
        year: '',
        semester: ''
    });
    const [classmates, setClassmates] = useState([]);

    // for GPA calculation
    const [selectedYear, setSelectedYear] = useState('');
    const [selectedSemester, setSelectedSemester] = useState('');
    const [GPA, setGPA] = useState(null);

    const gradeToPoint = {
        'A': 4.0,
        'A-': 3.7,
        'B+': 3.3,
        'B': 3.0,
        'B-': 2.7,
        'C+': 2.3,
        'C': 2.0,
        'C-': 1.7,
        'D+': 1.3,
        'D': 1.0,
        'D-': 0.7,
        'F': 0.0
    };

    const fetchCourses = useCallback(() => {
        axios.get(`http://localhost:6969/courses?userId=${user._id}`)
            .then(response => {
                setCourses(response.data);
                setError('');
            })
            .catch(error => {
                console.error("Error fetching courses:", error);
                setError("Failed to fetch courses.");
                setCourses([]); 
            });
    }, [user._id]);

    useEffect(() => {
        fetchCourses();
    }, [fetchCourses]);

    const calculateTotalCredits = () => {
        const total = courses.reduce((acc, course) => acc + course.numberOfCredits, 0);
        setTotalCredits(total);
        setShowTotalCredits(true);
    };

    const graduationProgress = (totalCredits / 120 * 100).toFixed(2); 
    const creditLeft = 120 - totalCredits; 

    const handleChange = (event) => {
        const { name, value } = event.target;
        setCourseData({ ...courseData, [name]: value });
    };

    const handleFilterChange = (event) => {
        const { name, value } = event.target;
        setFilter({ ...filter, [name]: value });
    };

    const addCourse = () => {
        if (!courseData.courseName || !courseData.numberOfCredits || !courseData.grade || !courseData.semester || !courseData.year) {
            setError("All fields must be filled.");
            alert("All fields must be filled.");
            return;
        }
        const payload = {
            userId: user._id,
            courseName: courseData.courseName,
            numberOfCredits: parseInt(courseData.numberOfCredits),
            grade: courseData.grade,
            semester: courseData.semester,
            year: parseInt(courseData.year)
        };
        
        axios.post("http://localhost:6969/addCourse", payload)
            .then(response => {
                alert(response.data.message);
                fetchCourses();
            })
            .catch(error => {
                console.error("Error adding course:", error);
                alert("Failed to add course.");
            });
    };

    const filteredCourses = courses.filter(course => 
        course.year.toString() === filter.year && course.semester === filter.semester);


    const calculateGPA = () => {
        const relevantCourses = courses.filter(course => course.year.toString() === selectedYear && course.semester === selectedSemester);
        const totalPoints = relevantCourses.reduce((acc, course) => acc + (gradeToPoint[course.grade] * course.numberOfCredits), 0);
        const total = relevantCourses.reduce((acc, course) => acc + course.numberOfCredits, 0);
        const gpa = total > 0 ? (totalPoints / total).toFixed(2) : 0;
        setGPA(gpa);
    };

    const processChartData = () => {
        const semesterGPAData = {};
        courses.forEach(course => {
            const semesterKey = `${course.semester} ${course.year}`;
            if (!semesterGPAData[semesterKey]) {
                semesterGPAData[semesterKey] = {
                    totalPoints: 0,
                    totalCredits: 0
                };
            }
            semesterGPAData[semesterKey].totalPoints += gradeToPoint[course.grade] * course.numberOfCredits;
            semesterGPAData[semesterKey].totalCredits += course.numberOfCredits;
        });

        const labels = [];
        const data = [];
        Object.keys(semesterGPAData).sort().forEach(key => {
            labels.push(key);
            const { totalPoints, totalCredits } = semesterGPAData[key];
            const gpa = totalCredits > 0 ? (totalPoints / totalCredits).toFixed(2) : 0;
            data.push(gpa);
        });

        setChartData({
            labels,
            datasets: [{
                label: 'GPA by Semester',
                data,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        });
    };

    const handleClassmateSearchChange = (event) => {
        const { name, value } = event.target;
        setClassmateSearch(prev => ({ ...prev, [name]: value }));
    };

    const viewClassmates = () => {
        const { courseName, year, semester } = classmateSearch;
        axios.get(`http://localhost:6969/getClassmates?courseName=${courseName}&year=${year}&semester=${semester}`)
            .then(response => {
                setClassmates(response.data);
                setError('');
            })
            .catch(error => {
                console.error("Error fetching classmates:", error);
                setError("Failed to fetch classmates.");
                setClassmates([]);
            });
    };

    const viewAverageGrade = () => {
        axios.get(`http://localhost:6969/getAverageGrade?courseName=${courseNameForAverage}`)
            .then(response => {
                setAverageGrade(response.data.average);  // Assuming the backend sends back an object with an 'average' key
            })
            .catch(error => {
                console.error("Error fetching average grade:", error);
                alert("Failed to fetch average grade.");
            });
    };


    return (
        <div>
            <h1>Welcome, {user.name}</h1>
            <p>Your email: {user.email}</p>

            <h2>Add a Course</h2>
            <input type="text" name="courseName" value={courseData.courseName} onChange={handleChange} placeholder="Course Name" />
            <input type="number" name="numberOfCredits" value={courseData.numberOfCredits} onChange={handleChange} placeholder="Number of Credits" />
            <select name="grade" value={courseData.grade} onChange={handleChange}>
                <option value="">Select Grade</option>
                <option value="A">A</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B">B</option>
                <option value="B-">B-</option>
                <option value="C+">C+</option>
                <option value="C">C</option>
                <option value="C-">C-</option>
                <option value="D+">D+</option>
                <option value="D">D</option>
                <option value="D-">D-</option>
                <option value="F">F</option>
            </select>
            <select name="semester" value={courseData.semester} onChange={handleChange}>
                <option value="">Select Semester</option>
                <option value="Fall">Fall</option>
                <option value="Spring">Spring</option>
            </select>
            <input type="number" name="year" value={courseData.year} onChange={handleChange} placeholder="Year" />
            <button onClick={addCourse}>Add Course</button>

            <h2>View Courses</h2>
            <button onClick={() => setShowCourses(!showCourses)}>View Courses</button>
            {showCourses && (
                <div>
                    <input type="number" name="year" placeholder="Year" value={filter.year} onChange={handleFilterChange} />
                    <select name="semester" value={filter.semester} onChange={handleFilterChange}>
                        <option value="">Select Semester</option>
                        <option value="Fall">Fall</option>
                        <option value="Spring">Spring</option>
                    </select>
                    <ul>
                        {filteredCourses.map(course => (
                            <li key={course._id}>
                                {course.courseName} - {course.numberOfCredits} credits - Grade: {course.grade}
                            </li>
                        ))}
                    </ul>
                </div>
            )}

            <h2>Graduation Progress</h2>
            <button onClick={calculateTotalCredits}>View Progress</button>
            {showTotalCredits && (
                <div>
                    <p>Required Total Credit: 120</p>
                    <p>Total Credits Taken: {totalCredits}</p>
                    <p>Credits Left: {creditLeft}</p>
                    <p>Graduation Progress: {graduationProgress}% completed</p>
                </div>
            )}

            <h2>Calculate GPA</h2>
            <select value={selectedSemester} onChange={e => setSelectedSemester(e.target.value)}>
                <option value="">Select Semester</option>
                <option value="Fall">Fall</option>
                <option value="Spring">Spring</option>
            </select>
            <input type="number" placeholder="Year" value={selectedYear} onChange={e => setSelectedYear(e.target.value)} />
            <button onClick={calculateGPA}>Calculate GPA</button>
            {GPA !== null && <h3>GPA for {selectedSemester} {selectedYear}: {GPA}</h3>}

            <h2>GPA Graph</h2>
            <button onClick={() => processChartData(courses)}>View Graph</button>
            {chartData.labels.length > 0 && (
            <div style={{ width: '600px', height: '400px' }}>
                <Line data={chartData} options={{ responsive: true }} />
            </div> )}

            <h2>View Classmates</h2>
            <div>
                <input
                    type="text"
                    name="courseName"
                    value={classmateSearch.courseName}
                    onChange={handleClassmateSearchChange}
                    placeholder="Course Name"
                />
                <input
                    type="number"
                    name="year"
                    value={classmateSearch.year}
                    onChange={handleClassmateSearchChange}
                    placeholder="Year"
                />
                <select
                    name="semester"
                    value={classmateSearch.semester}
                    onChange={handleClassmateSearchChange}
                >
                    <option value="">Select Semester</option>
                    <option value="Fall">Fall</option>
                    <option value="Spring">Spring</option>
                </select>
                <button onClick={viewClassmates}>Search</button>
            </div>

            {classmates.length > 0 && (
                <ul>
                    {classmates.map((mate, index) => (
                        <li key={index}>{mate.name} - {mate.email}</li>
                    ))}
                </ul>
            )}

            <h2>View Class Average</h2>
            <input
                type="text"
                value={courseNameForAverage}
                onChange={(e) => setCourseNameForAverage(e.target.value)}
                placeholder="Enter course name"
            />
            <button onClick={viewAverageGrade}>View Average Grade</button>
            {averageGrade && <p>Average Grade: {averageGrade}</p>}
        </div>
    );
};

export default Homepage;