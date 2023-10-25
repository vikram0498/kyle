import React from 'react'
import { OverlayTrigger, Tooltip } from 'react-bootstrap';

const BuyerCardResult = (props) => {
    console.log('re render 22');
    const {data,index,activeTab,handleLikeClick,handleDisikeClick,handleClickConfirmation,handleClickEditFlag} = props;
    let PreferenceIcons = './assets/images/contact-preferance.svg';
    if(data.contact_preferance_id === 1){
        PreferenceIcons = './assets/images/Email-Preference.svg';
    }else if(data.contact_preferance_id === 2){
        PreferenceIcons = './assets/images/Text-Preference.svg';
    }else if(data.contact_preferance_id === 3){
        PreferenceIcons = './assets/images/Call-Preference.svg';
    }else if(data.contact_preferance_id === 4){
        PreferenceIcons = './assets/images/1.svg';
    }
    // const entering = (e) => {
    //     e.children[0].style.borderTopColor = '#3F53FE';
    //     e.children[1].style.backgroundColor = '#3F53FE';
    // };
    //onEntering={entering}
  return (
    <div className="col-12 col-lg-6" >
        <div className={"property-critera-block property-section-"+data.id}>
            <div className="critera-card">
                <div className="center-align">
                    <span className="price-img">
                        <img alt="price" src="/assets/images/price.svg" className="img-fluid" /></span>
                    <p>Buyer </p>
                    {(activeTab ==='more_buyers')?
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
            </div>
            <div className={"property-critera-details unhide-"+index}>
                <ul className="list-unstyled mb-0">
                    <li>
                        <span className="detail-icon"><img alt="user-gradient" src="/assets/images/user-gradient.svg" className="img-fluid" /></span>
                        <span className="name-dealer">{data.name}</span>
                    </li>
                    <li>
                        <span className="detail-icon"><img alt="phone-gradient" src="/assets/images/phone-gradient.svg" className="img-fluid" /></span>
                        {(activeTab ==='more_buyers')? 
                            <span className="name-dealer">{data.phone}</span>
                            :
                            <a href={'tel:+'+data.phone} className="name-dealer">{data.phone}</a>
                        }
                    </li>
                    <li>
                        <span className="detail-icon"><img alt="email" src="/assets/images/email.svg" className="img-fluid" /></span>
                        {(activeTab ==='more_buyers')? 
                            <span className="name-dealer">{data.email}</span>
                            :
                            <a href={'mailto:'+data.email} className="name-dealer">{data.email}</a>
                        }
                    </li>
                    <li>
                        <span className="detail-icon">
                            <img alt="preference" src={PreferenceIcons} className="img-fluid" />
                        </span>
                        <span className="name-dealer">{data.contact_preferance}</span>
                    </li>
                </ul>
            </div>
            <div className="cornor-block">
                {
                    (data.createdByAdmin)?
                        (data.redFlagShow) ? <>
                            <div className="red-flag" onClick={()=>{handleClickEditFlag(data.redFlag,data.id)}}>
                                <img alt="flag" src="/assets/images/red-flag.svg" className="img-fluid" />
                            </div>
                        </>:
                        <div className="show-hide-data">
                            <OverlayTrigger placement="top" style={{ backgroundColor: 'green' }} overlay={<Tooltip>Click here to unlock the complete details of this buyer</Tooltip>} >
                                <button type="button" className="unhide-btn" onClick={()=>{handleClickConfirmation(data.id,index)}}>
                                    <span className="icon-unhide" >
                                        <img alt="unhide-icon" src="/assets/images/unhide-icon.svg" className="img-fluid" />
                                    </span>
                                </button>
                            </OverlayTrigger>
                        </div>:''
                }
            </div>
            <div className={data.createdByAdmin ? 'purchase-buyer':'your-buyer' }>{data.createdByAdmin ? 'Premium buyer':'Your buyer' }</div>
        </div>
    </div>
  )
}
export default BuyerCardResult