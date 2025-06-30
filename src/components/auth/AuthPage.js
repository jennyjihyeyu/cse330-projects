import React from "react";
import Login from "../login/Login";
import Register from "../register/Register";


export default function AuthPage({ setLoginUser }) {
    return (
        <>
        <h1> Login / Register Page </h1>
        Login: <Login setLoginUser={setLoginUser}/>
        <br></br>
        Register: <Register/>
        </>
    )
}