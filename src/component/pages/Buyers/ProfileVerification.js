import React from "react";
import BuyerHeader from "../../partials/Layouts/BuyerHeader";
import HorizontalLinearStepper from "./StepperForm/HorizontalLinearStepper";
import Footer from "../../partials/Layouts/Footer";
const ProfileVerification = () => {
  return (
    <>
      <BuyerHeader />
      <section className="main-section position-relative pt-4 pb-120">
        <div className="container position-relative">
          {" "}
          <HorizontalLinearStepper />
        </div>
      </section>
      <Footer />
    </>
  );
};
export default ProfileVerification;
