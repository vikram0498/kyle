import React, { useState } from 'react';
import {Link , useNavigate} from "react-router-dom";
import Header from "../partials/Layouts/Header";
import Footer from "../partials/Layouts/Footer";

const MyBuyersResult = ({buyerData}) =>{
    console.log('resultPage', buyerData);
 return (
    <>
       <div className="tab-pane fade show active" id="pills-my-buyers" role="tabpanel" aria-labelledby="pills-my-buyers-tab">
            <div className="property-critera">
                <div className="row">
                    { buyerData.map((data) => { 
                        return(<div className="col-12 col-lg-6" key={data.id}>
                            <div className="property-critera-block">
                                <div className="critera-card">
                                    <div className="center-align">
                                        <span className="price-img"><img src="/assets/images/price.svg" className="img-fluid" /></span>
                                        <p>Buyer</p>
                                        <ul className="like-unlike mb-0 list-unstyled">
                                            <li>
                                                <span className="numb">02</span>
                                                <span className="ico-no ml-min"><img src="/assets/images/like.svg" className="img-fluid" /></span>
                                            </li>
                                            <li>
                                                <span className="ico-no mr-min"><img src="/assets/images/unlike.svg" className="img-fluid" /></span>
                                                <span className="numb text-end">0</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div className="property-critera-details">
                                    <ul className="list-unstyled mb-0">
                                        <li>
                                            <span className="detail-icon"><img src="/assets/images/user-gradient.svg" className="img-fluid" /></span>
                                            <span className="name-dealer">{data.first_name+' '+ data.last_name}</span>
                                        </li>
                                        <li>
                                            <span className="detail-icon"><img src="/assets/images/phone-gradient.svg" className="img-fluid" /></span>
                                            <a href={data.phone} className="name-dealer">{data.phone}</a>
                                        </li>
                                        <li>
                                            <span className="detail-icon"><img src="/assets/images/gmail.svg" className="img-fluid" /></span>
                                            <a href={'mailto:'+data.email} className="name-dealer">{data.email}</a>
                                        </li>
                                    </ul>
                                </div>
                                <div className="cornor-block">
                                    <div className="red-flag"><img src="/assets/images/red-flag.svg" className="img-fluid" /></div>
                                </div>
                            </div>
                        </div>)
                    })}
                </div>
            </div>
        </div>
    </>
 )
}
export default MyBuyersResult;
