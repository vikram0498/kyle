import React from "react";
import { Routes, Route } from "react-router-dom";
import VerifyAndSetPassword from "../component/auth/Buyers/VerifyAndSetPassword";
import BuyerProfile from "../component/pages/Buyers/BuyerProfile";
import ProfileVerification from "../component/pages/Buyers/ProfileVerification";
import EditBuyerProfile from "../component/pages/Buyers/EditBuyerProfile";
import Protected from "../util/Protected";
import BoostYourProfile from "../component/pages/Buyers/BoostYourProfile";
import BoostYourProfilePurchased from "../component/pages/Buyers/BoostYourProfilePurchased";
import MultiStepForm from "../component/pages/Buyers/MultiStepForm";
import PaymentConfirm from "../component/pages/Buyers/PaymentConfirm";
import RegisterBuyer from "../component/auth/Buyers/RegisterBuyer";
import DealNotifications from "../component/pages/Sellers/DealNotifications";
import Message from "../component/pages/Sellers/Message";
const Buyers = () => {
  return (
    <Routes>
      <Route path="/register-buyer/:token?" element={<RegisterBuyer/>} />
      <Route
        path="/verify-and-setpassword/:userId/:token"
        element={<VerifyAndSetPassword />}
      />
      <Route path="/profile-verification" element={<ProfileVerification />} />
      <Route
        path="/buyer-profile/:token?"
        element={<Protected Component={BuyerProfile} />}
      />

      <Route
        path="/edit-profile"
        element={<Protected Component={EditBuyerProfile} />}
      />
      <Route
        path="/boost-your-profile"
        element={<Protected Component={BoostYourProfile} />}
      />
      <Route path="/payment-confirm/:token" element={<PaymentConfirm/>} />
      <Route
        path="/boost-your-profile-purchased"
        element={<Protected Component={BoostYourProfilePurchased} />}
      />
       <Route
        path="/deal-notifications"
        element={<Protected Component={DealNotifications} />}
      />
      {/* <Route
        path="/stepper-form"
        element={<Protected Component={MultiStepForm} />}
      /> */}
    </Routes>
  );
};
export default Buyers;
