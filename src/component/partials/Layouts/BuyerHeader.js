import React, { useEffect, useState } from "react";
import { Link, useLocation, useNavigate } from "react-router-dom";
import { useAuth } from "../../../hooks/useAuth";
import { toast } from "react-toastify";
import axios from "axios";
import DarkMode from "./DarkMode";
import { Dropdown, Image } from "react-bootstrap";

function BuyerHeader() {
  const navigate = useNavigate();
  const { setLogout, getTokenData, getLocalStorageUserdata,setLocalStorageUserdata } = useAuth();
  const [userDetails, setUserDetails] = useState(null);
  const apiUrl = process.env.REACT_APP_API_URL;

  useEffect(() => {
    if (getTokenData().access_token !== null) {
      let userData = getLocalStorageUserdata();
      isActiveUser(userData);
      if (userData !== null) {
        if (userData.role === 2) {
          navigate("/");
        }
        setUserDetails(userData);
      } 
    }
  }, []);

  const isActiveUser = async (userData) => {
    try{
      let userId = userData.id;
      const apiUrl = process.env.REACT_APP_API_URL;
      let headers = {
        Accept: "application/json",
        Authorization: "Bearer " + getTokenData().access_token,
        "auth-token": getTokenData().access_token,
      };
      let url = apiUrl + "is-user-status";
      let response = await axios.post(url, {user_id:userId},{ headers: headers });
      if(response.data.status){
        if(response.data.user_status){
          userData.is_verified = response.data.is_buyer_verified;
          setLocalStorageUserdata(userData);
        }else{
          setLogout();
        }
      }

    }catch(error){
        if (error.response) {
          if (error.response.status === 401) {
            setLogout();
          }
          if (error.response.data.error) {
            toast.error(error.response.data.error, {
              position: toast.POSITION.TOP_RIGHT,
            });
          }
        }
    }
  }

  const handleToggleSeller = async () => {
    try {
        let headers = {
          Accept: "application/json",
          Authorization: "Bearer " + getTokenData().access_token,
        };
        let response = await axios.post(`${apiUrl}update-user-role`,{},{headers});
        if(response.data.status){
          setLocalStorageUserdata(response.data.userData);
          navigate('/');
        }

    } catch (error) {
      if (error.response.status === 401) {
        setLogout();
      } 
    }
  }
  return (
    <>
      <header className="dashboard-header">
        <div className="container-fluid">
          <div className="row align-items-center">
            <div className="col-6 col-sm-6 col-md-4 col-lg-3">
              <div className="header-logo">
                <Link to="/buyer-profile">
                  <img
                    src="/assets/images/logo.svg"
                    className="img-fluid darkmode-none"
                    alt=""
                  />
                </Link>
                {/* <a href="">
                  <img
                    src="/assets/images/dark-mode-logo.png"
                    className="img-fluid lightmode-none"
                  />
                </a> */}
              </div>
            </div>
            <div className="col-6 col-sm-6 col-md-8 col-lg-9">
              <div className="block-session">
              <div className="top_icons_list d-none d-xxl-block">
                  <ul>
                    <li>
                      <Dropdown>
                        <Dropdown.Toggle variant="success" id="dropdown-basic">
                          <Image src='/assets/images/home-dollar.svg' alt='' /><span className="list_numbers">5</span>
                        </Dropdown.Toggle>
                        <Dropdown.Menu>
                          <h5>New Deals</h5>
                          <ul>
                            <li>
                              <div className="dropdown_start">
                                <Image src='/assets/images/home-dollar-drop-icon.svg' alt='' />
                              </div>
                              <div className="dropdown_middle">
                                <h6>Property Name</h6>
                                <p>Buyer want to buy you property...</p>
                              </div>
                              <div className="dropdown_end align-self-center">
                                <Link to="#">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="9" viewBox="0 0 14 9" fill="none">
                                    <path d="M1 4.5L12.9972 4.5" stroke="#121639" strokeLinecap="round" stroke-linejoin="round"/>
                                    <path d="M9.80078 1L13.0003 4.5L9.80078 8" stroke="#121639" strokeLinecap="round" stroke-linejoin="round"/>
                                  </svg>
                                </Link>
                              </div>
                            </li>
                            <li>
                              <div className="dropdown_start">
                                <Image src='/assets/images/home-dollar-drop-icon.svg' alt='' />
                              </div>
                              <div className="dropdown_middle">
                                <h6>Property Name</h6>
                                <p>Buyer want to buy you property...</p>
                              </div>
                              <div className="dropdown_end align-self-center">
                                <Link to="#">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="9" viewBox="0 0 14 9" fill="none">
                                    <path d="M1 4.5L12.9972 4.5" stroke="#121639" strokeLinecap="round" stroke-linejoin="round"/>
                                    <path d="M9.80078 1L13.0003 4.5L9.80078 8" stroke="#121639" strokeLinecap="round" stroke-linejoin="round"/>
                                  </svg>
                                </Link>
                              </div>
                            </li>
                            <li>
                              <div className="dropdown_start">
                                <Image src='/assets/images/home-dollar-drop-icon.svg' alt='' />
                              </div>
                              <div className="dropdown_middle">
                                <h6>Property Name</h6>
                                <p>Buyer want to buy you property...</p>
                              </div>
                              <div className="dropdown_end align-self-center">
                                <Link to="#">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="9" viewBox="0 0 14 9" fill="none">
                                    <path d="M1 4.5L12.9972 4.5" stroke="#121639" strokeLinecap="round" stroke-linejoin="round"/>
                                    <path d="M9.80078 1L13.0003 4.5L9.80078 8" stroke="#121639" strokeLinecap="round" stroke-linejoin="round"/>
                                  </svg>
                                </Link>
                              </div>
                            </li>
                            <li>
                              <div className="dropdown_start">
                                <Image src='/assets/images/home-dollar-drop-icon.svg' alt='' />
                              </div>
                              <div className="dropdown_middle">
                                <h6>Property Name</h6>
                                <p>Buyer want to buy you property...</p>
                              </div>
                              <div className="dropdown_end align-self-center">
                                <Link to="#">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="9" viewBox="0 0 14 9" fill="none">
                                    <path d="M1 4.5L12.9972 4.5" stroke="#121639" strokeLinecap="round" stroke-linejoin="round"/>
                                    <path d="M9.80078 1L13.0003 4.5L9.80078 8" stroke="#121639" strokeLinecap="round" stroke-linejoin="round"/>
                                  </svg>
                                </Link>
                              </div>
                            </li>
                          </ul>
                          <Link to="/deal-notifications">View All</Link>
                        </Dropdown.Menu>
                      </Dropdown>
                    </li>
                    <li>
                      <Dropdown>
                        <Dropdown.Toggle variant="success" id="dropdown-basic">
                          <Image src='/assets/images/msg-top.svg' alt='' /><span className="list_numbers">6</span>
                        </Dropdown.Toggle>
                        <Dropdown.Menu>
                          <h5>New Messages</h5>
                          <ul>
                            <li>
                              <div className="dropdown_start">
                                <Image src='/assets/images/msg-dropdown-icon.svg' alt='' />
                              </div>
                              <div className="dropdown_middle">
                                <h6>Brooklyn Simmons</h6>
                                <p>New buy added in your buyer list....</p>
                              </div>
                              <div className="dropdown_end">
                                2m ago
                              </div>
                            </li>
                            <li>
                              <div className="dropdown_start">
                                <Image src='/assets/images/msg-dropdown-icon.svg' alt='' />
                              </div>
                              <div className="dropdown_middle">
                                <h6>Brooklyn Simmons</h6>
                                <p>New buy added in your buyer list....</p>
                              </div>
                              <div className="dropdown_end">
                                2m ago
                              </div>
                            </li>
                            <li>
                              <div className="dropdown_start">
                                <Image src='/assets/images/msg-dropdown-icon.svg' alt='' />
                              </div>
                              <div className="dropdown_middle">
                                <h6>Brooklyn Simmons</h6>
                                <p>New buy added in your buyer list....</p>
                              </div>
                              <div className="dropdown_end">
                                2m ago
                              </div>
                            </li>
                            <li>
                              <div className="dropdown_start">
                                <Image src='/assets/images/msg-dropdown-icon.svg' alt='' />
                              </div>
                              <div className="dropdown_middle">
                                <h6>Brooklyn Simmons</h6>
                                <p>New buy added in your buyer list....</p>
                              </div>
                              <div className="dropdown_end">
                                2m ago
                              </div>
                            </li>
                          </ul>
                          <Link to="/message">View All</Link>
                        </Dropdown.Menu>
                      </Dropdown>
                    </li>
                  </ul>
                </div>
                {userDetails?.is_switch_role == 1 && 
                <div className="buyer_seller_toggle">
                  <input type="checkbox" onChange={handleToggleSeller} defaultChecked={true}/>
                  <label>
                    <span>Seller</span>
                    <span>Buyer</span>
                  </label>
                </div>
                }
                
                <div className="modetype">
                  <DarkMode />
                </div>
                <Link to="/boost-your-profile" className="upload-buyer">
                  <span className="upload-buyer-icon">
                    <img
                      src="/assets/images/rocket.svg"
                      className="img-fluid"
                      alt=""
                    />
                  </span>
                  <p>boost your profile</p>
                </Link>
                <div className="dropdown user-dropdown">
                  <button
                    className="btn dropdown-toggle ms-auto"
                    type="button"
                    id="dropdownMenuButton1"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                  >
                    <div className="dropdown-data">
                      <div className="img-user">
                        <img
                          src={
                            userDetails !== null &&
                            userDetails.profile_image !== ""
                              ? userDetails.profile_image
                              : "/assets/images/avtar.png"
                          }
                          className="img-fluid user-profile"
                          alt=""
                        />
                      </div>
                      <div className="welcome-user">
                        <span className="welcome">welcome</span>
                        <span className="user-name-title">
                          {userDetails !== null
                            ? userDetails.first_name +
                              " " +
                              userDetails.last_name
                            : ""}
                        </span>{" "}
                      </div>
                    </div>
                    <span className="arrow-icon">
                      <svg
                        width="14"
                        height="8"
                        viewBox="0 0 14 8"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <path
                          d="M13.002 7L7.00195 0.999999L1.00195 7"
                          stroke="black"
                          strokeWidth="1.5"
                          strokeLinecap="round"
                          strokeLinejoin="round"
                        />
                      </svg>
                    </span>
                  </button>
                  <div
                    className="dropdown-menu"
                    aria-labelledby="dropdownMenuButton1"
                  >
                    <ul className="list-unstyled mb-0">
                      <li>
                        <Link to="/buyer-profile" className="dropdown-item">
                          <img
                            src="/assets/images/user-login.svg"
                            className="img-fluid" alt=""
                          />
                          My Profile
                        </Link>
                      </li>
                      {(userDetails !== null && userDetails.is_verified) ? '':
                      <li className="active">
                        <Link to="/profile-verification" className="dropdown-item">
                          <img
                            src="/assets/images/search-log.svg"
                            className="img-fluid" alt=""
                          />
                          Profile Verification
                        </Link>
                      </li>
                      }
                      
                      <li>
                        <Link to="/support" className="dropdown-item">
                          <img
                            src="/assets/images/messages.svg"
                            className="img-fluid"
                          />
                          Support
                        </Link>
                      </li>
                      <li>
                        <Link className="dropdown-item" to="/settings">
                          <img
                            alt=""
                            src="./assets/images/setting-1.svg"
                            className="img-fluid"
                          />
                          Settings
                        </Link>
                      </li>
                      <li>
                        <a
                          href={void 0}
                          className="dropdown-item"
                          style={{ cursor: "pointer" }}
                          onClick={setLogout}
                        >
                          <img
                            alt=""
                            src="/assets/images/logoutcurve.svg"
                            className="img-fluid"
                          />
                          Logout
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </header>
    </>
  );
}

export default BuyerHeader;
