import React,{useEffect, useState} from "react";
import axios from 'axios';

import MyBuyersResult from "./MyBuyersResult";
import MoreBuyersResult from "./MoreBuyersResult";
import HedgeFundResult from "./HedgefundResult";
import InvestorsResult from "./InvestorsResult";
import Header from "../partials/Layouts/Header";
import Footer from "../partials/Layouts/Footer";
import RedFlagModal from "./RedFlagModal";
import {useAuth} from "../../hooks/useAuth";
import Pagination from "../partials/Layouts/Pagination";
import Loader from "../partials/Layouts/Loader";

const ResultPage = ({setIsFiltered}) =>{

    const [buyerId, setBuyerId] = useState(0);
    const [buyerStatus, setBuyerStatus] = useState(true);
    const [filterType, setFilterType] = useState('search_page');
    const [buyerType, setBuyerType] = useState('');
    const [activeTab, setActiveTab] = useState('my_buyers');
	const {getTokenData} = useAuth();
	const [buyerData, setBuyerData] = useState([]);
	const [pageNumber, setPageNumber] = useState(1);
	
	const [totalRecord,setTotalRecord] = useState(0);
	const [currentRecord,setCurrentRecord] = useState(0);
	const [fromRecord,setFromRecord] = useState(0);
	const [toRecord,setToRecord] = useState(0);
	const [totalPage,setTotalPage] = useState(1);
	const [showLoader,setShowLoader] = useState(true);

    useEffect(() => {
		if(localStorage.getItem('get_filtered_data') !== null){
			getFilterResult();
		}
    }, []);	
    
    const getFilterResult = (page="") => {
		const apiUrl = process.env.REACT_APP_API_URL;
        let searchFields = JSON.parse(localStorage.getItem('filter_buyer_fields'));
		searchFields.activeTab = activeTab;
		searchFields.buyer_type = buyerType;
		searchFields.filterType = '';
        console.log(searchFields,'searchFields');
        let headers = { 
			'Accept': 'application/json',
			'Authorization': 'Bearer ' + getTokenData().access_token,
			'auth-token' : getTokenData().access_token,
		};
        let url = apiUrl+'buy-box-search';
		if(page>1){
			url = apiUrl+'buy-box-search?page='+page;
		}
		axios.post(url, searchFields, { headers: headers }).then(response => {
			addBuyerDetails(response.data);
        })
    }
    const addBuyerDetails = (buyer_data) => {
		setBuyerData(buyer_data.buyers.data)
			
		setCurrentRecord(buyer_data.buyers.data.length);
		setTotalRecord(buyer_data.total_records);
		setTotalPage(buyer_data.buyers.last_page);

		setFromRecord(buyer_data.buyers.from);
		setToRecord(buyer_data.buyers.to);
		setShowLoader(false);
	}
    const handleBackClick = () => {
		if(localStorage.getItem('get_filtered_data') !== null){
			localStorage.removeItem('get_filtered_data');
		}
		setIsFiltered(false)
		window.history.pushState(null, "", "/sellers-form")
	}
    const handlePagination = (page_number) =>{
		if(localStorage.getItem('get_filtered_data') !== null){
			localStorage.removeItem('get_filtered_data');
		}
		setShowLoader(true);
		setPageNumber(page_number);
		getFilterResult(pageNumber);
	}
    const handleClickMyBuyers = () => {
        setShowLoader(true);
        setActiveTab('more_buyers');
        getFilterResult();
    }
    const handleClickMoreBuyers = () => {
        setShowLoader(true);
        setActiveTab('my_buyers');
        getFilterResult();
    }
    return(
        <>
            <section className="main-section position-relative pt-4 pb-120">
                <div className="container position-relative">
                    <div className="back-block">
                        <div className="row">
                            <div className="col-12 col-sm-4 col-md-4 col-lg-4">
                                <a onClick={handleBackClick} style={{ cursor: 'pointer'}} className="back">
                                    <svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M15 6H1" stroke="#0A2540" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"></path>
                                        <path d="M5.9 11L1 6L5.9 1" stroke="#0A2540" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"></path>
                                    </svg>
                                    Back
                                </a>
                            </div>
                            <div className="col-12 col-sm-4 col-md-4 col-lg-4">
                                <h6 className="center-head text-center mb-0">Result Page</h6>
                            </div>
                            <div className="col-12 col-sm-4 col-md-4 col-lg-4">
                                <p className="page-out mb-0 text-center text-sm-end text-md-end text-lg-end">20 Out of 20</p>
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
                                                <ul className="nav nav-pills mb-0" id="pills-tab" role="tablist">
                                                    <li className="nav-item" role="presentation">
                                                        <button className="nav-link active" id="pills-my-buyers-tab" data-bs-toggle="pill" data-bs-target="#pills-my-buyers" type="button" role="tab" aria-controls="pills-my-buyers" aria-selected="true" onClick={handleClickMyBuyers}>My Buyers</button>
                                                    </li>
                                                    <li className="nav-item" role="presentation">
                                                        <button className="nav-link" id="pills-more-buyers-tab" data-bs-toggle="pill" data-bs-target="#pills-more-buyers" type="button" role="tab" aria-controls="pills-more-buyers" aria-selected="false" onClick={handleClickMoreBuyers}>More Buyers</button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div className="column-6">
                                            <div className="inner-page-title text-center">
                                                <h3 className="text-center">Property Criteria Match With 10 Buyers</h3>
                                                <p className="mb-0">5 Additional Buyer interested in similar property</p>
                                            </div>
                                        </div>
                                        <div className="column-3">
                                            <div className="buyers-tabs">
                                                <ul className="nav nav-pills mb-0" id="pills-tab" role="tablist">
                                                    <li className="nav-item" role="presentation">
                                                        <button className="nav-link" id="pills-hedgefund-tab" data-bs-toggle="pill" data-bs-target="#pills-hedgefund" type="button" role="tab" aria-controls="pills-hedgefund" aria-selected="true">Hedgefund</button>
                                                    </li>
                                                    <li className="nav-item" role="presentation">
                                                        <button className="nav-link" id="pills-investors-tab" data-bs-toggle="pill" data-bs-target="#pills-investors" type="button" role="tab" aria-controls="pills-investors" aria-selected="false">Investors</button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="tab-content" id="pills-tabContent">
                                    <div>
                                        {showLoader && <Loader />}
                                        {!showLoader && (
                                            <div>
                                                <MyBuyersResult buyerData={buyerData}/>
                                                <MoreBuyersResult buyerData={buyerData}/>
                                                <HedgeFundResult/>
                                                <InvestorsResult/>
                                            </div>
                                        )}
                                    </div>
                                        {/* <RedFlagModal buyer_id={buyerId} buyer_status={buyerStatus} /> */}
                                    </div>
                                </div>
                                <div className="row justify-content-center">
                                    <Pagination
                                        totalPage={totalPage}
                                        currentPage={pageNumber}
                                        onPageChange={handlePagination}
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </>
    );
}
export default ResultPage;