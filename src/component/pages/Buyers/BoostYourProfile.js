import React, { useState, useEffect } from "react";
import BuyerHeader from "../../partials/Layouts/BuyerHeader";
import { useAuth } from "../../../hooks/useAuth";
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
              <div className="col-12 col-lg-12">
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
