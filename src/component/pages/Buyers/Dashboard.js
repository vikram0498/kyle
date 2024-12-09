import { Link } from "react-router-dom";
import BuyerHeader from "../../partials/Layouts/BuyerHeader";
import Footer from "../../partials/Layouts/Footer";
const Dashboard = () => {
  return (
    <>
      <BuyerHeader />
        <section className="main-section position-relative pt-4 pb-120">
            <div className="container position-relative pat-40">
                <div className="back-block">
                    <div className="row">
                        <div className="col-12 col-sm-6">
                            <div className="d-flex gap-3 align-items-center dash_boost_profile">
                                <span className="upload-buyer-icon">
                                    <img src="/assets/images/rocket.svg" className="img-fluid" alt="" />
                                </span>
                                <p className="mb-0">boost your profile</p>
                            </div>
                        </div>
                        <div className="col-12 col-sm-6 text-sm-end align-self-center mt-3 mt-sm-0">
                            <Link to="/boost-your-profile" className="inner_boost_btn">
                                Boost Now
                            </Link>
                        </div>
                    </div>
                </div>
                <div>
                    <ul className="buyer_dash_linkTop">
                        <li>
                            <Link href="#">
                                <span className="buyer-icon">
                                    <img src="/assets/images/deal-icon.svg" className="img-fluid" alt="" />
                                </span>
                                Deals
                            </Link>
                        </li>
                        <li>
                            <Link href="#">
                                <span className="buyer-icon">
                                    <img src="/assets/images/buybox-criteria-icon.svg" className="img-fluid" alt="" />
                                </span>
                                buybox criteria
                            </Link>
                        </li>
                        <li>
                            <Link href="#">
                                <span className="buyer-icon">
                                    <img src="/assets/images/complete-verification-icon.svg" className="img-fluid" alt="" />
                                </span>
                                complete verification
                            </Link>
                        </li>
                        <li>
                            <Link href="#">
                                <span className="buyer-icon">
                                    <img src="/assets/images/chats-icon.svg" className="img-fluid" alt="" />
                                </span>
                                Chats
                            </Link>
                        </li>
                    </ul>
                </div>
                <div className="row">
                    <div className="col-12 col-lg-6">
                        <div className="buyer_dash_bothBtn mt-0">
                            <Link href="#">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="34" viewBox="0 0 35 34" fill="none">
                                        <path d="M31.2407 28.7583L25.999 23.5167C30.3907 17.9917 29.5407 9.91668 24.0157 5.52501C18.4907 1.13335 10.4157 2.12501 6.02404 7.50835C1.63237 13.0333 2.62404 21.1083 8.00737 25.5C12.6824 29.1833 19.3407 29.1833 24.0157 25.5L29.2574 30.7417C29.824 31.3083 30.674 31.3083 31.2407 30.7417C31.8074 30.175 31.8074 29.325 31.2407 28.7583ZM16.0824 25.5C10.5574 25.5 6.1657 21.1083 6.1657 15.5833C6.1657 10.0583 10.5574 5.66668 16.0824 5.66668C21.6074 5.66668 25.999 10.0583 25.999 15.5833C25.999 21.1083 21.6074 25.5 16.0824 25.5Z" fill="#121639"/>
                                    </svg>
                                </span>
                                Browse all matches
                            </Link>
                            <Link href="#">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 34 34" fill="none">
                                        <path d="M30.1383 25.2632C30.1383 29.6179 24.2566 33.15 16.9998 33.15C9.74308 33.15 3.86133 29.6179 3.86133 25.2632C3.86133 20.9055 9.74308 17.3733 16.9998 17.3733C24.2566 17.3733 30.1383 20.9055 30.1383 25.2632Z" fill="url(#paint0_linear_564_7672)"/>
                                        <path d="M9.58203 8.26873C9.58203 4.17062 12.9026 0.850098 17.0006 0.850098C21.0986 0.850098 24.4192 4.17062 24.4192 8.26873C24.4192 12.3667 21.0986 15.6911 17.0006 15.6911C12.9026 15.6911 9.58203 12.3667 9.58203 8.26873Z" fill="url(#paint1_linear_564_7672)"/>
                                        <defs>
                                            <linearGradient id="paint0_linear_564_7672" x1="-4.13152" y1="2.10465" x2="27.1543" y2="36.3902" gradientUnits="userSpaceOnUse">
                                                <stop stop-color="#97E0FF"/>
                                                <stop offset="1" stop-color="#1075FF"/>
                                            </linearGradient>
                                            <linearGradient id="paint1_linear_564_7672" x1="4.33017" y1="-5.61568" x2="35.616" y2="28.6699" gradientUnits="userSpaceOnUse">
                                                <stop stop-color="#97E0FF"/>
                                                <stop offset="1" stop-color="#1075FF"/>
                                            </linearGradient>
                                        </defs>
                                    </svg>
                                </span>
                                My Profile
                            </Link>
                        </div>
                    </div>
                    <div className="col-12 col-lg-6">
                        <div className="card-box mt-0 buyer_dash_deals">
                            <h3>featured deals</h3>
                            <ul>
                                <li>
                                    <div className="dash_deals_left">
                                        <img src="/assets/images/property-img.png" className="img-fluid" alt="" />
                                    </div>
                                    <div className="dash_deals_center">
                                        <h4>4517 Washington Ave. Manch...</h4>
                                        <p>real easte company that...</p>
                                    </div>
                                    <div className="dash_deals_right">
                                        <Link href="#">View Now</Link>
                                    </div>
                                </li>
                                <li>
                                    <div className="dash_deals_left">
                                        <img src="/assets/images/property-img.png" className="img-fluid" alt="" />
                                    </div>
                                    <div className="dash_deals_center">
                                        <h4>4517 Washington Ave. Manch...</h4>
                                        <p>real easte company that...</p>
                                    </div>
                                    <div className="dash_deals_right">
                                        <Link href="#">View Now</Link>
                                    </div>
                                </li>
                                <li>
                                    <div className="dash_deals_left">
                                        <img src="/assets/images/property-img.png" className="img-fluid" alt="" />
                                    </div>
                                    <div className="dash_deals_center">
                                        <h4>4517 Washington Ave. Manch...</h4>
                                        <p>real easte company that...</p>
                                    </div>
                                    <div className="dash_deals_right">
                                        <Link href="#">View Now</Link>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
      <Footer />
    </>
  );
};
export default Dashboard;
