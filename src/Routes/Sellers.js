import React, { useState,useEffect } from "react";
import { Routes, Route,useLocation  } from "react-router-dom";

// Import your components for each route

import Login from "../component/auth/Login";
import Register from "../component/auth/Sellers/Register";
import ForgotPassword from "../component/auth/Sellers/ForgotPassword";
import ResetPassword from "../component/auth/Sellers/ResetPassword";
import VerifyEmail from "../component/auth/Sellers/VerifyEmail";

import { GoogleOAuthProvider } from "@react-oauth/google";

import { useAuth } from "../hooks/useAuth";
import AuthContext from "../context/authContext";
import Home from "../component/pages/Sellers/Home";
import AddBuyerDetails from "../component/pages/Sellers/AddBuyerDetails";
import MyBuyer from "../component/pages/Sellers/MyBuyers";
import SellerForm from "../component/pages/Sellers/SellerForm";
import ChooseYourPlan from "../component/pages/Sellers/ChooseYourPlan";
import AdditionalCreadits from "../component/pages/Sellers/AdditionalCreadits";
import AdminMessage from "../component/pages/Sellers/AdminMessage";
import AdminRequest from "../component/pages/Sellers/AdminRequest";
import MyProfile from "../component/pages/Sellers/MyProfile";
import PrivacyPolicy from "../component/pages/Sellers/PrivacyPolicy";
import TermCondition from "../component/pages/Sellers/TermCondition";
import Protected from "../util/Protected";
import Support from "../component/pages/Sellers/Support";
import CopyAddBuyer from "../component/pages/Sellers/CopyAddBuyer";
import EditRequest from "../component/partials/Modal/EditRequest";
import SentRequest from "../component/partials/Modal/SentRequest";
import Payment from "../component/pages/Sellers/Payment";
import Completion from "../component/pages/Sellers/Completion";
import Cancel from "../component/pages/Sellers/Cancel";
import LastSearchData from "../component/pages/Sellers/LastSearchData";
import PropertyDealResult from "../component/pages/Sellers/PropertyDealResult";
import PropertyDealDetails from "../component/pages/Sellers/PropertyDealDetails";
import DealNotifications from "../component/pages/Sellers/DealNotifications";
import Message from "../component/pages/Sellers/Message";
import Settings from "../component/pages/Sellers/Settings";
// import GoogleMap from "../component/partials/GoogleMap";
const Seller = () => {
  const { userData, isLogin } = useAuth();
  const googleClientId = process.env.REACT_APP_GOOGLE_CLIENT_ID;
  const [authData, setAuthData] = useState({
    signedIn: userData.signedIn,
    user: userData.user,
    access_token: userData.access_token,
  });
  const location = useLocation();
  useEffect(() => {
    let routeArray = ['/login','/forget-password','/register','/register-buyer'];
    if(routeArray.includes(location.pathname)){
      document.body.classList.add('pb-0');
    }else{
      document.body.classList.remove('pb-0');
    }
  }, [location]);

  return (
    <GoogleOAuthProvider clientId={googleClientId}>
      <AuthContext.Provider value={{ authData, setAuthData }}>
        <Routes>
          {/* Auth routes */}
          <Route index path="/login" element={<Login />} />
          <Route path="/register" element={<Register />} />
          <Route path="/forget-password" element={<ForgotPassword />} />
          <Route
            path="/reset-password/:token/:hash"
            element={<ResetPassword />}
          />
          <Route path="/email/verify/:id/:hash" element={<VerifyEmail />} />
          <Route path="/support" element={<Support />} />
          {/* add buyer link */}
          <Route path="/add-buyer/:token" element={<CopyAddBuyer urlType={'private-buyer'}/>} />
          <Route path="/social-share-add-buyer/:token" element={<CopyAddBuyer urlType={'public-buyer'} />} />
          {/* App routes */}

          <Route path="/" element={<Protected Component={Home} />} />
          <Route
            path="/add-buyer-details"
            element={<Protected Component={AddBuyerDetails} />}
          />
          <Route
            path="/my-buyers"
            element={<Protected Component={MyBuyer} />}
          />
          <Route
            path="/last-search-data"
            element={<Protected Component={LastSearchData} />}
          />
          <Route
            path="/sellers-form"
            element={<Protected Component={SellerForm} />}
          />
          {/* <Route path="/condo" element={<Protected Component={Condo}/>} /> */}
          {/* <Route path="/development" element={<Protected Component={Development}/>} /> */}
          {/* <Route path="/multifamily-residential" element={<Protected Component={MultiFamilyResidential}/>} /> */}
          <Route
            path="/choose-your-plan"
            element={<Protected Component={ChooseYourPlan} />}
          />
          <Route
            path="/additional-credits"
            element={<Protected Component={AdditionalCreadits} />}
          />
          <Route
            path="/admin-message"
            element={<Protected Component={AdminMessage} />}
          />
          <Route
            path="/admin-request"
            element={<Protected Component={AdminRequest} />}
          />
          <Route
            path="/my-profile"
            element={<Protected Component={MyProfile} />}
          />
          <Route
            path="/settings"
            element={<Protected Component={Settings} />}
          />
          {/* <Route path="/result-page" element={<Protected Component={ResultPage} type={'result'}/>} /> */}
          <Route path="/payment" element={<Protected Component={Payment} />} />
          <Route path="/privacy-policy" element={<PrivacyPolicy />} />
          <Route path="/terms-and-condition" element={<TermCondition />} />

          <Route path="/edit-modal" element={<EditRequest />} />
          <Route path="/submit-modal" element={<SentRequest />} />
          <Route path="/completion/:token" element={<Completion />} />
          <Route path="/cancel" element={<Cancel />} />
          <Route path="/property-deal-result" element={<PropertyDealResult />} />
          <Route path="/property-deal-details/:id" element={<PropertyDealDetails />} />
          {/* <Route path="/deal-notifications" element={<DealNotifications />} /> */}
          <Route path="/message" element={<Protected Component={Message} />} />

          {/* <Route path="/google-api" element={<GoogleMap />} /> */}
        </Routes>
      </AuthContext.Provider>
    </GoogleOAuthProvider>
  );
};

export default Seller;
