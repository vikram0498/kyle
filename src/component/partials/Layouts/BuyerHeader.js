import React, { useEffect, useState } from "react";
import { Link, useLocation, useNavigate } from "react-router-dom";
import { useAuth } from "../../../hooks/useAuth";
import DarkMode from "./DarkMode";

function BuyerHeader() {
  const navigate = useNavigate();
  const { setLogout, getTokenData, getLocalStorageUserdata } = useAuth();
  const [userDetails, setUserDetails] = useState(null);
  useEffect(() => {
    if (getTokenData().access_token !== null) {
      let userData = getLocalStorageUserdata();
      console.log(userData,'userData');
      if (userData !== null) {
        if (userData.role === 2) {
          navigate("/");
        }
        setUserDetails(userData);
      }
    }
  }, []);
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
                <div className="modetype">
                  <DarkMode />
                </div>
                <Link to="/boost-your-profile" className="upload-buyer">
                  <span className="upload-buyer-icon">
                    <img
                      src="/assets/images/rocket.svg"
                      className="img-fluid"
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
                              : "./assets/images/avtar.png"
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
                            className="img-fluid"
                          />
                          My Profile
                        </Link>
                      </li>
                      {(userDetails !== null && userDetails.is_profile_verified) ? '':
                      <li className="active">
                        <Link to="/profile-verification" className="dropdown-item">
                          <img
                            src="/assets/images/search-log.svg"
                            className="img-fluid"
                          />
                          Profile verification
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
                        <a
                          href={void 0}
                          className="dropdown-item"
                          style={{ cursor: "pointer" }}
                          onClick={setLogout}
                        >
                          <img
                            alt=""
                            src="./assets/images/logoutcurve.svg"
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
