import React, {useState} from 'react';
import { Routes, Route } from 'react-router-dom';

// Import your components for each route

import Login from './component/auth/Login';
import Register from './component/auth/Register';
import ForgotPassword from './component/auth/ForgotPassword';
import ResetPassword from './component/auth/ResetPassword';
import VerifyEmail from './component/auth/VerifyEmail';
import { GoogleOAuthProvider } from '@react-oauth/google';

import {useAuth} from "./hooks/useAuth";
import AuthContext from "./context/authContext";
import Home from './component/pages/Home';
import AddBuyerDetails from './component/pages/AddBuyerDetails';
import MyBuyer from './component/pages/MyBuyers';
import SellerForm from './component/pages/SellerForm';
import Development from './component/pages/Development';
import MultiFamilyResidential from './component/pages/MultiFamilyResidential';
import Condo from './component/pages/Condo';
import ChooseYourPlan from './component/pages/ChooseYourPlan';
import AdditionalCreadits from './component/pages/AdditionalCreadits';
import AdminMessage from './component/pages/AdminMessage';
import AdminRequest from './component/pages/AdminRequest';
import MyProfile from './component/pages/MyProfile';
const RoutesList = () => {
  
  const {userData,isLogin} = useAuth();

  // console.log(userData);

  const [authData, setAuthData] = useState({signedIn: userData.signedIn, user: userData.user, access_token: userData.access_token});

  return (      
    <GoogleOAuthProvider clientId="228707625591-afemor5re8dlrdjfvb0fht68g0apfjuv.apps.googleusercontent.com">
      <AuthContext.Provider value={{authData, setAuthData }}>
        <Routes>
            {/* Auth routes */}
            <Route index path="/login" element={<Login />} />
            <Route path="/register" element={<Register />} />
            <Route path="/forget-password" element={<ForgotPassword />} />
            <Route path="/reset-password/:token/:hash" element={<ResetPassword />} />
            <Route path="/email/verify/:id/:hash" element={<VerifyEmail />} />

            {/* App routes */}
            <Route path="/" element={<Home />} />
            <Route path="/add-buyer-details" element={<AddBuyerDetails />} />
            <Route path="/my-buyers" element={<MyBuyer />} />
            <Route path="/sellers-form" element={<SellerForm/>} />
            <Route path="/condo" element={<Condo/>} />
            <Route path="/development" element={<Development/>} />
            <Route path="/multifamily-residential" element={<MultiFamilyResidential/>} />
            <Route path="/choose-your-plan" element={<ChooseYourPlan/>} />
            <Route path="/additional-credits" element={<AdditionalCreadits/>} />
            <Route path="/admin-message" element={<AdminMessage/>} />
            <Route path="/admin-request" element={<AdminRequest/>} />
            <Route path="/my-profile" element={<MyProfile/>} />
        </Routes>
      </AuthContext.Provider>
      </GoogleOAuthProvider>
  );
};

export default RoutesList;