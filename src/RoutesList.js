import React, {useState} from 'react';
import { Routes, Route } from 'react-router-dom';

// Import your components for each route
import Home from './component/Home';
import Login from './component/auth/Login';
import Register from './component/auth/Register';
import ForgotPassword from './component/auth/ForgotPassword';
import ResetPassword from './component/auth/ResetPassword';
import VerifyEmail from './component/auth/VerifyEmail';


import {useAuth} from "./hooks/useAuth";
import AuthContext from "./context/authContext";

const RoutesList = () => {
  
  const {userData} = useAuth();

  // console.log(userData);

  const [authData, setAuthData] = useState({signedIn: userData.signedIn, user: userData.user, access_token: userData.access_token});

  return (      
      <AuthContext.Provider value={{authData, setAuthData }}>
        <Routes>
            {/* Auth routes */}
            <Route index path="/login" element={<Login />} />
            <Route path="/register" element={<Register />} />
            <Route path="/forget-password" element={<ForgotPassword />} />
            <Route path="/reset-password/:token/:hash" element={<ResetPassword />} />
            <Route path="/email/verify/:id/:hash" element={<VerifyEmail />} />

            {/* App routes */}
            <Route path="/" element={<Home />}>
            </Route>
        </Routes>
      </AuthContext.Provider>
  );
};

export default RoutesList;