import React, { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { useAuth } from "../../../hooks/useAuth";
import Header from "../../partials/Layouts/Header";
import Footer from "../../partials/Layouts/Footer";
import Pagination from "../../partials/Layouts/Pagination";
import EditRequest from "../../partials/Modal/EditRequest";
import SentRequest from "../../partials/Modal/SentRequest";
import { toast } from "react-toastify";
import axios from "axios";
import BuyerCard from "./Section/BuyerCard";
import { Button, Col, Image, Modal, Row, Form } from "react-bootstrap";

const MyBuyer = () => {
  const { getTokenData, setLogout, getLocalStorageUserdata } = useAuth();
  const [buyerData, setBuyerData] = useState([]);
  const [pageNumber, setPageNumber] = useState(1);
  const [isLoader, setIsLoader] = useState(true);
  const [totalRecord, setTotalRecord] = useState(0);
  const [totalPage, setTotalPage] = useState(1);
  const [currentPageNo, setCurrentPageNo] = useState(1);
  const [editOpen, setEditOpen] = useState(false);
  const [sentOpen, setSentOpen] = useState(false);
  const [buyerId, setBuyerId] = useState("");
  const [likeCount, setLikeCount] = useState(50);
  const [sendDealShow, setSendDealShow] = useState(false);

  const sendDealClose = () => setSendDealShow(false);

  useEffect(() => {
    getBuyerLists();
  }, []);

  const getBuyerLists = async (page = "") => {
    try {
      setIsLoader(true);
      const apiUrl = process.env.REACT_APP_API_URL;
      let headers = {
        Accept: "application/json",
        Authorization: "Bearer " + getTokenData().access_token,
        "auth-token": getTokenData().access_token,
      };
      let url = apiUrl + "my-buyers";
      if (page > 1) {
        url = apiUrl + "my-buyers?page=" + page;
      }
      let response = await axios.post(url, {}, { headers: headers });
      setIsLoader(false);
      if (response.data.status) {
        setBuyerData(response.data.buyers.data);
        setTotalRecord(response.data.buyers.total);
        setTotalPage(response.data.buyers.last_page);
        setCurrentPageNo(response.data.buyers.current_page);
      }
    } catch (error) {
      if (error.response) {
        if (error.response.status === 401) {
          setLogout();
        }
        if (error.response.error) {
          toast.error(error.response.error, {
            position: toast.POSITION.TOP_RIGHT,
          });
        }
      }
    }
  };

  const handlePagination = (page_number) => {
    setIsLoader(true);

    setPageNumber(page_number);

    getBuyerLists(page_number);
  };

  const handleClickEditFlag = (data, id) => {
    setBuyerId(id);
    if (data) {
      setSentOpen(true);
    } else {
      setEditOpen(true);
    }
  };

  async function likeUnlikeBuyer(id, like, unlike, index) {
    try {
      const apiUrl = process.env.REACT_APP_API_URL;
      let headers = {
        Accept: "application/json",
        Authorization: "Bearer " + getTokenData().access_token,
        "auth-token": getTokenData().access_token,
      };
      const response = await axios.post(
        apiUrl + "like-unlike-buyer",
        { buyer_id: id, like: like, unlike: unlike },
        { headers: headers }
      );

      if (response.data.status) {
        //console.log(response.data,'resp');
        const addLoaderParent = document.querySelectorAll(
          `.property-critera-block`
        )[index];
        const likeCount = addLoaderParent.querySelectorAll(".like-span")[0];
        const unLikeCount = addLoaderParent.querySelectorAll(".unlike-span")[0];
        likeCount.innerHTML = response.data.data.totalBuyerLikes;
        unLikeCount.innerHTML = response.data.data.totalBuyerUnlikes;
        //toast.success(response.data.message, {position: toast.POSITION.TOP_RIGHT});
      }
    } catch (error) {
      if (error.response) {
        if (error.response.status === 401) {
          setLogout();
        }
        if (error.response.data.errors) {
          toast.error(error.response.data.errors, {
            position: toast.POSITION.TOP_RIGHT,
          });
        }
        if (error.response.data.error) {
          toast.error(error.response.data.error, {
            position: toast.POSITION.TOP_RIGHT,
          });
        }
      }
    }
  }

  const handleLikeClick = (id, ind) => {
    let index = ind;
    let CurrentBuyer = buyerData.filter((data) => {
      return data.id === id;
    });

    if (!CurrentBuyer[0].liked) {
      CurrentBuyer[0].totalBuyerLikes = CurrentBuyer[0].totalBuyerLikes + 1;
      CurrentBuyer[0].liked = true;
      if (CurrentBuyer[0].disliked) {
        CurrentBuyer[0].totalBuyerUnlikes =
          CurrentBuyer[0].totalBuyerUnlikes - 1;
        CurrentBuyer[0].disliked = false;
      }
      likeUnlikeBuyer(id, 1, 0, index);
    } else {
      CurrentBuyer[0].totalBuyerLikes = CurrentBuyer[0].totalBuyerLikes - 1;
      CurrentBuyer[0].liked = false;
      handleDoubleClick(id);
    }
    let newBuyerData = buyerData;
    newBuyerData[index] = CurrentBuyer[0];
    setBuyerData(newBuyerData);
    setLikeCount(likeCount + 1);
  };

  const handleDisikeClick = (id, ind) => {
    let index = ind;
    let CurrentBuyer = buyerData.filter((data) => {
      return data.id === id;
    });

    if (!CurrentBuyer[0].disliked) {
      CurrentBuyer[0].totalBuyerUnlikes = CurrentBuyer[0].totalBuyerUnlikes + 1;
      CurrentBuyer[0].disliked = true;
      if (CurrentBuyer[0].liked) {
        CurrentBuyer[0].totalBuyerLikes = CurrentBuyer[0].totalBuyerLikes - 1;
        CurrentBuyer[0].liked = false;
      }
      likeUnlikeBuyer(id, 0, 1, index);
    } else {
      CurrentBuyer[0].totalBuyerUnlikes = CurrentBuyer[0].totalBuyerUnlikes - 1;
      CurrentBuyer[0].disliked = false;
      handleDoubleClick(id);
    }
    let newBuyerData = buyerData;
    console.log(newBuyerData,'newBuyerData');
    newBuyerData[index] = CurrentBuyer[0];
    setBuyerData(newBuyerData);
    setLikeCount(likeCount + 1);
  };

  const handleDoubleClick = async (buyerid) => {
    try {
      let userId = getLocalStorageUserdata().id;
      const apiUrl = process.env.REACT_APP_API_URL;
      let headers = {
        Accept: "application/json",
        Authorization: "Bearer " + getTokenData().access_token,
        "auth-token": getTokenData().access_token,
      };
      const response = await axios.delete(
        apiUrl + `del-like-unlike-buyer/${userId}/${buyerid}`,
        { headers: headers }
      );
      if (response.data.status) {
        //toast.success(response.data.message, {position: toast.POSITION.TOP_RIGHT});
      }
    } catch (error) {
      if (error.response) {
        if (error.response.status === 401) {
          setLogout();
        }
        if (error.response.data.errors) {
          toast.error(error.response.data.errors, {
            position: toast.POSITION.TOP_RIGHT,
          });
        }
        if (error.response.data.error) {
          toast.error(error.response.data.error, {
            position: toast.POSITION.TOP_RIGHT,
          });
        }
      }
    }
  };
  return (
    <>
      <Header />
      <section className="main-section position-relative pt-4">
        {isLoader ? (
          <div className="loader" style={{ textAlign: "center" }}>
            <img alt="" src="assets/images/loader.svg" />
          </div>
        ) : (
          <div className="container position-relative pat-40">
            <div className="back-block">
              <div className="row align-items-center">
                <div className="col-12 col-sm-4 col-md-4 col-lg-4">
                  <Link to="/" className="back">
                    <svg
                      width="16"
                      height="12"
                      viewBox="0 0 16 12"
                      fill="none"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        d="M15 6H1"
                        stroke="#0A2540"
                        strokeWidth="1.5"
                        strokeLinecap="round"
                        strokeLinejoin="round"
                      />
                      <path
                        d="M5.9 11L1 6L5.9 1"
                        stroke="#0A2540"
                        strokeWidth="1.5"
                        strokeLinecap="round"
                        strokeLinejoin="round"
                      />
                    </svg>
                    Back
                  </Link>
                </div>
                <div className="col-12 col-sm-4 col-md-4 col-lg-4">
                  <h6 className="center-head fs-3 text-center mb-0">
                    My Buyers
                  </h6>
                </div>
                <div className="col-12 col-sm-4 col-md-4 col-lg-4">
                  <div className="buyer_top_bar d-flex align-items-center justify-content-end">
                    <div className="buyer_top_select">
                      <span>Select : </span>
                      <label><input type="checkbox" /> <span>All</span></label>
                    </div>
                    <Button className="top_buyer_btn" onClick={() => setSendDealShow(true)}>
                      <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none">
                          <path d="M12.6404 10.5404C12.4409 11.064 12.0207 11.4619 11.4722 11.6435C10.703 11.8948 9.91239 12.0833 9.11466 12.2159C9.03631 12.2299 8.95796 12.2439 8.87961 12.2509C8.7514 12.2718 8.6232 12.2858 8.49499 12.2997C8.33829 12.3207 8.17447 12.3346 8.01065 12.3486C7.56193 12.3835 7.12032 12.4044 6.6716 12.4044C6.21575 12.4044 5.7599 12.3835 5.31118 12.3416C5.11887 12.3276 4.93368 12.3067 4.74849 12.2788C4.64165 12.2648 4.53481 12.2509 4.43509 12.2369C4.35674 12.2229 4.2784 12.2159 4.20005 12.202C3.40944 12.0763 2.62595 11.8878 1.86383 11.6365C1.29402 11.448 0.859536 11.05 0.667225 10.5404C0.474914 10.0377 0.54614 9.45127 0.852413 8.9486L1.65727 7.63608C1.82821 7.34984 1.98491 6.7983 1.98491 6.46319V5.16463C1.98491 2.63034 4.08609 0.570801 6.6716 0.570801C9.24999 0.570801 11.3512 2.63034 11.3512 5.16463V6.46319C11.3512 6.7983 11.5079 7.34984 11.6859 7.63608L12.4908 8.9486C12.7828 9.43731 12.8398 10.0098 12.6404 10.5404Z" fill="#3F53FE"/>
                          <path d="M6.65265 6.65221C6.3535 6.65221 6.11133 6.41484 6.11133 6.12162V3.95735C6.11133 3.66413 6.3535 3.42676 6.65265 3.42676C6.9518 3.42676 7.19397 3.66413 7.19397 3.95735V6.12162C7.18684 6.41484 6.94468 6.65221 6.65265 6.65221Z" fill="white"/>
                          <path d="M8.32176 13.1519C8.48163 13.1323 8.6021 13.2884 8.52168 13.4279C8.15323 14.0673 7.4546 14.4993 6.65506 14.4993C6.09238 14.4993 5.53681 14.2759 5.14507 13.878C4.98811 13.7337 4.85817 13.5564 4.75991 13.3665C4.7 13.2507 4.80227 13.1199 4.93139 13.1379C5.09521 13.1589 5.26615 13.1798 5.4371 13.1938C5.84308 13.2287 6.2562 13.2496 6.66931 13.2496C7.0753 13.2496 7.48129 13.2287 7.88016 13.1938C8.02973 13.1798 8.17931 13.1728 8.32176 13.1519Z" fill="#3F53FE"/>
                          <circle cx="11.2748" cy="10.2138" r="3.46429" fill="#ECECFF" stroke="white" stroke-width="0.5"/>
                          <path d="M12.2062 11.2005C12.0984 11.088 12.0984 10.9018 12.2062 10.7893C12.3075 10.6835 12.2326 10.5079 12.0861 10.5079L9.426 10.5079C9.27254 10.5079 9.14397 10.378 9.14397 10.2135C9.14397 10.049 9.2684 9.91915 9.426 9.91915L12.0861 9.91915C12.2326 9.91915 12.3075 9.74352 12.2062 9.63776C12.0984 9.52521 12.0984 9.33906 12.2062 9.22651C12.314 9.11395 12.4924 9.11395 12.6002 9.22651L13.3467 10.0057C13.3512 10.0104 13.3531 10.0172 13.3572 10.0223C13.3757 10.0457 13.3936 10.0698 13.4048 10.101C13.4214 10.1399 13.4297 10.1746 13.4297 10.2135C13.4297 10.2525 13.4214 10.2871 13.409 10.3261C13.3965 10.3607 13.3758 10.3953 13.3467 10.4213L12.6002 11.2005C12.4924 11.3131 12.314 11.3131 12.2062 11.2005Z" fill="#3F53FE"/>
                        </svg>
                      </span> SEND DEAL
                    </Button>
                  </div>
                  {/* <p className="page-out mb-0 text-center text-sm-end text-md-end text-lg-end">
                    {currentPageNo} out of {totalPage}
                  </p> */}
                </div>
              </div>
            </div>
            <div className="card-box bg-white-gradient pt-0">
              <div className="row">
                <div className="col-12 col-lg-12">
                  {totalRecord === 0 ? (
                    <div className="card-box-inner">
                      {" "}
                      <h5>No Data Found</h5>{" "}
                    </div>
                  ) : (
                    <div className="card-box-inner">
                      {/* <h3 className="text-center">Property Criteria Match With {totalRecord} Buyers </h3>
                      <h3 className="text-center">Your Purchased Buyer Details</h3> */}
                      <div className="property-critera">
                        <div className="row cust-row">
                          {buyerData.map((data, index) => {
                            return (
                              <BuyerCard
                                data={data}
                                key={data.id}
                                handleLikeClick={handleLikeClick}
                                handleDisikeClick={handleDisikeClick}
                                handleClickEditFlag={handleClickEditFlag}
                                index={index}
                              />
                            );
                          })}
                        </div>
                        <div className="row justify-content-center">
                          {/* {(pageNumber >1) ? <div className='col-md-2'><a className="btn btn-fill" onClick={handleClickPrev}>Prev</a></div>: ''}
									{(totalPage != pageNumber) ? <div className='col-md-2'><a className="btn btn-fill" onClick={handleClickNext}>Next</a></div>:''} */}
                          {totalPage > 1 ? (
                            <Pagination
                              totalPage={totalPage}
                              currentPage={pageNumber}
                              onPageChange={handlePagination}
                            />
                          ) : (
                            ""
                          )}
                          {/* <div className="col-12 col-lg-12">
										<div className="want-to-see">
											<h3 className="text-center">Want to see more buyer!</h3>
											<Link className="btn btn-fill" to={'/choose-your-plan'}>Click Here</Link>
										</div>
									</div> */}
                        </div>
                      </div>
                    </div>
                  )}
                </div>
                <EditRequest
                  setEditOpen={setEditOpen}
                  setBuyerData={setBuyerData}
                  editOpen={editOpen}
                  buyerId={buyerId}
                  buyerData={buyerData}
                />
                <SentRequest
                  setSentOpen={setSentOpen}
                  sentOpen={sentOpen}
                  buyerId={buyerId}
                />
              </div>
            </div>
          </div>
        )}
      </section>
      <Footer />
      <Modal show={sendDealShow} onHide={() => setSendDealShow(false)} centered className='radius_30 max-980'>
        <Modal.Body className=''>
            <div className="send_deal_modal">
              <Row>
                <Col lg={5}>
                  <div className="deal_property_img">
                    <Image src='/assets/images/property-img.png' alt='' />
                  </div>
                </Col>
                <Col lg={7} className="align-self-center">
                  <div className="deal_property_content">
                    <h2>real estate company that prioritizes Property</h2>
                    <p><Image src="/assets/images/map_pin.svg" alt="" />4517 Washington Ave. Manchester, Kentucky 39495..</p>
                    <ul>
                      <li><span><Image src="/assets/images/bed.svg" alt="" /></span> 02 Bed</li>
                      <li><span><Image src="/assets/images/bathroom.svg" alt="" /></span> 02 Baths</li>
                    </ul>
                  </div>
                </Col>
                <Col lg={12}>
                  <Form className="send_deal_form">
                    <Form.Group className="m-0">
                      <Form.Label>Message</Form.Label>
                      <Form.Control as="textarea" placeholder="enter Message" />
                    </Form.Group>
                    <Form.Group className="both_btn_group m-0">
                      <Button className="light_bg_btn" onClick={sendDealClose}>
                        Cancel
                      </Button>
                      <Button className="btn btn-fill" type="submit">
                        Submit
                      </Button>
                    </Form.Group>
                  </Form>
                </Col>
              </Row>
            </div>
        </Modal.Body>
      </Modal>
    </>
  );
};
export default MyBuyer;
