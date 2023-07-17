import React, {useContext, useEffect} from "react";
import {useNavigate} from "react-router-dom";
import AuthContext from "../context/authContext";
import Head  from "../layouts/Head";
import Footer from "../layouts/Footer"

//import Image from "../assets/images"

function Home (){
    const {authData} = useContext(AuthContext);
    const navigate = useNavigate();
    
    useEffect(() => {
        if(!authData.signedIn) {
            navigate('/login');
        }
    }, [navigate, authData]);

    function importAll(r) {
        let images = {};
        r.keys().forEach((item, index) => { images[item.replace('./', '')] = r(item); });
        return images
    }
    const images = importAll(require.context('../assets/images', false, /\.(png|jpe?g|svg)$/));
       
    return (
        <>
            <Head/>
            
            <header className="dashboard-header">
                <div className="container-fluid">
                    <div className="row align-items-center">
                        <div className="col-7 col-md-4 col-lg-3">
                            <div className="header-logo">
                                <a href="">
                                    <img src={images["logo.svg"]} className="img-fluid" />
                                </a>
                            </div>
                        </div>
                        <div className="col-5 col-md-8 col-lg-9">
                            <div className="block-session">
                                <div className="upload-buyer">
                                    <span className="upload-buyer-icon">
                                        <img src={images["folder.svg"]}className="img-fluid" />
                                    </span>
                                    <p>uploaded Buyer Data : <b>0</b></p>
                                </div>
                                <div className="dropdown user-dropdown">
                                    <button className="btn dropdown-toggle ms-auto" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                        <div className="dropdown-data">
                                            <div className="img-user">
                                                <img src={images["avtar.svg"]}className="img-fluid" alt="" />
                                                </div>
                                            <div className="welcome-user">
                                                <span className="welcome">welcome</span>
                                                <span className="user-name-title">John Thomsan</span>
                                            </div>
                                        </div>
                                        <span className="arrow-icon">
                                            <svg width="14" height="8" viewBox="0 0 14 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M13.002 7L7.00195 0.999999L1.00195 7" stroke="black" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
                                            </svg>
                                        </span>
                                    </button>
                                    <div className="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <ul className="list-unstyled mb-0">
                                            <li><a className="dropdown-item" href="my-profile.html">
                                                <img src={images["user-login.svg"]}className="img-fluid" />
                                                My Profile</a></li>
                                            <li><a className="dropdown-item" href="#">
                                                <img src={images["booksaved.svg"]} className="img-fluid" />
                                                My Buyers Data</a></li>
                                            <li><a className="dropdown-item" href="#">
                                                <img src={images["messages.svg"]} className="img-fluid" />
                                                Support</a></li>
                                            <li><a className="dropdown-item" href="#">
                                                <img src={images["logoutcurve.svg"]} className="img-fluid" />
                                                Logout</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

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
                            <a href="single-buyer.html" className="grid-block-view">
                                <div className="grid-block-icon"><img src={images["upload-buyer.svg"]} className="img-fluid" alt="" /></div>
                                <h3>Upload Buyer</h3>
                            </a>
                        </div>
                        <div className="col-12 col-sm-6 col-md-6 col-lg-3">
                            <a href="sellers-form.html" className="grid-block-view">
                                <div className="grid-block-icon"><img src={images["buybox-search.svg"]} className="img-fluid" alt="" /></div>
                                <h3>Buybox Search</h3>
                            </a>
                        </div>
                        <div className="col-12 col-sm-6 col-md-6 col-lg-3">
                            <a href="my-buyers.html" className="grid-block-view">
                                <div className="grid-block-icon"><img src={images["my-buyers.svg"]} className="img-fluid" alt="" /></div>
                                <h3>My Buyers</h3>
                            </a>
                        </div>
                        <div className="col-12 col-sm-6 col-md-6 col-lg-3">
                            <form className="form-container upload-multiple-data" encType='multipart/form-data'>
                                <div className="upload-files-container">
                                    <div className="drag-file-area">
                                        <span className="upload-icon"> </span>
                                        <h5>Upload Multiple Buyer Data</h5>
                                        <p className="dynamic-message mb-0">Drag & Drop</p>
                                        <button type="button" className="upload-button">
                                            <img src={images["folder-big.svg"]} className="img-fluid" alt="" />
                                        </button>
                                        <label className="label">
                                            <span className="browse-files">
                                                <input type="file" className="default-file-input"/> 
                                                <span className="d-block upload-file">Upload your .CSV file</span>
                                                <span className="browse-files-text">browse Now</span> 
                                            </span> 
                                        </label>
                                    </div>
                                    <span className="cannot-upload-message"> <span className="error">error</span> Please select a file first <span className="cancel-alert-button">cancel</span> </span>
                                    <div className="file-block">
                                        <div className="file-info"><span className="file-name"> </span> | <span className="file-size">  </span> </div>
                                        
                                        <div className="progress-bar"> </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

            <Footer/>
        </>
    )
}
  
export default Home;