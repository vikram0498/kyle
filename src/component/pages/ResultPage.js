import React from "react";
import MyBuyersResult from "./MyBuyersResult";
import MoreBuyersResult from "./MoreBuyersResult";
import HedgeFundResult from "./HedgefundResult";
import InvestorsResult from "./InvestorsResult";
import Header from "../partials/Layouts/Header";
import Footer from "../partials/Layouts/Footer";
import RedFlagModal from "./RedFlagModal";

const ResultPage = () =>{

    const [buyerId, setBuyerId] = useState(0);
    const [buyerStatus, setBuyerStatus] = useState('');

    const handleRedFlagClick = (buyer_id, buyer_status) => {
        setBuyerId(buyer_id);
        setBuyerStatus(buyer_status);
    }

    return(
        <>
            <Header/>
            <section className="main-section position-relative pt-4 pb-120">
                <div className="container position-relative">
                    <div className="back-block">
                        <div className="row">
                            <div className="col-12 col-sm-4 col-md-4 col-lg-4">
                                <a href="https://deeksha.hipl-staging5.com/kyle/result-page.html#" className="back">
                                    <svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M15 6H1" stroke="#0A2540" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"></path>
                                        <path d="M5.9 11L1 6L5.9 1" stroke="#0A2540" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"></path>
                                    </svg>
                                    Back
                                </a>
                            </div>
                            <div className="col-12 col-sm-4 col-md-4 col-lg-4">
                                <h6 className="center-head text-center mb-0">Result Page</h6>
                            </div>
                            <div className="col-12 col-sm-4 col-md-4 col-lg-4">
                                <p className="page-out mb-0 text-center text-sm-end text-md-end text-lg-end">20 Out of 20</p>
                            </div>
                        </div>
                    </div>
                    <div className="card-box">
                        <div className="row">
                            <div className="col-12 col-lg-12">
                                <div className="card-box-inner">
                                    <div className="custom-divide">
                                        <div className="column-3">
                                            <div className="buyers-tabs">
                                                <ul className="nav nav-pills mb-0" id="pills-tab" role="tablist">
                                                    <li className="nav-item" role="presentation">
                                                        <button className="nav-link active" id="pills-my-buyers-tab" data-bs-toggle="pill" data-bs-target="#pills-my-buyers" type="button" role="tab" aria-controls="pills-my-buyers" aria-selected="true">My Buyers</button>
                                                    </li>
                                                    <li className="nav-item" role="presentation">
                                                        <button className="nav-link" id="pills-more-buyers-tab" data-bs-toggle="pill" data-bs-target="#pills-more-buyers" type="button" role="tab" aria-controls="pills-more-buyers" aria-selected="false">More Buyers</button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div className="column-6">
                                            <div className="inner-page-title text-center">
                                                <h3 className="text-center">Property Criteria Match With 10 Buyers</h3>
                                                <p className="mb-0">5 Additional Buyer interested in similar property</p>
                                            </div>
                                        </div>
                                        <div className="column-3">
                                            <div className="buyers-tabs">
                                                <ul className="nav nav-pills mb-0" id="pills-tab" role="tablist">
                                                    <li className="nav-item" role="presentation">
                                                        <button className="nav-link" id="pills-hedgefund-tab" data-bs-toggle="pill" data-bs-target="#pills-hedgefund" type="button" role="tab" aria-controls="pills-hedgefund" aria-selected="true">Hedgefund</button>
                                                    </li>
                                                    <li className="nav-item" role="presentation">
                                                        <button className="nav-link" id="pills-investors-tab" data-bs-toggle="pill" data-bs-target="#pills-investors" type="button" role="tab" aria-controls="pills-investors" aria-selected="false">Investors</button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="tab-content" id="pills-tabContent">
                                        <MyBuyersResult handleRFClick = {handleRedFlagClick} />
                                        <MoreBuyersResult/>
                                        <HedgeFundResult/>
                                        <InvestorsResult/>

                                        <RedFlagModal buyer_id={buyerId} buyer_status={buyerStatus} />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <Footer/>
        </>
    );
}
export default ResultPage;