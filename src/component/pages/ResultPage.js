import React, { useEffect, useState } from "react";
import axios from "axios";

import MyBuyersResult from "./MyBuyersResult";
import MoreBuyersResult from "./MoreBuyersResult";
import HedgeFundResult from "./HedgefundResult";
import InvestorsResult from "./InvestorsResult";
import Header from "../partials/Layouts/Header";
import Footer from "../partials/Layouts/Footer";
import RedFlagModal from "./RedFlagModal";
import { useAuth } from "../../hooks/useAuth";
import Pagination from "../partials/Layouts/Pagination";
import Loader from "../partials/Layouts/Loader";
import { toast } from "react-toastify";
import { useFormError } from "../../hooks/useFormError";
import { Link, useNavigate } from "react-router-dom";

const ResultPage = ({ setIsFiltered }) => {
  const [buyerId, setBuyerId] = useState(0);
  const [buyerStatus, setBuyerStatus] = useState(true);
  const [filterType, setFilterType] = useState("search_page");
  const [buyerType, setBuyerType] = useState("");
  const [activeTab, setActiveTab] = useState("my_buyers");
  const { getTokenData, setLogout, getLocalStorageUserdata } = useAuth();
  const [buyerData, setBuyerData] = useState([]);
  const [pageNumber, setPageNumber] = useState(1);
  const [additionalBuyerCount, setAdditionalBuyerCount] = useState(0);
  const [totalRecord, setTotalRecord] = useState(0);
  const [currentRecord, setCurrentRecord] = useState(0);
  const [fromRecord, setFromRecord] = useState(0);
  const [toRecord, setToRecord] = useState(0);
  const [totalPage, setTotalPage] = useState(1);
  const [currentPageNo, setCurrentPageNo] = useState(1);
  const [showLoader, setShowLoader] = useState(true);
  const { setErrors, renderFieldError } = useFormError();

  useEffect(() => {
    getFilterResult();
  }, [activeTab, buyerType, pageNumber]);

  const getFilterResult = async (
    page = pageNumber,
    active_tab = activeTab,
    buyer_type = buyerType
  ) => {
    console.log(activeTab, "activeTab");
    try {
      setShowLoader(true);
      const apiUrl = process.env.REACT_APP_API_URL;
      let searchFields = JSON.parse(
        localStorage.getItem("filter_buyer_fields")
      );
      searchFields.activeTab = active_tab == null ? "my_buyers" : activeTab;
      searchFields.buyer_type = buyer_type;
      searchFields.filterType = "";
      let headers = {
        Accept: "application/json",
        Authorization: "Bearer " + getTokenData().access_token,
        "auth-token": getTokenData().access_token,
      };
      let url = apiUrl + "buy-box-search";
      if (page > 1) {
        url = apiUrl + "buy-box-search?page=" + page;
      }
      let response = await axios.post(url, searchFields, { headers: headers });
      if (response) {
        addBuyerDetails(response.data);
      }
      setShowLoader(false);
    } catch (error) {
      setShowLoader(false);
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
  const addBuyerDetails = (buyer_data) => {
    setBuyerData(buyer_data.buyers.data);
    setCurrentRecord(buyer_data.buyers.data.length);
    setTotalRecord(buyer_data.total_records);
    setTotalPage(buyer_data.buyers.last_page);
    setCurrentPageNo(buyer_data.buyers.current_page);
    setAdditionalBuyerCount(buyer_data.additional_buyers_count);
    setFromRecord(buyer_data.buyers.from);
    setToRecord(buyer_data.buyers.to);
    setShowLoader(false);
  };
  const handleBackClick = () => {
    if (localStorage.getItem("get_filtered_data") !== null) {
      localStorage.removeItem("get_filtered_data");
    }
    setIsFiltered(false);
    //window.history.pushState(null, "", "/sellers-form")
  };
  const handlePagination = (page_number) => {
    if (localStorage.getItem("get_filtered_data") !== null) {
      localStorage.removeItem("get_filtered_data");
    }
    setPageNumber(page_number);
  };
  const handleClickMyBuyers = () => {
    setPageNumber(1);
    setBuyerType("");
    setActiveTab("my_buyers");
    //console.log(activeTab,'activeTab1',pageNumber);
  };
  const handleClickMoreBuyers = () => {
    setActiveTab("more_buyers");
    setPageNumber(1);
    setBuyerType("");
  };
  const handleClickHedgeFund = () => {
    setPageNumber(1);
    setBuyerType(5);
  };
  const handleClickInvestors = () => {
    setPageNumber(1);
    setBuyerType(11);
  };

  const user_data = getLocalStorageUserdata();
  return (
    <>
      <section className="main-section position-relative pt-4 pb-120">
        <div className="container position-relative">
          <div className="back-block">
            <div className="row">
              <div className="col-12 col-sm-4 col-md-4 col-lg-4">
                <a
                  onClick={handleBackClick}
                  style={{ cursor: "pointer" }}
                  className="back"
                >
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
                    ></path>
                    <path
                      d="M5.9 11L1 6L5.9 1"
                      stroke="#0A2540"
                      strokeWidth="1.5"
                      strokeLinecap="round"
                      strokeLinejoin="round"
                    ></path>
                  </svg>
                  Back
                </a>
              </div>
              <div className="col-12 col-sm-4 col-md-4 col-lg-4">
                <h6 className="center-head text-center mb-0">Result Page</h6>
              </div>
              <div className="col-12 col-sm-4 col-md-4 col-lg-4">
                <p className="page-out mb-0 text-center text-sm-end text-md-end text-lg-end">
                  {currentPageNo} out of {totalPage}
                </p>
              </div>
            </div>
          </div>
          <div className="card-box">
            <div className="row">
              <div className="col-12 col-lg-12">
                <div className="card-box-inner">
                  <div className="custom-divide">
                    <div className="column-3">
                      <div className="buyers-tabs">
                        <ul
                          className="nav nav-pills mb-0"
                          id="pills-tab"
                          role="tablist"
                        >
                          <li className="nav-item" role="presentation">
                            <button
                              className="nav-link active"
                              id="pills-my-buyers-tab"
                              data-bs-toggle="pill"
                              data-bs-target="#pills-my-buyers"
                              type="button"
                              role="tab"
                              aria-controls="pills-my-buyers"
                              aria-selected="true"
                              onClick={handleClickMyBuyers}
                            >
                              My Buyers
                            </button>
                          </li>
                          {user_data.level_type === 2 ? (
                            <li className="nav-item" role="presentation">
                              <button
                                className="nav-link"
                                id="pills-more-buyers-tab"
                                data-bs-toggle="pill"
                                data-bs-target="#pills-more-buyers"
                                type="button"
                                role="tab"
                                aria-controls="pills-more-buyers"
                                aria-selected="false"
                                onClick={handleClickMoreBuyers}
                              >
                                More Buyers
                              </button>
                            </li>
                          ) : (
                            ""
                          )}
                        </ul>
                      </div>
                    </div>
                    <div className="column-6">
                      <div className="inner-page-title text-center">
                        <h3 className="text-center">
                          Property Criteria Match With {totalRecord} Buyers
                        </h3>
                        <p className="mb-0">
                          {additionalBuyerCount} Additional Buyer interested in
                          similar property
                        </p>
                      </div>
                    </div>
                    <div className="column-3">
                      <div className="buyers-tabs">
                        <ul
                          className="nav nav-pills mb-0"
                          id="pills-tab"
                          role="tablist"
                        >
                          <li className="nav-item" role="presentation">
                            <button
                              className={`nav-link ${
                                buyerType === 5 ? " active" : ""
                              }`}
                              id="pills-hedgefund-tab"
                              data-bs-toggle="pill"
                              data-bs-target="#pills-hedgefund"
                              type="button"
                              role="tab"
                              aria-controls="pills-hedgefund"
                              aria-selected="true"
                              onClick={handleClickHedgeFund}
                            >
                              Hedgefund
                            </button>
                          </li>
                          <li className="nav-item" role="presentation">
                            <button
                              className={`nav-link ${
                                buyerType === 11 ? " active" : ""
                              }`}
                              id="pills-investors-tab"
                              data-bs-toggle="pill"
                              data-bs-target="#pills-investors"
                              type="button"
                              role="tab"
                              aria-controls="pills-investors"
                              aria-selected="false"
                              onClick={handleClickInvestors}
                            >
                              Investors
                            </button>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div className="tab-content" id="pills-tabContent">
                    {showLoader && <Loader />}
                    {!showLoader && (
                      <div>
                        <MyBuyersResult
                          setBuyerData={setBuyerData}
                          buyerData={buyerData}
                          getFilterResult={getFilterResult}
                          pageNumber={pageNumber}
                          activeTab={activeTab}
                          buyerType={buyerType}
                        />
                        {/* <MoreBuyersResult buyerData={buyerData}/>
                                                <HedgeFundResult buyerData={buyerData}/>
                                                <InvestorsResult buyerData={buyerData}/> */}
                        {/* <RedFlagModal/> */}
                      </div>
                    )}
                  </div>
                </div>
                {user_data.level_type === 1 && additionalBuyerCount > 0 ? (
                  <div className="col-12 col-lg-12">
                    <div className="want-to-see">
                      <h3 className="text-center">Want to see more buyer!</h3>
                      <Link className="btn btn-fill" to={"/choose-your-plan"}>
                        Click Here
                      </Link>
                    </div>
                  </div>
                ) : (
                  ""
                )}

                <div className="row justify-content-center">
                  {totalPage > 1 ? (
                    <Pagination
                      totalPage={totalPage}
                      currentPage={pageNumber}
                      onPageChange={handlePagination}
                    />
                  ) : (
                    ""
                  )}
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </>
  );
};
export default ResultPage;
