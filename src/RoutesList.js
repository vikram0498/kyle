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
import ChooseYourPlan from './component/pages/ChooseYourPlan';
import AdditionalCreadits from './component/pages/AdditionalCreadits';
import AdminMessage from './component/pages/AdminMessage';
import AdminRequest from './component/pages/AdminRequest';
import MyProfile from './component/pages/MyProfile';
import PrivacyPolicy from './component/pages/PrivacyPolicy';
import TermCondition from './component/pages/TermCondition';
import Protected from './util/Protected';
import Support from './component/pages/Support';
import CopyAddBuyer from './component/pages/CopyAddBuyer';


const RoutesList = () => {
  const {userData,isLogin} = useAuth();
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

            {/* add buyer link */}
            <Route path="/add-buyer/:token" element={<CopyAddBuyer />} />


            {/* App routes */}
            
            <Route path="/" element={<Protected Component={Home} />} />
            <Route path="/add-buyer-details" element={<Protected Component={AddBuyerDetails}/>} />
            <Route path="/my-buyers" element={<Protected Component={MyBuyer}/>} />
            <Route path="/sellers-form" element={<Protected Component={SellerForm}/>} />
            {/* <Route path="/condo" element={<Protected Component={Condo}/>} /> */}
            {/* <Route path="/development" element={<Protected Component={Development}/>} /> */}
            {/* <Route path="/multifamily-residential" element={<Protected Component={MultiFamilyResidential}/>} /> */}
            <Route path="/choose-your-plan" element={<Protected Component={ChooseYourPlan}/>} />
            <Route path="/additional-credits" element={<Protected Component={AdditionalCreadits}/>} />
            <Route path="/admin-message" element={<Protected Component={AdminMessage}/>} />
            <Route path="/admin-request" element={<Protected Component={AdminRequest}/>} />
            <Route path="/my-profile" element={<Protected Component={MyProfile}/>} />
            <Route path="/privacy-policy" element={<PrivacyPolicy/>} />
            <Route path="/terms-and-condition" element={<TermCondition/>} />
            <Route path="/support" element={<Support/>} />
        </Routes>
      </AuthContext.Provider>
      </GoogleOAuthProvider>
  );
};

export default RoutesList;