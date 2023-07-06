import React from 'react';
import './../../assets/css/bootstrap.min.css';
import './../../assets/css/main.css';
import './../../assets/css/responsive.css';

import BgImage from './../../assets/images/bg.jpg'
  
const  Layout = ({ children }) => { 
    return (
        <section className="account-block">
            <div className="container-fluid p-0">
                <div className="account-session">				
                    <div className="row align-items-center g-0 h-100vh">
                        <div className="col-12 col-lg-6 d-flex align-items-center justify-content-center">
                            <main>{children}</main>
                        </div>
                        <div className="col-12 col-lg-6">
                            <div className="session-img">
                                <img src={BgImage} className="img-fluid" alt="logo" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
}
  
export default Layout;