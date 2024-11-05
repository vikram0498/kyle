  import React, { useEffect, useState } from "react";
  import Header from "../../partials/Layouts/Header";
  import Footer from "../../partials/Layouts/Footer";
  import { Col, Container, Image, Row } from 'react-bootstrap';
  import { useAuth } from "../../../hooks/useAuth";
  import { Link } from "react-router-dom";
  import axios from "axios";

  const Settings = () => {
    const apiUrl = process.env.REACT_APP_API_URL;
    const {getTokenData} = useAuth();
    const [notificationData, setNotificationData] = useState([]);
    useEffect(()=>{
      const fetchUserSetting = async () => {
        try {
            let headers = {
              Accept: "application/json",
              Authorization: "Bearer " + getTokenData().access_token,
              "auth-token": getTokenData().access_token,
            }
            let response = await axios.get(`${apiUrl}notification-settings/`, { headers: headers });
            setNotificationData(response.data.data);
        } catch (error) {
          
        }
      }
      fetchUserSetting();
    },[])
    console.log('notificationData', notificationData)
    return (
      <>
        <Header />
        <section className="main-section position-relative pt-4 pb-120">
          <Container className='position-relative'>
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
                          Settings
                      </h6>
                  </div>
              </div>
            </div>
            <div className='card-box column_bg_space'>
                <div className='deal_column d-none d-md-block'>
                  <Row className='align-items-center'>
                    <Col lg={8} md={6}>
                      <div className='deal_left_column'>
                          <div className='list_icon'>
                              {/* <Image src='/assets/images/home_buy.svg' alt='' /> */}
                          </div>
                          <div className='pro_details'>
                              <h3 className="mb-0">Settings</h3>
                          </div>
                      </div>
                    </Col>
                    <Col lg={2} md={3}>
                      <div class=""><label><span>Push Notifications</span></label></div>
                    </Col>
                    <Col lg={2} md={3}>
                      <div class=""><label><span>Email Notifications</span></label></div>
                    </Col>
                  </Row>
                </div>
                { notificationData.length > 0 ? (
                  notificationData.map((data, index) => (
                    <div key={index} className="deal_column settings">
                      <Row className="align-items-center">
                        <Col lg={8} md={6}>
                          <div className="deal_left_column">
                            <div className="list_icon">
                              <Image src="/assets/images/home_buy.svg" alt="" />
                            </div>
                            <div className="pro_details">
                              <h3 className="mb-0">{data.display_name}</h3>
                            </div>
                          </div>
                        </Col>
                        <Col lg={2} md={3}>
                          <div className="buyer_seller_toggle" data-notifications="Push Notifications">
                            <input type="checkbox" name={data.key} defaultChecked={data.value} />
                            <label>
                              <span>Disable</span><span>Enable</span>
                            </label>
                          </div>
                        </Col>
                        <Col lg={2} md={3}>
                          <div className="buyer_seller_toggle" data-notifications="Email Notifications">
                            <input type="checkbox" />
                            <label>
                              <span>Disable</span><span>Enable</span>
                            </label>
                          </div>
                        </Col>
                      </Row>
                    </div>
                  ))
                ) : (
                  <p>No notifications found.</p>
            )}
            </div>
          </Container>
        </section>
        <Footer />
      </>
    );
  };
  export default Settings;
