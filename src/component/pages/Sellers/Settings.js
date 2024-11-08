  import React, { useEffect, useState } from "react";
  import Header from "../../partials/Layouts/Header";
  import Footer from "../../partials/Layouts/Footer";
  import { Col, Container, Image, Row } from 'react-bootstrap';
  import { useAuth } from "../../../hooks/useAuth";
  import { Link } from "react-router-dom";
  import axios from "axios";
  import BuyerHeader from "../../partials/Layouts/BuyerHeader";
  import { toast } from "react-toastify";

  const Settings = () => {
    const apiUrl = process.env.REACT_APP_API_URL;
    const { getTokenData, getLocalStorageUserdata, setLogout } = useAuth();
    const [notificationData, setNotificationData] = useState([]);
    const [userRole, setUserRole] = useState(0);
    const [isLoader, setIsLoader] = useState(false);

    const handleNotificationStatus = async (notificationKey, type, isEnabled) => {
      try {
          const headers = {
              Accept: "application/json",
              Authorization: `Bearer ${getTokenData().access_token}`,
              "auth-token": getTokenData().access_token,
          };

          // Constructing formData with the desired nested structure
          const formData = {
              [notificationKey]: {
                  [type]: isEnabled
              }
          };

          // Send formData to the server
          const response = await axios.post(
              `${apiUrl}notification-settings/update`,
              formData,
              { headers }
          );
          toast.success(response.data.message, {
            position: toast.POSITION.TOP_RIGHT,
          });
          // Update local notification data state after success
          setNotificationData(prevData => 
              prevData.map(data => data.key === notificationKey ? { ...data,[type]: { ...data[type], enabled: isEnabled }}: data)
          );

      } catch (error) {
          console.error("Error updating notification status:", error);
      }
  };
  
    useEffect(()=>{
      const fetchUserSetting = async () => {
        try {
            setIsLoader(true);
            let headers = {
              Accept: "application/json",
              Authorization: "Bearer " + getTokenData().access_token,
              "auth-token": getTokenData().access_token,
            }
            let response = await axios.get(`${apiUrl}notification-settings/`, { headers: headers });
            setIsLoader(false);
            setNotificationData(response.data.data);

        } catch (error) {
          setIsLoader(false);
          if (error.response.status === 401) {
            setLogout();
          }
        }
      }
      fetchUserSetting();
    },[]);

    useEffect(() => {
      if (getTokenData().access_token && userRole == 0) {
          const userData = getLocalStorageUserdata();
          setUserRole(userData.role);
      }
  }, []);

    return (
      <>
        {userRole == 3 && <BuyerHeader /> }
        {userRole == 2 && <Header /> }
        <section className="main-section position-relative pt-4 pb-120">
        {isLoader ? 
          <div className="loader" style={{ textAlign: "center" }}>
            <img src="assets/images/loader.svg" />
          </div>:
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
                      <div className=""><label><span>Push Notifications</span></label></div>
                    </Col>
                    <Col lg={2} md={3}>
                      <div className=""><label><span>Email Notifications</span></label></div>
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
                            <input type="checkbox" checked={data.push.enabled} onChange={(e) =>handleNotificationStatus(data.key, 'push', e.target.checked)}/>
                            <label>
                              <span>Disable</span><span>Enable</span>
                            </label>
                          </div>
                        </Col>
                        <Col lg={2} md={3}>
                          <div className="buyer_seller_toggle" data-notifications="Email Notifications">
                          <input type="checkbox" checked={data.email.enabled} onChange={(e) =>handleNotificationStatus(data.key, 'email', e.target.checked)}/>                          <label>
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
          }
        </section>
        <Footer />
      </>
    );
  };
  export default Settings;
