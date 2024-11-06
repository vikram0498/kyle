import React, { useEffect, useState } from 'react';
import { Button, Col, Container, Form, Image, Modal, Row } from 'react-bootstrap';
import { Link } from 'react-router-dom';
import Header from "../../partials/Layouts/Header";
import Footer from '../../partials/Layouts/Footer';
import { useAuth } from "../../../hooks/useAuth";

import axios from 'axios';
import BuyerHeader from '../../partials/Layouts/BuyerHeader';
import { Tooltip as ReactTooltip } from "react-tooltip";

const DealNotifications = () => {
    // Common Modal for want-to-buy, interested and not-interested
    const { getTokenData, setLogout, getLocalStorageUserdata } = useAuth();
    const [dealConfirmation, setDealConfirmation] = useState(true);
    const [modalContent, setModalContent] = useState('interested');
    const [errors, setErrors] = useState([]);
    const [dealData, setDealData] = useState([]);
    const [dealId, setDealId] = useState(0);
    const [isDealDocumentVerified, setIsDealDocumentVerified] = useState(false);
    const [dealFeedback, setDealFeedback] = useState("");
    const handleOpenModal = (content,id=0) => {
        setModalContent(content);
        setDealConfirmation(true);
        setDealId(id);
    };
    const apiUrl = process.env.REACT_APP_API_URL;

    useEffect(()=>{
        const fetchDeal = async ()=>{
            try {
                let headers = {
                    Accept: "application/json",
                    Authorization: "Bearer " + getTokenData().access_token,
                    "auth-token": getTokenData().access_token,
                };
                let response = await axios.get(`${apiUrl}buyer-deals/list`,{headers:headers});
                setDealData(response.data.deals.data)
            } catch (error) {
                console.log('error', error)
            }
        }
        fetchDeal();
    },[]);

    const updateDealStatus = async (propertyStatus, buyerId) => {
        try {
            let status = (propertyStatus == 'interested') ? 'interested' :  propertyStatus == 'want-to-buy' ? 'want_to_buy':'not_interested';
            let headers = {
                Accept: "application/json",
                Authorization: "Bearer " + getTokenData().access_token,
                "auth-token": getTokenData().access_token,
            };
            let payload = {
                status:status,
                buyer_deal_id:buyerId || dealId,
            }
            if(propertyStatus == 'not-interested-submitted'){
                payload.buyer_feedback = dealFeedback;
            }
            let response = await axios.post(`${apiUrl}buyer-deals/status`,payload,{headers:headers});
            handleOpenModal(propertyStatus)
        } catch (error) {
            if(error.response?.data?.errors){
                setErrors(error.response.data.errors);
            }
        }
    }
  return (
    <>
            {/* <Header /> */}
            <BuyerHeader />
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
                        {dealData.map((data, index) => {
                            // Destructure first image and remaining images from data.property_images
                            const [firstImage, ...remainingImages] = data.property_images || []; 
                            return (
                                <div className='deal_column' key={index}>
                                    <div className='deal_left_column notifications_deal_column border-end-0'>
                                        <div className='deal_notifications_left flex_1column align-items-center'>
                                            
                                            {/* Profile Image Section */}
                                            <div className='pro_img'>
                                                {/* Display first image or fallback if no images available */}
                                                {firstImage ? (
                                                    <Image src={firstImage} alt='Property Image' width={200} height={200} />
                                                ) : (
                                                    <Image src='/assets/images/property-img.png' alt='Default Image' width={200} height={200} />
                                                )}
                                                
                                                {/* Remaining Images Section */}
                                                <div className='deal_img_group'>
                                                    {remainingImages.map((imgUrl, i) => (
                                                    <div key={i}>
                                                        <Image src={imgUrl} alt={`Deal Image ${i + 1}`} width={100} height={100} />
                                                    </div>
                                                    ))}
                                                    <Link to={data.picture_link}>
                                                        <div className='align-items-center cursor-pointer'>
                                                            More..
                                                        </div>
                                                    </Link>
                                                </div>
                                            </div>
                                            
                                            {/* Property Details Section */}
                                            <div className='pro_details'>
                                            <h3>{data.title}</h3>
                                            <ul className='notification_pro_deal'>
                                                <li>
                                                <Image src='/assets/images/home_retail.svg' alt='Property Type Icon' /> {data.property_type}
                                                </li>
                                                <li>
                                                <Image src='/assets/images/map_pin.svg' alt='Location Icon' /> {data.address}
                                                </li>
                                            </ul>
                                            </div>
                                        </div>
                                        {/* Buttons Section */}
                                        <div className='deal_notifications_right flex_auto_column'>
                                            <ul className={`deal_notifications_btn ${data.status != null ? 'disabled-btn' : ''}`}>
                                            <li>
                                                {data.is_proof_of_fund_verified ? 
                                                    <Button className='outline_btn' onClick={data.status === null ? () => updateDealStatus('want-to-buy', data.id) : null}>
                                                        <Image src='/assets/images/want_buy.svg' alt='' /> Want to Buy 
                                                        {data.status === 'want_to_buy' &&
                                                            <span>
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="8" viewBox="0 0 10 8" fill="none">
                                                                    <path fillRule="evenodd" clipRule="evenodd" d="M9.81632 0.133089C10.0421 0.33068 10.0626 0.674907 9.86176 0.897832L4.20761 7.1736C4.00571 7.39769 3.65904 7.41194 3.43943 7.20522L0.167566 4.12504C-0.0364783 3.93294 -0.0560104 3.61286 0.119053 3.39404C0.312223 3.15257 0.671949 3.11934 0.901844 3.32611L3.44037 5.60947C3.66098 5.80791 4.00062 5.79016 4.19938 5.56984L9.06279 0.177574C9.25956 -0.0406347 9.59519 -0.0604144 9.81632 0.133089Z" fill="#19955A"></path>
                                                                </svg>
                                                            </span>
                                                        }
                                                    </Button>
                                                :
                                                    <div data-tooltip-id="my-tooltip-1">
                                                        <Button className='outline_btn' disabled>
                                                            <Image src='/assets/images/want_buy.svg' alt='' /> Want to Buy 
                                                            {data.status === 'want_to_buy' &&
                                                                <span>
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="8" viewBox="0 0 10 8" fill="none">
                                                                        <path fillRule="evenodd" clipRule="evenodd" d="M9.81632 0.133089C10.0421 0.33068 10.0626 0.674907 9.86176 0.897832L4.20761 7.1736C4.00571 7.39769 3.65904 7.41194 3.43943 7.20522L0.167566 4.12504C-0.0364783 3.93294 -0.0560104 3.61286 0.119053 3.39404C0.312223 3.15257 0.671949 3.11934 0.901844 3.32611L3.44037 5.60947C3.66098 5.80791 4.00062 5.79016 4.19938 5.56984L9.06279 0.177574C9.25956 -0.0406347 9.59519 -0.0604144 9.81632 0.133089Z" fill="#19955A"></path>
                                                                    </svg>
                                                                </span>
                                                            }
                                                        </Button>
                                                    </div>
                                                }
                                            </li>
                                                <li>
                                                    <Button className='outline_btn' onClick={data.status === null ? () => updateDealStatus('interested',data.id) : null}>
                                                    <Image src='/assets/images/interested_icon.svg' alt='' /> Interested
                                                    {data.status == 'interested' &&
                                                     <span>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="8" viewBox="0 0 10 8" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M9.81632 0.133089C10.0421 0.33068 10.0626 0.674907 9.86176 0.897832L4.20761 7.1736C4.00571 7.39769 3.65904 7.41194 3.43943 7.20522L0.167566 4.12504C-0.0364783 3.93294 -0.0560104 3.61286 0.119053 3.39404C0.312223 3.15257 0.671949 3.11934 0.901844 3.32611L3.44037 5.60947C3.66098 5.80791 4.00062 5.79016 4.19938 5.56984L9.06279 0.177574C9.25956 -0.0406347 9.59519 -0.0604144 9.81632 0.133089Z" fill="#19955A"></path></svg>
                                                     </span>
                                                     }
                                                    </Button>
                                                </li>
                                                <li>
                                                    <Button className='text_btn' onClick={data.status === null ? () => handleOpenModal('not-interested',data.id) : null}>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                                        <path d="M11 1L1 11" stroke="#E21B1B" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
                                                        <path d="M1 1L11 11" stroke="#E21B1B" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
                                                    </svg> Not Interested
                                                    {data.status == 'not_interested' &&
                                                     <span>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="8" viewBox="0 0 10 8" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M9.81632 0.133089C10.0421 0.33068 10.0626 0.674907 9.86176 0.897832L4.20761 7.1736C4.00571 7.39769 3.65904 7.41194 3.43943 7.20522L0.167566 4.12504C-0.0364783 3.93294 -0.0560104 3.61286 0.119053 3.39404C0.312223 3.15257 0.671949 3.11934 0.901844 3.32611L3.44037 5.60947C3.66098 5.80791 4.00062 5.79016 4.19938 5.56984L9.06279 0.177574C9.25956 -0.0406347 9.59519 -0.0604144 9.81632 0.133089Z" fill="#19955A"></path></svg>
                                                     </span>
                                                     }
                                                    </Button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                </Container>
            </section>
        <Footer />
        <Modal show={dealConfirmation} onHide={() =>{ setDealConfirmation(false); setDealFeedback("");}} centered className='radius_30 max-648'>
            <Modal.Header closeButton className='new_modal_close'></Modal.Header>
            <Modal.Body className='space_modal'>
                <div className='modal_inner_content'>
                    {modalContent === 'want-to-buy' && (
                        <>
                            <div className='buy_modal_icon light_green_bg ps-2'><Image src='/assets/images/home-dollar2.svg' alt='' /></div>
                            <h3>Let’s Connect With Us</h3>
                            <p className='mb-0'>Please keep an eye on next available options which can match your criteria.</p>
                            <ul className='deal_notifications_btn'>
                                <li><Button className='outline_btn'><Image src='/assets/images/call-preference-green.svg' alt='' /> make an offer</Button></li>
                                <li><Link to='/message'><Button className='outline_btn'><Image src='/assets/images/msg-top.svg' alt='' /> Chat With Seller</Button></Link></li>
                            </ul>
                        </>
                    )}
                    {modalContent === 'interested' && (
                        <>
                            <div className='buy_modal_icon light_blue_bg'><Image src='/assets/images/home-interested.svg' alt='' /></div>
                            <h3>Let’s Connect With Us</h3>
                            <p className='mb-0'>Please keep an eye on next available options which can match your criteria.</p>
                            {isDealDocumentVerified ?  
                                <ul className='deal_notifications_btn'>
                                    <li><Button className='outline_btn'><Image src='/assets/images/call-preference-green.svg' alt='' /> make an offer</Button></li>
                                    <li><Link to='/message'><Button className='outline_btn'><Image src='/assets/images/msg-top.svg' alt='' /> Chat With Seller</Button></Link></li>
                                </ul>
                                :
                                <div className='row upload-document-section'>
                                    <Form.Group controlId="formFile" className="mb-3">
                                        <Form.Control type="file" />
                                    </Form.Group>
                                    <div className='col-md-12'>
                                        <button type="button" className="btn btn-primary" onClick={()=>{setIsDealDocumentVerified(true)}}>Submit</button>
                                    </div>
                                </div>

                            }
                        </>
                    )}
                    {modalContent === 'not-interested' && (
                        <>
                            <div className='buy_modal_icon light_gray_bg'><Image src='/assets/images/like-vector.svg' alt='' /></div>
                            <h3>Please Share Your Feedback</h3>
                            <p className='mb-0'>Please share your experience with us.</p>
                            <div className='row'>
                                <div className="col-12 col-md-12 col-lg-12">
                                    <div className="form-group">
                                        <textarea className="form-control-form h-50" rows="3" onChange={(e)=>{setDealFeedback(e.target.value)}}>{dealFeedback}</textarea>
                                    </div>
                                    {errors.buyer_feedback && <span className='error'>{errors.buyer_feedback[0]}</span>
}
                                </div>
                                <button type="button" className="btn btn-primary" onClick={()=>updateDealStatus('not-interested-submitted')}>Submit</button>
                            </div>
                        </>
                    )}
                    {modalContent === 'not-interested-submitted' && (
                        <>
                            <div className='buy_modal_icon light_gray_bg'><Image src='/assets/images/like-vector.svg' alt='' /></div>
                            <h3>Thank you for your feedback</h3>
                            <p className='mb-0'>Please keep an eye on next available options which can match your criteria.</p>
                        </>
                    )}
                </div>
            </Modal.Body>
        </Modal>
        <ReactTooltip
        id="my-tooltip-1"
        place="top"
        content="Please verify your proof of funds to enable this feature."/>
    </>
  );
};
export default DealNotifications;