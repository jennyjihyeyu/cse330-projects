import React, { useState } from 'react';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';

const Login = ({ setLoginUser }) => {
    const navigate = useNavigate();
    const [user, setUser] = useState({ email: "", password: "" });
    const [setError] = useState('');

    const handleChange = e => {
        const { name, value } = e.target;
        setUser({
            ...user,
            [name]: value
        });
    };

    const login = () => {
      if (!user.email || !user.password) {
        setError("Please fill in all fields.");
        return;
    }
        axios.post("http://localhost:6969/Login", user)
          .then(res => {
            if (res.data.message === "Login successful") {
              setLoginUser(res.data.user);
              navigate("/homepage"); 
            } else {
              alert(res.data.message);
            }
          })
          .catch(error => {
            console.error("Login failed:", error);
            alert("Login failed, please try again.");
          });
      };

    return (
        <div>
            <input type="email" name="email" placeholder="Email" value={user.email} onChange={handleChange} />
            <input type="password" name="password" placeholder="Password" value={user.password} onChange={handleChange} />
            <button onClick={login}>Login</button>
        </div>
    );
};

export default Login;
