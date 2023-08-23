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
import ResultPage from "./ResultPage";



const SellerForm = () =>{
	
	const {authData} = useContext(AuthContext);
    const {getTokenData} = useAuth();
    const navigate = useNavigate();
    const [isLoader, setIsLoader] = useState(true);

    const [isVideoLoader, setIsVideoloader] = useState(true);
    const [videoUrl, setVideoUrl] = useState('');

	const [isFiltered,setIsFiltered] = useState(false);

    const { setErrors, renderFieldError } = useForm();

	const [address, setAddress] = useState('');
	const [country, setCountry] = useState('');
    const [state, setState] = useState('');
    const [city, setCity] = useState('');
    const [zipCode, setZipCode] = useState('');

    const [price, setPrice] = useState('');
    const [ofStories, setOfStories] = useState('');


    //const [bedroomMin, setBedroomMin] = useState('');
    // const [bedroomMax, setBedroomMax] = useState('');
    // const [bathMin, setBathMin] = useState('');
    // const [bathMax, setBathMax] = useState('');
    // const [sizeMin, setSizeMin] = useState('');
    // const [sizeMax, setSizeMax] = useState('');
    // const [lotSizeMin, setLotSizeMin] = useState('');
    // const [lotSizeMax, setLotSizeMax] = useState('');
    // const [yearBuildMin, setYearBuildMin] = useState('');
    // const [yearBuildMax, setYearBuildMax] = useState('');
    // const [arvMin, setArvMin] = useState('');
    // const [arvMax, setArvMax] = useState('');

	const [bedroom, setBedroom] = useState('');
    const [bath, setBath] = useState('');
    const [size, setSize] = useState('');
    const [lotSize, setLotSize] = useState('');
    const [yearBuild, setYearBuild] = useState('');
    const [arv, setArv] = useState('');

    const [parking, setParking] = useState([]);
    const [locationFlaw, setLocationFlaw] = useState([]);
    const [purchaseMethod, setPurchaseMethod] = useState([]);	
    const [downPaymentPercentage, setDownPaymentPercentage] = useState('');
    const [downPaymentMoney, setDownPaymentMoney] = useState('');
    const [interestRate, setInterestRate] = useState('');
    const [balloonPayment, setBalloonPayment] = useState(null);
	const [zoning, setZoning] = useState([]);
    const [utilities, setUtilities] = useState([]);
    const [sewer, setSewer] = useState([]);
    const [marketPreferance, setMarketPreferance] = useState([]);
    const [contactPreferance, setContactPreferance] = useState([]);


	const [solar, setSolar] = useState(null);
    const [pool, setPool] = useState(null);
    const [septic, setSeptic] = useState(null);
    const [well, setWell] = useState(null);
    const [ageRestriction, setAgeRestriction] = useState(null);
    const [rentalRestriction, setRentalRestriction] = useState(null);
    const [hoa, setHoa] = useState(null);
    const [tenant, setTenant] = useState(null);
    const [postPossession, setPostPossession] = useState(null);
    const [buildingRequired, setBuildingRequired] = useState(null);
    const [foundationIssues, setFoundationIssues] = useState(null);
    const [mold, setMold] = useState(null);
    const [fireDamaged, setFireDamaged] = useState(null);
    const [rebuild, setRebuild] = useState(null);
    const [squatters, setSquatters] = useState(null);

    const [totalUnits, setTotalUnits] = useState('');
    const [buildingClass, setBuildingClass] = useState('');
    const [valueAdd, setValueAdd] = useState(null);

    const [countryOptions,setCountryOptions] = useState([]);
	const [stateOptions,setStateOptions] = useState([]);
    const [cityOptions,setCityOptions] = useState([]);

    const [purchaseMethodsOption, setPurchaseMethodsOption] = useState([])
    const [parkingOption, setParkingOption] = useState([]);
    const [locationFlawsOption,setLocationFlawsOption] = useState([]);
	const [propertyTypeOption, setPropertyTypeOption] = useState([]);
	const [buildingClassOption, setBuildingClassOption] = useState([]);
	const [marketPreferanceOption, setMarketPreferanceOption] = useState([]);
    const [contactPreferanceOption, setContactPreferanceOption] = useState([]);
    const [zoningOption, setZoningOption] = useState([]);
    const [sewerOption, setSewerOption] = useState([]);
    const [utilitiesOption, setUtilitiesOption] = useState([]);


    const [showCreativeFinancing,setShowCreativeFinancing] = useState(false);
    
    const [conodoSelected,setConodoSelected] = useState(false);
    const [landSelected,setLandSelected] = useState(false);
    const [developmentSelected,setDevelopmentSelected] = useState(false);
    const [multiFamilySelected,setMultiFamilySelected] = useState(false);


    const [parkingValue, setParkingValue] = useState([]);
    const [zoningValue, setZoningValue] = useState([]);
	const [utilitiesValue, setUtilitiesValue] = useState([]);
	const [sewerValue, setSewerValue] = useState([]);

    const [propertyTypeValue, setPropertyTypeValue] = useState([]);
    const [locationFlawsValue,setLocationFlawsValue] = useState([]);
    // const [buyerTypeValue,setBuyerTypeValue] = useState([]);
    const [purchaseMethodsValue, setPurchaseMethodsValue] = useState([]);

    const [loading, setLoading] = useState(false);

	/** states for level 2 users start */
	const [buyerType, setBuyerType] = useState('my_buyers');
	/** states for level 2 users end  */

    useEffect(() => {
		// redirect from payment completed page
		const paramsObject = decodeURI(window.location.search)
		.replace('?', '');
		if(paramsObject === 'search'){
			getLastSearch();
		}
		
        getOptionsValues();
		getVideoUrl();
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
				setPurchaseMethodsOption(result.purchase_methods);
                setLocationFlawsOption(result.location_flaws);
                setParkingOption(result.parking_values);
                setCountryOptions(result.countries);     
                setPropertyTypeOption(result.property_types);
				setBuildingClassOption(result.building_class_values);
				setMarketPreferanceOption(result.market_preferances);
				setContactPreferanceOption(result.contact_preferances);
				setZoningOption(result.zonings);
				setSewerOption(result.sewers);
				setUtilitiesOption(result.utilities);

                setIsLoader(false);
            }
        })
    }

	const getVideoUrl = () => {
        try{
            const apiUrl = process.env.REACT_APP_API_URL;
            let headers = { 
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + getTokenData().access_token,
                'auth-token' : getTokenData().access_token,
            };
            axios.get(apiUrl+'getVideo/upload_buyer_video', { headers: headers }).then(response => {
                //console.log(response.data.videoDetails.video.video_link,'response');
                let videoLink = response.data.videoDetails.video.video_link;
                setVideoUrl(videoLink);
                setIsVideoloader(false)
            })
        }catch(error){
            if(error.response) {
                if (error.response.validation_errors) {
                    setErrors(error.response.data.validation_errors);
                }
                if (error.response.errors) {
                    setErrors(error.response.errors);
                }
                if (error.response.error) {
                    toast.error(error.response.error, {position: toast.POSITION.TOP_RIGHT});
                }
            }
        }
    }
	
	const getStates = (country_id) => {
        if(country_id == null){
            setCountry([]); setState([]); setCity([]);
            setStateOptions([]); setCityOptions([]);
        } else {            
            axios.post(apiUrl+'getStates', { country_id: country_id }, { headers: headers }).then(response => {
                let result = response.data.options;

                setCountry([]); setState([]); setCity([]);                
                setCityOptions([]);
                setCountry(country_id); setStateOptions(result);
            });
        }
    }

    const getCities = (state_id) => {
        if(state_id == null){
            setState([]); setCity([]);

            setCityOptions([]);
        } else { 
            let country_id = {country};
            axios.post(apiUrl+'getCities', { state_id: state_id, country_id: country_id }, { headers: headers }).then(response => {
                let result = response.data.options;

                setState([]); setCity([]);
                
                setState(state_id); setCityOptions(result);
            });
        }
    }

	const makeStateBlank = () => {

		setAddress(''); setCountry(''); setState(''); setCity(''); setZipCode(''); 

		setPrice('');
		setOfStories('');
		//setBedroomMin(''); 		setBedroomMax(''); 
		// setBathMin(''); 		setBathMax(''); 
		// setSizeMin(''); 		setSizeMax('');
		// setLotSizeMin(''); 		setLotSizeMax('');
		// setYearBuildMin(''); 	setYearBuildMax('');
		// setArvMin(''); 			setArvMax('');

		setBedroom(''); 		
		setBath(''); 		 
		setSize(''); 		
		setLotSize(''); 		
		setYearBuild(''); 	
		setArv(''); 	
		setParking([]); setTotalUnits(''); setBuildingClass(''); setValueAdd(null);

		setPurchaseMethod([]); 
		
		setDownPaymentPercentage(''); setDownPaymentMoney(''); setInterestRate(''); setBalloonPayment(null);
		setZoning([]);
		setUtilities([]);
		setSewer([]);
		setMarketPreferance([]);
		setContactPreferance([]);

		setLocationFlaw([]);

		setSolar(null); setPool(null); setBalloonPayment(null); setWell(null); setAgeRestriction(null);
		setRentalRestriction(null); setHoa(null); setTenant(null); setPostPossession(null); setBuildingRequired(null);
		setFoundationIssues(null); setMold(null); setFireDamaged(null); setRebuild(null); setSquatters(null);

		setLocationFlawsValue([]);

		setPurchaseMethodsValue([]);

		setCityOptions([]);
		setStateOptions([]);

	}

	const handlePropertyTypeChange = (value) => {
		makeStateBlank();
		setErrors(null);
		if(value == null){
			setConodoSelected(false);
			setLandSelected(false);
			setDevelopmentSelected(false);
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
				if(propValue === 7){
					setLandSelected(true);
				}else{
					setLandSelected(false);
				}
				setDevelopmentSelected(true);
			} else {
				setDevelopmentSelected(false)
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
            formObject.property_flaw =  locationFlaw;
        }
		if (formObject.hasOwnProperty('purchase_method')) {
            formObject.purchase_method =  purchaseMethod;
        }
		if (formObject.hasOwnProperty('zoning')) {
            formObject.zoning =  zoning;
        }

		formObject.filterType = 'search_page';
		formObject.activeTab  = 'my_buyers';
		console.log(formObject,'formObject');
		buyBoxSearch(formObject)
    }


	const dataObj = {
		address, setAddress,
		country, setCountry,
		state, setState,
		city,	setCity,
		zipCode, setZipCode,

		price, setPrice,
		ofStories, setOfStories,

		//bedroomMin, setBedroomMin,
		// bedroomMax, setBedroomMax,
		// bathMin, setBathMin,
		// bathMax, setBathMax,
		// sizeMin, setSizeMin,
		// sizeMax, setSizeMax,
		// lotSizeMin, setLotSizeMin,
		// lotSizeMax, setLotSizeMax,
		// yearBuildMin, setYearBuildMin,
		// yearBuildMax, setYearBuildMax,
		// arvMin, setArvMin,
		// arvMax, setArvMax,
		bedroom, setBedroom,
		bath, setBath,
	    size, setSize,
		lotSize, setLotSize,
		yearBuild, setYearBuild,
		parking,  setParking,
		totalUnits,  setTotalUnits,
		buildingClass, setBuildingClass,
		valueAdd, setValueAdd,
		arv, setArv,
		purchaseMethod, setPurchaseMethod,

		downPaymentPercentage, setDownPaymentPercentage,
		downPaymentMoney, setDownPaymentMoney,
		interestRate, setInterestRate,
		balloonPayment, setBalloonPayment,
		zoning, setZoning,
		utilities, setUtilities,
		sewer, setSewer,
		marketPreferance, setMarketPreferance,
		contactPreferance,setContactPreferance,
		locationFlaw, setLocationFlaw,

		solar, setSolar,
		pool, setPool,
		septic, setSeptic,
		well, setWell,
		ageRestriction, setAgeRestriction,
		rentalRestriction, setRentalRestriction,
		hoa, setHoa,
		tenant, setTenant,
		postPossession, setPostPossession,
		buildingRequired, setBuildingRequired,
		foundationIssues, setFoundationIssues,
		mold, setMold,
		fireDamaged, setFireDamaged,
		rebuild, setRebuild,
		squatters,  setSquatters,

		renderFieldError,

		countryOptions,
		stateOptions,
		cityOptions,

		getStates,
		getCities,

		locationFlawsOption,
		purchaseMethodsOption,
		parkingOption,
		buildingClassOption,
		marketPreferanceOption,
		contactPreferanceOption,
		zoningOption,
		sewerOption,
		utilitiesOption,

		showCreativeFinancing,
		setShowCreativeFinancing,

		locationFlawsValue,
		setLocationFlawsValue,

		purchaseMethodsValue,
		setPurchaseMethodsValue,

		zoningValue,
		setZoningValue,

		utilitiesValue,
		setUtilitiesValue,

		sewerValue,
		setSewerValue,
	}
	const user_data = JSON.parse(localStorage.getItem('user_data'));
	//console.log(user_data,'user_data');
	function buyBoxSearch(formObject){
		axios.post(apiUrl+'buy-box-search', formObject, {headers: headers}).then(response => {
            setLoading(false);
            if(response.data.status){
                localStorage.setItem('filter_buyer_fields', JSON.stringify(formObject));
                localStorage.setItem('get_filtered_data', JSON.stringify(response.data));
                // navigate('/my-buyers')
				 //window.history.pushState(null, "", "/my-buyers");
				//window.history.pushState(null, "", "/result-page");
				// setIsLoader(true);
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
	const getLastSearch = () =>{
		setIsLoader(true);
		axios.get(apiUrl+'get-last-search', { headers: headers }).then(response => {
			if(response.data.status){
				let result = response.data.data.searchLog;
				console.log(result);
				buyBoxSearch(result)
				setIsLoader(false);
			}
		})
	}
 return (
    <>
     	<Header/>
	 	{ (isLoader)?<div className="loader" style={{textAlign:'center'}}><img src="assets/images/loader.svg"/></div>:
			isFiltered ? 
				<ResultPage setIsFiltered = {setIsFiltered}  /> 
			:
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
												<CondoPropertySearch data = {dataObj} /> 
											}

											{ developmentSelected 
												&& 
												<DevelopmentPropertySearch data = {dataObj} landSelected={landSelected} /> 
											}

											{ multiFamilySelected 
												&& 
												<MultiFamilyPropertySearch data = {dataObj} /> 
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
									<a href="" data-bs-toggle="modal" data-bs-target="#exampleModal" className="title">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="#121639" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
											<path d="M10 8L16 12L10 16V8Z" stroke="#121639" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
										</svg>
										Watch The Video!
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				{/* modal box for video */}
                <div className="modal fade" id="exampleModal" tabIndex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                </div>
			</section>			
		}
	<Footer/>
    </>
 )
}
export default SellerForm;
