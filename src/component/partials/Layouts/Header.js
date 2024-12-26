import React, { useEffect, useState } from "react";
import { useAuth } from "../../../hooks/useAuth";
import { Link, useLocation, useNavigate } from "react-router-dom";
import MiniLoader from "../MiniLoader";
import axios from "axios";
import { toast } from "react-toastify";
import DarkMode from "./DarkMode";
import { Dropdown, Image } from "react-bootstrap";
import { generatedToken,messaging } from "../../../notifications/firebase";
import { onMessage } from "firebase/messaging";

function Header() {
  const navigate = useNavigate();

  const [userDetails, setUserDetails] = useState(null);
  const [creditLimit, setCreditLimit] = useState(null);
  const [isNewNotification, setIsNewNotification] = useState('');
  const [notificationData, setNotificationData] = useState({
    deal_notification:[{
      total: 0,
      records: []
    }],
    new_buyer_notification:[{
      total: 0,
      records: []
    }],
    dm_notification:[{
      total: 0,
      records: []
    }],
    interested_buyer_notification:[{
      total: 0,
      records: []
    }]
  });
  
  const { setLogout, getTokenData, getLocalStorageUserdata, setLocalStorageUserdata } = useAuth();

  const location = useLocation();
  const isNotSearchPage = location.pathname !== "/sellers-form";
  const apiUrl = process.env.REACT_APP_API_URL;

  if (isNotSearchPage) {
    localStorage.removeItem("filter_buyer_fields");
    localStorage.removeItem("get_filtered_data");
  }

  useEffect(() => {
    getCurrentLimit();
    if (getTokenData().access_token !== null) {
      let userData = getLocalStorageUserdata();
      if (userData.role === 3) {
        navigate("/buyer-profile");
      }
      if (userData !== null) {
        setUserDetails(userData);
      }
    }else{
      navigate("/login");
    }
  }, []);
  const getCurrentLimit = async () => {
    try {
      let headers = {
        Accept: "application/json",
        Authorization: "Bearer " + getTokenData().access_token,
        "auth-token": getTokenData().access_token,
      };
      let url = apiUrl + "get-current-limit";
      let response = await axios.get(url, { headers: headers });
      if (response.data.status) {
        if (!response.data.is_active) {
          toast.error("Your account has been blocked!", {
            position: toast.POSITION.TOP_RIGHT,
          });
          setLogout();
        }
        setCreditLimit(response.data);
      }
    } catch (error) {
      if (error.response.status === 401) {
        setLogout();
      }
    }
  };

  // const handleConfirmation = () => {
  //   Swal.fire({
  //     icon: "warning",
  //     title: 'Are you sure want to logout ?',
  //     showCancelButton: true,
  //     confirmButtonText: "Yes",
  //   }).then(async (result) => {
  //     /* Read more about isConfirmed, isDenied below */
  //     if (result.isConfirmed) {
  //       console.log("yesss");
  //     } 
  //   });
  // }
  const handleToggleSeller = async () => {
    try {
        let headers = {
          Accept: "application/json",
          Authorization: "Bearer " + getTokenData().access_token,
        };
        let response = await axios.post(`${apiUrl}update-user-role`,{},{headers});
        if(response.data.status){
          setLocalStorageUserdata(response.data.userData);
          navigate('/buyer-profile');
        }

    } catch (error) {
      if (error.response.status === 401) {
        setLogout();
      }    
    }
  }

  useEffect(()=>{
    const fetchNotificationData = async () => {
      try {
        let headers = {
          Accept: "application/json",
          Authorization: "Bearer " + getTokenData().access_token,
        };
        let response = await axios.get(`${apiUrl}get-notifications`,{headers});
        setNotificationData(response.data.notifications);
      } catch (error) {
        if (error.response.status === 401) {
          setLogout();
        } 
      }
    }
    fetchNotificationData()
  },[isNewNotification]);

  useEffect(()=>{
    generatedToken()
    onMessage(messaging,(payload)=>{
      console.log(payload,"payload seller data")
      setIsNewNotification(payload.notification.body)
    })
  },[]);
  return (
    <>
      <header className="dashboard-header">
        <div className="container-fluid">
          <div className="row align-items-center">
            <div className="col-2 col-md-4 col-lg-8 col-xxl-8">
              <div className="d-flex align-items-center header-left">
                <div className="header-logo d-none d-md-block">
                  <Link to="/">
                    <img
                      alt="logo"
                      src="/assets/images/logo.svg"
                      className="img-fluid"
                    />
                  </Link>
                </div>
                <div className="header-logo d-md-none">
                  <Link to="/">
                    <img
                      alt="logo"
                      src="/assets/images/mobile-logo.svg"
                      className="img-fluid"
                    />
                  </Link>
                </div>
                <div className="d-flex">
                  <Link to="/additional-credits" className="upload-buyer bg-green d-none d-lg-flex">
                    <span className="upload-buyer-icon d-flex">
                      <img
                        alt="coin"
                        src="/assets/images/coin.svg"
                        className="img-fluid"
                      />
                    </span>
                    <p>Buy Credits</p>
                  </Link>
                  <Link to="/my-buyers" className="upload-buyer d-none d-lg-flex">
                    <span className="upload-buyer-icon d-flex">
                      <img
                        alt="folder"
                        src="/assets/images/folder.svg"
                        className="img-fluid"
                      />
                    </span>
                    <p>
                      Buyer Database :{" "}
                      <b>
                        {creditLimit !== null ? (
                          creditLimit.total_buyer_uploaded
                        ) : (
                          <MiniLoader />
                        )}
                      </b>
                    </p>
                  </Link>
                  {userDetails !== null && userDetails.level_type !== 1 ? (
                  <>
                    <div className="upload-buyer d-none d-lg-flex me-0">
                      <span className="upload-buyer-icon d-flex">
                        <img
                          alt="wallet"
                          src="/assets/images/wallet.svg"
                          className="img-fluid"
                        />
                      </span>
                      <p>
                        Credits :{" "}
                        <b className="credit_limit">
                          {creditLimit !== null ? (
                            creditLimit.credit_limit
                          ) : (
                            <MiniLoader />
                          )}
                        </b>
                      </p>
                    </div>
                  </>
                  ) : (
                    ""
                  )}
                </div>
              </div>
            </div>
            <div className="col-10 col-md-8 col-lg-4 col-xxl-4">
              <div className="block-session">
                <div className="top_icons_list d-none d-xxl-block">
                  <ul>
                    <li>
                      <Dropdown>
                        <Dropdown.Toggle variant="success" id="dropdown-basic">
                          <Image src='/assets/images/home-dollar.svg' alt='' /><span className="list_numbers">{notificationData.deal_notification.total || 0}</span>
                        </Dropdown.Toggle>
                        <Dropdown.Menu>
                          <h5>New Deals</h5>
                          <ul>
                            {notificationData.deal_notification.total > 0 && 
                              notificationData.deal_notification.records.map((data,index)=>{
                                return (
                                  <li key={index}>
                                    <div className="dropdown_start">
                                      <Image src='/assets/images/home-dollar-drop-icon.svg' alt='' />
                                    </div>
                                    <div className="dropdown_middle">
                                      <h6>{data.data.title}</h6>
                                      <p>{data.data.message}</p>
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
                                )
                              })
                            }
                          </ul>
                          <Link to="/property-deal-result">View All</Link>
                        </Dropdown.Menu>
                      </Dropdown>
                    </li>

                    <li>
                      <Dropdown>
                        <Dropdown.Toggle variant="success" id="dropdown-basic">
                          <Image src='/assets/images/user-top.svg' alt='' /><span className="list_numbers">{notificationData.new_buyer_notification.total || 0}</span>
                        </Dropdown.Toggle>
                        <Dropdown.Menu>
                          <h5>New Buyers</h5>
                          <ul>
                            {notificationData.new_buyer_notification.total > 0 ? 
                              notificationData.new_buyer_notification.records.map((data,index)=>{
                                return (
                                  <li key={index}>
                                    <div className="dropdown_start">
                                      <Image src='/assets/images/user-dropdown-icon.svg' alt='' />
                                    </div>
                                    <div className="dropdown_middle">
                                      <h6>{data.data.title}</h6>
                                      <p>{data.data.message}</p>
                                    </div>
                                    <div className="dropdown_end align-self-center">
                                      <Link to="/deal-notifications">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="9" viewBox="0 0 14 9" fill="none">
                                          <path d="M1 4.5L12.9972 4.5" stroke="#121639" strokeLinecap="round" stroke-linejoin="round"/>
                                          <path d="M9.80078 1L13.0003 4.5L9.80078 8" stroke="#121639" strokeLinecap="round" stroke-linejoin="round"/>
                                        </svg>
                                      </Link>
                                    </div>
                                  </li>
                                )}) : 
                                <li>
                                    <div className="dropdown_start">
                                      <Image src='/assets/images/user-dropdown-icon.svg' alt='' />
                                    </div>
                                    <div className="dropdown_middle">
                                      <h6>No Data Found</h6>
                                    </div>
                                  </li>
                                  }
                          </ul>
                          <Link to="/my-buyers">View All</Link>
                        </Dropdown.Menu>
                      </Dropdown>
                    </li>

                    <li>
                      <Dropdown>
                        <Dropdown.Toggle variant="success" id="dropdown-basic">
                          <Image src='/assets/images/msg-top.svg' alt='' /><span className="list_numbers">{notificationData.dm_notification.total || 0}</span>
                        </Dropdown.Toggle>
                        <Dropdown.Menu>
                          <h5>New Messages</h5>
                          <ul>
                          {notificationData.dm_notification.total > 0 ? 
                              notificationData.dm_notification.records.map((data,index)=>{
                                return(
                                  <li key={index}>
                                    <div className="dropdown_start">
                                      <Image src='/assets/images/msg-dropdown-icon.svg' alt='' />
                                    </div>
                                    <div className="dropdown_middle">
                                      <h6>{data.data.title}</h6>
                                      <p>{data.data.message}</p>
                                    </div>
                                    <div className="dropdown_end">{data.created_at}</div>
                                  </li>
                                )
                              }):
                              <li>
                              <div className="dropdown_start">
                                <Image src='/assets/images/msg-dropdown-icon.svg' alt='' />
                              </div>
                              <div className="dropdown_middle">
                                <h6>No Data Found</h6>
                              </div>
                            </li>
                            }
                          </ul>
                          <Link to="/message">View All</Link>
                        </Dropdown.Menu>
                      </Dropdown>
                    </li>

                    <li>
                      <Dropdown>
                        <Dropdown.Toggle variant="success" id="dropdown-basic">
                          <Image src='/assets/images/home-top-check.svg' alt='' /><span className="list_numbers">{notificationData.interested_buyer_notification.total || 0 }</span>
                        </Dropdown.Toggle>
                        <Dropdown.Menu>
                          <h5>Interested Buyers</h5>
                          <ul>
                          {notificationData.interested_buyer_notification.total > 0 ? 
                              notificationData.interested_buyer_notification.records.map((data,index)=>{
                                return(
                                    <li key={index}>
                                      <div className="dropdown_start">
                                        <Image src='/assets/images/interested-buyer-drop.svg' alt='' />
                                      </div>
                                      <div className="dropdown_middle">
                                        <h6>{data.data.title}</h6>
                                        <p>{data.data.message}</p>
                                      </div>
                                      <div className="dropdown_end align-self-center">
                                        <Link to={`property-deal-details/${data.data.module_id}`}>
                                          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="9" viewBox="0 0 14 9" fill="none">
                                            <path d="M1 4.5L12.9972 4.5" stroke="#121639" strokeLinecap="round" stroke-linejoin="round"/>
                                            <path d="M9.80078 1L13.0003 4.5L9.80078 8" stroke="#121639" strokeLinecap="round" stroke-linejoin="round"/>
                                          </svg>
                                        </Link>
                                      </div>
                                    </li>
                                )}):
                                <li>
                                  <div className="dropdown_start">
                                  <Image src='/assets/images/interested-buyer-drop.svg' alt='' />
                                  </div>
                                  <div className="dropdown_middle">
                                    <h6>No Data Found</h6>
                                  </div>
                                </li>
                                }
                          </ul>
                          <Link to="/property-deal-result">View All</Link>
                        </Dropdown.Menu>
                      </Dropdown>
                    </li>
                  </ul>
                </div>
                {/* Mobile Notifications */}
                <div className="top_icons_list d-xxl-none">
                  <ul className="mobile-header-list">
                    <li className="d-lg-none">
                      <Link to="/additional-credits" className="upload-buyer bg-green m-0">
                        <span className="upload-buyer-icon d-flex m-0">
                          <img
                            alt="coin"
                            src="/assets/images/coin.svg"
                            className="img-fluid"
                          />
                        </span>
                      </Link>
                    </li>
                    <li>
                      <Dropdown>
                        <Dropdown.Toggle variant="success" id="dropdown-basic">
                          <Image src='/assets/images/mobile_notification.svg' alt='' /><span className="list_numbers"></span>
                        </Dropdown.Toggle>
                        <Dropdown.Menu>
                          <h5>Notifications</h5>
                          <ul>
                            {notificationData.deal_notification.total > 0 && 
                            <li>
                              <div className="dropdown_start">
                                <Image src='/assets/images/home-dollar-drop-icon.svg' alt='' />
                              </div>
                              <div className="dropdown_middle">
                                <h6>{notificationData.deal_notification.records[0].data.title} Property Name</h6>
                                <p>{notificationData.deal_notification.records[0].data.message}</p>
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
                            }
                            <li>
                              <div className="dropdown_start">
                                <Image src='/assets/images/msg-dropdown-icon.svg' alt='' />
                              </div>
                              <div className="dropdown_middle">
                                <h6>Brooklyn Simmons</h6>
                                <p>New buy added in your buyer list....</p>
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
                                <Image src='/assets/images/user-dropdown-icon.svg' alt='' />
                              </div>
                              <div className="dropdown_middle">
                                <h6>Brooklyn Simmons</h6>
                                <p>New buy added in your buyer list....</p>
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
                                <Image src='/assets/images/interested-buyer-drop.svg' alt='' />
                              </div>
                              <div className="dropdown_middle">
                                <h6>Brooklyn Simmons</h6>
                                <p>New buy added in your buyer list....</p>
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
                        </Dropdown.Menu>
                      </Dropdown>
                    </li>
                  </ul>
                </div>
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
                      <div
                        className="welcome-user"
                        style={{ display: "block" }}
                      >
                        <span className="welcome">welcome</span>
                        <span className="user-name-title">
                          {userDetails !== null
                            ? userDetails.first_name +
                              " " +
                              userDetails.last_name
                            : ""}
                        </span>
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
                    <div className="mobile_drop_top d-flex d-lg-none">
                      <div className="upload-buyer">
                        <span className="upload-buyer-icon d-flex">
                          <img
                            alt="folder"
                            src="/assets/images/folder.svg"
                            className="img-fluid"
                          />
                        </span>
                        <Link to="/my-buyers">
                          <p>
                            uploaded Buyer Data{" "}
                            <b>
                              {creditLimit !== null ? (
                                creditLimit.total_buyer_uploaded
                              ) : (
                                <MiniLoader />
                              )}
                            </b>
                          </p>
                        </Link>
                      </div>
                      {userDetails !== null && userDetails.level_type !== 1 ? (
                        <>
                        <div className="upload-buyer">
                          <span className="upload-buyer-icon d-flex">
                            <img
                              alt="wallet"
                              src="/assets/images/wallet.svg"
                              className="img-fluid"
                            />
                          </span>
                          <p>
                            Credits{" "}
                            <b className="credit_limit">
                              {creditLimit !== null ? (
                                creditLimit.credit_limit
                              ) : (
                                <MiniLoader />
                              )}
                            </b>
                          </p>
                        </div>
                        </>
                      ) : (
                        ""
                      )}
                    </div>
                    <ul className="list-unstyled mb-0">
                      <li>
                        <Link className="dropdown-item" to="/my-profile">
                          <img
                            alt=""
                            src="/assets/images/user-login.svg"
                            className="img-fluid"
                          />
                          My Profile
                        </Link>
                      </li>
                      <li>
                        <Link className="dropdown-item" to="/my-buyers">
                          <img
                            alt=""
                            src="/assets/images/booksaved.svg"
                            className="img-fluid"
                          />
                          My Buyers Data
                        </Link>
                      </li>
                      <li>
                        {/* <Link className="dropdown-item" to="/last-search-data"> */}
                        <Link className="dropdown-item" to="/last-search-data">
                          <img
                            alt=""
                            src="/assets/images/search-log.svg"
                            className="img-fluid"
                          />
                          Last Searched Data
                        </Link>
                      </li>
                      <li>
                        <Link className="dropdown-item" to="/support">
                          <img
                            alt=""
                            src="/assets/images/messages.svg"
                            className="img-fluid"
                          />
                          My Support
                        </Link>
                      </li>
                      <li>
                        <Link className="dropdown-item" to="/settings">
                          <img
                            alt=""
                            src="/assets/images/setting-1.svg"
                            className="img-fluid"
                          />
                          Settings
                        </Link>
                      </li>
                      <li>
                          <DarkMode />
                      </li>
                      <li>
                        <Link className="dropdown-item position-relative">
                          {userDetails?.level_type > 1 && 
                            <div className="buyer_seller_toggle2">
                              <input type="checkbox" onChange={handleToggleSeller}/>
                              <label>
                                <span>Buyer</span>
                              </label>
                            </div>
                          }
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

export default Header;
