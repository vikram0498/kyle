import React from 'react';

import Logo from './../../assets/images/logo.svg';
import mailIcon from './../../assets/images/mail.svg';

import Layout from './Layout';

function ForgotPassword (){
    return (
        <Layout>
            <div class="account-in">
                <div class="center-content">
                    <img src={Logo} class="img-fluid" alt="" />
                    <h2>Forgot Password</h2>
                </div>
                <form>
                    <div class="row">
                        <div class="col-12 col-lg-12">
                            <div class="form-group mb-0">
                                <label>Email</label>
                                <div class="form-group-inner">
                                    <span class="form-icon"><img src={mailIcon} class="img-fluid" alt="" /></span>
                                    <input type="email" placeholder="Enter Your Email" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-12">
                            <div class="form-group-btn mb-0">
                                <a href="" class="btn btn-fill">submit</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </Layout>
    )
}
  
export default ForgotPassword;