import React, {useContext, useEffect, useState} from "react";
import {useNavigate , Link} from "react-router-dom";
import AuthContext from "../../context/authContext";
import Header from "../partials/Layouts/Header";
import Footer from "../partials/Layouts/Footer";
// import SingleSelect from "../partials/Select2/SingleSelect";
import MultiSelect from "../partials/Select2/MultiSelect";
// import { City, Country, State } from "country-state-city";
import {useAuth} from "../../hooks/useAuth";
import Select from "react-select";
import {useForm} from "../../hooks/useForm";
import axios from 'axios';
import MiniLoader from "../partials/MiniLoader";
import { toast } from "react-toastify";

import CondoPropertySearch from './FilterPropertyForm/Condo';
import DevelopmentPropertySearch from './FilterPropertyForm/Development';
import MultiFamilyPropertySearch from './FilterPropertyForm/MultiFamilyResidential';
import UploadMultipleBuyers from "../partials/UploadMultipleBuyers";
import FilterResult from "../partials/FilterResult";



const SellerForm = () =>{
	const {authData} = useContext(AuthContext);
    const {getTokenData} = useAuth();
    const navigate = useNavigate();
    const [isLoader, setIsLoader] = useState(true);

	const [isFiltered,setIsFiltered] = useState(false);

    const { setErrors, renderFieldError } = useForm();
   
	const [propertyTypeOption, setPropertyTypeOption] = useState([]);
    
    const [conodoSelected,setConodoSelected] = useState(false);
    const [landSelected,setLandSelected] = useState(false);
    const [multiFamilySelected,setMultiFamilySelected] = useState(false);


    const [propertyTypeValue, setPropertyTypeValue] = useState('');
    const [locationFlawsValue,setLocationFlawsValue] = useState([]);
    const [purchaseMethodsValue, setPurchaseMethodsValue] = useState([]);

    const [loading, setLoading] = useState(false);

    useEffect(() => {
        getOptionsValues();
    }, []);
    
    
    const apiUrl = process.env.REACT_APP_API_URL;
    
    let headers = { 
        'Accept': 'application/json',
        'Authorization': 'Bearer ' + getTokenData().access_token,
		'auth-token' : getTokenData().access_token,
    };
    const getOptionsValues = () =>{
        axios.get(apiUrl+'single-buyer-form-details', { headers: headers }).then(response => {
            if(response.data.status){
                let result = response.data.result;

                setPropertyTypeOption(result.property_types);
                setIsLoader(false);
            }
        })
    }

	const handlePropertyTypeChange = (value) => {
		setErrors(null);
		if(value == null){
			setConodoSelected(false);
			setLandSelected(false);
			setMultiFamilySelected(false);

			setPropertyTypeValue('');
		} else {
			let propValue = value.value;

			let propertTypeCanDo = [ 1, 4, 5, 8, 9, 12, 13 ];
			let propertTypeLand = [6, 7];
			let propertTypeMultiFamily = [2, 3, 10, 11 ,14 , 15];

			if (propertTypeCanDo.includes(propValue)) {
				setConodoSelected(true);
			} else {
				setConodoSelected(false);
			}

			if (propertTypeLand.includes(propValue)) {
				setLandSelected(true);
			} else {
				setLandSelected(false);
			}

			if (propertTypeMultiFamily.includes(propValue)) {
				setMultiFamilySelected(true);
			} else {
				setMultiFamilySelected(false);
			}
			setPropertyTypeValue(value);
		}
	}

    const submitSearchBuyerForm = (e) => {
        e.preventDefault();

		setErrors(null);

        setLoading(true);

        var data = new FormData(e.target);
        let formObject = Object.fromEntries(data.entries());

		if (formObject.hasOwnProperty('property_flaw')) {
            formObject.property_flaw =  locationFlawsValue;
        }
		if (formObject.hasOwnProperty('purchase_method')) {
            formObject.purchase_method =  purchaseMethodsValue;
        }

		formObject.filterType = 'search_page';
		
        axios.post(apiUrl+'buy-box-search', formObject, {headers: headers}).then(response => {
            setLoading(false);
            if(response.data.status){
                localStorage.setItem('filter_buyer_fields', JSON.stringify(formObject));
                // localStorage.setItem('get_filtered_data', JSON.stringify(response.data.buyers));

                // navigate('/my-buyers')
				setIsFiltered(true);
            }
            
        }).catch(error => {
            setLoading(false);
            if(error.response) {
                if (error.response.data.errors) {
                    setErrors(error.response.data.errors);
                }
                if (error.response.data.error) {
                    toast.error(error.response.data.error, {position: toast.POSITION.TOP_RIGHT});
                }
            }
        });
    }



 return (
    <>
     	<Header/>
	 	{ (isLoader)?<div className="loader" style={{textAlign:'center'}}><img src="assets/images/loader.svg"/></div>:
			isFiltered ? 
				<FilterResult  setIsFiltered = {setIsFiltered} /> :
				<section className="main-section position-relative pt-4 pb-120">
				<div className="container position-relative">
					<div className="back-block">
						<Link to="/" className="back">
							<svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M15 6H1" stroke="#0A2540" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
								<path d="M5.9 11L1 6L5.9 1" stroke="#0A2540" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
							</svg>
							Back
						</Link>
					</div>
					<div className="card-box">
						<div className="row">
							<div className="col-12 col-lg-8">
								<div className="card-box-inner">
									<h3>Search Property Details</h3>
									<p>Fill the below form OR send link to the buyer</p>
									<form method='post' onSubmit={submitSearchBuyerForm}>
										<div className="card-box-blocks">
											<div className="row">
												<div className="col-12 col-lg-12">
													<div className="form-group">
														<label>Property Type<span>*</span></label>
														<Select
															name="property_type"
															defaultValue=''
															options={propertyTypeOption}
															onChange={(item) => handlePropertyTypeChange(item)}
															className="select"
															isClearable={true}
															isSearchable={true}
															isDisabled={false}
															isLoading={false}
															value={propertyTypeValue}
															isRtl={false}
															placeholder="Select Property Type"
															closeMenuOnSelect={true}
														/>
														{renderFieldError('property_type') }
													</div>													
												</div>
											</div>

											{ conodoSelected 
												&& 
												<CondoPropertySearch 
													renderFieldError = {renderFieldError}
													setPurchaseMethodsValue = {setPurchaseMethodsValue}
													setLocationFlawsValue = {setLocationFlawsValue}										
												/> 
											}

											{ landSelected 
												&& 
												<DevelopmentPropertySearch 
													renderFieldError = {renderFieldError}
													setPurchaseMethodsValue = {setPurchaseMethodsValue}
													setLocationFlawsValue = {setLocationFlawsValue}										
												/> 
											}

											{ multiFamilySelected 
												&& 
												<MultiFamilyPropertySearch 
													renderFieldError = {renderFieldError}
													setPurchaseMethodsValue = {setPurchaseMethodsValue}
													setLocationFlawsValue = {setLocationFlawsValue}										
												/> 
											}

											<div className="submit-btn">
												<button type="submit" className="btn btn-fill" disabled={ loading ? 'disabled' : ''}>Submit Now! { loading ? <MiniLoader/> : ''} </button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div className="col-12 col-lg-4">
								<UploadMultipleBuyers/>
								<div className="watch-video">
									<p>Don’t Know How to Upload</p>
									<a href="" className="title">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="#121639" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
											<path d="M10 8L16 12L10 16V8Z" stroke="#121639" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
										</svg>
										Watch the Video!
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>			
		}
	<Footer/>
    </>
 )
}
export default SellerForm;
