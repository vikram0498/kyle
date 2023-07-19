import React from 'react';
import Header from "../partials/Layouts/Header";
import Footer from "../partials/Layouts/Footer";

 const AdditionalCreadits = () => {
    return(
        <>
            <section className="main-section position-relative pt-4 pb-5">
                <div className="container position-relative">
                    <div className="back-block">
                        <div className="row">
                            <div className="col-12 col-sm-4 col-md-4 col-lg-4">
                                <a href="#" className="back">
                                    <svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M15 6H1" stroke="#0A2540" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M5.9 11L1 6L5.9 1" stroke="#0A2540" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    Back
                                </a>
                            </div>
                            <div className="col-12 col-sm-4 col-md-4 col-lg-4">
                                <h6 className="center-head text-center mb-0">Pricing Page</h6>
                            </div>
                            <div className="col-12 col-lg-4"></div>
                        </div>
                    </div>
                    <div className="card-box">
                        <div className="row">
                            <div className="col-12 col-lg-12">
                                <div className="card-box-inner">
                                    <div className="inner-page-title text-center">
                                        <h3 className="text-center">Buy Additional Credits</h3>
                                        <p className="mb-0">You can purchase it accordingly Monthly, yearly or require more data then here is another plan for you.</p>
                                    </div>
                                    <div className="card-box-light">
                                        <form>
                                            <div className="row">
                                                <div className="col-12 col-md-6 col-lg-6">
                                                    <div className="radio-item-features">
                                                        <label className="features-items">
                                                            <input type="radio" name="radio"/>
                                                            <div className="feature-item-content">
                                                                <div className="feature-item-title">01 Credit Plans</div>
                                                                <span className="price-pc">$100</span>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div className="col-12 col-md-6 col-lg-6">
                                                    <div className="radio-item-features">
                                                        <label className="features-items">
                                                            <input type="radio" name="radio"/>
                                                            <div className="feature-item-content">
                                                                <div className="feature-item-title">10 Credit Plans</div>
                                                                <span className="price-pc">$1000</span>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div className="col-12 col-md-6 col-lg-6">
                                                    <div className="radio-item-features">
                                                        <label className="features-items">
                                                            <input type="radio" name="radio"/>
                                                            <div className="feature-item-content">
                                                                <div className="feature-item-title">1,000 Credit Plans</div>
                                                                <span className="price-pc">$100</span>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div className="col-12 col-md-6 col-lg-6">
                                                    <div className="radio-item-features">
                                                        <label className="features-items">
                                                            <input type="radio" name="radio"/>
                                                            <div className="feature-item-content">
                                                                <div className="feature-item-title">100 Credit Plans</div>
                                                                <span className="price-pc">$1000</span>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div className="col-12 col-lg-12">
                                                    <div className="buynow-btn">
                                                        <a href="" className="btn btn-fill">Buy Now!</a>
                                                    </div>
                                                    <div className="note">Note : 1 Credit is 1 Buyer</div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <Footer/>
        </>
    )
 }
 export default AdditionalCreadits;