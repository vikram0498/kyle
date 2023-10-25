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
import ResultPage from './component/pages/ResultPage';
import EditRequest from './component/partials/Modal/EditRequest';
import SentRequest from './component/partials/Modal/SentRequest';
import Payment from './component/pages/Payment';
import Completion from './component/pages/Completion';
import Cancel from './component/pages/Cancel';
import LastSearchData from './component/pages/LastSearchData';


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
            <Route path="/support" element={<Support/>} />
            {/* add buyer link */}
            <Route path="/add-buyer/:token" element={<CopyAddBuyer />} />


            {/* App routes */}
            
            <Route path="/" element={<Protected Component={Home} />} />
            <Route path="/add-buyer-details" element={<Protected Component={AddBuyerDetails}/>} />
            <Route path="/my-buyers" element={<Protected Component={MyBuyer}/>} />
            <Route path="/last-search-data" element={<Protected Component={LastSearchData}/>} />
            <Route path="/sellers-form" element={<Protected Component={SellerForm}/>} />
            {/* <Route path="/condo" element={<Protected Component={Condo}/>} /> */}
            {/* <Route path="/development" element={<Protected Component={Development}/>} /> */}
            {/* <Route path="/multifamily-residential" element={<Protected Component={MultiFamilyResidential}/>} /> */}
            <Route path="/choose-your-plan" element={<Protected Component={ChooseYourPlan}/>} />
            <Route path="/additional-credits" element={<Protected Component={AdditionalCreadits}/>} />
            <Route path="/admin-message" element={<Protected Component={AdminMessage}/>} />
            <Route path="/admin-request" element={<Protected Component={AdminRequest}/>} />
            <Route path="/my-profile" element={<Protected Component={MyProfile}/>} />
            {/* <Route path="/result-page" element={<Protected Component={ResultPage} type={'result'}/>} /> */}
            <Route path="/payment" element={<Protected Component={Payment}/>} />
            <Route path="/privacy-policy" element={<PrivacyPolicy/>} />
            <Route path="/terms-and-condition" element={<TermCondition/>} />
            
            <Route path="/edit-modal" element={<EditRequest/>} />
            <Route path="/submit-modal" element={<SentRequest/>} />
            <Route path="/completion/:token" element={<Completion/>} />
            <Route path="/cancel" element={<Cancel/>} />
        </Routes>
      </AuthContext.Provider>
    </GoogleOAuthProvider>
  );
};

export default RoutesList;