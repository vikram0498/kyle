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
const Buyers = () => {
  return (
    <Routes>
      <Route
        path="/verify-and-setpassword/:userId/:token"
        element={<VerifyAndSetPassword />}
      />
      <Route path="/profile-verification" element={<ProfileVerification />} />
      <Route
        path="/buyer-profile"
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
      <Route
        path="/boost-your-profile-purchased"
        element={<Protected Component={BoostYourProfilePurchased} />}
      />
      <Route
        path="/stepper-form"
        element={<Protected Component={MultiStepForm} />}
      />
    </Routes>
  );
};
export default Buyers;
