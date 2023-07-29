import React, {useContext, useEffect} from "react";
import {Link , useNavigate} from "react-router-dom";
import AuthContext from "../../context/authContext";
import Header from "../partials/Layouts/Header";
import Footer from "../partials/Layouts/Footer";
import UploadMultipleBuyersOnChange from "../partials/UploadMultipleBuyersOnChange";

function Home ({userDetails}){
    const {authData} = useContext(AuthContext);
    return (
        <>
            <Header/>
            <section className="main-section pt-120 pb-5 position-relative">
                <div className="watch-video block-fix">
                    <p>Don’t Know How to Upload</p>
                    <a href="" className="title">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="#121639" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
                            <path d="M10 8L16 12L10 16V8Z" stroke="#121639" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
                        </svg>
                        Watch the Video!
                    </a>
                </div>
                <div className="container position-relative">
                    <div className="row justify-content-center">
                        <div className="col-12 col-lg-8">
                            <div className="heading-title">
                                <h2>Select The option below!</h2>
                                <p>Upload your buyer’s criteria and use the Buybox Search to find the right buyers for your deals.</p>
                            </div>
                        </div>
                    </div>
                    <div className="row row-gap">
                        <div className="col-12 col-sm-6 col-md-6 col-lg-3">
                            <Link to='/add-buyer-details' className="grid-block-view">
                                <div className="grid-block-icon"><img src="./assets/images/upload-buyer.svg" className="img-fluid" alt="" /></div>
                                <h3>Upload Buyer</h3>
                            </Link>
                        </div>
                        <div className="col-12 col-sm-6 col-md-6 col-lg-3">
                            <Link to='/sellers-form' className="grid-block-view">
                                <div className="grid-block-icon"><img src="./assets/images/buybox-search.svg" className="img-fluid" alt="" /></div>
                                <h3>Buybox Search</h3>
                            </Link>
                        </div>
                        <div className="col-12 col-sm-6 col-md-6 col-lg-3">
                            <Link to='/my-buyers' className="grid-block-view">
                                <div className="grid-block-icon"><img src="./assets/images/my-buyers.svg" className="img-fluid" alt="" /></div>
                                <h3>My Buyers</h3>
                            </Link>
                        </div>
                        <div className="col-12 col-sm-6 col-md-6 col-lg-3">
                           <UploadMultipleBuyersOnChange/>
                        </div>
                    </div>
                </div>
            </section>
            <Footer/>
        </>
    )
}
  
export default Home;