import React from "react";
// import './../../assets/css/bootstrap.min.css';
// import './../../assets/css/main.css';
// import './../../assets/css/responsive.css';

import { Link, useLocation } from "react-router-dom";

const Layout = ({ children }) => {
  const location = useLocation();
  const isNotLogin = location.pathname !== "/login";
  return (
    <section className="account-block">
      <div className="container-fluid p-0">
        <div className="account-session">
          {/* { isNotLogin ? 
                        <Link to='/login' className="back back-fix">
                            <svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15 6H1" stroke="#0A2540" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
                                <path d="M5.9 11L1 6L5.9 1" stroke="#0A2540" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
                            </svg>
                            Back 
                        </Link> : ''
                    } */}
          <div className="row align-items-center g-0 h-100vh">
            <div className="col-12 col-lg-6">{children}</div>
            <div className="col-12 col-lg-6">
              <div className="session-img">
                <img
                  src="/assets/images/bg.jpg"
                  className="img-fluid"
                  alt="logo"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};

export default Layout;
