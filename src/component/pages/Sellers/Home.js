import React, { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { useFormError } from "../../../hooks/useFormError";
import { toast } from "react-toastify";
import Header from "../../partials/Layouts/Header";
import Footer from "../../partials/Layouts/Footer";
import { useAuth } from "../../../hooks/useAuth";
import axios from "axios";
import UploadMultipleBuyersOnChange from "../../partials/UploadMultipleBuyersOnChange";
import WatchVideo from "../../partials/Modal/WatchVideo";

function Home({ userDetails }) {
  //const {authData} = useContext(AuthContext);
  const { getTokenData, setLogout } = useAuth();
  const { setErrors } = useFormError();
  const [videoUrl, setVideoUrl] = useState("");
  const [videoTitle, setVideoTitle] = useState("");
  const [videoSubTitle, setVideoSubTitle] = useState("");
  const [isActiveVideo, setIsActiveVideo] = useState("");
  const [isLoader, setIsloader] = useState(true);
  const [openVideoModal, SetOpenVideoModal] = useState(false);

  useEffect(() => {
    getVideoUrl();
  }, []);
  const getVideoUrl = async () => {
    try {
      const apiUrl = process.env.REACT_APP_API_URL;
      let headers = {
        Accept: "application/json",
        Authorization: "Bearer " + getTokenData().access_token,
        "auth-token": getTokenData().access_token,
      };

      let response = await axios.get(apiUrl + "getVideo/upload_buyer_video", {
        headers: headers,
      });
      if (response) {
        let videoLink = response.data.videoDetails.video.video_link;
        let videoText = response.data.videoDetails.video.title;
        setVideoUrl(videoLink);
        setVideoTitle(videoText);
        setVideoSubTitle(response.data.videoDetails.video.sub_title);
        setIsActiveVideo(response.data.videoDetails.is_active);
        //setIsPlaying(true)
      }
      setIsloader(false);
    } catch (error) {
      if (error.response) {
        if (error.response.status === 401) {
          setLogout();
        }
        if (error.response.validation_errors) {
          setErrors(error.response.data.validation_errors);
        }
        if (error.response.errors) {
          setErrors(error.response.errors);
        }
        if (error.response.error) {
          toast.error(error.response.error, {
            position: toast.POSITION.TOP_RIGHT,
          });
        }
      }
    }
  };
  const handleOpenModal = () => {
    SetOpenVideoModal(true);
  };
  // video.currentTime = 0;
  // video.play();
  //video.pause();
  return (
    <>
      <Header />
      <section className="main-section pt-120 pb-5 position-relative">
        {isActiveVideo ? (
          <div className="watch-video block-fix">
            <p>{videoTitle}</p>
            {/* <a href="" data-bs-toggle="modal" data-bs-target="#exampleModal" className="title"> */}
            <a onClick={handleOpenModal} className="title">
              <svg
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
                  stroke="#121639"
                  strokeWidth="2"
                  strokeLinecap="round"
                  strokeLinejoin="round"
                />
                <path
                  d="M10 8L16 12L10 16V8Z"
                  stroke="#121639"
                  strokeWidth="2"
                  strokeLinecap="round"
                  strokeLinejoin="round"
                />
              </svg>
              Watch The Video!
            </a>
          </div>
        ) : (
          ""
        )}
        <div className="container position-relative">
          <div className="row justify-content-center">
            <div className="col-12 col-lg-8">
              <div className="heading-title">
                <h2>Select The option below!</h2>
                <p>
                  Upload your buyer’s criteria and use the Buybox Search to find
                  the right buyers for your deals.
                </p>
              </div>
            </div>
          </div>
          <div className="row row-gap">
            <div className="col-12 col-sm-6 col-md-6 col-lg-3">
              <Link to="/add-buyer-details" className="grid-block-view">
                <div className="grid-block-icon">
                  <img
                    src="./assets/images/upload-buyer.svg"
                    className="img-fluid"
                    alt=""
                  />
                </div>
                <h3>Upload Buyers</h3>
              </Link>
            </div>
            <div className="col-12 col-sm-6 col-md-6 col-lg-3">
              <Link to="/sellers-form" className="grid-block-view">
                <div className="grid-block-icon">
                  <img
                    src="./assets/images/buybox-search.svg"
                    className="img-fluid"
                    alt=""
                  />
                </div>
                <h3>Buybox Search</h3>
              </Link>
            </div>
            <div className="col-12 col-sm-6 col-md-6 col-lg-3">
              <Link to="/my-buyers" className="grid-block-view">
                <div className="grid-block-icon">
                  <img
                    src="./assets/images/my-buyers.svg"
                    className="img-fluid"
                    alt=""
                  />
                </div>
                <h3>My Buyers</h3>
              </Link>
            </div>
            <div className="col-12 col-sm-6 col-md-6 col-lg-3">
              <UploadMultipleBuyersOnChange />
            </div>
          </div>
        </div>
      </section>
      <Footer />

      {/* modal box for video */}
      <WatchVideo
        isLoader={isLoader}
        videoUrl={videoUrl}
        videoSubTitle={videoSubTitle}
        SetOpenVideoModal={SetOpenVideoModal}
        openVideoModal={openVideoModal}
      />
      {/* <div className="modal fade" id="exampleModal" tabIndex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div className="modal-dialog" role="document">
                    <div className="modal-content">
                        <div className="modal-header">
                            <h5 className="modal-title" id="exampleModalLabel">Watch The Video</h5>
                            <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div className="modal-body">
                        {(isLoader)?<div className="video-loader"> <img src="/assets/images/data-loader.svg"/></div>:
                            <div className="video">
                                <video width="460" height="240" src={videoUrl} loop autoPlay muted controls/>
                            </div>
                        }
                        </div>
                    </div>
                </div>
            </div> */}
    </>
  );
}

export default Home;
