import React, { useState } from 'react';

import Logo from './../../assets/images/logo.svg';
import passwordIcon from './../../assets/images/password.svg';
import eyeIcon from './../../assets/images/eye.svg';

import Layout from './Layout';

function ResetPassword (){

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
            <div className="account-in">
                <div className="center-content">
                    <img src={Logo} className="img-fluid" alt="" />
                    <h2>Reset Password</h2>
                </div>
                <form>
                    <div className="row">
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
                            <div className="form-group-btn mb-0">
                                <a href="" className="btn btn-fill">Submit</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </Layout>
    )
}
  
export default ResetPassword;