import React, { useState } from 'react';

import { Link } from 'react-router-dom';

import Logo from './../../assets/images/logo.svg';
import userIcon from './../../assets/images/user.svg';
import mailIcon from './../../assets/images/mail.svg';
import phoneIcon from './../../assets/images/phone.svg';
import mapPinIcon from './../../assets/images/map-pin.svg';
import passwordIcon from './../../assets/images/password.svg';
import eyeIcon from './../../assets/images/eye.svg';
import facebookImg from './../../assets/images/facebook.svg';
import googleImg from './../../assets/images/google.svg';

import Layout from './Layout';
  
function Register (){

    const [showPassoword, setshowPassoword] = useState(false);
    const togglePasswordVisibility  = () => {
        setshowPassoword(!showPassoword);
    };

    const [showConfirmPassword, setshowConfirmPassword] = useState(false);
    const toggleConfirmPasswordVisibility  = () => {
        setshowConfirmPassword(!showConfirmPassword);
    };

    return (
        <Layout>
            <div className="gap-between">
                <div className="account-in">
                    <div className="center-content">
                        <img src={Logo} className="img-fluid" alt="" />
                        <h2>Welcome to Inucation!</h2>
                    </div>
                    <form>
                        <div className="row">
                            <div className="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div className="form-group">
                                    <label>First Name</label>
                                    <div className="form-group-inner">
                                        <span className="form-icon"><img src={userIcon} className="img-fluid" alt="" /></span>
                                        <input type="text" placeholder="First Name" name="" className="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div className="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div className="form-group">
                                    <label>Last Name</label>
                                    <div className="form-group-inner">
                                        <span className="form-icon"><img src={userIcon} className="img-fluid" alt="" /></span>
                                        <input type="text" placeholder="Last Name" name="" className="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div className="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div className="form-group">
                                    <label>Email</label>
                                    <div className="form-group-inner">
                                        <span className="form-icon"><img src={mailIcon} className="img-fluid" alt="" /></span>
                                        <input type="email" placeholder="Enter Your Email" name="" className="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div className="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div className="form-group">
                                    <label>Mobile Number</label>
                                    <div className="form-group-inner">
                                        <span className="form-icon"><img src={phoneIcon} className="img-fluid" alt="" /></span>
                                        <input type="text" placeholder="(123) 456-7890" name="" className="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div className="col-12 col-lg-12">
                                <div className="form-group">
                                    <label>Company Name</label>
                                    <div className="form-group-inner">
                                        <span className="form-icon"><img src={mapPinIcon} className="img-fluid" alt="" /></span>
                                        <input type="text" placeholder="Enter Your Password" name="" className="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div className="col-12 col-lg-12">
                                <div className="form-group">
                                    <label>password</label>
                                    <div className="form-group-inner">
                                        <span className="form-icon"><img src={passwordIcon} className="img-fluid" alt="" /></span>
                                        <input id="pass_log_id" type={showPassoword ? 'text' : 'password'} placeholder="Enter Your Password"  className="form-control" />
                                        <span onClick={togglePasswordVisibility} className={`form-icon-password ${showPassoword ? '' : 'eye-close'}`}><img src={eyeIcon} className="img-fluid" alt="" /></span>
                                    </div>
                                </div>
                            </div>
                            <div className="col-12 col-lg-12">
                                <div className="form-group mb-0">
                                    <label>Confirm password</label>
                                    <div className="form-group-inner">
                                        <span className="form-icon"><img src={passwordIcon} className="img-fluid" alt="" /></span>
                                        <input id="conpass_log_id" type={showConfirmPassword ? 'text' : 'password'} placeholder="Enter Your Password" className="form-control" />
                                        <span onClick={toggleConfirmPasswordVisibility} className={`form-icon-password toggle-password ${showConfirmPassword ? '' : 'eye-close'}`}><img src={eyeIcon} className="img-fluid" alt="" /></span>
                                    </div>
                                </div>
                            </div>
                            <div className="col-12 col-lg-12">
                                <div className="form-group-btn">
                                    <a href="" className="btn btn-fill">Register Now!</a>
                                </div>
                            </div>
                            <div className="col-12 col-lg-12">
                                <p className="account-now">Already Have an account? <Link to="/login">Login Now!</Link></p>
                                <div className="or"><span>OR</span></div>
                                <ul className="account-with-social list-unstyled mb-0">
                                    <li><Link to="https://facebook.com"><img src={facebookImg} className="img-fluid" /> with Facebook</Link></li>
                                    <li><Link to="https://google.com"><img src={googleImg} className="img-fluid" /> with Google</Link></li>
                                </ul>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </Layout> 
    );
}
  
export default Register;