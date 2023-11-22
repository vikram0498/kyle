import React from "react";
import BuyerHeader from "../../partials/Layouts/BuyerHeader";
import Footer from "../../partials/Layouts/Footer";
import { Link } from "react-router-dom";
import axios from "axios";
import { useState } from "react";
import { useAuth } from "../../../hooks/useAuth";
import { useFormError } from "../../../hooks/useFormError";
import { toast } from "react-toastify";
import { OverlayTrigger, Tooltip } from "react-bootstrap";

const BuyerProfile = () => {
  console.log("enterrrerer");
  const { getTokenData, setLogout } = useAuth();
  const [currentBuyerData, setCurrentBuyerData] = useState({});
  const [loader, setLoader] = useState(true);
  const { setErrors } = useFormError();

  const apiUrl = process.env.REACT_APP_API_URL;
  let headers = {
    Accept: "application/json",
    Authorization: "Bearer " + getTokenData().access_token,
  };

  const fetchBuyerData = async () => {
    try {
      let response = await axios.get(apiUrl + "edit-buyer", {
        headers: headers,
      });
      if (response.data.status) {
        setLoader(false);
        let responseData = response.data.buyer;
        setCurrentBuyerData(responseData);
        console.log(responseData, "responseData");
      }
    } catch (error) {
      if (error.response) {
        console.log(error.response,'sssss');
        if (error.response.status === 401) {
          setLogout();
        }
        if (error.response.data.errors) {
          setErrors(error.response.data.errors);
        }
        if (error.response.data.error) {
          toast.error(error.response.data.error, {
            position: toast.POSITION.TOP_RIGHT,
          });
        }
      }
    }
  };

  const profileStatus = async (data) => {
    try {
      let response = await axios.post(
        apiUrl + "update-buyer-search-status",
        { status: data },
        {
          headers: headers,
        }
      );
      console.log(response.data);
      if (response.data.status) {
        fetchBuyerData();
        setLoader(false);
        toast.success(response.data.message, {
          position: toast.POSITION.TOP_RIGHT,
        });
      }
    } catch (error) {
      if (error.response) {
        if (error.response.status === 401) {
          setLogout();
        }
        if (error.response.data.errors) {
          setErrors(error.response.data.errors);
        }
        if (error.response.data.error) {
          toast.error(error.response.data.error, {
            position: toast.POSITION.TOP_RIGHT,
          });
        }
      }
    }
  };
  const contactPreferanceUpdate = async (data) => {
    try {
      let response = await axios.post(
        apiUrl + "update-buyer-contact-pref",
        { contact_preference: data },
        {
          headers: headers,
        }
      );
      if (response.data.status) {
        fetchBuyerData();
        setLoader(false);
        toast.success(response.data.message, {
          position: toast.POSITION.TOP_RIGHT,
        });
      }
    } catch (error) {
      if (error.response) {
        if (error.response.status === 401) {
          setLogout();
        }
        if (error.response.data.errors) {
          setErrors(error.response.data.errors);
        }
        if (error.response.data.error) {
          toast.error(error.response.data.error, {
            position: toast.POSITION.TOP_RIGHT,
          });
        }
      }
    }
  };

  useState(() => {
    fetchBuyerData();
  }, []);

  const getLabelValue = (data) => {
    if (data !== undefined) {
      console.log("enter");
      const selectedBuildingClass = data.map((item) => item.label);
      console.log(selectedBuildingClass.join(","));
      return selectedBuildingClass.join(", ");
    }
  };
  const profileIcons = {
    1: "./assets/images/buyer-01.svg",
    2: "./assets/images/buyer-03.svg",
    3: "./assets/images/buyer-02.svg",
    4: "./assets/images/buyer-04.svg",
  };
  return (
    <>
      <BuyerHeader />
      <section className="main-section position-relative pt-4 pb-120 buyer-proinner">
        {loader ? (
          <div className="loader" style={{ textAlign: "center" }}>
            <img src="assets/images/loader.svg" />
          </div>
        ) : (
          <div className="container position-relative">
            <div className="back-block">
              <div className="row">
                <div className="col-12 col-lg-12">
                  <h6 className="center-head text-center mb-0">Profile</h6>
                </div>
              </div>
            </div>
            <div className="card-box">
              <div className="row">
                <div className="col-12 col-lg-4">
                  <div className="personal-information card-box-inner">
                    <h3 className="text-capitalize">personal information</h3>
                    <p>Lorem Ipsum is simply dummy text of the .</p>
                    <div className="contact-update">
                      <div className="contact-update-item">
                        <a href={void 0}>
                          <span className="icon">
                            <svg
                              width="22"
                              height="22"
                              viewBox="0 0 22 22"
                              fill="none"
                              xmlns="http://www.w3.org/2000/svg"
                            >
                              <path
                                d="M10.9993 1.83398C8.46805 1.83398 6.41602 3.88602 6.41602 6.41732C6.41602 8.94862 8.46805 11.0007 10.9993 11.0007C13.5306 11.0007 15.5827 8.94862 15.5827 6.41732C15.5827 3.88602 13.5306 1.83398 10.9993 1.83398Z"
                                fill="url(#paint0_linear_2365_13344)"
                              />
                              <path
                                d="M8.24935 12.834C7.03378 12.834 5.86799 13.3169 5.00845 14.1764C4.1489 15.0359 3.66602 16.2017 3.66602 17.4173V19.2507C3.66602 19.7569 4.07643 20.1673 4.58268 20.1673H17.416C17.9223 20.1673 18.3327 19.7569 18.3327 19.2507V17.4173C18.3327 16.2017 17.8498 15.0359 16.9902 14.1764C16.1308 13.3169 14.9649 12.834 13.7493 12.834H8.24935Z"
                                fill="url(#paint1_linear_2365_13344)"
                              />
                              <defs>
                                <linearGradient
                                  id="paint0_linear_2365_13344"
                                  x1="10.9993"
                                  y1="1.83398"
                                  x2="10.9993"
                                  y2="20.1673"
                                  gradientUnits="userSpaceOnUse"
                                >
                                  <stop stopColor="#9F9FFF" />
                                  <stop offset="1" stopColor="#2222FF" />
                                </linearGradient>
                                <linearGradient
                                  id="paint1_linear_2365_13344"
                                  x1="10.9993"
                                  y1="1.83398"
                                  x2="10.9993"
                                  y2="20.1673"
                                  gradientUnits="userSpaceOnUse"
                                >
                                  <stop stopColor="#9F9FFF" />
                                  <stop offset="1" stopColor="#2222FF" />
                                </linearGradient>
                              </defs>
                            </svg>
                          </span>
                          <span className="contact-title align-self-center">
                            {currentBuyerData.first_name +
                              " " +
                              currentBuyerData.last_name}
                          </span>
                        </a>
                      </div>
                      <div className="contact-update-item">
                        <a href={"tel:+" + currentBuyerData.phone}>
                          <span className="icon">
                            <svg
                              width="18"
                              height="18"
                              viewBox="0 0 18 18"
                              fill="none"
                              xmlns="http://www.w3.org/2000/svg"
                            >
                              <g clipPath="url(#clip0_2365_13352)">
                                <path
                                  d="M15.0945 14.7566C15.0476 14.2828 14.7937 13.8595 14.3977 13.5953L11.7131 11.8058C11.0864 11.3881 10.2485 11.4726 9.7165 12.0035L8.85854 12.8687C8.66336 12.9367 7.76061 12.5541 6.59828 11.3912C5.43545 10.2279 5.05798 9.33283 5.10741 9.15465L5.98751 8.27455C6.51897 7.7431 6.60239 6.90314 6.18526 6.27796L4.3957 3.59336C4.1315 3.19732 3.70819 2.94346 3.23442 2.8966C2.7596 2.84921 2.29716 3.01606 1.96085 3.35236L0.633212 4.68C-1.18623 6.49944 1.18323 10.4978 4.33801 13.6531C6.48275 15.7978 11.1762 19.4953 13.3111 17.3579L14.6387 16.0302C14.975 15.6944 15.1413 15.2299 15.0945 14.7566Z"
                                  fill="url(#paint0_linear_2365_13352)"
                                />
                                <path
                                  d="M10.0898 0C9.79836 0 9.5625 0.235863 9.5625 0.527344C9.5625 0.818824 9.79836 1.05469 10.0898 1.05469C13.8698 1.05469 16.9453 4.13016 16.9453 7.91016C16.9453 8.20164 17.1812 8.4375 17.4727 8.4375C17.7641 8.4375 18 8.20164 18 7.91016C18 3.54825 14.4517 0 10.0898 0ZM10.0898 2.10938C9.79836 2.10938 9.5625 2.34524 9.5625 2.63672C9.5625 2.9282 9.79836 3.16406 10.0898 3.16406C12.707 3.16406 14.8359 5.29302 14.8359 7.91016C14.8359 8.20164 15.0718 8.4375 15.3633 8.4375C15.6548 8.4375 15.8906 8.20164 15.8906 7.91016C15.8906 4.71161 13.2884 2.10938 10.0898 2.10938ZM10.0898 4.21875C9.79836 4.21875 9.5625 4.45461 9.5625 4.74609C9.5625 5.03757 9.79836 5.27344 10.0898 5.27344C11.5437 5.27344 12.7266 6.45634 12.7266 7.91016C12.7266 8.20164 12.9624 8.4375 13.2539 8.4375C13.5454 8.4375 13.7812 8.20164 13.7812 7.91016C13.7812 5.87493 12.1251 4.21875 10.0898 4.21875ZM10.0898 6.32812C9.79836 6.32812 9.5625 6.56399 9.5625 6.85547C9.5625 7.14695 9.79836 7.38281 10.0898 7.38281C10.3808 7.38281 10.6172 7.6192 10.6172 7.91016C10.6172 8.20164 10.8531 8.4375 11.1445 8.4375C11.436 8.4375 11.6719 8.20164 11.6719 7.91016C11.6719 7.03779 10.9622 6.32812 10.0898 6.32812Z"
                                  fill="url(#paint1_linear_2365_13352)"
                                />
                              </g>
                              <defs>
                                <linearGradient
                                  id="paint0_linear_2365_13352"
                                  x1="7.55112"
                                  y1="18.0009"
                                  x2="7.55112"
                                  y2="2.88862"
                                  gradientUnits="userSpaceOnUse"
                                >
                                  <stop stopColor="#5558FF" />
                                  <stop offset="1" stopColor="#00C0FF" />
                                </linearGradient>
                                <linearGradient
                                  id="paint1_linear_2365_13352"
                                  x1="13.7812"
                                  y1="8.4375"
                                  x2="13.7812"
                                  y2="0"
                                  gradientUnits="userSpaceOnUse"
                                >
                                  <stop stopColor="#ADDCFF" />
                                  <stop offset="0.5028" stopColor="#EAF6FF" />
                                  <stop offset="1" stopColor="#EAF6FF" />
                                </linearGradient>
                                <clipPath id="clip0_2365_13352">
                                  <rect width="18" height="18" fill="white" />
                                </clipPath>
                              </defs>
                            </svg>
                          </span>
                          <span className="contact-title align-self-center">
                            {currentBuyerData.phone}
                          </span>
                        </a>
                      </div>
                      <div className="contact-update-item">
                        <a href={"mailto:" + currentBuyerData.email}>
                          <span className="icon">
                            <svg
                              width="24"
                              height="24"
                              viewBox="0 0 24 24"
                              fill="none"
                              xmlns="http://www.w3.org/2000/svg"
                            >
                              <path
                                d="M12.4346 14.3299L2.91709 8.27314C2.42505 7.94774 2.08048 7.44213 1.95758 6.86517C1.83468 6.28821 1.94329 5.68606 2.26001 5.18839C2.57673 4.69072 3.07622 4.33734 3.65094 4.20434C4.22566 4.07134 4.82961 4.16937 5.33276 4.47732L12.3153 8.92054L18.5846 4.53192C19.0737 4.20046 19.6736 4.07471 20.2547 4.18182C20.8357 4.28893 21.3514 4.62032 21.6902 5.10439C22.0289 5.58847 22.1637 6.18639 22.0654 6.76901C21.9671 7.35162 21.6435 7.87218 21.1646 8.21824L12.4346 14.3299Z"
                                fill="#EA4435"
                              />
                              <path
                                d="M20.625 19.875H17.625V6.375C17.625 5.77826 17.8621 5.20597 18.284 4.78401C18.706 4.36205 19.2783 4.125 19.875 4.125C20.4717 4.125 21.044 4.36205 21.466 4.78401C21.8879 5.20597 22.125 5.77826 22.125 6.375V18.375C22.125 18.7728 21.967 19.1544 21.6857 19.4357C21.4044 19.717 21.0228 19.875 20.625 19.875Z"
                                fill="#00AC47"
                              />
                              <path
                                d="M22.0925 6.04884C22.0859 6.00384 22.0864 5.95787 22.077 5.91294C22.0626 5.84409 22.0358 5.78049 22.0153 5.71404C21.9954 5.63799 21.9714 5.56305 21.9434 5.48957C21.9284 5.45394 21.9053 5.42267 21.8883 5.38802C21.8416 5.28804 21.7876 5.19165 21.7268 5.09964C21.6968 5.05607 21.6593 5.01894 21.6261 4.97769C21.5692 4.90272 21.5077 4.83136 21.442 4.76402C21.3921 4.71549 21.3353 4.67492 21.281 4.63119C21.2234 4.58178 21.1633 4.5353 21.101 4.49192C21.0416 4.45307 20.9765 4.42314 20.9135 4.38999C20.8472 4.35542 20.7823 4.31777 20.7133 4.29017C20.6466 4.26332 20.5755 4.24622 20.5058 4.22574C20.4362 4.20527 20.3669 4.18074 20.2953 4.16747C20.2067 4.15323 20.1173 4.14421 20.0276 4.14047C19.9706 4.13649 19.9142 4.12652 19.8571 4.12697C19.751 4.13006 19.6452 4.14071 19.5407 4.15884C19.4984 4.16514 19.4559 4.16462 19.414 4.17332C19.2725 4.21656 19.1313 4.26106 18.9906 4.30682C18.952 4.32332 18.9182 4.34784 18.8807 4.36644C18.4999 4.54416 18.1786 4.82838 17.9558 5.18476C17.7331 5.54114 17.6182 5.95442 17.6253 6.37464V10.6961L21.1653 8.21792C21.5129 7.98461 21.7855 7.65556 21.95 7.27057C22.1145 6.88559 22.1641 6.46132 22.0925 6.04884Z"
                                fill="#FFBA00"
                              />
                              <path
                                d="M4.125 4.125C4.72174 4.125 5.29403 4.36205 5.71599 4.78401C6.13795 5.20597 6.375 5.77826 6.375 6.375V19.875H3.375C2.97718 19.875 2.59564 19.717 2.31434 19.4357C2.03304 19.1544 1.875 18.7728 1.875 18.375V6.375C1.875 5.77826 2.11205 5.20597 2.53401 4.78401C2.95597 4.36205 3.52826 4.125 4.125 4.125Z"
                                fill="#4285F4"
                              />
                              <path
                                d="M1.90883 6.04937C1.91543 6.00437 1.9149 5.95839 1.92428 5.91347C1.93868 5.84462 1.96545 5.78102 1.986 5.71457C2.00592 5.63854 2.02991 5.56363 2.05785 5.49017C2.07285 5.45454 2.09595 5.42327 2.11305 5.38862C2.15969 5.2886 2.21369 5.19219 2.2746 5.10017C2.3046 5.05659 2.3421 5.01947 2.37525 4.97822C2.43212 4.90325 2.49358 4.83189 2.5593 4.76454C2.60918 4.71602 2.66603 4.67544 2.72033 4.63172C2.77794 4.58229 2.83802 4.5358 2.90033 4.49244C2.9598 4.45359 3.0249 4.42367 3.08783 4.39052C3.15286 4.35374 3.21969 4.32024 3.28808 4.29017C3.35483 4.26332 3.42593 4.24622 3.49553 4.22574C3.56513 4.20527 3.6345 4.18074 3.70605 4.16747C3.7947 4.15323 3.8841 4.14422 3.9738 4.14047C4.0308 4.13649 4.08713 4.12652 4.14428 4.12697C4.25038 4.13006 4.35612 4.14071 4.4607 4.15884C4.50293 4.16514 4.54545 4.16462 4.58745 4.17332C4.66005 4.19182 4.73165 4.21405 4.80195 4.23992C4.87265 4.25871 4.94237 4.28104 5.01083 4.30682C5.04938 4.32332 5.0832 4.34784 5.1207 4.36644C5.21792 4.41212 5.31172 4.46475 5.40135 4.52394C5.70183 4.73026 5.94757 5.00661 6.11736 5.32914C6.28715 5.65167 6.37589 6.01068 6.3759 6.37517V10.6967L2.8359 8.21859C2.48834 7.98522 2.21582 7.65616 2.05129 7.2712C1.88677 6.88624 1.83729 6.46186 1.90883 6.04937Z"
                                fill="#C52528"
                              />
                            </svg>
                          </span>
                          <span className="contact-title align-self-center">
                            {currentBuyerData.email}
                          </span>
                        </a>
                      </div>
                      {/* <div className="contact-update-item">
                      <a href="#">
                        <span className="icon">
                          <svg
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                          >
                            <g clipPath="url(#clip0_2365_14198)">
                              <path
                                d="M11.9995 24.0004C16.9612 24.0004 20.9834 23.1355 20.9834 22.0686C20.9834 21.0016 16.9612 20.1367 11.9995 20.1367C7.03786 20.1367 3.01562 21.0016 3.01562 22.0686C3.01562 23.1355 7.03786 24.0004 11.9995 24.0004Z"
                                fill="url(#paint0_linear_2365_14198)"
                              />
                              <path
                                d="M20.9849 22.0686C20.9849 21.0017 16.9626 20.1367 12.001 20.1367C11.1755 20.1367 10.3764 20.1608 9.61719 20.2056L11.2988 22.6796C11.3458 22.7486 11.4005 22.8077 11.4606 22.8575L12.5992 23.9961C17.2818 23.9298 20.9849 23.0923 20.9849 22.0686Z"
                                fill="url(#paint1_linear_2365_14198)"
                              />
                              <path
                                d="M21.8457 9.84668C21.8457 4.40852 17.4373 0 11.9991 0C6.58461 0 2.20716 4.33144 2.15285 9.74569C2.11186 13.8349 4.56396 17.3557 8.08207 18.8827C8.67078 19.1383 9.17436 19.5565 9.53516 20.0873L11.2969 22.6792C11.6338 23.1747 12.3642 23.1747 12.7011 22.6792L14.4629 20.0873C14.8225 19.5582 15.3235 19.1395 15.9105 18.8851C19.4026 17.3719 21.8457 13.8949 21.8457 9.84668Z"
                                fill="url(#paint2_linear_2365_14198)"
                              />
                              <path
                                d="M12.0005 22.5597C11.9435 22.5597 11.8004 22.5444 11.7042 22.4029L9.94247 19.811C9.52816 19.2015 8.95292 18.7247 8.27894 18.4322C4.81861 16.9302 2.60717 13.5223 2.64501 9.75014C2.66995 7.26186 3.65282 4.9324 5.41254 3.1908C7.17207 1.44939 9.51197 0.490234 12.0005 0.490234H12.0006C17.1595 0.490234 21.3566 4.68736 21.3566 9.84628C21.3566 13.5788 19.1429 16.9499 15.717 18.4345C15.0468 18.7249 14.4734 19.2009 14.0586 19.8111L12.2969 22.4029C12.2006 22.5444 12.0576 22.5597 12.0005 22.5597Z"
                                fill="url(#paint3_linear_2365_14198)"
                              />
                              <path
                                d="M12.0001 16.0354C15.4185 16.0354 18.1897 13.2642 18.1897 9.8458C18.1897 6.42741 15.4185 3.65625 12.0001 3.65625C8.5817 3.65625 5.81055 6.42741 5.81055 9.8458C5.81055 13.2642 8.5817 16.0354 12.0001 16.0354Z"
                                fill="url(#paint4_linear_2365_14198)"
                              />
                              <path
                                d="M21.5392 12.2978L15.6586 6.41726C14.7438 5.44173 13.4435 4.83203 12.0005 4.83203C9.23127 4.83203 6.98633 7.07697 6.98633 9.84624C6.98633 11.2892 7.59602 12.5895 8.57155 13.5043L14.769 19.7018C15.0875 19.3535 15.4759 19.0736 15.9121 18.8847C18.6739 17.6878 20.7793 15.2625 21.5392 12.2978Z"
                                fill="url(#paint5_linear_2365_14198)"
                              />
                              <path
                                d="M12.0005 14.8605C14.7698 14.8605 17.0148 12.6155 17.0148 9.84624C17.0148 7.07697 14.7698 4.83203 12.0005 4.83203C9.23127 4.83203 6.98633 7.07697 6.98633 9.84624C6.98633 12.6155 9.23127 14.8605 12.0005 14.8605Z"
                                fill="url(#paint6_linear_2365_14198)"
                              />
                            </g>
                            <defs>
                              <linearGradient
                                id="paint0_linear_2365_14198"
                                x1="11.8017"
                                y1="21.2349"
                                x2="12.9177"
                                y2="25.9381"
                                gradientUnits="userSpaceOnUse"
                              >
                                <stop stopColor="#FFDA45" />
                                <stop offset="1" stopColor="#FFA425" />
                              </linearGradient>
                              <linearGradient
                                id="paint1_linear_2365_14198"
                                x1="16.6659"
                                y1="22.9626"
                                x2="13.6946"
                                y2="19.1715"
                                gradientUnits="userSpaceOnUse"
                              >
                                <stop stopColor="#FFDA45" stopOpacity="0" />
                                <stop offset="1" stopColor="#B53759" />
                              </linearGradient>
                              <linearGradient
                                id="paint2_linear_2365_14198"
                                x1="6.05614"
                                y1="3.90344"
                                x2="25.7032"
                                y2="23.5504"
                                gradientUnits="userSpaceOnUse"
                              >
                                <stop stopColor="#FF7044" />
                                <stop offset="1" stopColor="#F92814" />
                              </linearGradient>
                              <linearGradient
                                id="paint3_linear_2365_14198"
                                x1="9.88892"
                                y1="7.74012"
                                x2="2.98473"
                                y2="0.835928"
                                gradientUnits="userSpaceOnUse"
                              >
                                <stop stopColor="#FF7044" stopOpacity="0" />
                                <stop offset="1" stopColor="#FFA425" />
                              </linearGradient>
                              <linearGradient
                                id="paint4_linear_2365_14198"
                                x1="15.7323"
                                y1="13.578"
                                x2="3.38749"
                                y2="1.23319"
                                gradientUnits="userSpaceOnUse"
                              >
                                <stop stopColor="#FF7044" />
                                <stop offset="1" stopColor="#F92814" />
                              </linearGradient>
                              <linearGradient
                                id="paint5_linear_2365_14198"
                                x1="18.4399"
                                y1="16.2856"
                                x2="12.9685"
                                y2="10.8142"
                                gradientUnits="userSpaceOnUse"
                              >
                                <stop stopColor="#F92814" stopOpacity="0" />
                                <stop offset="1" stopColor="#C1272D" />
                              </linearGradient>
                              <linearGradient
                                id="paint6_linear_2365_14198"
                                x1="10.7731"
                                y1="7.97434"
                                x2="13.7361"
                                y2="12.4929"
                                gradientUnits="userSpaceOnUse"
                              >
                                <stop stopColor="#F9F7FC" />
                                <stop offset="1" stopColor="#F0DDFC" />
                              </linearGradient>
                              <clipPath id="clip0_2365_14198">
                                <rect width="24" height="24" fill="white" />
                              </clipPath>
                            </defs>
                          </svg>
                        </span>
                        <span className="contact-title">
                          4517 Washington Ave. Manchester, Kentucky 39495
                        </span>
                      </a>
                    </div> */}
                    </div>
                  </div>
                </div>
                <div className="col-12 col-lg-8">
                  <div className="contact-detail">
                    <div className="row align-items-center">
                      <div className="col-12 col-lg-6">
                        <div className="profile-data">
                          <div className="profile-img">
                            <img
                              src={
                                currentBuyerData.profile_image != ""
                                  ? currentBuyerData.profile_image
                                  : "/assets/images/avtar-big.png"
                              }
                              className="img-fluid"
                              alt=""
                              title=""
                            />
                          </div>
                          <div className="profile-data-content">
                            <h3>
                              {currentBuyerData.first_name +
                                " " +
                                currentBuyerData.last_name}
                            </h3>
                            <p className="mb-0">
                              Lorem Ipsum is simply dummy text of the .
                            </p>
                          </div>
                        </div>
                      </div>
                      <div className="col-12 col-lg-6">
                        <div className="component-group">
                          <div
                            className={
                              currentBuyerData.buyer_search_status === 1
                                ? "active-inactive status-active"
                                : "active-inactive status-inactive"
                            }
                          >
                            <div className="dropdown">
                              <button
                                className="btn dropdown-toggle"
                                type="button"
                                id="dropdownMenuButton1"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                              >
                                <span className="userimg">
                                  <img
                                    src={
                                      currentBuyerData.profile_image != ""
                                        ? currentBuyerData.profile_image
                                        : "/assets/images/avtar-big.png"
                                    }
                                    alt=""
                                    title=""
                                  />
                                </span>
                                <span className="dropdown-arr">
                                  <svg
                                    width="10"
                                    height="6"
                                    viewBox="0 0 10 6"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                  >
                                    <path
                                      d="M1 1L5 5L9 1"
                                      stroke="#464B70"
                                      strokeLinecap="round"
                                      strokeLinejoin="round"
                                    />
                                  </svg>
                                </span>
                              </button>
                              <ul
                                className="dropdown-menu"
                                aria-labelledby="dropdownMenuButton1"
                              >
                                <li
                                  className="profile-status"
                                  onClick={() => {
                                    profileStatus(1);
                                  }}
                                >
                                  <a className="dropdown-item" href={void 0}>
                                    Active{" "}
                                    {currentBuyerData.buyer_search_status ===
                                    1 ? (
                                      <span className="markedChecked">✓</span>
                                    ) : (
                                      ""
                                    )}
                                  </a>
                                </li>
                                <li
                                  className="profile-status"
                                  onClick={() => {
                                    profileStatus(0);
                                  }}
                                >
                                  <a className="dropdown-item" href={void 0}>
                                    Inactive{" "}
                                    {currentBuyerData.buyer_search_status ===
                                    0 ? (
                                      <span className="markedChecked">✓</span>
                                    ) : (
                                      ""
                                    )}
                                  </a>
                                </li>
                              </ul>
                            </div>
                          </div>
                          <div className="premium-quality">
                            <button className="btn">
                              <svg
                                width="20"
                                height="20"
                                viewBox="0 0 20 20"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                              >
                                <g clipPath="url(#clip0_2365_14183)">
                                  <path
                                    d="M19.8102 10.8004C20.0632 10.2992 20.0632 9.70081 19.8102 9.19964L19.5482 8.68065C19.4911 8.56752 19.4711 8.44153 19.4905 8.31625L19.5793 7.74171C19.665 7.18689 19.4802 6.61778 19.0846 6.21935L18.6751 5.80674C18.5858 5.71677 18.5278 5.60309 18.5076 5.47796L18.4145 4.90409C18.3256 4.35608 17.9807 3.87658 17.4902 3.61777L16.9864 3.3206C16.9766 3.31486 16.9667 3.30939 16.9566 3.30423C16.8439 3.2463 16.7537 3.15609 16.6958 3.04335L16.4299 2.52635C16.1792 2.03881 15.668 1.67828 15.0958 1.58546L14.522 1.4924C14.3969 1.47209 14.2832 1.41419 14.1932 1.32493L13.7806 0.915324C13.3822 0.519823 12.8132 0.335042 12.2583 0.420674L11.6838 0.509471C11.5584 0.528847 11.4325 0.508885 11.3193 0.45177L10.8003 0.189757C10.2992 -0.0632327 9.70083 -0.0632718 9.1997 0.189757L8.68067 0.45177C8.56758 0.508846 8.44163 0.528769 8.31627 0.50951L7.74173 0.420713C7.1868 0.334886 6.61781 0.519862 6.21938 0.915363L5.80673 1.32489C5.7168 1.41419 5.60312 1.47209 5.47799 1.4924L4.90407 1.58546C4.33192 1.67828 3.82074 2.03878 3.57006 2.52635L3.30422 3.04335C3.24624 3.15605 3.15604 3.2463 3.0433 3.30423L2.52626 3.57011C2.03872 3.8208 1.67818 4.33197 1.5854 4.90413L1.49231 5.47804C1.47203 5.60317 1.4141 5.71681 1.3248 5.80678L0.915309 6.21939C0.519808 6.61782 0.33491 7.18689 0.420659 7.74171L0.509495 8.31628C0.528871 8.44153 0.508909 8.56752 0.451756 8.68065L0.189742 9.19964C-0.0632474 9.70077 -0.0632474 10.2991 0.189742 10.8003L0.451756 11.3193C0.50887 11.4324 0.528832 11.5584 0.509495 11.6837L0.420659 12.2583C0.33491 12.8131 0.519847 13.3822 0.915309 13.7806L1.32487 14.1932C1.41418 14.2832 1.47211 14.3969 1.49239 14.522L1.58548 15.0959C1.67826 15.6681 2.0388 16.1792 2.52634 16.4299L3.04334 16.6958C3.15608 16.7537 3.24628 16.8439 3.30426 16.9567L3.5701 17.4737C3.82082 17.9613 4.332 18.3218 4.90415 18.4146L5.47803 18.5077C5.60315 18.528 5.71684 18.5859 5.80677 18.6751L6.21942 19.0847C6.61792 19.4802 7.18696 19.665 7.74173 19.5794L8.31631 19.4905C8.44151 19.4711 8.56758 19.4911 8.68067 19.5483L9.1997 19.8103C9.45027 19.9368 9.72513 20 10 20C10.2749 20 10.5498 19.9368 10.8003 19.8103L11.3193 19.5483C11.4325 19.4911 11.5585 19.4712 11.6837 19.4905L12.2583 19.5793C12.8131 19.665 13.3822 19.4802 13.7806 19.0847L14.1932 18.6751C14.2832 18.5858 14.3968 18.5279 14.522 18.5076L15.0958 18.4145C15.6439 18.3256 16.1234 17.9807 16.3822 17.4902L16.6794 16.9863C16.6851 16.9766 16.6906 16.9667 16.6957 16.9566C16.7537 16.8439 16.8439 16.7537 16.9566 16.6957L17.4736 16.4299C17.9729 16.1732 18.3246 15.6891 18.4145 15.1349L18.5076 14.5611C18.5279 14.4359 18.5858 14.3222 18.6751 14.2323C18.6813 14.226 19.0949 13.7702 19.0949 13.7702C19.4833 13.3723 19.6643 12.8083 19.5793 12.2583L19.4905 11.6837C19.4711 11.5585 19.491 11.4324 19.5482 11.3193L19.8102 10.8004Z"
                                    fill="url(#paint0_linear_2365_14183)"
                                  />
                                  <path
                                    d="M10.0006 2.92969C6.11858 2.92969 2.92969 6.11944 2.92969 10.0006C2.92969 13.8826 6.11944 17.0715 10.0006 17.0715C13.8826 17.0715 17.0715 13.8817 17.0715 10.0006C17.0715 6.11858 13.8817 2.92969 10.0006 2.92969ZM14.085 8.38474L12.913 13.0726C12.8478 13.3335 12.6134 13.5165 12.3445 13.5165H7.65664C7.38775 13.5165 7.15335 13.3335 7.08815 13.0726L5.91618 8.38474C5.84715 8.10859 5.98658 7.82275 6.24672 7.70711C6.50682 7.59152 6.81243 7.67961 6.97115 7.91592C7.13152 8.15164 7.7116 8.82861 8.24262 8.82861C8.33966 8.82861 8.64031 8.60273 8.95003 7.90447C9.2279 7.27805 9.4146 6.47201 9.4146 5.89868C9.4146 5.57506 9.67696 5.3127 10.0006 5.3127C10.3242 5.3127 10.5866 5.57506 10.5866 5.89868C10.5866 6.47201 10.7733 7.27805 11.0511 7.90447C11.3609 8.60273 11.6615 8.82861 11.7585 8.82861C12.3053 8.82861 12.9005 8.10871 13.03 7.91592C13.1887 7.67965 13.4944 7.59155 13.7544 7.70711C14.0146 7.82275 14.154 8.10859 14.085 8.38474Z"
                                    fill="url(#paint1_linear_2365_14183)"
                                  />
                                </g>
                                <defs>
                                  <linearGradient
                                    id="paint0_linear_2365_14183"
                                    x1="10"
                                    y1="20"
                                    x2="10"
                                    y2="1.43051e-05"
                                    gradientUnits="userSpaceOnUse"
                                  >
                                    <stop stopColor="#FD5900" />
                                    <stop offset="1" stopColor="#FFDE00" />
                                  </linearGradient>
                                  <linearGradient
                                    id="paint1_linear_2365_14183"
                                    x1="10.0006"
                                    y1="17.0715"
                                    x2="10.0006"
                                    y2="2.92969"
                                    gradientUnits="userSpaceOnUse"
                                  >
                                    <stop stopColor="#FFE59A" />
                                    <stop offset="1" stopColor="#FFFFD5" />
                                  </linearGradient>
                                  <clipPath id="clip0_2365_14183">
                                    <rect width="20" height="20" fill="white" />
                                  </clipPath>
                                </defs>
                              </svg>
                            </button>
                          </div>
                          <div className="account-check user-ac-check">
                            <OverlayTrigger
                              placement="top"
                              style={{ backgroundColor: "green" }}
                              overlay={
                                <Tooltip>
                                  {currentBuyerData.is_buyer_verified
                                    ? "Profile Verified"
                                    : "Profile Not Verified"}{" "}
                                </Tooltip>
                              }
                            >
                              <button
                                className={
                                  currentBuyerData.is_buyer_verified
                                    ? "btn"
                                    : "btn not-verified notify"
                                }
                              >
                                <img
                                  src={
                                    currentBuyerData.is_buyer_verified
                                      ? "./assets/images/profile-verified.svg"
                                      : "./assets/images/profile-not-verified.svg"
                                  }
                                />
                              </button>
                            </OverlayTrigger>
                          </div>
                          <div className="field-box">
                            <div className="dropdown">
                              <button
                                className="btn dropdown-toggle"
                                type="button"
                                id="dropdownMenuButton1"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                              >
                                <span className="commentic">
                                  <img
                                    src={
                                      profileIcons[
                                        currentBuyerData.contact_value
                                      ]
                                    }
                                  />
                                </span>
                                <span className="dropdown-arr">
                                  <svg
                                    width="10"
                                    height="6"
                                    viewBox="0 0 10 6"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                  >
                                    <path
                                      d="M1 1L5 5L9 1"
                                      stroke="#464B70"
                                      strokeLinecap="round"
                                      strokeLinejoin="round"
                                    />
                                  </svg>
                                </span>
                              </button>
                              <ul
                                className="dropdown-menu"
                                aria-labelledby="dropdownMenuButton1"
                              >
                                <li
                                  className="profile-status"
                                  onClick={() => contactPreferanceUpdate(1)}
                                >
                                  <a className="dropdown-item" href={void 0}>
                                    Email
                                    {currentBuyerData.contact_value === 1 ? (
                                      <span className="markedChecked">✓</span>
                                    ) : (
                                      ""
                                    )}
                                  </a>
                                </li>
                                <li
                                  className="profile-status"
                                  onClick={() => contactPreferanceUpdate(2)}
                                >
                                  <a className="dropdown-item" href={void 0}>
                                    Text
                                    {currentBuyerData.contact_value === 2 ? (
                                      <span className="markedChecked">✓</span>
                                    ) : (
                                      ""
                                    )}
                                  </a>
                                </li>
                                <li
                                  className="profile-status"
                                  onClick={() => contactPreferanceUpdate(3)}
                                >
                                  <a className="dropdown-item" href={void 0}>
                                    Call
                                    {currentBuyerData.contact_value === 3 ? (
                                      <span className="markedChecked">✓</span>
                                    ) : (
                                      ""
                                    )}
                                  </a>
                                </li>
                                <li
                                  className="profile-status"
                                  onClick={() => contactPreferanceUpdate(4)}
                                >
                                  <a className="dropdown-item" href={void 0}>
                                    No Preference
                                    {currentBuyerData.contact_value === 4 ? (
                                      <span className="markedChecked">✓</span>
                                    ) : (
                                      ""
                                    )}
                                  </a>
                                </li>
                              </ul>
                            </div>
                          </div>
                          <Link to="/edit-profile">
                            <div className="account-check">
                              <button className="btn">
                                <svg
                                  width="30"
                                  height="30"
                                  viewBox="0 0 30 30"
                                  fill="none"
                                  xmlns="http://www.w3.org/2000/svg"
                                >
                                  <path
                                    d="M18.075 4.075L19.175 2.975C20.475 1.675 22.7375 1.675 24.0375 2.975L24.925 3.8625C25.575 4.5125 25.9375 5.375 25.9375 6.2875C25.9375 7.2 25.575 8.075 24.925 8.7125L23.825 9.8125L18.075 4.0625V4.075ZM16.75 5.4L5.3625 16.7875C5 17.15 4.775 17.625 4.7375 18.1375L4.4 21.8C4.3625 22.2625 4.525 22.7125 4.85 23.05C5.15 23.35 5.5375 23.5125 5.95 23.5125H6.0875L9.75 23.175C10.2625 23.125 10.7375 22.9 11.1 22.5375L22.4875 11.15L16.7375 5.4H16.75ZM28.4375 27.5C28.4375 26.9875 28.0125 26.5625 27.5 26.5625H2.5C1.9875 26.5625 1.5625 26.9875 1.5625 27.5C1.5625 28.0125 1.9875 28.4375 2.5 28.4375H27.5C28.0125 28.4375 28.4375 28.0125 28.4375 27.5Z"
                                    fill="#19B400"
                                  />
                                </svg>
                              </button>
                            </div>
                          </Link>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div className="contact-desc-box">
                    <div className="row">
                      <div className="col-12 col-lg-4">States</div>
                      <div className="col-12 col-lg-8">
                        {getLabelValue(currentBuyerData.state)}
                      </div>
                      <div className="col-12 col-lg-4">Cities</div>
                      <div className="col-12 col-lg-8">
                        {getLabelValue(currentBuyerData.city)}
                      </div>
                      <div className="col-12 col-lg-4">Company/LLC</div>
                      <div className="col-12 col-lg-8">
                        {currentBuyerData.company_name}
                      </div>
                      <div className="col-12 col-lg-4">MLS Status</div>
                      <div className="col-12 col-lg-8">
                        {getLabelValue(currentBuyerData.market_preferance)}
                      </div>
                      <div className="col-12 col-lg-4">Contact Preference</div>
                      <div className="col-12 col-lg-8">
                        {getLabelValue(currentBuyerData.contact_preferance)}
                      </div>
                      <div className="col-12 col-lg-4">Property Type</div>
                      <div className="col-12 col-lg-8">
                        {getLabelValue(currentBuyerData.property_type)}
                      </div>
                      <div className="col-12 col-lg-4">Purchase Method</div>
                      <div className="col-12 col-lg-8">
                        {getLabelValue(currentBuyerData.purchase_method)}
                      </div>
                    </div>
                  </div>
                  {/* <div className="update-profile">
                  <a href="" className="btn btn-fill">
                    Update Profile
                  </a>
                </div> */}
                </div>
              </div>
            </div>
          </div>
        )}
      </section>
      <Footer />
    </>
  );
};
export default BuyerProfile;
