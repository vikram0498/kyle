import React, { useState } from "react";
import Modal from "react-bootstrap/Modal";
import {
  FacebookShareButton,
  FacebookIcon,
  TwitterShareButton,
  TwitterIcon,
  WhatsappShareButton,
  WhatsappIcon,
} from 'react-share';

const SocialShare = ({openSocialShareModal,SetOpenSocialShareModal,handleCopyToClipBoard}) => {
  const handleClose = () => {
    SetOpenSocialShareModal(false);
  };
  const url = 'http://localhost:3006/add-buyer/OQ0VIYG2N1eJQxuhxdA598rFtytwz1K6';
  const text = 'Check out this page!';
  return (
    <div>
      <Modal
        show={openSocialShareModal}
        onHide={handleClose}
        className="modal-socialshare-main"
        centered
      >
        {/* <button type="button" className="btn-close" onClick={handleClose}>
                <i className='fa fa-times fa-lg'></i>
            </button> */}
        <Modal.Header closeButton>
          <h5>Social Share</h5>
        </Modal.Header>
        <Modal.Body>
        <div className="social-share-btn">
          <div className="social-share-list">
            <FacebookShareButton url={url} quote={text}>
              <FacebookIcon size={32} round />
            </FacebookShareButton>

            <TwitterShareButton url={url} title={text}>
              <TwitterIcon size={32} round />
            </TwitterShareButton>

            <WhatsappShareButton url={url} title={text}>
              <WhatsappIcon size={32} round />
            </WhatsappShareButton>
          </div>
          <div id="" className="invite-page modal-invite-link">
              <input id="link" value={url} />
              <div id="copy" onClick={()=>{handleCopyToClipBoard(url)}}>
                <i className="fa-solid fa-copy" aria-hidden="true"></i>
              </div>
            </div>
        </div>
        </Modal.Body>
      </Modal>
    </div>
  );
};
export default SocialShare;
