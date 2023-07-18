import React from 'react';
import {Link , useNavigate} from "react-router-dom";
import Header from "../partials/Layouts/Header";
import Footer from "../partials/Layouts/Footer";

const MyBuyer = () =>{
 return (
    <>
    <Header/>
    <section className="main-section position-relative pt-4 pb-120">
		<div className="container position-relative">
			<div className="back-block">
				<div className="row">
					<div className="col-12 col-sm-4 col-md-4 col-lg-4">
                        <Link to="/" className="back">
							<svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M15 6H1" stroke="#0A2540" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M5.9 11L1 6L5.9 1" stroke="#0A2540" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
							Back
						</Link>
					</div>
					<div className="col-12 col-sm-4 col-md-4 col-lg-4">
						<h6 className="center-head text-center mb-0">My Buyers</h6>
					</div>
					<div className="col-12 col-sm-4 col-md-4 col-lg-4">
						<p className="page-out mb-0 text-center text-sm-end text-md-end text-lg-end">20 Out of 20</p>
					</div>
				</div>
			</div>
			<div className="card-box bg-white-gradient">
				<div className="row">
					<div className="col-12 col-lg-12">
						<div className="card-box-inner">
							<h3 className="text-center">Property Criteria Match With 10 Buyers</h3>
							<div className="property-critera">
								<div className="row">
									<div className="col-12 col-lg-6">
										<div className="property-critera-block">
											<div className="critera-card">
												<div className="center-align">
													<span className="price-img">
                                                        <img src="./assets/images/price.svg" className="img-fluid" /></span>
													<p>Buyer</p>
												</div>
											</div>
											<div className="property-critera-details">
												<ul className="list-unstyled mb-0">
													<li>
														<span className="detail-icon">
                                                            <img src="./assets/images/user-gradient.svg" className="img-fluid" /></span>
														<span className="name-dealer">Devid Miller</span>
													</li>
													<li>
														<span className="detail-icon">
                                                            <img src="./assets/images/phone-gradient.svg" className="img-fluid" /></span>
														<a href="91123456789" className="name-dealer">+91123456789</a>
													</li>
													<li>
														<span className="detail-icon">
                                                            <img src="./assets/images/gmail.svg" className="img-fluid"/></span>
														<a href="mailto:Devidmiller@gmail.com" className="name-dealer">Devidmiller@gmail.com</a>
													</li>
												</ul>
											</div>
											<div className="cornor-block">
												<div className="red-flag"><img src="./assets/images/red-flag.svg" className="img-fluid" /></div>
											</div>
										</div>
									</div>
									<div className="col-12 col-lg-6">
										<div className="property-critera-block">
											<div className="critera-card">
												<div className="center-align">
													<span className="price-img"><img src="./assets/images/price.svg" className="img-fluid" /></span>
													<p>Buyer</p>
												</div>
											</div>
											<div className="property-critera-details">
												<ul className="list-unstyled mb-0">
													<li>
														<span className="detail-icon">
                                                            <img src="./assets/images/user-gradient.svg" className="img-fluid" /></span>
														<span className="name-dealer">Devid Miller</span>
													</li>
													<li>
														<span className="detail-icon">
                                                            <img src="./assets/images/phone-gradient.svg" className="img-fluid" /></span>
														<a href="91123456789" className="name-dealer">+91123456789</a>
													</li>
													<li>
														<span className="detail-icon">
                                                            <img src="./assets/images/gmail.svg" className="img-fluid" />
                                                            </span>
														<a href="mailto:Devidmiller@gmail.com" className="name-dealer">Devidmiller@gmail.com</a>
													</li>
												</ul>
											</div>
											<div className="cornor-block">
												<div className="red-flag">
                                                    <img src="./assets/images/red-flag.svg" className="img-fluid" /></div>
											</div>
										</div>
									</div>
									<div className="col-12 col-lg-6">
										<div className="property-critera-block">
											<div className="critera-card">
												<div className="center-align">
													<span className="price-img"><img src="./assets/images/price.svg" className="img-fluid" /></span>
													<p>Buyer</p>
												</div>
											</div>
											<div className="property-critera-details">
												<ul className="list-unstyled mb-0">
													<li>
														<span className="detail-icon">
                                                            <img src="./assets/images/user-gradient.svg" className="img-fluid" /></span>
														<span className="name-dealer">Devid Miller</span>
													</li>
													<li>
														<span className="detail-icon">
                                                            <img src="./assets/images/phone-gradient.svg" className="img-fluid" /></span>
														<a href="91123456789" className="name-dealer">+91123456789</a>
													</li>
													<li>
														<span className="detail-icon">
                                                            <img src="./assets/images/gmail.svg" className="img-fluid" />
                                                            </span>
														<a href="mailto:Devidmiller@gmail.com" className="name-dealer">Devidmiller@gmail.com</a>
													</li>
												</ul>
											</div>
											<div className="cornor-block">
												<div className="red-flag">
                                                    <img src="./assets/images/red-flag.svg" className="img-fluid" /></div>
											</div>
										</div>
									</div>
									<div className="col-12 col-lg-6">
										<div className="property-critera-block">
											<div className="critera-card">
												<div className="center-align">
													<span className="price-img"><img src="./assets/images/price.svg" className="img-fluid" /></span>
													<p>Buyer</p>
												</div>
											</div>
											<div className="property-critera-details">
												<ul className="list-unstyled mb-0">
													<li>
														<span className="detail-icon">
                                                            <img src="./assets/images/user-gradient.svg" className="img-fluid" /></span>
														<span className="name-dealer">Devid Miller</span>
													</li>
													<li>
														<span className="detail-icon">
                                                            <img src="./assets/images/phone-gradient.svg" className="img-fluid" /></span>
														<a href="91123456789" className="name-dealer">+91123456789</a>
													</li>
													<li>
														<span className="detail-icon">
                                                            <img src="./assets/images/gmail.svg" className="img-fluid" /></span>
														<a href="mailto:Devidmiller@gmail.com" className="name-dealer">Devidmiller@gmail.com</a>
													</li>
												</ul>
											</div>
											<div className="cornor-block">
												<div className="red-flag"><img src="./assets/images/red-flag.svg" className="img-fluid" /></div>
											</div>
										</div>
									</div>
									<div className="col-12 col-lg-6">
										<div className="property-critera-block">
											<div className="critera-card">
												<div className="center-align">
													<span className="price-img"><img src="./assets/images/price.svg" className="img-fluid" /></span>
													<p>Buyer</p>
												</div>
											</div>
											<div className="property-critera-details">
												<ul className="list-unstyled mb-0">
													<li>
														<span className="detail-icon">
                                                            <img src="./assets/images/user-gradient.svg" className="img-fluid" /></span>
														<span className="name-dealer">Devid Miller</span>
													</li>
													<li>
														<span className="detail-icon">
                                                            <img src="./assets/images/phone-gradient.svg" className="img-fluid" /></span>
														<a href="91123456789" className="name-dealer">+91123456789</a>
													</li>
													<li>
														<span className="detail-icon">
                                                            <img src="./assets/images/gmail.svg" className="img-fluid" /></span>
														<a href="mailto:Devidmiller@gmail.com" className="name-dealer">Devidmiller@gmail.com</a>
													</li>
												</ul>
											</div>
											<div className="cornor-block">
												<div className="red-flag">
                                                    <img src="./assets/images/red-flag.svg" className="img-fluid" /></div>
											</div>
										</div>
									</div>
								</div>
								<div className="row justify-content-center">
									<div className="col-12 col-lg-12">
										<div className="want-to-see">
											<h3 className="text-center">Want to see more buyer!</h3>
											<a href="" className="btn btn-fill">Click Here</a>
										</div>
									</div>
								</div>
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
export default MyBuyer;
