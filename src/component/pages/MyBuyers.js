import React ,{ useEffect, useState} from 'react';
import {Link , useNavigate} from "react-router-dom";
import {useAuth} from "../../hooks/useAuth";
import Header from "../partials/Layouts/Header";
import Footer from "../partials/Layouts/Footer";
import Pagination from '../partials/Layouts/Pagination';
import EditRequest from '../partials/Modal/EditRequest';
import SentRequest from '../partials/Modal/SentRequest';
import { useFormError } from '../../hooks/useFormError';
import { toast } from "react-toastify";
import axios from 'axios';

const MyBuyer = () =>{
	const {getTokenData} = useAuth();
	const [buyerData, setBuyerData] = useState([]);
	const [pageNumber, setPageNumber] = useState(1);
	const [isLoader, setIsLoader] = useState(true);
	const [totalRecord,setTotalRecord] = useState(0);
	const [currentRecord,setCurrentRecord] = useState(0);
	const [fromRecord,setFromRecord] = useState(0);
	const [toRecord,setToRecord] = useState(0);
	const [totalPage,setTotalPage] = useState(1);
	const [editOpen, setEditOpen] = useState(false);
    const [sentOpen, setSentOpen] = useState(false);
    const [buyerId, setBuyerId]   = useState('');
	const { setErrors, renderFieldError } = useFormError();
	useEffect(() => {
			getBuyerLists();
    },[]);

	const getBuyerLists = (page='') =>{
		setIsLoader(true);
		const apiUrl = process.env.REACT_APP_API_URL;
		let headers = { 
			'Accept': 'application/json',
			'Authorization': 'Bearer ' + getTokenData().access_token,
			'auth-token' : getTokenData().access_token,
		};
		let url = apiUrl+'last-search-buyer';
		if(page>1){
			url = apiUrl+'last-search-buyer?page='+page;
		}
		axios.post(url,{}, { headers: headers }).then(response => {
			setBuyerData(response.data.buyers.data)
			setIsLoader(false);
			setCurrentRecord(response.data.buyers.data.length);
			setTotalRecord(response.data.buyers.total);
			setTotalPage(response.data.buyers.last_page);

			setFromRecord(response.data.buyers.from);
			setToRecord(response.data.buyers.to);
        })
	}


	const handlePagination = (page_number) =>{
		setIsLoader(true);

		setPageNumber(page_number);

		getBuyerLists(page_number);
	} 

	const handleClickEditFlag = (data,id) => {
        setBuyerId(id);
        if(data){
            setSentOpen(true);
        }else{
            setEditOpen(true);
        }
    }

	async function likeUnlikeBuyer(id,like,unlike,index) {
        try{
            const apiUrl = process.env.REACT_APP_API_URL;
            let headers = { 
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + getTokenData().access_token,
                'auth-token' : getTokenData().access_token,
            };
            const response = await axios.post(apiUrl+"like-unlike-buyer",{'buyer_id':id,'like':like,unlike:unlike},{headers: headers});

            if(response.data.status){
                //console.log(response.data,'resp');
                const addLoaderParent = document.querySelectorAll(`.property-critera-block`)[index];
                const likeCount = addLoaderParent.querySelectorAll('.like-span')[0];
                const unLikeCount = addLoaderParent.querySelectorAll('.unlike-span')[0];
                likeCount.innerHTML = response.data.data.totalBuyerLikes;
                unLikeCount.innerHTML = response.data.data.totalBuyerUnlikes;
                toast.success(response.data.message, {position: toast.POSITION.TOP_RIGHT});
            }
        }catch(error){
            if(error.response) {
                if (error.response.data.errors) {
                    toast.error(error.response.data.errors, {position: toast.POSITION.TOP_RIGHT});
                }
                if (error.response.data.validation_errors) {
                    setErrors(error.response.data.validation_errors);
                }
                if (error.response.data.error) {
                    toast.error(error.response.data.error, {position: toast.POSITION.TOP_RIGHT});
                }
            }
        }
    }

 return (
    <>
    <Header/>
    <section className="main-section position-relative pt-4">
		{(isLoader)?<div className="loader" style={{textAlign:'center'}}><img src="assets/images/loader.svg"/></div>:
		<div className="container position-relative">
			<div className="back-block">
				<div className="row">
					<div className="col-12 col-sm-4 col-md-4 col-lg-4">
                        <Link to="/" className="back">
							<svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M15 6H1" stroke="#0A2540" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
								<path d="M5.9 11L1 6L5.9 1" stroke="#0A2540" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
							</svg>
							Back
						</Link>
					</div>
					<div className="col-12 col-sm-4 col-md-4 col-lg-4">
						<h6 className="center-head fs-3 text-center mb-0">My Buyers</h6>
					</div>
					<div className="col-12 col-sm-4 col-md-4 col-lg-4">
					<p className="page-out mb-0 text-center text-sm-end text-md-end text-lg-end">{(toRecord) ? toRecord : 0 } out of {totalRecord}</p>
					</div>
				</div>
			</div>
			<div className="card-box bg-white-gradient">
				<div className="row">
					<div className="col-12 col-lg-12">
					{ (totalRecord == 0)?<div className="card-box-inner"> <h5>No Data Found</h5> </div>: 
						<div className="card-box-inner">
							<h3 className="text-center">Property Criteria Match With {totalRecord} Buyers</h3>
							<div className="property-critera">
								<div className="row ">
									{ buyerData.map((data,index) => { 
									return(<div className="col-12 col-lg-6" key={data.id}>
										<div className="property-critera-block">
											<div className="critera-card">
												<div className="center-align">
													<span className="price-img">
                                                        <img src="./assets/images/price.svg" className="img-fluid" /></span>
													<p>Buyer</p>
													<ul className="like-unlike mb-0 list-unstyled">
														<li>
															<span className="numb like-span">{data.totalBuyerLikes}</span>
															
															<span className="ico-no ml-min" onClick={()=>{likeUnlikeBuyer(data.id,1,0,index)}}>
															<img src="/assets/images/like.svg" className="img-fluid" /></span>
														</li>
														<li>
															<span className="ico-no mr-min" onClick={()=>{likeUnlikeBuyer(data.id,0,1,index)}}><img src="/assets/images/unlike.svg" className="img-fluid" /></span>
															<span className="numb text-end unlike-span">{data.totalBuyerUnlikes}</span>
														</li>
													</ul>
												</div>
											</div>
											<div className="property-critera-details">
												<ul className="list-unstyled mb-0">
													<li>
														<span className="detail-icon">
                                                            <img src="./assets/images/user-gradient.svg" className="img-fluid" />
														</span>
														<span className="name-dealer">{data.first_name} {data.last_name}</span>
													</li>
													<li>
														<span className="detail-icon">
                                                            <img src="./assets/images/phone-gradient.svg" className="img-fluid" /></span>
														<a href={'tel:+'+data.phone} className="name-dealer">{data.phone}</a>
													</li>
													<li>
														<span className="detail-icon">
                                                            <img src="./assets/images/gmail.svg" className="img-fluid"/></span>
														<a href={'mailto:'+data.email} className="name-dealer">{data.email}</a>
													</li>
													<li>
														<span className="detail-icon"><i className="fa fa-cog contact-preferance" aria-hidden="true"></i></span>
														<a className="name-dealer">{data.contact_preferance}</a>
													</li>
												</ul>
											</div>
											<div className="cornor-block">
												{
													(data.createdByAdmin) ? 
														(data.redFlagShow) ? <>	
															<div className="red-flag" onClick={()=>{handleClickEditFlag(data.redFlag,data.id)}}><img src="./assets/images/red-flag.svg" className="img-fluid" /></div>
															</> 
														:
														''
													:
													''
												}
											</div>
										</div>
									</div>)})}
								</div>
								<div className="row justify-content-center">
									{/* {(pageNumber >1) ? <div className='col-md-2'><a className="btn btn-fill" onClick={handleClickPrev}>Prev</a></div>: ''}
									{(totalPage != pageNumber) ? <div className='col-md-2'><a className="btn btn-fill" onClick={handleClickNext}>Next</a></div>:''} */}
									
									<Pagination
										totalPage={totalPage}
										currentPage={pageNumber}
										onPageChange={handlePagination}
									/>
									
									{/* <div className="col-12 col-lg-12">
										<div className="want-to-see">
											<h3 className="text-center">Want to see more buyer!</h3>
											<Link className="btn btn-fill" to={'/choose-your-plan'}>Click Here</Link>
										</div>
									</div> */}
								</div>
							</div>
						</div>
					}
					</div>
					<EditRequest 
                        setEditOpen={setEditOpen} 
                        editOpen={editOpen} 
                        buyerId={buyerId}
                        pageNumber={pageNumber}
						getFilterResult={getBuyerLists}
                    />
                    <SentRequest setSentOpen={setSentOpen} sentOpen={sentOpen} buyerId={buyerId}/>
				</div>
			</div>
		</div>
		}
	</section>
    <Footer/>
    </>
 )
}
export default MyBuyer;
