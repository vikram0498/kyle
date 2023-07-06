import React from 'react';
import { BrowserRouter as Router, Routes, Route, Link } from 'react-router-dom';

// Import your components for each route
import Home from './component/Home';
import Login from './component/auth/Login';
import Register from './component/auth/Register';
import ForgotPassword from './component/auth/ForgotPassword';
import ResetPassword from './component/auth/ResetPassword';

const RoutesList = () => {
  return (
    <Router>
        <Routes>
            {/* Auth routes */}
            <Route index path="/login" element={<Login />} />
            <Route path="/register" element={<Register />} />
            <Route path="/forget-password" element={<ForgotPassword />} />
            <Route path="/reset-password" element={<ResetPassword />} />
            <Route path="/" element={<Home />}>
            </Route>
        </Routes>
    </Router>
  );
};

export default RoutesList;