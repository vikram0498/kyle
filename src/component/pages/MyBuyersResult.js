import React, { useState } from 'react';
import {Link , useNavigate} from "react-router-dom";
import Header from "../partials/Layouts/Header";
import Footer from "../partials/Layouts/Footer";

const MyBuyersResult = ({handleRFClick}) =>{

    const handleRedFlagClick = (buyer_id, buyer_status) => {
        handleRFClick(buyer_id, buyer_status);
    }

 return (
    <>
       <div className="tab-pane fade show active" id="pills-my-buyers" role="tabpanel" aria-labelledby="pills-my-buyers-tab">
            <div className="property-critera">
                <div className="row">
                    <div className="col-12 col-lg-6">
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
                                        <span className="name-dealer">Devid Miller</span>
                                    </li>
                                    <li>
                                        <span className="detail-icon"><img src="/assets/images/phone-gradient.svg" className="img-fluid" /></span>
                                        <a href="https://deeksha.hipl-staging5.com/kyle/91123456789" className="name-dealer">+91123456789</a>
                                    </li>
                                    <li>
                                        <span className="detail-icon"><img src="/assets/images/gmail.svg" className="img-fluid" /></span>
                                        <a href="mailto:Devidmiller@gmail.com" className="name-dealer">Devidmiller@gmail.com</a>
                                    </li>
                                </ul>
                            </div>
                            <div className="cornor-block">
                                <div className="red-flag" onClick={handleRedFlagClick(2, 'not_send')}><img src="/assets/images/red-flag.svg" className="img-fluid" /></div>
                            </div>
                        </div>
                    </div>
                    <div className="col-12 col-lg-6">
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
                                        <span className="name-dealer">Devid Miller</span>
                                    </li>
                                    <li>
                                        <span className="detail-icon"><img src="/assets/images/phone-gradient.svg" className="img-fluid" /></span>
                                        <a href="https://deeksha.hipl-staging5.com/kyle/91123456789" className="name-dealer">+91123456789</a>
                                    </li>
                                    <li>
                                        <span className="detail-icon"><img src="/assets/images/gmail.svg" className="img-fluid" /></span>
                                        <a href="mailto:Devidmiller@gmail.com" className="name-dealer">Devidmiller@gmail.com</a>
                                    </li>
                                </ul>
                            </div>
                            <div className="cornor-block">
                                <div className="red-flag" onClick={handleRedFlagClick(3, 'sent')}><img src="/assets/images/red-flag.svg" className="img-fluid" /></div>
                            </div>
                        </div>
                    </div>
                    <div className="col-12 col-lg-6">
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
                                        <span className="name-dealer">Devid Miller</span>
                                    </li>
                                    <li>
                                        <span className="detail-icon"><img src="/assets/images/phone-gradient.svg" className="img-fluid" /></span>
                                        <a href="https://deeksha.hipl-staging5.com/kyle/91123456789" className="name-dealer">+91123456789</a>
                                    </li>
                                    <li>
                                        <span className="detail-icon"><img src="/assets/images/gmail.svg" className="img-fluid" /></span>
                                        <a href="mailto:Devidmiller@gmail.com" className="name-dealer">Devidmiller@gmail.com</a>
                                    </li>
                                </ul>
                            </div>
                            <div className="cornor-block">
                                <div className="red-flag"><img src="/assets/images/red-flag.svg" className="img-fluid" /></div>
                            </div>
                        </div>
                    </div>
                    <div className="col-12 col-lg-6">
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
                                        <span className="name-dealer">Devid Miller</span>
                                    </li>
                                    <li>
                                        <span className="detail-icon"><img src="/assets/images/phone-gradient.svg" className="img-fluid" /></span>
                                        <a href="https://deeksha.hipl-staging5.com/kyle/91123456789" className="name-dealer">+91123456789</a>
                                    </li>
                                    <li>
                                        <span className="detail-icon"><img src="/assets/images/gmail.svg" className="img-fluid" /></span>
                                        <a href="mailto:Devidmiller@gmail.com" className="name-dealer">Devidmiller@gmail.com</a>
                                    </li>
                                </ul>
                            </div>
                            <div className="cornor-block">
                                <div className="red-flag"><img src="/assets/images/red-flag.svg" className="img-fluid" /></div>
                            </div>
                        </div>
                    </div>
                    <div className="col-12 col-lg-6">
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
                                        <span className="name-dealer">Devid Miller</span>
                                    </li>
                                    <li>
                                        <span className="detail-icon"><img src="/assets/images/phone-gradient.svg" className="img-fluid" /></span>
                                        <a href="https://deeksha.hipl-staging5.com/kyle/91123456789" className="name-dealer">+91123456789</a>
                                    </li>
                                    <li>
                                        <span className="detail-icon"><img src="/assets/images/gmail.svg" className="img-fluid" /></span>
                                        <a href="mailto:Devidmiller@gmail.com" className="name-dealer">Devidmiller@gmail.com</a>
                                    </li>
                                </ul>
                            </div>
                            <div className="cornor-block">
                                <div className="red-flag"><img src="/assets/images/red-flag.svg" className="img-fluid" /></div>
                                <div className="show-hide-data">
                                    <button type="button" className="unhide-btn">
                                        <span className="icon-unhide">
                                            <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <g clipPath="url(#clip0_677_7400)">
                                            <path d="M1.16699 7.99996C1.16699 7.99996 3.83366 2.66663 8.50033 2.66663C13.167 2.66663 15.8337 7.99996 15.8337 7.99996C15.8337 7.99996 13.167 13.3333 8.50033 13.3333C3.83366 13.3333 1.16699 7.99996 1.16699 7.99996Z" stroke="white" strokeLinecap="round" strokeLinejoin="round"></path>
                                            <path d="M8.5 10C9.60457 10 10.5 9.10457 10.5 8C10.5 6.89543 9.60457 6 8.5 6C7.39543 6 6.5 6.89543 6.5 8C6.5 9.10457 7.39543 10 8.5 10Z" stroke="white" strokeLinecap="round" strokeLinejoin="round"></path>
                                            </g>
                                            <defs>
                                            <clippath id="clip0_677_7400">
                                            <rect width="16" height="16" fill="white" transform="translate(0.5)"></rect>
                                            </clippath>
                                            </defs>
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="col-12 col-lg-6">
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
                                        <span className="name-dealer">Devid Miller</span>
                                    </li>
                                    <li>
                                        <span className="detail-icon"><img src="/assets/images/phone-gradient.svg" className="img-fluid" /></span>
                                        <a href="https://deeksha.hipl-staging5.com/kyle/91123456789" className="name-dealer">+91123456789</a>
                                    </li>
                                    <li>
                                        <span className="detail-icon"><img src="/assets/images/gmail.svg" className="img-fluid" /></span>
                                        <a href="mailto:Devidmiller@gmail.com" className="name-dealer">Devidmiller@gmail.com</a>
                                    </li>
                                </ul>
                            </div>
                            <div className="cornor-block">
                                <div className="red-flag"><img src="/assets/images/red-flag.svg" className="img-fluid" /></div>
                                <div className="show-hide-data">
                                    <button type="button" className="unhide-btn">
                                        <span className="icon-unhide">
                                            <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <g clipPath="url(#clip0_677_7400)">
                                            <path d="M1.16699 7.99996C1.16699 7.99996 3.83366 2.66663 8.50033 2.66663C13.167 2.66663 15.8337 7.99996 15.8337 7.99996C15.8337 7.99996 13.167 13.3333 8.50033 13.3333C3.83366 13.3333 1.16699 7.99996 1.16699 7.99996Z" stroke="white" strokeLinecap="round" strokeLinejoin="round"></path>
                                            <path d="M8.5 10C9.60457 10 10.5 9.10457 10.5 8C10.5 6.89543 9.60457 6 8.5 6C7.39543 6 6.5 6.89543 6.5 8C6.5 9.10457 7.39543 10 8.5 10Z" stroke="white" strokeLinecap="round" strokeLinejoin="round"></path>
                                            </g>
                                            <defs>
                                            <clippath id="clip0_677_7400">
                                            <rect width="16" height="16" fill="white" transform="translate(0.5)"></rect>
                                            </clippath>
                                            </defs>
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="col-12 col-lg-6">
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
                                        <span className="name-dealer">Devid Miller</span>
                                    </li>
                                    <li>
                                        <span className="detail-icon"><img src="/assets/images/phone-gradient.svg" className="img-fluid" /></span>
                                        <a href="https://deeksha.hipl-staging5.com/kyle/91123456789" className="name-dealer">+91123456789</a>
                                    </li>
                                    <li>
                                        <span className="detail-icon"><img src="/assets/images/gmail.svg" className="img-fluid" /></span>
                                        <a href="mailto:Devidmiller@gmail.com" className="name-dealer">Devidmiller@gmail.com</a>
                                    </li>
                                </ul>
                            </div>
                            <div className="cornor-block">
                                <div className="red-flag"><img src="/assets/images/red-flag.svg" className="img-fluid" /></div>
                            </div>
                        </div>
                    </div>
                    <div className="col-12 col-lg-6">
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
                                        <span className="name-dealer">Devid Miller</span>
                                    </li>
                                    <li>
                                        <span className="detail-icon"><img src="/assets/images/phone-gradient.svg" className="img-fluid" /></span>
                                        <a href="https://deeksha.hipl-staging5.com/kyle/91123456789" className="name-dealer">+91123456789</a>
                                    </li>
                                    <li>
                                        <span className="detail-icon"><img src="/assets/images/gmail.svg" className="img-fluid" /></span>
                                        <a href="mailto:Devidmiller@gmail.com" className="name-dealer">Devidmiller@gmail.com</a>
                                    </li>
                                </ul>
                            </div>
                            <div className="cornor-block">
                                <div className="red-flag"><img src="/assets/images/red-flag.svg" className="img-fluid" /></div>
                            </div>
                        </div>
                    </div>
                    <div className="col-12 col-lg-6">
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
                                        <span className="name-dealer">Devid Miller</span>
                                    </li>
                                    <li>
                                        <span className="detail-icon"><img src="/assets/images/phone-gradient.svg" className="img-fluid" /></span>
                                        <a href="https://deeksha.hipl-staging5.com/kyle/91123456789" className="name-dealer">+91123456789</a>
                                    </li>
                                    <li>
                                        <span className="detail-icon"><img src="/assets/images/gmail.svg" className="img-fluid" /></span>
                                        <a href="mailto:Devidmiller@gmail.com" className="name-dealer">Devidmiller@gmail.com</a>
                                    </li>
                                </ul>
                            </div>
                            <div className="cornor-block">
                                <div className="red-flag"><img src="/assets/images/red-flag.svg" className="img-fluid" /></div>
                            </div>
                        </div>
                    </div>
                    <div className="col-12 col-lg-6">
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
                                        <span className="name-dealer">Devid Miller</span>
                                    </li>
                                    <li>
                                        <span className="detail-icon"><img src="/assets/images/phone-gradient.svg" className="img-fluid" /></span>
                                        <a href="https://deeksha.hipl-staging5.com/kyle/91123456789" className="name-dealer">+91123456789</a>
                                    </li>
                                    <li>
                                        <span className="detail-icon"><img src="/assets/images/gmail.svg" className="img-fluid" /></span>
                                        <a href="mailto:Devidmiller@gmail.com" className="name-dealer">Devidmiller@gmail.com</a>
                                    </li>
                                </ul>
                            </div>
                            <div className="cornor-block">
                                <div className="red-flag"><img src="/assets/images/red-flag.svg" className="img-fluid" /></div>
                            </div>
                        </div>
                    </div>
                    <div className="col-12 col-lg-6">
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
                                        <span className="name-dealer">Devid Miller</span>
                                    </li>
                                    <li>
                                        <span className="detail-icon"><img src="/assets/images/phone-gradient.svg" className="img-fluid" /></span>
                                        <a href="https://deeksha.hipl-staging5.com/kyle/91123456789" className="name-dealer">+91123456789</a>
                                    </li>
                                    <li>
                                        <span className="detail-icon"><img src="/assets/images/gmail.svg" className="img-fluid" /></span>
                                        <a href="mailto:Devidmiller@gmail.com" className="name-dealer">Devidmiller@gmail.com</a>
                                    </li>
                                </ul>
                            </div>
                            <div className="cornor-block">
                                <div className="red-flag"><img src="/assets/images/red-flag.svg" className="img-fluid" /></div>
                            </div>
                        </div>
                    </div>
                    <div className="col-12 col-lg-6">
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
                                        <span className="name-dealer">Devid Miller</span>
                                    </li>
                                    <li>
                                        <span className="detail-icon"><img src="/assets/images/phone-gradient.svg" className="img-fluid" /></span>
                                        <a href="https://deeksha.hipl-staging5.com/kyle/91123456789" className="name-dealer">+91123456789</a>
                                    </li>
                                    <li>
                                        <span className="detail-icon"><img src="/assets/images/gmail.svg" className="img-fluid" /></span>
                                        <a href="mailto:Devidmiller@gmail.com" className="name-dealer">Devidmiller@gmail.com</a>
                                    </li>
                                </ul>
                            </div>
                            <div className="cornor-block">
                                <div className="red-flag"><img src="/assets/images/red-flag.svg" className="img-fluid" /></div>
                            </div>
                        </div>
                    </div>
                    <div className="col-12 col-lg-6">
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
                                        <span className="name-dealer">Devid Miller</span>
                                    </li>
                                    <li>
                                        <span className="detail-icon"><img src="/assets/images/phone-gradient.svg" className="img-fluid" /></span>
                                        <a href="https://deeksha.hipl-staging5.com/kyle/91123456789" className="name-dealer">+91123456789</a>
                                    </li>
                                    <li>
                                        <span className="detail-icon"><img src="/assets/images/gmail.svg" className="img-fluid" /></span>
                                        <a href="mailto:Devidmiller@gmail.com" className="name-dealer">Devidmiller@gmail.com</a>
                                    </li>
                                </ul>
                            </div>
                            <div className="cornor-block">
                                <div className="red-flag"><img src="/assets/images/red-flag.svg" className="img-fluid" /></div>
                            </div>
                        </div>
                    </div>
                    <div className="col-12 col-lg-6">
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
                                        <span className="name-dealer">Devid Miller</span>
                                    </li>
                                    <li>
                                        <span className="detail-icon"><img src="/assets/images/phone-gradient.svg" className="img-fluid" /></span>
                                        <a href="https://deeksha.hipl-staging5.com/kyle/91123456789" className="name-dealer">+91123456789</a>
                                    </li>
                                    <li>
                                        <span className="detail-icon"><img src="/assets/images/gmail.svg" className="img-fluid" /></span>
                                        <a href="mailto:Devidmiller@gmail.com" className="name-dealer">Devidmiller@gmail.com</a>
                                    </li>
                                </ul>
                            </div>
                            <div className="cornor-block">
                                <div className="red-flag"><img src="/assets/images/red-flag.svg" className="img-fluid" /></div>
                            </div>
                        </div>
                    </div>
                    <div className="col-12 col-lg-6">
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
                                        <span className="name-dealer">Devid Miller</span>
                                    </li>
                                    <li>
                                        <span className="detail-icon"><img src="/assets/images/phone-gradient.svg" className="img-fluid" /></span>
                                        <a href="https://deeksha.hipl-staging5.com/kyle/91123456789" className="name-dealer">+91123456789</a>
                                    </li>
                                    <li>
                                        <span className="detail-icon"><img src="/assets/images/gmail.svg" className="img-fluid" /></span>
                                        <a href="mailto:Devidmiller@gmail.com" className="name-dealer">Devidmiller@gmail.com</a>
                                    </li>
                                </ul>
                            </div>
                            <div className="cornor-block">
                                <div className="red-flag"><img src="/assets/images/red-flag.svg" className="img-fluid" /></div>
                            </div>
                        </div>
                    </div>
                    <div className="col-12 col-lg-6">
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
                                        <span className="name-dealer">Devid Miller</span>
                                    </li>
                                    <li>
                                        <span className="detail-icon"><img src="/assets/images/phone-gradient.svg" className="img-fluid" /></span>
                                        <a href="https://deeksha.hipl-staging5.com/kyle/91123456789" className="name-dealer">+91123456789</a>
                                    </li>
                                    <li>
                                        <span className="detail-icon"><img src="/assets/images/gmail.svg" className="img-fluid" /></span>
                                        <a href="mailto:Devidmiller@gmail.com" className="name-dealer">Devidmiller@gmail.com</a>
                                    </li>
                                </ul>
                            </div>
                            <div className="cornor-block">
                                <div className="red-flag"><img src="/assets/images/red-flag.svg" className="img-fluid" /></div>
                            </div>
                        </div>
                    </div>
                    <div className="col-12 col-lg-6">
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
                                        <span className="name-dealer">Devid Miller</span>
                                    </li>
                                    <li>
                                        <span className="detail-icon"><img src="/assets/images/phone-gradient.svg" className="img-fluid" /></span>
                                        <a href="https://deeksha.hipl-staging5.com/kyle/91123456789" className="name-dealer">+91123456789</a>
                                    </li>
                                    <li>
                                        <span className="detail-icon"><img src="/assets/images/gmail.svg" className="img-fluid" /></span>
                                        <a href="mailto:Devidmiller@gmail.com" className="name-dealer">Devidmiller@gmail.com</a>
                                    </li>
                                </ul>
                            </div>
                            <div className="cornor-block">
                                <div className="red-flag"><img src="/assets/images/red-flag.svg" className="img-fluid" /></div>
                            </div>
                        </div>
                    </div>
                    <div className="col-12 col-lg-6">
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
                                        <span className="name-dealer">Devid Miller</span>
                                    </li>
                                    <li>
                                        <span className="detail-icon"><img src="/assets/images/phone-gradient.svg" className="img-fluid" /></span>
                                        <a href="https://deeksha.hipl-staging5.com/kyle/91123456789" className="name-dealer">+91123456789</a>
                                    </li>
                                    <li>
                                        <span className="detail-icon"><img src="/assets/images/gmail.svg" className="img-fluid" /></span>
                                        <a href="mailto:Devidmiller@gmail.com" className="name-dealer">Devidmiller@gmail.com</a>
                                    </li>
                                </ul>
                            </div>
                            <div className="cornor-block">
                                <div className="red-flag"><img src="/assets/images/red-flag.svg" className="img-fluid" /></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </>
 )
}
export default MyBuyersResult;
