import React, { useState } from "react";
import Modal from "react-bootstrap/Modal";

const SocialShare = ({openSocialShareModal,SetOpenSocialShareModal}) => {
  const handleClose = () => {
    SetOpenSocialShareModal(false);
  };

  return (
    <div>
      <Modal
        show={openSocialShareModal}
        onHide={handleClose}
        className="modal-socialshare-main"
      >
        {/* <button type="button" className="btn-close" onClick={handleClose}>
                <i className='fa fa-times fa-lg'></i>
            </button> */}
        <Modal.Header closeButton>
          <h5>Social Share</h5>
        </Modal.Header>
        <Modal.Body>
            <ul className="share_social">
              <li><a href="javascript:void(0)"><img src="/assets/images/whatsapp-fill-icon.svg"/></a></li>
              <li><a href="javascript:void(0)"><img src="/assets/images/facebook-fill-icon.svg"/></a></li>
              <li><a href="javascript:void(0)"><img src="/assets/images/twitter-fill-icon.svg"/></a></li>
            </ul>
            <div id="" className="invite-page modal-invite-link">
              <input id="link" value="" />
              <div id="copy">
                <i
                  className="fa-solid fa-copy"
                  aria-hidden="true"
                ></i>
              </div>
            </div>
        </Modal.Body>
      </Modal>
    </div>
  );
};
export default SocialShare;
