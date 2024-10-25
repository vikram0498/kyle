import React, { useEffect, useState } from 'react';
import { Button, Col, Container, Image, Modal, Row } from 'react-bootstrap';
import { Link } from 'react-router-dom';
import Header from "../../partials/Layouts/Header";
import Footer from '../../partials/Layouts/Footer';

const DealNotifications = () => {
    const [dealConfirmation, setDealConfirmation] = useState(false);
    useEffect(() => {
        let timer;
        if (dealConfirmation) {
            timer = setTimeout(() => {
                setDealConfirmation(false);
            }, 3000);
        }
        return () => {
            if (timer) clearTimeout(timer);
        };
    }, [dealConfirmation]);

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
                                    Deal Notifications
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div className='card-box column_bg_space'>
                        <div className='deal_column'>
                            <div className='deal_left_column notifications_deal_column border-end-0'>
                                <div className='deal_notifications_left flex_1column align-items-center'>
                                    <div className='pro_img'>
                                        <Image src='/assets/images/property-img.png' alt='' />
                                        <div className='deal_img_group'>
                                            <div>
                                                <Image src='/assets/images/property-img.png' alt='' />
                                            </div>
                                            <div>
                                                <Image src='/assets/images/property-img.png' alt='' />
                                            </div>
                                            <div>
                                                <Image src='/assets/images/property-img.png' alt='' />
                                            </div>
                                        </div>
                                    </div>
                                    <div className='pro_details'>
                                        <h3>real easte company that prioritizes Property</h3>
                                        <ul className='notification_pro_deal'>
                                            <li>
                                                <Image src='/assets/images/home_retail.svg' alt='' /> Commercial - Retail
                                            </li>
                                            <li>
                                                <Image src='/assets/images/map_pin.svg' alt='' /> 4517 Washington Ave. Manchester, Kentucky 39495..
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div className='deal_notifications_right flex_auto_column'>
                                    <ul>
                                        <li><Button className='outline_btn' onClick={() => setDealConfirmation(true)}><Image src='/assets/images/want_buy.svg' alt='' /> want to buy</Button></li>
                                        <li><Button className='outline_btn' onClick={() => setDealConfirmation(true)}><Image src='/assets/images/interested_icon.svg' alt='' /> Interested</Button></li>
                                        <li>
                                            <Button className='text_btn' onClick={() => setDealConfirmation(true)}>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                                    <path d="M11 1L1 11" stroke="#E21B1B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M1 1L11 11" stroke="#E21B1B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg> Not interested
                                            </Button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div className='deal_column'>
                            <div className='deal_left_column notifications_deal_column border-end-0'>
                                <div className='deal_notifications_left flex_1column align-items-center'>
                                    <div className='pro_img'>
                                        <Image src='/assets/images/property-img.png' alt='' />
                                        <div className='deal_img_group'>
                                            <div>
                                                <Image src='/assets/images/property-img.png' alt='' />
                                            </div>
                                            <div>
                                                <Image src='/assets/images/property-img.png' alt='' />
                                            </div>
                                            <div>
                                                <Image src='/assets/images/property-img.png' alt='' />
                                            </div>
                                        </div>
                                    </div>
                                    <div className='pro_details'>
                                        <h3>real easte company that prioritizes Property</h3>
                                        <ul className='notification_pro_deal'>
                                            <li>
                                                <Image src='/assets/images/home_retail.svg' alt='' /> Commercial - Retail
                                            </li>
                                            <li>
                                                <Image src='/assets/images/map_pin.svg' alt='' /> 4517 Washington Ave. Manchester, Kentucky 39495..
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div className='deal_notifications_right flex_auto_column'>
                                    <ul>
                                        <li><Button className='outline_btn'><Image src='/assets/images/want_buy.svg' alt='' /> want to buy</Button></li>
                                        <li><Button className='outline_btn'><Image src='/assets/images/interested_icon.svg' alt='' /> Interested</Button></li>
                                        <li>
                                            <Button className='text_btn'>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                                    <path d="M11 1L1 11" stroke="#E21B1B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M1 1L11 11" stroke="#E21B1B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg> Not interested
                                            </Button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </Container>
            </section>
        <Footer />
        <Modal show={dealConfirmation} onHide={() => setDealConfirmation(false)} centered className='radius_30 max-648'>
            <Modal.Body className='space_modal'>
                <div className='modal_inner_content'>
                    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100" fill="none">
                        <circle cx="50" cy="50" r="50" fill="#00D859"/>
                        <path d="M74 36L43.0625 67L29 52.9091" stroke="white" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <h3>Successfully Send Deal Notifications</h3>
                    <p className='mb-0'>Upload your buyer’s criteria and use the Buybox Search to find the right buyers for your deals.</p>
                </div>
            </Modal.Body>
        </Modal>
    </>
  );
};
export default DealNotifications;