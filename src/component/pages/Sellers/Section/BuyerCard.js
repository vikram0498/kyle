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
    PreferenceIcons = "./assets/images/Email-Preference.svg";
  } else if (data.contact_preferance_id === 2) {
    PreferenceIcons = "./assets/images/Text-Preference.svg";
  } else if (data.contact_preferance_id === 3) {
    PreferenceIcons = "./assets/images/Call-Preference.svg";
  } else if (data.contact_preferance_id === 4) {
    PreferenceIcons = "./assets/images/1.svg";
  }
  return (
    <div className="col-12 col-lg-6">
      <div className="property-critera-block buyer-blog-area">
        <div className="critera-card">
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
          <div className="center-align position-relative">
            {(data.buyer_profile_image !='') ? 
              <img src={data.buyer_profile_image} className="img-fluid price-img" />
            :
            <span className="price-img">
              <img src='./assets/images/price.svg' className="img-fluid" />
            </span>
            }
            {(data.is_buyer_verified) &&
              <div className="buyer-check">
                <OverlayTrigger
                  placement="top"
                  style={{ backgroundColor: "green" }}
                  overlay={ <Tooltip> Profile Verified </Tooltip>}>
                  <img src='./assets/images/pcheck.svg' className="img-fluid" />
                </OverlayTrigger>
              </div>
            }
            <p>Buyer</p>

            <span class={data.status? 'ac-de-feeture active-feat':'ac-de-feeture deactive-feat'}>{data.status? 'Active':'Inactive'}</span>
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
        </div>
        <div className="property-critera-details">
          <ul className="list-unstyled mb-0">
            <li>
              <span className="detail-icon">
                <img
                  src="./assets/images/user-gradient.svg"
                  className="img-fluid"
                />
              </span>
              <span className="name-dealer">
                {data.first_name} {data.last_name}
              </span>
            </li>
            <li>
              <span className="detail-icon">
                <img
                  src="./assets/images/phone-gradient.svg"
                  className="img-fluid"
                />
              </span>
              <a href={"tel:+" + data.phone} className="name-dealer">
                {data.phone}
              </a>
            </li>
            <li>
              <span className="detail-icon">
                <img src="./assets/images/email.svg" className="img-fluid" />
              </span>
              <a href={"mailto:" + data.email} className="name-dealer">
                {data.email}
              </a>
            </li>
            <li>
              <span className="detail-icon">
                <img src={PreferenceIcons} className="img-fluid" />
              </span>
              <span className="name-dealer">{data.contact_preferance}</span>
            </li>
          </ul>
        </div>
        <div className="cornor-block">
          {data.createdByAdmin ? (
            data.redFlagShow ? (
              <>
                <div
                  className="red-flag"
                  onClick={() => {
                    handleClickEditFlag(data.redFlag, data.id);
                  }}
                >
                  <img
                    src="./assets/images/red-flag.svg"
                    className="img-fluid"
                  />
                </div>
              </>
            ) : (
              ""
            )
          ) : (
            ""
          )}
        </div>
        <div className={data.createdByAdmin ? "purchase-buyer" : "your-buyer"}>
          {data.createdByAdmin ? "Premium buyer" : "Your buyer"}
        </div>
      </div>
    </div>
  );
}
