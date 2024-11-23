import React from "react";
import { OverlayTrigger, Tooltip } from 'react-bootstrap';

export default function BuyerCard({
  data,
  handleLikeClick,
  handleDisikeClick,
  handleClickEditFlag,
  index,
}) {
  let PreferenceIcons = "./assets/images/contact-preferance.svg";
  if (data.contact_preferance_id === 1) {
    PreferenceIcons = "./assets/images/Email-Preference-bg.svg";
  } else if (data.contact_preferance_id === 2) {
    PreferenceIcons = "./assets/images/Text-Preference-bg.svg";
  } else if (data.contact_preferance_id === 3) {
    PreferenceIcons = "./assets/images/Call-Preference-bg.svg";
  } else if (data.contact_preferance_id === 4) {
    PreferenceIcons = "./assets/images/no-preference-bg.svg";
  }
  return (
    <div className="col-12 col-lg-6">
      <div className="property-critera-block buyer-blog-area buyer-blog-new">
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
        <div className="critera-card">
          <div className="center-align position-relative">
            {(data.buyer_profile_image !='') ? 
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

            {/* <span class={data.status? 'ac-de-feeture active-feat':'ac-de-feeture deactive-feat'}>{data.status? 'Active':'Inactive'}</span> */}
            {data.createdByAdmin ? (
              <ul className="like-unlike mb-0 list-unstyled 888">
                <li>
                  <span className="numb like-span">{data.totalBuyerLikes}</span>
                  <span
                    className="ico-no ml-min"
                    onClick={() => {
                      handleLikeClick(data.id, index);
                    }}
                  >
                    {/* <span className="ico-no ml-min" onDoubleClick={handleDoubleClick}> */}
                    <img
                      src={
                        !data.liked
                          ? "/assets/images/like.svg"
                          : "/assets/images/liked.svg"
                      }
                      className="img-fluid"
                      alt=""
                    />
                  </span>
                </li>
                <li>
                  <span
                    className="ico-no mr-min"
                    onClick={() => {
                      handleDisikeClick(data.id, index);
                    }}
                  >
                    <img
                      src={
                        !data.disliked
                          ? "/assets/images/unlike.svg"
                          : "/assets/images/unliked.svg"
                      }
                      className="img-fluid"
                      alt=""
                    />
                  </span>
                  <span className="numb text-end unlike-span">
                    {data.totalBuyerUnlikes}
                  </span>
                </li>
              </ul>
            ) : (
              ""
            )}
          </div>
          <div className={data.createdByAdmin ? "purchase-buyer" : "your-buyer"}>
          {data.createdByAdmin ? "Premium buyer" : "Your buyer"}
        </div>
        </div>
        <div className="property-critera-details">
          <ul className="list-unstyled mb-0">
            <li>
              <span className="detail-icon">
                  {/* <span className="contact-preference mx-4"> */}
                    <OverlayTrigger placement="top" style={{ backgroundColor: 'green' }} overlay={<Tooltip>Contact Preference</Tooltip>} >
                    <img src={PreferenceIcons} className="img-fluid" alt=""/>
                    </OverlayTrigger>
                  {/* </span> */}
                {/* <img
                  src="./assets/images/uservbox.svg"
                  className="img-fluid"
                /> */}
              </span>
              <span className="name-dealer b-name">
                {data.first_name} {data.last_name}
              </span>
            </li>
            <li>
              <span className="detail-icon">
                <img
                  src="./assets/images/callingbox.svg"
                  className="img-fluid"
                  alt=""
                />
              </span>
              <a href={"tel:+" + data.phone} className="name-dealer">
                {data.phone} 
              </a>
            </li>
            <li>
              <span className="detail-icon">
                <img src="./assets/images/emailbox.svg" className="img-fluid" alt=""/>
              </span>
              <a href={"mailto:" + data.email} className="name-dealer email-tag">
                {data.email}
                <span className="mx-2">
                </span>
              </a>
            </li>
            {/* <li>
              <span className="detail-icon">
                <img src={PreferenceIcons} className="img-fluid" />
              </span>
              <span className="name-dealer">{data.contact_preferance}</span>
            </li> */}
          </ul>
        </div>
        {(data.phone_verified || data.email_verified || data.driver_license_verified || data.llc_verified || data.proof_of_funds_verified || data.certified_closer_verified || data.createdByAdmin ) ? 
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
          
          {data.createdByAdmin ? (
            data.redFlagShow ? (
              <>
                <div
                  className="red-flag"
                  onClick={() => {
                    handleClickEditFlag(data.redFlag, data.id);
                  }}
                >
                  <OverlayTrigger placement="top" style={{ backgroundColor: 'green' }} overlay={<Tooltip>Flag</Tooltip>} >
                    <img
                      src="/assets/images/red-flag-bg.svg"
                      className="img-fluid"
                      alt=""
                    />
                  </OverlayTrigger>
                </div>
              </>
            ) : (
              ""
            )
          ) : (
            ""
          )}
        </div>:''}
       
      </div>
    </div>
  );
}
