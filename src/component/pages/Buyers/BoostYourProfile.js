import React, { useState, useEffect } from "react";
import BuyerHeader from "../../partials/Layouts/BuyerHeader";
import { useAuth } from "../../../hooks/useAuth";
import { useNavigate, Link } from "react-router-dom";
import axios from "axios";

import Footer from "../../partials/Layouts/Footer";

const BoostYourProfile = () => {
  const { getTokenData, setLogout } = useAuth();
  const [plans, setPlans] = useState("");
  const fetchBuyerPlans = async () => {
    try {
      const apiUrl = process.env.REACT_APP_API_URL;
      let headers = {
        Accept: "application/json",
        Authorization: "Bearer " + getTokenData().access_token,
      };

      let response = await axios.get(`${apiUrl}get-buyer-plans`, {
        headers: headers,
      });
      console.log(response.data.buyer_plans);
      setPlans(response.data.buyer_plans);
    } catch (error) {}
  };
  useEffect(() => {
    fetchBuyerPlans();
  }, []);
  return (
    <>
      <BuyerHeader />
      <section className="main-section position-relative pt-4 pb-120">
        <div className="container position-relative">
          <div className="back-block">
            <div className="row">
              <div className="col-4 col-sm-4 col-md-4 col-lg-4">
                <Link to="/" className="back">
                  <svg
                    width="16"
                    height="12"
                    viewBox="0 0 16 12"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      d="M15 6H1"
                      stroke="#0A2540"
                      strokeWidth="1.5"
                      strokeLinecap="round"
                      strokeLinejoin="round"
                    />
                    <path
                      d="M5.9 11L1 6L5.9 1"
                      stroke="#0A2540"
                      strokeWidth="1.5"
                      strokeLinecap="round"
                      strokeLinejoin="round"
                    />
                  </svg>
                  Back
                </Link>
              </div>
              <div className="col-7 col-sm-4 col-md-4 col-lg-4 align-self-center">
                <h6 className="center-head text-center mb-0">
                  boost your profile
                </h6>
              </div>
            </div>
          </div>
          <div className="card-box boost-box">
            <h3 className="text-center">Boost Your Profile</h3>
            <p className="text-center">
              Lorem Ipsum is simply dummy text of the printing and typesetting
              industry.
            </p>
            <div className="card-box-inner mt-4">
              {plans.length > 0
                ? plans.map((data, index) => {
                    return (
                      <div className="boost-box-block-item">
                        <div className="boost-headingwithicon">
                          <div className="boost-icon">
                            <img src={data.image_url} />
                          </div>
                          <div className="boost-title">
                            {data.title.toUpperCase()}
                          </div>
                        </div>
                        <div className="postion">
                          position : {data.position}
                        </div>
                        <div className="priceby">
                          $ {data.amount} <span>/{data.type}</span>
                        </div>
                        <div className="notify">
                          <img src="../assets/images/info.svg" />
                          <div className="notifyinfo">{data.description}</div>
                        </div>
                        <div className="purchase-btn">
                          <button type="button" className="btn btn-fill">
                            purchase
                          </button>
                        </div>
                      </div>
                    );
                  })
                : ""}
            </div>
          </div>
        </div>
      </section>
      <Footer />
    </>
  );
};

export default BoostYourProfile;
