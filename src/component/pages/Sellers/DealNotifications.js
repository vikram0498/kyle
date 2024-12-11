import React, { useEffect, useState,useRef } from 'react';
import { Button, Col, Container, Form, Image, Modal, Row } from 'react-bootstrap';
import { Link } from 'react-router-dom';
import Header from "../../partials/Layouts/Header";
import Footer from '../../partials/Layouts/Footer';
import { useAuth } from "../../../hooks/useAuth";

import axios from 'axios';
import BuyerHeader from '../../partials/Layouts/BuyerHeader';
import { Tooltip as ReactTooltip } from "react-tooltip";
import Pagination from '../../partials/Pagination';
import { toast } from "react-toastify";
import { useNavigate } from 'react-router-dom';


const DealNotifications = () => {
    // Common Modal for want-to-buy, interested and not-interested
    const navigate = useNavigate();

    const { getTokenData, setLogout } = useAuth();
    const [dealConfirmation, setDealConfirmation] = useState(false);
    const [modalContent, setModalContent] = useState('');
    const [errors, setErrors] = useState([]);
    const [dealData, setDealData] = useState([]);
    const [dealId, setDealId] = useState(0);
    const [isDealDocumentVerified, setIsDealDocumentVerified] = useState(false);
    const [dealFeedback, setDealFeedback] = useState("");
    const [isSubmitted, setIsSubmitted] = useState(false);
    const [attachmentsError,setAttachmentsError] = useState("");
    const fileInputRef = useRef(null);
    const [page, setPage]= useState(1);
    const [total, setTotal] = useState(0);
    const [limit, setLimit] = useState(0);
    const [offerPrice, setOfferPrice] = useState(0);
    const [isLoader, setIsLoader] = useState(false);
    const [isProofOfFund, setIsProofOfFund] = useState(false);
    const [isUpdatedStatus,setIsUpdatedStatus] = useState(false);
    const [proofSave, setProofSave] = useState(false);
    const [isConfirmProofOfFund, setIsConfirmProofOfFund] = useState(false);
    const [proofOfFundFormData, setProofOfFundFormData]= useState({});

    const [submitOffer, setSubmitOffer] = useState(false);
    const [letsConnect, setLetsConnect] = useState(false);
    const [interestedProperty, setInterestedProperty] = useState(false);
    const [thankyouFeedback, setThankyouFeedback] = useState(false);

    const handleProofShow = () => {
        setProofSave(true)
    };
    const handleSubmitOffer = () => {
        setSubmitOffer(true)
    };
    const handleLetsConnect = () => {
        setLetsConnect(true)
    };
    const handleInterestedProperty = () => {
        setInterestedProperty(true)
    };
    const handleThankyouFeedback = () => {
        setThankyouFeedback(true);
        setInterestedProperty(false)
    };

    const handleProofHide = () => {
        setProofSave(false);
        setDealConfirmation(true);
        setIsConfirmProofOfFund(false);
        setIsDealDocumentVerified(false);
        setSubmitOffer(false);
    }

    const handleOpenModal = (content,id=0) => {
        setAttachmentsError('');
        setDealConfirmation(true);
        setModalContent(content);
        if (id) {
            setDealId(id);
        }
    };
    const apiUrl = process.env.REACT_APP_API_URL;

    useEffect(()=>{
        const fetchDeal = async ()=>{
            setIsLoader(true);
            try {
                let headers = {
                    Accept: "application/json",
                    Authorization: "Bearer " + getTokenData().access_token,
                    "auth-token": getTokenData().access_token,
                };
                let response = await axios.get(`${apiUrl}buyer-deals/list?page=${page}`,{headers:headers});
                setDealData(response.data.deals.data);
                setLimit(response.data.deals.per_page);
                setPage(response.data.deals.current_page);
                setTotal(response.data.deals.total);
                setIsLoader(false);
            } catch (error) {
                if (error.response.status === 401) {
                    setLogout();
                }
                console.log('error', error)
                setIsLoader(false);
            }
        }
        fetchDeal();
    },[isUpdatedStatus, page]);

    const handleStatusType = async (propertyStatus, buyerId) => {
        try {
            setIsSubmitted(true);
            setSubmitOffer(false);
            setDealConfirmation(true);
            setIsDealDocumentVerified(false);
            handleOpenModal(propertyStatus);
            if(propertyStatus =='interested'){
                let payload = {
                    status:"interested",
                    buyer_deal_id:buyerId,
                }
                await updateDealStatus(payload);
            }
            if(propertyStatus =='not-interested'){
                setDealId(buyerId);
            }
            if(propertyStatus =='not-interested-feedback-submitted'){
                let payload = {
                    status:"not_interested",
                    buyer_deal_id:dealId,
                    buyer_feedback:dealFeedback,
                }
                await updateDealStatus(payload);
            }
            if(propertyStatus =='want-to-buy'){
                setDealId(buyerId);
            }
            if(propertyStatus == "submit-want-to-buy-doc"){
                setModalContent('want-to-buy');
                setAttachmentsError("Attachment is required");
                setProofSave(true);
                setDealConfirmation(false);

                if(isProofOfFund){
                    setAttachmentsError(""); // Clear error if file is valid
                    const formData = new FormData();
                    formData.append("status", "want_to_buy");
                    formData.append("buyer_deal_id", dealId); // Ensure dealId is available in scope
                    formData.append("is_proof_of_fund", isProofOfFund); // The file itself
                    formData.append("offer_price", offerPrice); // The file itself
                    setProofOfFundFormData(formData)
                    setIsDealDocumentVerified(true)
                }else if (fileInputRef.current && fileInputRef.current.files.length > 0) {
                    const file = fileInputRef.current.files[0];
                    if (file.type !== "application/pdf") {
                        setAttachmentsError("Only PDF files are allowed");
                        return;
                    }
                    setAttachmentsError(""); // Clear error if file is valid
                    const formData = new FormData();
                    formData.append("status", "want_to_buy");
                    formData.append("buyer_deal_id", dealId); // Ensure dealId is available in scope
                    formData.append("pdf_file", file); // The file itself
                    formData.append("offer_price", offerPrice); // The file itself
                    setProofOfFundFormData(formData)
                    // await updateDealStatus(formData);
                }
                
            }
        } catch (error) {
            console.log(error,"error");
        }
    }

    const updateDealStatus = async (payload) => {
        let currentStatus = '';
        if (payload instanceof FormData) {
            currentStatus =  payload.get("status");
        } else if (typeof payload === "object" && payload !== null) {
            currentStatus =  payload.status;
        }
        try {
            let headers = {
                Accept: "application/json",
                Authorization: "Bearer " + getTokenData().access_token,
                "auth-token": getTokenData().access_token,
            };
            if(dealFeedback == '' && currentStatus == 'not_interested'){
                setModalContent('not-interested');
                return false;
            }else {
                let response = await axios.post(`${apiUrl}buyer-deals/status`,payload,{headers:headers});
                if(response.data.status){
                    toast.success(response.data.message, {
                        position: toast.POSITION.TOP_RIGHT,
                    });
                    setIsUpdatedStatus(!isUpdatedStatus);
                    // setTimeout(() => {
                    //     navigate(0);
                    // }, 1000);
                }
                // handleOpenModal(currentStatus)
    
                if(currentStatus == 'want_to_buy'){
                    setIsDealDocumentVerified(true);
                    setProofSave(false);
                }else if(currentStatus == 'not_interested'){
                    handleOpenModal('not-interested-submitted');
                }
            }
        } catch (error) {
          console.log(error);
          console.log(currentStatus,"currentStatus")
            setProofSave(false);
            setDealConfirmation(true);
            setIsConfirmProofOfFund(false);
            if(error.response?.data?.errors){
                setErrors(error.response.data.errors);
            }
        }
    }
    useEffect(() => {
        const handleDealUpdate = async () => {
            try {
                if (isConfirmProofOfFund && proofOfFundFormData) {
                    await updateDealStatus(proofOfFundFormData);
                }
            } catch (error) {
                console.error("Error in deal update:", error);
            }
        };
    
        handleDealUpdate(); // Call the async function inside useEffect
    }, [isConfirmProofOfFund, proofOfFundFormData]);

  return (
    <>
        {/* <Header /> */}
        <BuyerHeader />
        <section className='main-section position-relative pt-4 pb-120'>
            { isLoader ? <div className="loader" style={{ textAlign: "center" }}><img src="assets/images/loader.svg" /></div> : 
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
                                    {/* Deal Notifications */}
                                    Browse Deal
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div className='card-box column_bg_space'>
                        {dealData.length > 0 ? dealData.map((data, index) => {
                            // Destructure first image and remaining images from data.property_images
                            const [firstImage, ...remainingImages] = data.property_images || []; 
                            return (
                                <div className='deal_column' key={index}>
                                    <div className='deal_left_column notifications_deal_column border-end-0'>
                                        <div className='deal_notifications_left flex_1column align-items-center'>
                                            {/* Profile Image Section */}
                                            <div className='pro_img'>
                                                {/* Display first image or fallback if no images available */}
                                                <div className='pro_img-main'>
                                                {firstImage ? (
                                                    <Image src={firstImage} alt='Property Image' width={200} height={200} />
                                                ) : (
                                                    <Image src='/assets/images/property-img.png' alt='Default Image' width={200} height={200} />
                                                )}
                                                </div>
                                                
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
                                                <h3 onClick={handleProofShow}>{data.title}</h3>
                                                <p>Real Easte Company That Prioritizes Property</p>
                                                <div className="property-details-Browse-Deal-icons">
                                                    <div className="detail">
                                                        <div>
                                                            <img src='/assets/images/double-bed.svg'/>
                                                        </div>
                                                        <span>Beds: {data.bedroom_min || 0}</span>
                                                    </div>
                                                    <div className="detail">
                                                        <div>
                                                            <img src='/assets/images/bath-1.svg'/>
                                                        </div>
                                                        <span>Baths: {data.bath || 0}</span>
                                                    </div>
                                                    <div className="detail">
                                                        <div>
                                                            <img src='/assets/images/network-1.svg'/>
                                                        </div>
                                                        <span>Liveable sq. ft. : {data.size || 0}</span>
                                                    </div>
                                                    <div className="detail">
                                                        <div>
                                                            <img src='/assets/images/full-screen-2.svg'/>
                                                        </div>
                                                        <span>Lot sq. ft. : {data.lot_size || 0}</span>
                                                    </div>
                                                </div>
                                                <div className='d-flex align-items-center deal_bottom_price mt-3'>
                                                    Asking Price :
                                                    <p className='dollar-text mb-0'><strong>${data.price || 0}</strong></p>
                                                </div>
                                                {/* <ul className='notification_pro_deal'>
                                                    <li>2</li>
                                                    <li>3</li>
                                                    <li>4</li>
                                                    <li>5</li>
                                                </ul> */}
                                            </div>
                                        </div>
                                        {/* Buttons Section */}
                                        <div className='deal_notifications_right flex_auto_column'>
                                            <ul className={`deal_notifications_btn ${data.status != null ? 'disabled-btn' : ''}`}>
                                                <li>
                                                    {data.is_proof_of_fund_verified ? 
                                                        <Button className='outline_btn' onClick={handleSubmitOffer}>
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
                                                    <Button className='outline_btn' onClick={handleLetsConnect}>
                                                    <Image src='/assets/images/chat-seller.svg' alt='' /> chat with seller
                                                    {data.status == 'interested' &&
                                                        <span>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="8" viewBox="0 0 10 8" fill="none"><path fillRule="evenodd" clipRule="evenodd" d="M9.81632 0.133089C10.0421 0.33068 10.0626 0.674907 9.86176 0.897832L4.20761 7.1736C4.00571 7.39769 3.65904 7.41194 3.43943 7.20522L0.167566 4.12504C-0.0364783 3.93294 -0.0560104 3.61286 0.119053 3.39404C0.312223 3.15257 0.671949 3.11934 0.901844 3.32611L3.44037 5.60947C3.66098 5.80791 4.00062 5.79016 4.19938 5.56984L9.06279 0.177574C9.25956 -0.0406347 9.59519 -0.0604144 9.81632 0.133089Z" fill="#19955A"></path></svg>
                                                        </span>
                                                        }
                                                    </Button>
                                                </li>
                                                <li>
                                                    <Button className='text_btn' onClick={handleInterestedProperty}>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                                        <path d="M11 1L1 11" stroke="#E21B1B" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
                                                        <path d="M1 1L11 11" stroke="#E21B1B" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
                                                    </svg> Not Interested
                                                    {data.status == 'not_interested' &&
                                                        <span>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="8" viewBox="0 0 10 8" fill="none"><path fillRule="evenodd" clipRule="evenodd" d="M9.81632 0.133089C10.0421 0.33068 10.0626 0.674907 9.86176 0.897832L4.20761 7.1736C4.00571 7.39769 3.65904 7.41194 3.43943 7.20522L0.167566 4.12504C-0.0364783 3.93294 -0.0560104 3.61286 0.119053 3.39404C0.312223 3.15257 0.671949 3.11934 0.901844 3.32611L3.44037 5.60947C3.66098 5.80791 4.00062 5.79016 4.19938 5.56984L9.06279 0.177574C9.25956 -0.0406347 9.59519 -0.0604144 9.81632 0.133089Z" fill="#19955A"></path></svg>
                                                        </span>
                                                    }
                                                    </Button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            );
                        }) :<div className='deal_column text-center'><p>No Data found</p></div>}
                    </div>
                    <Pagination page={page} setPage={setPage} limit={limit} total={total}/>
                </Container>
            }
        </section>
        <Footer />
        <Modal show={submitOffer} onHide={() => setSubmitOffer(false)} centered className='radius_30 max-648'>
            <Modal.Header closeButton className='new_modal_close'></Modal.Header>
            <Modal.Body className='space_modal'>
                <div className='modal_inner_content'>
                    <div className='buy_modal_icon light_green_bg ps-2'>
                        <Image src='/assets/images/home-dollar2.svg' alt='' />
                    </div>
                    <h3>submit your offer</h3>
                    <p className='mb-4 px-md-5'>Please upload your proof of funds to submit your offer. Or select a proof of fund</p>
                    {isDealDocumentVerified ?  
                        <ul className='deal_notifications_btn'>
                            <li>
                                <Button className='outline_btn'><Image src='/assets/images/call-preference-green.svg' alt='' /> make an offer</Button>
                            </li>
                            <li>
                                <Link to='/message'><Button className='outline_btn'><Image src='/assets/images/msg-top.svg' alt='' /> Chat With Seller</Button></Link>
                            </li>
                        </ul>
                        :
                        <form>
                            <div className='offer_price_input'>
                                <label className='offer_label'>Offer Your Price</label>
                                <div className=''>
                                    <input type='text' placeholder='Enter Offer Price' value={offerPrice} onChange={(e)=> setOfferPrice(e.target.value)}/>
                                </div>
                            </div>
                            <div className='upload-document-section'>
                                <div className='offer_price_area'>
                                    <label className='offer_label'>Upload Your Proof Of Funds</label>
                                    <div className='offer_price_select'>
                                        <select>
                                            <option>verified proof of funds</option>
                                            <option>verified proof of funds</option>
                                            <option>verified proof of funds</option>
                                        </select>
                                    </div>
                                </div>
                                <div className=''>
                                    <span className="browse-files position-relative">
                                        <input type='hidden' name="buyer_deal_id" value={dealId}/>
                                        <input id='formFile' type="file" className="default-file-input" ref={fileInputRef}/>
                                        <span className="d-block upload-file">PDF-Name.pdf</span>
                                        <span className="browse-files-text">submit proof of funds</span>
                                    </span>
                                    {attachmentsError !='' && <span className='error'>{attachmentsError}</span>}
                                </div>
                                <div className='proof_checkbox position-relative text-start'>
                                    <label>
                                        <input type='checkbox' name='current_poof' checked={isProofOfFund} onChange={(e)=>{setIsProofOfFund(e.target.checked)}}/>
                                        <span>Save as default proof of funds</span>
                                    </label>
                                </div>
                                <button type="button" className="btn btn-fill btn-fill-green btn btn-primary w-100" onClick={()=>handleStatusType('submit-want-to-buy-doc')}>Submit your offer</button>
                            </div>
                        </form>
                    }
                </div>
            </Modal.Body>
        </Modal>

        <Modal show={letsConnect} onHide={() => setLetsConnect(false)} centered className='radius_30 max-648'>
            <Modal.Header closeButton className='new_modal_close'></Modal.Header>
            <Modal.Body className='space_modal'>
                <div className='modal_inner_content'>
                    <div className='buy_modal_icon light_blue_bg'>
                        <Image src='/assets/images/home-interested.svg' alt='' />
                    </div>
                    <h3>Let’s Connect With Us</h3>
                    <p className='mb-4 px-md-5'>Please keep an eye on next available options which can match your criteria.</p>
                    <ul className='deal_notifications_btn'>
                        {/* <li><Button className='outline_btn'><Image src='/assets/images/call-preference-green.svg' alt='' /> make an offer</Button></li> */}
                        <li><Link to='/message'><Button className='outline_btn'><Image src='/assets/images/msg-top.svg' alt='' /> Chat With Seller</Button></Link></li>
                    </ul>
                </div>
            </Modal.Body>
        </Modal>

        <Modal show={interestedProperty} onHide={() => setInterestedProperty(false)} centered className='radius_30 max-648'>
            <Modal.Header closeButton className='new_modal_close'></Modal.Header>
            <Modal.Body className='space_modal'>
                <div className='modal_inner_content'>
                    <div className='buy_modal_icon light_gray_bg'>
                        <Image src='/assets/images/like-vector.svg' alt='' />
                    </div>
                    <h3>not interested in this property?</h3>
                    <p className='mb-4'>Would you like to share some feedbacks (optional)</p>
                    <div className='row'>
                        <div className="col-12 col-md-12 col-lg-12">
                            <div className="form-group">
                                <textarea className="form-control-form h-50" rows="3" onChange={(e)=>{setDealFeedback(e.target.value)}} placeholder='Enter Your Feedback'>{dealFeedback}</textarea>
                                {isSubmitted && dealFeedback.trim() == '' && <span className='error'>This field is required</span>}
                                {errors.buyer_feedback && <span className='error'>{errors.buyer_feedback[0]}</span>}
                            </div>
                            <button type="button" className="btn btn-fill  btn btn-primary w-100" onClick={handleThankyouFeedback}>Share Feedback</button>
                        </div>
                    </div>
                </div>
            </Modal.Body>
        </Modal>

        <Modal show={thankyouFeedback} onHide={() => setThankyouFeedback(false)} centered className='radius_30 max-648'>
            <Modal.Header closeButton className='new_modal_close'></Modal.Header>
            <Modal.Body className='space_modal'>
                <div className='modal_inner_content'>
                    <div className='buy_modal_icon light_gray_bg'>
                        <Image src='/assets/images/like-vector.svg' alt='' />
                    </div>
                    <h3>Thank you for your feedback</h3>
                    <p className='mb-0'>Please keep an eye out for your next BuyBox match</p>
                </div>
            </Modal.Body>
        </Modal>

        {/* Save Proof of fund */}
        <Modal show={proofSave} onHide={handleProofHide} centered className='radius_30 max-340'>
            <Modal.Body className='some_space'>
                <div className='modal_inner_content proofSave'>
                    <h3>Do you want to save this proof of fund</h3>
                    <div class="both_btn_group m-0">
                        <button type="button" class="light_bg_btn btn btn-primary" onClick={handleProofHide}>No</button>
                        <button type="submit" class="btn btn-fill btn-primary" onClick={()=>setIsConfirmProofOfFund(true)}>Yes</button>
                    </div>
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