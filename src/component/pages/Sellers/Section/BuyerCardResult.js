import React from 'react'
import { OverlayTrigger, Tooltip, Button } from 'react-bootstrap';

const BuyerCardResult = (props) => {
    console.log('re render 22');
    const {data,index,activeTab,checkSelectedDeals,setSendDealShow,setCurrentBuyerId, handleLikeClick,handleDisikeClick,handleClickConfirmation,handleClickEditFlag,selectedDeals,handleCheckboxChange,user_data} = props;
    let PreferenceIcons = './assets/images/contact-preferance.svg';
    if(data.contact_preferance_id === 1){
        PreferenceIcons = './assets/images/result-user-icon.svg';
    }else if(data.contact_preferance_id === 2){
        PreferenceIcons = './assets/images/Text-Preference-bg.svg';
    }else if(data.contact_preferance_id === 3){
        PreferenceIcons = './assets/images/Call-Preference-bg.svg';
    }else if(data.contact_preferance_id === 4){
        PreferenceIcons = './assets/images/no-preference-bg.svg';
    }
    // const entering = (e) => {
    //     e.children[0].style.borderTopColor = '#3F53FE';
    //     e.children[1].style.backgroundColor = '#3F53FE';
    // };
    //onEntering={entering}
    const handleClickCurrentDeal = (id) => {
        setCurrentBuyerId([id]);
        setSendDealShow(true);
    }

  return (
    <div className="col-12 col-lg-6" >
        <div className={`position-relative property-critera-outer ${data.user_detail.level_type ==2 ? 'change-badge-color' : ''}` }>
            {(activeTab ==='my_buyers' && user_data.level_type > 1 )&&<input type="checkbox" id={data.buyer_user_id} value={data.buyer_user_id} className='deal-check-box' checked={selectedDeals.includes(data.buyer_user_id)} onChange={() => handleCheckboxChange(data.buyer_user_id)}/>}
            <label className={`property-critera-block buyer-blog-area property-section-${data.id} ${data.user_detail.level_type ==2 ? 'change-badge-color' : ''}`} htmlFor={data.buyer_user_id}>
                <div className='buyer-notifaction'>

                    {(activeTab ==='my_buyers' && user_data.level_type > 1 ) && 
                        <Button className="top_buyer_btn" onClick={()=>handleClickCurrentDeal(data.buyer_user_id)}>
                            <span>
                                <svg width="10" height="12" viewBox="0 0 10 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.90356 8.58951C9.74006 9.04064 9.39553 9.3835 8.9459 9.53989C8.31524 9.75643 7.66706 9.91884 7.01304 10.0331C6.94881 10.0452 6.88457 10.0572 6.82034 10.0632C6.71523 10.0812 6.61012 10.0933 6.50501 10.1053C6.37654 10.1233 6.24223 10.1354 6.10793 10.1474C5.74004 10.1775 5.378 10.1955 5.01011 10.1955C4.63639 10.1955 4.26266 10.1775 3.89478 10.1414C3.73711 10.1294 3.58529 10.1113 3.43346 10.0873C3.34587 10.0752 3.25828 10.0632 3.17653 10.0512C3.11229 10.0391 3.04806 10.0331 2.98382 10.0211C2.33565 9.91282 1.69331 9.75041 1.06849 9.53387C0.60133 9.37146 0.245124 9.02861 0.0874591 8.58951C-0.0702059 8.15642 -0.0118114 7.65116 0.239285 7.21807L0.899142 6.08724C1.03929 5.84062 1.16776 5.36543 1.16776 5.07671V3.95791C1.16776 1.77444 2.89039 0 5.01011 0C7.12399 0 8.84663 1.77444 8.84663 3.95791V5.07671C8.84663 5.36543 8.97509 5.84062 9.12108 6.08724L9.78094 7.21807C10.0204 7.63913 10.0671 8.13236 9.90356 8.58951Z" fill="#3F53FE"/>
                                    <path d="M4.98872 5.23941C4.74346 5.23941 4.54492 5.0349 4.54492 4.78226V2.91759C4.54492 2.66496 4.74346 2.46045 4.98872 2.46045C5.23398 2.46045 5.43252 2.66496 5.43252 2.91759V4.78226C5.42668 5.0349 5.22814 5.23941 4.98872 5.23941Z" fill="white"/>
                                    <path d="M6.64304 10.8034C6.39778 11.5011 5.7496 12.0004 4.99047 12.0004C4.52916 12.0004 4.07368 11.8079 3.75251 11.465C3.56565 11.2846 3.4255 11.044 3.34375 10.7974C3.41966 10.8094 3.49558 10.8154 3.57733 10.8274C3.71163 10.8455 3.85178 10.8635 3.99193 10.8756C4.32478 10.9056 4.66346 10.9237 5.00215 10.9237C5.335 10.9237 5.66785 10.9056 5.99486 10.8756C6.11749 10.8635 6.24011 10.8575 6.3569 10.8395C6.45033 10.8274 6.54376 10.8154 6.64304 10.8034Z" fill="#3F53FE"/>
                                </svg>
                            </span>
                            <div className="arrow">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </Button>
                    }

                    {data.profile_tag_name &&
                        <OverlayTrigger
                            placement="top"
                            style={{ backgroundColor: "green" }}
                            overlay={ <Tooltip> Profile Tag </Tooltip>}>
                            <div className="buyer-active-verfiy">
                                {(data.profile_tag_image) && <img
                                    src={data.profile_tag_image}
                                    className="img-fluid profile-tag-image"
                                    alt=""
                                    title=""
                                    />
                                }
                                <span>{data.profile_tag_name}</span>
                            </div>
                        </OverlayTrigger>
                    }
                </div>
                <div className={`critera-card ${data.user_detail.level_type ==2 ? 'change-badge-color' : ''}` } >
                    <div className="center-align position-relative">
                    { 
                        (data.buyer_profile_image !='' && data.buyer_profile_image != undefined) ? 
                        <img src={data.buyer_profile_image} className="img-fluid price-img" alt=""/>
                    :
                        <span className="price-img">
                        <img src='./assets/images/price.svg' className="img-fluid" alt=""/>
                        </span>
                    }
                        {(data.is_buyer_verified) &&
                            <div className="buyer-check">
                                <OverlayTrigger
                                placement="top"
                                style={{ backgroundColor: "green" }}
                                overlay={ <Tooltip> Profile Verified </Tooltip>}>
                                <img src='./assets/images/pcheck.svg' className="img-fluid" alt=""/>
                                </OverlayTrigger>
                            </div>
                        }
                        <p>
                            Buyer  
                        
                        </p>
                        {(activeTab ==='more_buyers') ?
                        <ul className="like-unlike mb-0 list-unstyled">
                            <li>
                                <span className="numb like-span ">{data.totalBuyerLikes}</span>
                                
                                <span className="ico-no ml-min like-btn-disabled">
                                <img src="/assets/images/like.svg" className="img-fluid" alt="like" /></span>
                            </li>
                            <li>
                                <span className="ico-no mr-min like-btn-disabled">
                                    <img src="/assets/images/unlike.svg" className="img-fluid" alt="unlike"/></span>
                                <span className="numb text-end unlike-span">{data.totalBuyerUnlikes}</span>
                            </li>
                        </ul>
                        :
                        (data.createdByAdmin ?  
                            <ul className="like-unlike mb-0 list-unstyled">
                                <li>
                                    <span className="numb like-span">{data.totalBuyerLikes}</span>
                                    <span className="ico-no ml-min" onClick={()=>{handleLikeClick(data.id, index)}}>
                                    {/* <span className="ico-no ml-min" onDoubleClick={handleDoubleClick}> */}
                                    <img alt="like" src={(!data.liked) ? "/assets/images/like.svg" : "/assets/images/liked.svg"} className="img-fluid" /></span>
                                </li>
                                <li>
                                    <span className="ico-no mr-min" onClick={()=>{handleDisikeClick(data.id, index)}}>
                                        <img alt="dislike" src={(!data.disliked) ? "/assets/images/unlike.svg":"/assets/images/unliked.svg"} className="img-fluid" /></span>
                                    <span className="numb text-end unlike-span">{data.totalBuyerUnlikes}</span>
                                </li>
                            </ul>:
                        "")
                        }
                    </div>
                    <div className={data.createdByAdmin ? 'purchase-buyer':'your-buyer' }>{data.createdByAdmin ? 'Premium buyer':'Your buyer' }</div>
                </div>
            

                <div className={"property-critera-details unhide-"+index}>
                    <ul className="list-unstyled mb-0">
                        <li>
                            <span className="detail-icon">
                                {/* <span className="contact-preference mx-4"> */}
                                    <OverlayTrigger placement="top" style={{ backgroundColor: 'green' }} overlay={<Tooltip>Contact Preference</Tooltip>} >
                                    <img src={PreferenceIcons} className="img-fluid" alt=""/>
                                    </OverlayTrigger>
                                {/* </span> */}
                                {/* <img alt="user-gradient" src="/assets/images/uservbox.svg" className="img-fluid" /> */}
                            </span>
                            <span className="name-dealer b-name">{data.name}</span>
                        </li>
                        <li>
                            <span className="detail-icon">
                                <img alt="phone-gradient" src="/assets/images/callingbox.svg" className="img-fluid" />
                            </span>
                            {(activeTab ==='more_buyers') ? 
                                <span className="name-dealer">{data.phone}</span>
                                :
                                <><a href={'tel:+'+data.phone} className="name-dealer">{data.phone}</a></>
                            }
                        </li>
                        <li>
                            <span className="detail-icon">
                                <img alt="email" src="/assets/images/emailbox.svg" className="img-fluid" />                            
                            </span>

                            {(activeTab ==='more_buyers')? 
                                <span className="name-dealer">{data.email}</span>
                                :
                                <>
                                    <a href={'mailto:'+data.email} className="name-dealer email-tag">{data.email}</a>
                                </>
                            }
                        </li>
                        {/* <li>
                            <span className="detail-icon">
                                <img alt="preference" src={PreferenceIcons} className="img-fluid" />
                            </span>
                            <span className="name-dealer">{data.contact_preferance}</span>
                        </li> */}
                    </ul>
                </div>
                {
                (data.createdByAdmin)?
                    (data.redFlagShow) ? 
                    '':
                    <div className="show-hide-data purchase_btn_class">
                        <OverlayTrigger placement="top" style={{ backgroundColor: 'green' }} overlay={<Tooltip>Click here to unlock the complete details of this buyer</Tooltip>} >
                            <div className='pointerswal' onClick={()=>{handleClickConfirmation(data.id,index)}}>
                                <span className="icon-unhide" >
                                    Purchase
                                    {/* <img alt="unhide-icon" src="/assets/images/unhide-icon.svg" className="img-fluid" /> */}
                                </span>
                            </div>
                        </OverlayTrigger>
                    </div>:''
                }
                {
                    data.createdByAdmin && data.redFlagShow && 
                        <div className="red-flag inner_red_flag" onClick={()=>{handleClickEditFlag(data.redFlag,data.id)}}>
                            <OverlayTrigger placement="top" style={{ backgroundColor: 'green' }} overlay={<Tooltip>Flag</Tooltip>} >
                                <img
                                src="/assets/images/red-flag-bg.svg"
                                className="img-fluid"
                                alt=""
                                />
                            </OverlayTrigger>
                        </div>
                }
                {(data.phone_verified || data.email_verified || data.driver_license_verified || data.llc_verified || data.proof_of_funds_verified || data.certified_closer_verified|| data.createdByAdmin ) ? 
                    <div className="cornor-block">
                        {data.phone_verified && 
                            <OverlayTrigger placement="top" style={{ backgroundColor: 'green' }} overlay={<Tooltip>Phone Verified</Tooltip>} >
                                    <img src="/assets/images/ver-phone-number.svg" className="img-fluid" alt=""/>
                            </OverlayTrigger>
                        }
                        {data.email_verified && 
                            <OverlayTrigger placement="top" style={{ backgroundColor: 'green' }} overlay={<Tooltip>Email Verified</Tooltip>} >
                                <img src="/assets/images/ver-email.svg" className="img-fluid" alt=""/>
                            </OverlayTrigger>
                        }
                        {(data.driver_license_verified) && 
                            <OverlayTrigger placement="top" style={{ backgroundColor: 'green' }} overlay={<Tooltip>ID/Driver’s License</Tooltip>} >
                                <img
                                src="/assets/images/drivers -license.svg"
                                className="img-fluid"
                                alt=""
                                />
                            </OverlayTrigger>
                        }
                        {(data.llc_verified) && 
                        <OverlayTrigger placement="top" style={{ backgroundColor: 'green' }} overlay={<Tooltip>Proof of Funds</Tooltip>} >
                            <img
                            src="/assets/images/Proof-of-Funds.svg"
                            className="img-fluid"
                            alt=""
                            />
                        </OverlayTrigger>
                        }
                        {(data.proof_of_funds_verified) && 
                            <OverlayTrigger placement="top" style={{ backgroundColor: 'green' }} overlay={<Tooltip>LLC Verification</Tooltip>} >
                                <img
                                src="/assets/images/LLC-verification.svg"
                                className="img-fluid"
                                alt=""
                                />
                            </OverlayTrigger>
                        }
                        {(data.certified_closer_verified) && 
                            <OverlayTrigger placement="top" style={{ backgroundColor: 'green' }} overlay={<Tooltip>Certified Closer Verification</Tooltip>} >
                            <img
                            src="/assets/images/certified-closer.svg"
                            className="img-fluid"
                            alt=""
                            />
                            </OverlayTrigger>
                        }
 
                    </div>:
                    ''
                }
            </label>
        </div>
    </div>
  )
}
export default BuyerCardResult