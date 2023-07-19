import React from 'react';
import Header from "../partials/Layouts/Header";
import Footer from "../partials/Layouts/Footer";

 const MyProfile = () => {
    return(
        <>
            <Header/>
            <section className="main-section position-relative pt-4 pb-120">
                <div className="container position-relative">
                    <div className="card-box mt-0">
                        <div className="row">
                            <div className="col-12 col-lg-8">
                                <div className="card-box-inner">
                                    <h3>My Profile</h3>
                                    <p>Fill the below form OR send link to the buyer</p>
                                    <form className="profile-account">
                                        <div className="row">
                                            <div className="col-12 col-lg-12">
                                                <div className="form-group">
                                                    <label>Username</label>
                                                    <input type="text" name="" className="form-control-form" placeholder="Username" /> 
                                                </div>
                                            </div>
                                            <div className="col-12 col-md-6 col-lg-6">
                                                <div className="form-group">
                                                    <label>Email</label>
                                                    <input type="email" name="" className="form-control-form" placeholder="Email" /> 
                                                </div>
                                            </div>
                                            <div className="col-12 col-md-6 col-lg-6">
                                                <div className="form-group">
                                                    <label>Phone Number</label>
                                                    <input type="text" name="" className="form-control-form" placeholder="Phone Number" /> 
                                                </div>
                                            </div>
                                        </div>
                                        <div className="form-title">
                                            <h5>Change Password</h5>
                                        </div>
                                        <div className="row">
                                            <div className="col-12 col-lg-12">
                                                <div className="form-group">
                                                    <label>Old Password</label>
                                                    <input type="password" name="" className="form-control-form" placeholder="Enter your old password" /> 
                                                </div>
                                            </div>
                                            <div className="col-12 col-lg-12">
                                                <div className="form-group">
                                                    <label>Confirm Password</label>
                                                    <input type="password" name="" className="form-control-form" placeholder="Confirm Password" /> 
                                                </div>
                                            </div>
                                        </div>
                                        <div className="save-btn mt-3">
                                            <a href="" className="btn btn-fill">Save</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div className="col-12 col-lg-4">
                                <form className="form-container">
                                    <div className="outer-heading text-center">
                                        <h3>Edit Profile Picture </h3>
                                        <p>Lorem Ipsum is simply dummy text of the printing.</p>
                                    </div>
                                    <div className="upload-photo">
                                        <div className="containers">
                                            <div className="imageWrapper">
                                                <img className="image" src="./assets/images/avtar-big.png"/>
                                            </div>
                                        </div>
                                        <button className="file-upload">            
                                            <input type="file" className="file-input" />Change Profile Photo
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <Footer/>
        </>
    )
 }
 export default MyProfile;