import React from 'react';
import { Col, Container, Image, Nav, Row, Tab, Table } from 'react-bootstrap';
import { Link } from 'react-router-dom';
import Header from "../../partials/Layouts/Header";
import Footer from '../../partials/Layouts/Footer';

const PropertyDealDetails = () => {
  return (
    <>
        <Header />
            <section className='main-section position-relative pt-4 pb-120'>
                <Container className='position-relative'>
                    <div className="back-block">
                        <div className="row">
                            <div className="col-4 col-sm-4 col-md-4 col-lg-4">
                                <Link to="/" className="back">
                                    <svg
                                    width="16"
                                    height="12"
                                    viewBox="0 0 16 12"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                    >
                                    <path
                                        d="M15 6H1"
                                        stroke="#0A2540"
                                        strokeWidth="1.5"
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                    />
                                    <path
                                        d="M5.9 11L1 6L5.9 1"
                                        stroke="#0A2540"
                                        strokeWidth="1.5"
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                    />
                                    </svg>
                                    Back
                                </Link>
                            </div>
                            <div className="col-7 col-sm-4 col-md-4 col-lg-4 align-self-center">
                                <h6 className="center-head text-center mb-0">
                                    Property Deal Detail
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div className='card-box column_bg_space'>
                        <Tab.Container defaultActiveKey="first">
                            <div className='deal_column radius-b-0'>
                                <div className='deal_left_column border-end-0 detail_deal_column position-relative'>
                                    <div className='detail_deal_left flex_1column'>
                                        <div className='pro_img'>
                                            <Image src='/assets/images/property-img.png' alt='' />
                                        </div>
                                        <div className='pro_details'>
                                            <h3>real easte company that prioritizes Property</h3>
                                            <Nav variant="pills">
                                                <Nav.Item>
                                                    <Nav.Link eventKey="first">
                                                        <div className='list_icon'>
                                                            <Image src='/assets/images/home_buy.svg' alt='' />
                                                        </div>
                                                        <div className='list_content'>
                                                            <span>69</span>
                                                            <p>Total Buyer</p>
                                                        </div>
                                                    </Nav.Link>
                                                </Nav.Item>
                                                <Nav.Item>
                                                    <Nav.Link eventKey="first">
                                                        <div className='list_icon'>
                                                            <Image src='/assets/images/home_buy.svg' alt='' />
                                                        </div>
                                                        <div className='list_content'>
                                                            <span>23</span>
                                                            <p>Want To Buy</p>
                                                        </div>
                                                    </Nav.Link>
                                                </Nav.Item>
                                                <Nav.Item>
                                                    <Nav.Link eventKey="second">
                                                        <div className='list_icon'>
                                                            <Image src='/assets/images/home_check.svg' alt='' />
                                                        </div>
                                                        <div className='list_content'>
                                                            <span>23</span>
                                                            <p>Interested</p>
                                                        </div>
                                                    </Nav.Link>
                                                </Nav.Item>
                                                <Nav.Item>
                                                    <Nav.Link eventKey="third">
                                                        <div className='list_icon'>
                                                            <Image src='/assets/images/home_close.svg' alt='' />
                                                        </div>
                                                        <div className='list_content'>
                                                            <span>23</span>
                                                            <p>Not Interested</p>
                                                        </div>
                                                    </Nav.Link>
                                                </Nav.Item>
                                            </Nav>
                                        </div>
                                    </div>
                                    <div className='total_notified'>
                                        <p>Total Notified Buyers <span>: 58</span></p>
                                    </div>
                                </div>
                            </div>  
                            <div className='pro_deal_table'>
                                <Tab.Content>
                                    <Tab.Pane eventKey="first">
                                        <Table>
                                            <thead>
                                                <tr>
                                                    <th>Buyer Name</th>
                                                    <th>Phone Number</th>
                                                    <th>Email Address</th>
                                                    <th>documents</th>
                                                    <th>Status</th>
                                                    <th>Chat</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr><td colspan="5"></td></tr>
                                                <tr>
                                                    <td><span><span className='deal_user_img_block'><Image src='/assets/images/user-img1.png' className='deal_user_img' alt='' /> <Image src='/assets/images/pcheck.svg' className='user_verified_check' alt='' /></span> Brooklyn Simmons</span></td>
                                                    <td>+91123456789</td>
                                                    <td>Devidmiller@gmail.com</td>
                                                    <td><span><Image src='/assets/images/folder-zip.svg' className='' alt='' /> Documents.zip</span></td>
                                                    <td><span><Image src='/assets/images/folder-zip.svg' className='' alt='' /> Documents.zip</span></td>
                                                    <td><span><Image src='/assets/images/chat-icon.svg' className='' alt='' /> Chat With Buyer</span></td>
                                                </tr>
                                                <tr>
                                                    <td><span><span className='deal_user_img_block'><Image src='/assets/images/user-img1.png' className='deal_user_img' alt='' /> <Image src='/assets/images/pcheck.svg' className='user_verified_check' alt='' /></span> Brooklyn Simmons</span></td>
                                                    <td>+91123456789</td>
                                                    <td>Devidmiller@gmail.com</td>
                                                    <td><span><Image src='/assets/images/folder-zip.svg' className='' alt='' /> Documents.zip</span></td>
                                                    <td><span><Image src='/assets/images/folder-zip.svg' className='' alt='' /> Documents.zip</span></td>
                                                    <td><span><Image src='/assets/images/chat-icon.svg' className='' alt='' /> Chat With Buyer</span></td>
                                                </tr>
                                                <tr>
                                                    <td><span><span className='deal_user_img_block'><Image src='/assets/images/user-img1.png' className='deal_user_img' alt='' /> <Image src='/assets/images/pcheck.svg' className='user_verified_check' alt='' /></span> Brooklyn Simmons</span></td>
                                                    <td>+91123456789</td>
                                                    <td>Devidmiller@gmail.com</td>
                                                    <td><span><Image src='/assets/images/folder-zip.svg' className='' alt='' /> Documents.zip</span></td>
                                                    <td><span><Image src='/assets/images/folder-zip.svg' className='' alt='' /> Documents.zip</span></td>
                                                    <td><span><Image src='/assets/images/chat-icon.svg' className='' alt='' /> Chat With Buyer</span></td>
                                                </tr>
                                            </tbody>
                                        </Table>
                                    </Tab.Pane>
                                    <Tab.Pane eventKey="second">
                                        <Table>
                                            <thead>
                                                <tr>
                                                    <th>Buyer Name</th>
                                                    <th>Phone Number</th>
                                                    <th>Email Address</th>
                                                    <th>documents</th>
                                                    <th>Status</th>
                                                    <th>Chat</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr><td colspan="5"></td></tr>
                                                <tr>
                                                    <td><span><span className='deal_user_img_block'><Image src='/assets/images/user-img1.png' className='deal_user_img' alt='' /> <Image src='/assets/images/pcheck.svg' className='user_verified_check' alt='' /></span> Brooklyn Simmons</span></td>
                                                    <td>+91123456789</td>
                                                    <td>Devidmiller@gmail.com</td>
                                                    <td><span><Image src='/assets/images/folder-zip.svg' className='' alt='' /> Documents.zip</span></td>
                                                    <td><span><Image src='/assets/images/folder-zip.svg' className='' alt='' /> Documents.zip</span></td>
                                                    <td><span><Image src='/assets/images/chat-icon.svg' className='' alt='' /> Chat With Buyer</span></td>
                                                </tr>
                                                <tr>
                                                    <td><span><span className='deal_user_img_block'><Image src='/assets/images/user-img1.png' className='deal_user_img' alt='' /> <Image src='/assets/images/pcheck.svg' className='user_verified_check' alt='' /></span> Brooklyn Simmons</span></td>
                                                    <td>+91123456789</td>
                                                    <td>Devidmiller@gmail.com</td>
                                                    <td><span><Image src='/assets/images/folder-zip.svg' className='' alt='' /> Documents.zip</span></td>
                                                    <td><span><Image src='/assets/images/folder-zip.svg' className='' alt='' /> Documents.zip</span></td>
                                                    <td><span><Image src='/assets/images/chat-icon.svg' className='' alt='' /> Chat With Buyer</span></td>
                                                </tr>
                                                <tr>
                                                    <td><span><span className='deal_user_img_block'><Image src='/assets/images/user-img1.png' className='deal_user_img' alt='' /> <Image src='/assets/images/pcheck.svg' className='user_verified_check' alt='' /></span> Brooklyn Simmons</span></td>
                                                    <td>+91123456789</td>
                                                    <td>Devidmiller@gmail.com</td>
                                                    <td><span><Image src='/assets/images/folder-zip.svg' className='' alt='' /> Documents.zip</span></td>
                                                    <td><span><Image src='/assets/images/folder-zip.svg' className='' alt='' /> Documents.zip</span></td>
                                                    <td><span><Image src='/assets/images/chat-icon.svg' className='' alt='' /> Chat With Buyer</span></td>
                                                </tr>
                                            </tbody>
                                        </Table>
                                    </Tab.Pane>
                                    <Tab.Pane eventKey="third">
                                        <Table>
                                            <thead>
                                                <tr>
                                                    <th>Buyer Name</th>
                                                    <th>Phone Number</th>
                                                    <th>Email Address</th>
                                                    <th>documents</th>
                                                    <th>Status</th>
                                                    <th>Chat</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr><td colspan="5"></td></tr>
                                                <tr>
                                                    <td><span><span className='deal_user_img_block'><Image src='/assets/images/user-img1.png' className='deal_user_img' alt='' /> <Image src='/assets/images/pcheck.svg' className='user_verified_check' alt='' /></span> Brooklyn Simmons</span></td>
                                                    <td>+91123456789</td>
                                                    <td>Devidmiller@gmail.com</td>
                                                    <td><span><Image src='/assets/images/folder-zip.svg' className='' alt='' /> Documents.zip</span></td>
                                                    <td><span><Image src='/assets/images/folder-zip.svg' className='' alt='' /> Documents.zip</span></td>
                                                    <td><span><Image src='/assets/images/chat-icon.svg' className='' alt='' /> Chat With Buyer</span></td>
                                                </tr>
                                                <tr>
                                                    <td><span><span className='deal_user_img_block'><Image src='/assets/images/user-img1.png' className='deal_user_img' alt='' /> <Image src='/assets/images/pcheck.svg' className='user_verified_check' alt='' /></span> Brooklyn Simmons</span></td>
                                                    <td>+91123456789</td>
                                                    <td>Devidmiller@gmail.com</td>
                                                    <td><span><Image src='/assets/images/folder-zip.svg' className='' alt='' /> Documents.zip</span></td>
                                                    <td><span><Image src='/assets/images/folder-zip.svg' className='' alt='' /> Documents.zip</span></td>
                                                    <td><span><Image src='/assets/images/chat-icon.svg' className='' alt='' /> Chat With Buyer</span></td>
                                                </tr>
                                                <tr>
                                                    <td><span><span className='deal_user_img_block'><Image src='/assets/images/user-img1.png' className='deal_user_img' alt='' /> <Image src='/assets/images/pcheck.svg' className='user_verified_check' alt='' /></span> Brooklyn Simmons</span></td>
                                                    <td>+91123456789</td>
                                                    <td>Devidmiller@gmail.com</td>
                                                    <td><span><Image src='/assets/images/folder-zip.svg' className='' alt='' /> Documents.zip</span></td>
                                                    <td><span><Image src='/assets/images/folder-zip.svg' className='' alt='' /> Documents.zip</span></td>
                                                    <td><span><Image src='/assets/images/chat-icon.svg' className='' alt='' /> Chat With Buyer</span></td>
                                                </tr>
                                            </tbody>
                                        </Table>
                                    </Tab.Pane>
                                </Tab.Content>
                            </div>
                        </Tab.Container>
                    </div>
                </Container>
            </section>
        <Footer />
    </>
  );
};
export default PropertyDealDetails;