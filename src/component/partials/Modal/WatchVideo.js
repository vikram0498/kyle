import React, { useState } from 'react';
import Button from 'react-bootstrap/Button';
import Modal from 'react-bootstrap/Modal';

const WatchVideo = ({isLoader,videoUrl,videoTitle,SetOpenVideoModal,openVideoModal}) =>{
    const handleClose = () => {
        //const video = document.getElementById("myVideo");
        // video.currentTime = 0;
        // video.pause();
        SetOpenVideoModal(false);
    };

    return (
        <div>
          <Modal show={openVideoModal} onHide={handleClose} className="modal-video-main">
            {/* <button type="button" className="btn-close" onClick={handleClose}>
                <i className='fa fa-times fa-lg'></i>
            </button> */}
            <Modal.Header closeButton>
                <h5 class="modal-title" id="exampleModalLabel">{(videoTitle)? videoTitle :'Watch The Video'}</h5>
            </Modal.Header>
            <Modal.Body>
                {(isLoader)?<div className="video-loader"> <img src="/assets/images/data-loader.svg"/></div>:
                    <div className="video">
                        <video width="460" id="myVideo" height="240" src={videoUrl} loop autoPlay muted controls/>
                    </div>
                }
            </Modal.Body>
          </Modal>
        </div>
    );
}
export default WatchVideo;