import React,{useState,useEffect} from 'react';
import Header from "../partials/Layouts/Header";
import Footer from "../partials/Layouts/Footer";
import {useAuth} from "../../hooks/useAuth"; 
import { Link } from 'react-router-dom';
import { toast } from "react-toastify";
import axios from 'axios';

 const AdditionalCreadits = () => {
    const [plans,setPlans] = useState([]);
    const [isLoader, setIsLoader] = useState(true);
    const [radioValue, setRadioValue] = useState('');
    const {getTokenData} = useAuth();
    const apiUrl = process.env.REACT_APP_API_URL;

    useEffect(()=>{
        getPlans();
    },[]);

    const getPlans = async ()=>{
        try{
            setIsLoader(true);
            let headers = {
                "Accept": "application/json", 
                'Authorization': 'Bearer ' + getTokenData().access_token,
                'auth-token' : getTokenData().access_token,
            }

            let plans = await axios.get(`${apiUrl}getAddtionalCredits`, { headers: headers });
            setPlans(plans.data.data.additional_credits);
            setIsLoader(false);
        }catch(error){
            if(error.response) {
                if (error.response.data.errors) {
                    toast.error(error.response.data.errors, {position: toast.POSITION.TOP_RIGHT});
                }
                if (error.response.data.error) {
                    toast.error(error.response.data.error, {position: toast.POSITION.TOP_RIGHT});
                }
            }
        }
    }
    return(
        <>
            <Header/>
            <section className="main-section position-relative pt-4 pb-5">
                <div className="container position-relative">
                    <div className="back-block">
                        <div className="row">
                            <div className="col-12 col-sm-4 col-md-4 col-lg-4">
                                <Link to="/" className="back">
                                    <svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M15 6H1" stroke="#0A2540" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
                                        <path d="M5.9 11L1 6L5.9 1" stroke="#0A2540" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
                                    </svg>
                                    Back
                                </Link>
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
                                            {(isLoader)?<div className="loader" style={{textAlign:'center'}}><img src="assets/images/loader.svg"/></div>: plans.length>0 ?plans.map((data,index)=>{
                                                return(
                                                    <div className="col-12 col-md-6 col-lg-6" key={index}>
                                                        <div className="radio-item-features">
                                                            <label className="features-items">
                                                                <input type="radio" name="radio"/>
                                                                <div className="feature-item-content">
                                                                    <div className="feature-item-title">{data.credit} Credit Plans</div>
                                                                    <span className="price-pc">${data.price}</span>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                )
                                            }):''}

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