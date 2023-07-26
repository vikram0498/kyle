import React from 'react';
import Header from "../partials/Layouts/Header";
import Footer from "../partials/Layouts/Footer";

 const ChooseYourPlan = () => {
    return(
        <>
            <Header/>
            <section class="main-section position-relative pt-4 pb-5">
                <div class="container position-relative">
                    <div class="back-block">
                        <div class="row">
                            <div class="col-12 col-lg-4">
                                <a href="#" class="back">
                                    <svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M15 6H1" stroke="#0A2540" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
                                        <path d="M5.9 11L1 6L5.9 1" stroke="#0A2540" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
                                    </svg>
                                    Back
                                </a>
                            </div>
                            <div class="col-12 col-lg-4">
                                <h6 class="center-head text-center mb-0">Pricing Page</h6>
                            </div>
                            <div class="col-12 col-lg-4"></div>
                        </div>
                    </div>
                    <div class="card-box">
                        <div class="row">
                            <div class="col-12 col-lg-12">
                                <div class="card-box-inner">
                                    <div class="inner-page-title text-center">
                                        <h3 class="text-center">Choose Your Plan</h3>
                                        <p class="mb-0">You can purchase it accordingly Monthly, yearly or require more data then here is another plan for you.</p>
                                    </div>
                                    <div class="card-box-light">
                                        <form>
                                            <div class="row">
                                                <div class="col-12 col-lg-6">
                                                    <div class="radio-item-features">
                                                        <div class="package-plan">Monthly</div>
                                                        <label class="features-items">
                                                            <input type="radio" name="radio"/>
                                                            <div class="feature-item-content">
                                                                <div class="feature-item-title">40 Credit Plans<span class="small-text"> /yearly</span></div>
                                                                <span class="price-pc">$100</span>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-6">
                                                    <div class="radio-item-features">
                                                        <div class="package-plan">Yearly</div>
                                                        <label class="features-items">
                                                            <input type="radio" name="radio"/>
                                                            <div class="feature-item-content">
                                                                <div class="feature-item-title">40 Credit<span class="small-text">/Monthly</span></div>
                                                                <span class="price-pc">$1000</span>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-12">
                                                    <div class="buynow-btn">
                                                        <a href="" class="btn btn-fill">Buy Now!</a>
                                                    </div>
                                                    <div class="note">Note : 1 Credit is 1 Buyer</div>
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
 export default ChooseYourPlan;