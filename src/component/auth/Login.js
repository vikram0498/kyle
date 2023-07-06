import React, { useState } from 'react';

import { Link } from 'react-router-dom';

import Logo from './../../assets/images/logo.svg'
import mailIcon from './../../assets/images/mail.svg'
import passwordIcon from './../../assets/images/password.svg'
import eyeIcon from './../../assets/images/eye.svg'
import facebookImg from './../../assets/images/facebook.svg'
import googleImg from './../../assets/images/google.svg'

import Layout from './Layout';
  
function Login (){

    const [showPassoword, setshowPassoword] = useState(false);

    const togglePasswordVisibility  = () => {
        // Update the attribute value when the element is clicked
        setshowPassoword(!showPassoword);
    };

    return (
        <Layout>
            <div className="account-in">
                <div className="center-content">
                    <img src={Logo} className="img-fluid" alt="" />
                    <h2>Welcome Back!</h2>
                </div>
                <form>
                    <div className="row">
                        <div className="col-12 col-lg-12">
                            <div className="form-group">
                                <label>Username</label>
                                <div className="form-group-inner">
                                    <span className="form-icon"><img src={mailIcon} className="img-fluid" alt="" /></span>
                                    <input type="text" placeholder="Enter Your Email" name="" className="form-control" />
                                </div>
                            </div>
                        </div>
                        <div className="col-12 col-lg-12">
                            <div className="form-group">
                                <label>password</label>
                                <div className="form-group-inner">
                                    <span className="form-icon"><img src={passwordIcon} className="img-fluid" alt="" /></span>
                                    <input id="pass_log_id" type={showPassoword ? 'text' : 'password'} placeholder="Enter Your Password" name="" className="form-control" />
                                    <span onClick={togglePasswordVisibility} className={`form-icon-password toggle-password ${showPassoword ? '' : 'eye-close'}`}><img src={eyeIcon} className="img-fluid" alt="" /></span>
                                </div>
                            </div>
                        </div>
                        <div className="col-12 col-sm-6 col-md-6 col-lg-6">
                            <div className="form-group-switch">
                                <label className="switch">
                                    <input type="checkbox"  />
                                    <span className="slider round"></span>
                                </label>
                                <span className="toggle-heading">Remember me</span>
                            </div>
                        </div>
                        <div className="col-12 col-sm-6 col-md-6 col-lg-6">
                            <Link to="/forget-password" className="link-pass">Forgot Password</Link>
                        </div>
                        <div className="col-12 col-lg-12">
                            <div className="form-group-btn">
                                <a href="" className="btn btn-fill">Login Now!</a>
                            </div>
                        </div>
                        <div className="col-12 col-lg-12">
                            <p className="account-now">Don’t Have an account?  
                                <Link to="/register"> Sign up Now!</Link>
                            </p>
                            <div className="or"><span>OR</span></div>
                            <ul className="account-with-social list-unstyled mb-0">
                                <li><Link to="https://facebook.com"><img src={facebookImg} className="img-fluid" /> with Facebook</Link></li>
                                <li><Link to="https://google.com"><img src={googleImg} className="img-fluid" /> with Google</Link></li>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
        </Layout>                           
    );
}
  
export default Login;