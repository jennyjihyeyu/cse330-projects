import React, { useState } from 'react';
import axios from 'axios';

const Register = () => {
    const [user, setUser] = useState({ name: '', email: '', password: '' });
    const [message, setMessage] = useState('');

    const handleChange = e => {
        const { name, value } = e.target;
        setUser({...user, [name]: value});
    };

    const handleRegister = () => {
        if (!user.name || !user.email || !user.password) {
            setMessage("All fields must be filled.");
            return;
        }
        axios.post("http://localhost:6969/Register", user)
            .then(res => {
                if (res.headers['content-type'].includes('application/json')) {
                    setMessage(res.data.message);
                } else {
                    throw new Error('Received non-JSON response from server.');
                }
            })
            .catch(error => {
                console.error("Registration failed:", error);
                setMessage('Failed to register. Please try again later.');
            });
    };

    return (
        <div>
            <input type="text" name="name" placeholder="Name" value={user.name} onChange={handleChange} />
            <input type="email" name="email" placeholder="Email" value={user.email} onChange={handleChange} />
            <input type="password" name="password" placeholder="Password" value={user.password} onChange={handleChange} />
            <button onClick={handleRegister}>Register</button>
            {message && <p>{message}</p>}
        </div>
    );
};

export default Register;
