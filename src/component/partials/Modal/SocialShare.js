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

const SocialShare = ({openSocialShareModal,SetOpenSocialShareModal,handleCopyToClipBoard,generatedUrl}) => {
  const handleClose = () => {
    SetOpenSocialShareModal(false);
  };
  const baseURL = window.location.origin;

  const text = 'Check out this page!';
  return (
    <div>
      <Modal
        show={openSocialShareModal}
        onHide={handleClose}
        className="modal-social-share-main"
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
            <FacebookShareButton url={generatedUrl} quote={text}>
              <FacebookIcon size={32} round />
            </FacebookShareButton>

            <TwitterShareButton url={generatedUrl} title={text}>
              <TwitterIcon size={32} round />
            </TwitterShareButton>

            <WhatsappShareButton url={generatedUrl} title={text}>
              <WhatsappIcon size={32} round />
            </WhatsappShareButton>
          </div>
          <div id="" className="invite-page modal-invite-link">
              <input id="link" defaultValue={generatedUrl} />
              <div id="copy" onClick={()=>{handleCopyToClipBoard(generatedUrl)}}>
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
