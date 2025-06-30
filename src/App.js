// App.js
import React, { useState } from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import Homepage from './components/homepage/Homepage';
// import Login from './components/login/Login';
// import Register from './components/register/Register';
import AuthPage from './components/auth/AuthPage';



function App() {
  const [user, setLoginUser] = useState({});

  return (
    <div className="App">
    <Router>
      <Routes>
        <Route path="/" element={user && user._id ? <Navigate to="/homepage" replace={true} /> : <Navigate to="/auth" replace={true} />} />
        <Route path="/homepage" element={<Homepage user={user} />} />
        <Route path="/auth" element={<AuthPage setLoginUser={setLoginUser} />} />
      </Routes>
    </Router>
  </div>
  );
}

export default App;
