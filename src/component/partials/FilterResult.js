import React ,{ useEffect, useState} from 'react';
import {Link , useNavigate} from "react-router-dom";
import {useAuth} from "../../hooks/useAuth";
import axios from 'axios';
import Header from "../partials/Layouts/Header";
import Footer from "../partials/Layouts/Footer";

const FilterResult = ({setIsFiltered}) =>{
	

	const {getTokenData} = useAuth();
	const [buyerData, setBuyerData] = useState([]);
	const [pageNumber, setPageNumber] = useState(1);
	const [isLoader, setIsLoader] = useState(true);
	const [totalRecord,setTotalRecord] = useState(0);
	const [currentRecord,setCurrentRecord] = useState(0);
	const [totalPage,setTotalPage] = useState(1);
	useEffect(() => {
		if(localStorage.getItem('filter_buyer_fields') !== null){
			getFilteredBuyers();
			console.log("dsgdgsdg");
		}        
    },[]);

	

	const getFilteredBuyers = (page='') => {
		const apiUrl = process.env.REACT_APP_API_URL;
		let searchFields = JSON.parse(localStorage.getItem('filter_buyer_fields'));

		searchFields.filterType = 'my_buyer'

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
			setBuyerData(response.data.buyers.data)
			setIsLoader(false);
			setCurrentRecord(response.data.buyers.data.length);
			setTotalRecord(response.data.total_records);
			setTotalPage(response.data.buyers.last_page);
        })
	}

	const handleClickNext = () =>{
		setIsLoader(true);
		let count = pageNumber+1;
		setPageNumber(count);
		if(localStorage.getItem('filter_buyer_fields') !== null){
			getFilteredBuyers(count);
		}
	}
	const handleClickPrev = () =>{
		setIsLoader(true);
		let count = pageNumber-1;
		setPageNumber(count);
		if(localStorage.getItem('filter_buyer_fields') !== null){
			getFilteredBuyers(count);
		} 
	} 

	const handleBackClick = () => {
		setIsFiltered(false)
	}
 return (
    <>
    <section className="main-section position-relative pt-4 pb-120">
		<div className="container position-relative">
			<div className="back-block">
				<div className="row">
					<div className="col-12 col-sm-4 col-md-4 col-lg-4">
                        <a onClick={handleBackClick} className="back">
							<svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M15 6H1" stroke="#0A2540" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
								<path d="M5.9 11L1 6L5.9 1" stroke="#0A2540" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
							</svg>
							Back
						</a>
					</div>
					<div className="col-12 col-sm-4 col-md-4 col-lg-4">
						<h6 className="center-head text-center mb-0">My Buyers</h6>
					</div>
					<div className="col-12 col-sm-4 col-md-4 col-lg-4">
						<p className="page-out mb-0 text-center text-sm-end text-md-end text-lg-end">{currentRecord} Out of {totalRecord}</p>
					</div>
				</div>
			</div>
			<div className="card-box bg-white-gradient">
				<div className="row">
					<div className="col-12 col-lg-12">
						<div className="card-box-inner">
							<h3 className="text-center">Property Criteria Match With 10 Buyers</h3>
							<div className="property-critera">
								<div className="row ">
									{ buyerData.map((data) => { 
									return(<div className="col-12 col-lg-6" key={data.id}>
										<div className="property-critera-block">
											<div className="critera-card">
												<div className="center-align">
													<span className="price-img">
                                                        <img src="./assets/images/price.svg" className="img-fluid" /></span>
													<p>Buyer</p>
												</div>
											</div>
											<div className="property-critera-details">
												<ul className="list-unstyled mb-0">
													<li>
														<span className="detail-icon">
                                                            <img src="./assets/images/user-gradient.svg" className="img-fluid" /></span>
														<span className="name-dealer">{data.first_name}</span>
													</li>
													<li>
														<span className="detail-icon">
                                                            <img src="./assets/images/phone-gradient.svg" className="img-fluid" /></span>
														<a href="91123456789" className="name-dealer">{data.phone}</a>
													</li>
													<li>
														<span className="detail-icon">
                                                            <img src="./assets/images/gmail.svg" className="img-fluid"/></span>
														<a href={'mailto:'+data.email} className="name-dealer">{data.email}</a>
													</li>
												</ul>
											</div>
											{/* <div className="cornor-block">
												<div className="red-flag"><img src="./assets/images/red-flag.svg" className="img-fluid" /></div>
											</div> */}
										</div>
									</div>)})}
								</div>
								<div className="row justify-content-center">
									{(pageNumber >1) ? <div className='col-md-2'><a className="btn btn-fill" onClick={handleClickPrev}>Prev</a></div>: ''}
									{(totalPage != pageNumber) ? <div className='col-md-2'><a className="btn btn-fill" onClick={handleClickNext}>Next</a></div>:''}
									
									{/* <div className="col-12 col-lg-12">
										<div className="want-to-see">
											<h3 className="text-center">Want to see more buyer!</h3>
											<a className="btn btn-fill" onClick={handleClick}>Click Here</a>
										</div>
									</div> */}
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
    </>
 )
}
export default FilterResult;
