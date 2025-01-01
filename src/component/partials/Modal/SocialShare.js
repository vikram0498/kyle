import React, { useState } from "react";
import Modal from "react-bootstrap/Modal";
import {
  FacebookShareButton,
  FacebookIcon,
  TwitterShareButton,
  TwitterIcon,
  WhatsappShareButton,
  WhatsappIcon,
  EmailShareButton, 
  EmailIcon,
} from 'react-share';

const SocialShare = ({
  openSocialShareModal,
  SetOpenSocialShareModal,
  handleCopyToClipBoard,
  generatedUrl
}) => {
  const handleClose = () => {
    SetOpenSocialShareModal(false);
  };

  const baseURL = window.location.origin;
  const text = 'Check out this page!';
  const subject = 'Check this out!';
  
  // Ensure the body value does not contain undefined
  const sanitizedBody = 'I found this amazing website, and I thought you should check it out.' || '';
  
  // Log to verify the sanitized body value
  console.log("Sanitized Email Body:", sanitizedBody);

  return (
    <div>
      <Modal
        show={openSocialShareModal}
        onHide={handleClose}
        className="modal-social-share-main"
        centered
      >
        <Modal.Header closeButton>
          <h5>Social Share</h5>
        </Modal.Header>
        <Modal.Body>
          <div className="social-share-btn">
            <div className="social-share-list">
              <FacebookShareButton url={generatedUrl} title={text}>
                <FacebookIcon size={32} round />
              </FacebookShareButton>

              <TwitterShareButton url={generatedUrl} title={text}>
                <TwitterIcon size={32} round />
              </TwitterShareButton>

              <WhatsappShareButton url={generatedUrl} title={text}>
                <WhatsappIcon size={32} round />
              </WhatsappShareButton>

              <EmailShareButton subject={subject} body={sanitizedBody} separator="" url="">
                <EmailIcon size={32} round={true} className="email-sharing" />
              </EmailShareButton>
            </div>
            <div id="" className="invite-page modal-invite-link">
              <input id="link" defaultValue={generatedUrl} />
              <div
                id="copy"
                onClick={() => {
                  handleCopyToClipBoard(generatedUrl);
                }}
              >
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
