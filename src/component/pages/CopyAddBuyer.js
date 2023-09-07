import React, {useEffect, useState} from "react";
import {useNavigate, useParams} from "react-router-dom";
import MultiSelect from "../partials/Select2/MultiSelect";
import Select from "react-select";
import { useFormError } from '../../hooks/useFormError';
import {useForm, Controller  } from "react-hook-form";
import axios from 'axios';
import MiniLoader from "../partials/MiniLoader";
import DatePicker from "react-datepicker";
import "react-datepicker/dist/react-datepicker.css";


import { toast } from "react-toastify";

function CopyAddBuyer (){

    const { token } = useParams();

    const navigate = useNavigate();
    const [isLoader, setIsLoader] = useState(true);
    const [firstName, setFirstName] = useState('');
    const [lastName, setLastName] = useState('');
    const [startDate, setStartDate] = useState('');
    const [endDate, setEndDate] = useState('');

    const { setErrors, renderFieldError } = useFormError();
    const { register, handleSubmit, control , formState: { errors }  } = useForm();


    const [country, setCountry] = useState([]);
    const [state, setState] = useState([]);
    const [city, setCity] = useState([]);

    const [purchaseMethodsOption, setPurchaseMethodsOption] = useState([])
    const [buildingClassNamesOption, setBuildingClassNamesOption] = useState([])
    const [propertyTypeOption, setPropertyTypeOption] = useState([]);
    const [parkingOption, setParkingOption] = useState([]);
    const [locationFlawsOption,setLocationFlawsOption] = useState([]);
    const [buyerTypeOption,setbuyerTypeOption] = useState([]);

    const [countryOptions,setCountryOptions] = useState([]);
    const [stateOptions,setStateOptions] = useState([]);
    const [cityOptions,setCityOptions] = useState([]);
    
    const [showCreativeFinancing,setShowCreativeFinancing] = useState(false);
    const [multiFamilyBuyerSelected,setMultiFamilyBuyerSelected] = useState(false);
    const [isLandSelected,setIsLandSelected] = useState(false);
    const [marketPreferanceOption, setMarketPreferanceOption] = useState([]);
    const [contactPreferanceOption, setContactPreferanceOption] = useState([]);
    const [zoningOption, setZoningOption] = useState([]);
    const [sewerOption, setSewerOption] = useState([]);
    const [utilitiesOption, setUtilitiesOption] = useState([]);

    const [parkingValue, setParkingValue] = useState([]);
    const [propertyTypeValue, setPropertyTypeValue] = useState([]);
    const [locationFlawsValue,setLocationFlawsValue] = useState([]);
    const [buyerTypeValue,setBuyerTypeValue] = useState([]);
    const [purchaseMethodsValue, setPurchaseMethodsValue] = useState([]);
    const [buildingClassNamesValue, setBuildingClassNamesValue] = useState([]);
    const [marketPreferanceValue, setMarketPreferanceValue] = useState([]);
    const [contactPreferanceValue, setContactPreferanceValue] = useState([]);
    const [zoningValue, setZoningValue] = useState([]);
    const [stateValue,setStatevalue] = useState([]);
    const [cityValue,setCityvalue] = useState([]);


    const [isTokenExpire, setIsTokenExpire] = useState(false);


    const [loading, setLoading] = useState(false);

    useEffect(() => {
        checkTokenExpire();        
    }, [navigate]);
    
    
    const apiUrl = process.env.REACT_APP_API_URL;
    
    let headers = { 
        'Accept': 'application/json',
    };

    const checkTokenExpire = () =>{
        try{
            axios.get(`${apiUrl}check-token/${token}`, { headers: headers }).then(response => {
                if(response.data.status){
                        setIsTokenExpire(false);
                        getOptionsValues();

                        setIsLoader(false);
                }
            }) .catch(error => {
                if(error.response) {
                    if (error.response.data.error) {
                        // toast.error(error.response.data.error, {position: toast.POSITION.TOP_RIGHT});
                        setIsTokenExpire(true);

                        setIsLoader(false);
                    }
                }
            });
        }catch{
            // setLogout();
            // navigate('/login');
        }
    }

    const getOptionsValues = () =>{
        try{
            axios.get(apiUrl+'single-buyer-form-details', { headers: headers }).then(response => {
                if(response.data.status){
                    let result = response.data.result;
    
                    setPurchaseMethodsOption(result.purchase_methods);
                    setBuildingClassNamesOption(result.building_class_values);
                    setPropertyTypeOption(result.property_types);
                    setLocationFlawsOption(result.location_flaws);
                    setParkingOption(result.parking_values);
                    //setCountryOptions(result.countries);
                    setStateOptions(result.states);
                    setbuyerTypeOption(result.buyer_types);
                    setMarketPreferanceOption(result.market_preferances);
                    setContactPreferanceOption(result.contact_preferances);
                    setZoningOption(result.zonings);
                    setSewerOption(result.sewers);
                    setUtilitiesOption(result.utilities);

                    setIsLoader(false);
    
                }
            })
        }catch{
            // setLogout();
            // navigate('/login');
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
                
                setCountry(country_id); setStateOptions(result);
            });
        }
    }

    const getCities = async (state_id) => {
        try{
            if(state_id == null){
                setState([]); setCity([]);
                setCityOptions([]);
            }else { 
                const selectedStates = state_id.map((item) => item.value);
                setStatevalue(selectedStates);
                let country_id = {country};
                let response = await axios.post(apiUrl+'getCities', { state_id: selectedStates, country_id: 233 }, { headers: headers });
                if(response){
                    let result = response.data.options;
                    //setState([]); 
                    setCity([]);
                    //setState(state_id); 
                    setCityOptions(result);
                }
            }
        }catch(error){

        }
    }

    const submitSingleBuyerForm = (data,e) => {
        e.preventDefault();

        setErrors(null);

        setLoading(true);

        var data = new FormData(e.target);
        let formObject = Object.fromEntries(data.entries());

        //formObject.parking          =  parkingValue;        
        formObject.property_type    =  propertyTypeValue;
        formObject.property_flaw    =  locationFlawsValue;
        // formObject.buyer_type       =  buyerTypeValue;
        formObject.purchase_method  =  purchaseMethodsValue;
        //console.log(formObject.hasOwnProperty('building_class'),'check');
        if (formObject.hasOwnProperty('building_class')) {
            formObject.building_class =  buildingClassNamesValue;
        }
        if (formObject.hasOwnProperty('state')) {
            formObject.states =  stateValue;
        }
        if (formObject.hasOwnProperty('city')) {
            formObject.city =  cityValue;
        }
        
        axios.post(`${apiUrl}store-single-buyer-details/${token}`, formObject, {headers: headers}).then(response => {
            setLoading(false);
            if(response.data.status){
                toast.success(response.data.message, {position: toast.POSITION.TOP_RIGHT});
                
                navigate('/add-buyer/'+token);

                setIsTokenExpire(true);
            }
            
        }).catch(error => {
            setLoading(false);
            if(error.response) {
                if (error.response.data.validation_errors) {
                    setErrors(error.response.data.validation_errors);
                }
                if (error.response.data.error) {
                    toast.error(error.response.data.error, {position: toast.POSITION.TOP_RIGHT});
                    if(error.response.data.error_type == 'token_expired'){
                        setIsTokenExpire(true);
                    }
                }
            }
        });
    }
    const handleChangeFirstName = (e) => {
        const regex = /^[a-zA-Z]+$/;
        const new_value = e.target.value.replace(/[^a-zA-Z]/g, "");
        if (regex.test(new_value)) {
            setFirstName(new_value);
        }
        if(new_value ==''){
            setFirstName('');
        }
    }
    const handleChangeLastName = (e) => {
        const regex = /^[a-zA-Z]+$/;
        const new_value = e.target.value.replace(/[^a-zA-Z]/g, "");
        if (regex.test(new_value)) {
            setLastName(new_value);
        }
        if(new_value ==''){
            setLastName('');
        }
    }
    const handleCustum = (e,name) => {
        const selectedValues = Array.isArray(e) ? e.map(x => x.value) : [];
        if(name == 'property_type'){
          if (selectedValues.includes(2) || selectedValues.includes(10) || selectedValues.includes(11) || selectedValues.includes(14) || selectedValues.includes(15)) {
            setMultiFamilyBuyerSelected(true);
          } else {
            setMultiFamilyBuyerSelected(false);
          }
          if(selectedValues.includes(7)){
            setIsLandSelected(true);
          }else {
            setIsLandSelected(false);
          }
          setPropertyTypeValue(selectedValues);
        }else if(name == 'purchase_method'){
            if (selectedValues.includes(5)) {
                setShowCreativeFinancing(true);
            } else {
                setShowCreativeFinancing(false);
            }
            setPurchaseMethodsValue(selectedValues);
        }else if(name == 'parking'){
            setParkingValue(e);
        }else if(name == 'country'){
            getStates(e)
        }else if(name == 'state'){
            setState(e);
            getCities(e);
        }else if(name == 'city'){
            //setCity(e);
        }
        else if(name == 'building_class'){
            setBuildingClassNamesValue(selectedValues);
        }else if(name == 'parking'){
            setParkingValue(e);
        }else if(name == 'buyer_type'){
            setBuyerTypeValue(e);
        }else if(name == 'start_date'){
            setStartDate(e);
        }
        else if(name == 'end_date'){
            setEndDate(e);
        }else if(name == 'market_preferance'){
            setMarketPreferanceValue(e);
        }
        else if(name == 'zoning'){
            setZoningValue(selectedValues);
        }
    }
    const handleCityChange = (event) => {
        let selectedValues = event.map((item) => item.value);
        console.log("enter");
        setCityvalue(selectedValues);
    }
    return (
        <>
           { (isLoader)?<div className="loader" style={{textAlign:'center'}}><img src="/assets/images/loader.svg"/></div> :
                isTokenExpire ? 
                    <div className="row">
                        <div className="col-md-12 pt-4 text-center">
                            <h3>Link is expired</h3>
                        </div>
                    </div> :
                    <section className="main-section position-relative pt-4 pb-120">
                            <div className="container position-relative">
                                <div className="card-box">
                                    <div className="row">
                                        <div className="col-12 col-lg-12">
                                            <div className="card-box-inner">
                                                <div className="row">
                                                    <div className="col-12 col-sm-7 col-md-6 col-lg-6">
                                                        <h3>Upload Single Buyer Detail</h3>
                                                        <p>Fill the below form </p>
                                                    </div>
                                                </div>

                                                <form method='post' onSubmit={handleSubmit(submitSingleBuyerForm)}>
                                                    <div className="card-box-blocks">
                                                        <div className="row">
                                                            <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                                <label>First Name<span>*</span></label>
                                                                <div className="form-group">
                                                                    <input type="text" name="first_name" className="form-control" placeholder="First Name"
                                                                    {...register("first_name", { required: 'First Name is required' , validate: {
                                                                        maxLength: (v) =>
                                                                        v.length <= 50 || "The First Name should have at most 50 characters",
                                                                        matchPattern: (v) =>
                                                                        /^[a-zA-Z\s]+$/.test(v) ||
                                                                        "First Name can not include number or special character",
                                                                    } })}/>

                                                                    {errors.first_name && <p className="error">{errors.first_name?.message}</p>}
                                                                    {renderFieldError('first_name') }
                                                                </div>
                                                            </div>
                                                            <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                                <label>Last Name<span>*</span></label>
                                                                <div className="form-group">
                                                                    <input type="text" name="last_name" className="form-control" placeholder="Last Name"
                                                                    {...register("last_name", { required: 'Last Name is required' , validate: {
                                                                        maxLength: (v) =>
                                                                        v.length <= 50 || "The Last Name should have at most 50 characters",
                                                                        matchPattern: (v) =>
                                                                        /^[a-zA-Z\s]+$/.test(v) ||
                                                                        "Last Name can not include number or special character",
                                                                    } })}/>

                                                                    {errors.last_name && <p className="error">{errors.last_name?.message}</p>}
                                                                    {renderFieldError('last_name') }
                                                                </div>
                                                            </div>
                                                            <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                                <label>Email Address<span>*</span></label>
                                                                <div className="form-group">
                                                                    <input type="text" name="email" className="form-control" placeholder="Email Address" {
                                                                    ...register("email", {
                                                                        required: "Email is required",
                                                                        validate: {
                                                                            maxLength: (v) =>
                                                                            v.length <= 50 || "The email should have at most 50 characters",
                                                                            matchPattern: (v) =>
                                                                            /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
                                                                            "Email address must be a valid address",
                                                                        },
                                                                    })
                                                                } />
                                                                {errors.email && <p className="error">{errors.email?.message}</p>}
                                                                    {renderFieldError('email') }
                                                                </div>
                                                            </div>
                                                            <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                                <label>Phone Number<span>*</span></label>
                                                                <div className="form-group">
                                                                    <input type="text" name="phone" className="form-control" placeholder="Phone Number" {
                                                                    ...register("phone", {
                                                                        required: "Phone is required",
                                                                        validate: {
                                                                            matchPattern: (v) =>
                                                                            /^[0-9]\d*$/.test(v) ||
                                                                            "Please enter valid phone number",
                                                                            maxLength: (v) =>
                                                                            v.length <= 15 && v.length >= 5 || "The phone number should be more than 4 digit and less than equal 15",
                                                                        },
                                                                    })
                                                                } />
                                                                    {errors.phone && <p className="error">{errors.phone?.message}</p>}
                                                                    {renderFieldError('phone') }
                                                                </div>
                                                            </div>
                                                            <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                                <label>Company/LLC</label>
                                                                <div className="form-group">
                                                                    <input type="text" className="form-control" name="company_name" placeholder="Company LLC" />
                                                                    {renderFieldError('company_name') }
                                                                </div>
                                                            </div>
                                                            <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                                <label>Market Preference<span>*</span></label>
                                                                <div className="form-group">
                                                                    <Controller
                                                                        control={control}
                                                                        name="market_preferance"
                                                                        rules={{ required: 'Market Preferance is required' }}
                                                                        render={({ field: { value, onChange, name } }) => (
                                                                        <Select
                                                                            options={marketPreferanceOption}
                                                                            name = {name}
                                                                            placeholder='Select Market Preferance'
                                                                            isClearable={true}
                                                                            onChange={(e)=>{
                                                                                onChange(e)
                                                                                handleCustum(e,'market_preferance')
                                                                            }}
                                                                        />
                                                                        )}
                                                                    />
                                                                    {errors.market_preferance && <p className="error">{errors.market_preferance?.message}</p>}
                                                                    {renderFieldError('market_preferance') }
                                                                </div>
                                                            </div>
                                                            <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                                <label>Contact Preference<span>*</span></label>
                                                                <div className="form-group">
                                                                <Controller
                                                                    control={control}
                                                                    name="contact_preferance"
                                                                    rules={{ required: 'Contact Preferance is required' }}
                                                                    render={({ field: { value, onChange, name } }) => (
                                                                    <Select
                                                                        options={contactPreferanceOption}
                                                                        name = {name}
                                                                        placeholder='Select Contact Preferance'
                                                                        isClearable={true}
                                                                        onChange={(e)=>{
                                                                            onChange(e)
                                                                            handleCustum(e,'contact_preferance')
                                                                        }}
                                                                    />
                                                                    )}
                                                                />
                                                                {errors.contact_preferance && <p className="error">{errors.contact_preferance?.message}</p>}
                                                                {renderFieldError('contact_preferance') }
                                                                </div>
                                                            </div>
                                                            {/* <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                                <label>Address<span>*</span></label>
                                                                <div className="form-group">
                                                                    <input type="text" name="address" className="form-control" placeholder="Enter Address" {
                                                                    ...register("address", {
                                                                        required: "Address is required",
                                                                    })
                                                                    } />
                                                                    {errors.address && <p className="error">{errors.address?.message}</p>}
                                                                    {renderFieldError('address') }
                                                                </div>
                                                            </div> */}
                                                            <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                                <label>Country<span>*</span></label>
                                                                <div className="form-group">
                                                                <input type="text" className="form-control country-field" value="United States" readOnly/>
                                                                </div>
                                                            </div>
                                                            <div className="col-12 col-lg-12">
                                                                <label>State</label>
                                                                <div className="form-group">
                                                                <Select
                                                                    name="state"
                                                                    defaultValue=''
                                                                    options={stateOptions}
                                                                    onChange={(item) => getCities(item)}
                                                                    className="select"
                                                                    isClearable={true}
                                                                    isSearchable={true}
                                                                    placeholder="Select State"
                                                                    closeMenuOnSelect={false}
                                                                    isMulti
                                                                />
                                                                    {renderFieldError('state') }
                                                                    {/* <Select
                                                                        name="state"
                                                                        defaultValue=''
                                                                        options={stateOptions}
                                                                        onChange={(item) => getCities(item)}
                                                                        className="select"
                                                                        isClearable={true}
                                                                        isSearchable={true}
                                                                        isDisabled={false}
                                                                        isLoading={false}
                                                                        value={state}
                                                                        isRtl={false}
                                                                        placeholder="Select State"
                                                                        closeMenuOnSelect={true}
                                                                    /> */}
                                                                    {/* <Controller
                                                                        control={control}
                                                                        name="state"
                                                                        rules={{ required: 'State is required' }}
                                                                        render={({ field: { value, onChange, name } }) => (
                                                                        <Select
                                                                            options={stateOptions}
                                                                            name = {name}
                                                                            value={state}
                                                                            isClearable={true}
                                                                            className="select"
                                                                            placeholder='Select State'
                                                                            onChange={(e)=>{
                                                                                onChange(e)
                                                                                handleCustum(e,'state')
                                                                            }}
                                                                        />
                                                                        )}
                                                                    />
                                                                    {errors.state && <p className="error">{errors.state?.message}</p>}
                                                                    {renderFieldError('state') } */}
                                                                </div>
                                                            </div>
                                                            <div className="col-12 col-lg-12">
                                                                <label>City</label>
                                                                <div className="form-group">
                                                                <Select
                                                                    name="city"
                                                                    defaultValue=''
                                                                    options={cityOptions}
                                                                    className="select"
                                                                    isClearable={true}
                                                                    isSearchable={true}
                                                                    isDisabled={false}
                                                                    isLoading={false}
                                                                    onChange={handleCityChange}
                                                                    isRtl={false}
                                                                    placeholder="Select City"
                                                                    closeMenuOnSelect={false}
                                                                    isMulti
                                                                />
                                                                    {renderFieldError('city') }
                                                                    {/* <Select
                                                                        name="city"
                                                                        defaultValue=''
                                                                        options={cityOptions}
                                                                        onChange={(item) => setCity(item)}
                                                                        className="select"
                                                                        isClearable={true}
                                                                        isSearchable={true}
                                                                        isDisabled={false}
                                                                        isLoading={false}
                                                                        value={city}
                                                                        isRtl={false}
                                                                        placeholder="Select City"
                                                                        closeMenuOnSelect={true}
                                                                    /> */}
                                                                    {/* <Controller
                                                                        control={control}
                                                                        name="city"
                                                                        rules={{ required: 'City is required' }}
                                                                        render={({ field: { value, onChange, name } }) => (
                                                                        <Select
                                                                            options={cityOptions}
                                                                            name = {name}
                                                                            value={city}
                                                                            isClearable={true}
                                                                            className="select"
                                                                            placeholder='Select City'
                                                                            onChange={(e)=>{
                                                                                onChange(e)
                                                                                handleCustum(e,'city')
                                                                            }}
                                                                        />
                                                                        )}
                                                                    />
                                                                    {errors.city && <p className="error">{errors.city?.message}</p>} */}

                                                                    {renderFieldError('city') }
                                                                </div>
                                                            </div>
                                                            {/* <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                                <label>Zip<span>*</span></label>
                                                                <div className="form-group">
                                                                    <input type="text" name="zip_code" className="form-control" placeholder="Zip Code" {
                                                                        ...register("zip_code", {
                                                                            required: "Zip Code is required",
                                                                            validate: {
                                                                                maxLength: (v) =>
                                                                                v.length <= 10 || "The digit should be less than equal 10",
                                                                            },
                                                                        })
                                                                    } />
                                                                    {errors.address && <p className="error">{errors.zip_code?.message}</p>}
                                                                    {renderFieldError('zip_code') }
                                                                </div>
                                                            </div> */}
                                                            
                                                            <div className="col-12 col-lg-12">
                                                                <div className="form-group">
                                                                    <label>Property Type<span>*</span></label>
                                                                    <div className="form-group">
                                                                        <Controller
                                                                        control={control}
                                                                        name="property_type"
                                                                        rules={{ required: 'Property Type is required' }}
                                                                        render={({ field: { value, onChange, name } }) => (
                                                                        <Select
                                                                            options={propertyTypeOption}
                                                                            name = {name}
                                                                            placeholder='Select Property Type'
                                                                            setMultiselectOption = {setPropertyTypeValue}
                                                                            showCreative={setMultiFamilyBuyerSelected}
                                                                            onChange={(e)=>{
                                                                                onChange(e)
                                                                                handleCustum(e,'property_type')
                                                                            }}
                                                                            isMulti
                                                                            closeMenuOnSelect={false}
                                                                            />
                                                                        )}
                                                                        />
                                                                        {errors.property_type && <p className="error">{errors.property_type?.message}</p>}
                                                                        
                                                                        {renderFieldError('property_type') }
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {isLandSelected && 
                                                                <div className="block-divide">
                                                                    <div className="row">
                                                                        <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-4">
                                                                            <label>Zoning</label>
                                                                            <div className="form-group">
                                                                            <Controller
                                                                            control={control}
                                                                            name="zoning"
                                                                            rules={{ required: 'Zoning Type is required' }}
                                                                            render={({ field: { value, onChange, name } }) => (
                                                                            <Select
                                                                                options={zoningOption}
                                                                                name = {name}
                                                                                placeholder='Select Zoning Type'
                                                                                onChange={(e)=>{
                                                                                    onChange(e)
                                                                                    handleCustum(e,'zoning')
                                                                                }}
                                                                                isMulti
                                                                                closeMenuOnSelect={false}
                                                                            />
                                                                            )}
                                                                            />
                                                                                {renderFieldError('zoning') }
                                                                            </div>
                                                                        </div>
                                                                        <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-4">
                                                                            <label>Utilities</label>
                                                                            <div className="form-group">

                                                                            <Controller
                                                                                control={control}
                                                                                name="utilities"
                                                                                rules={{ required: 'Utilities Type is required' }}
                                                                                    render={({ field: { value, onChange, name } }) => (
                                                                                        <Select
                                                                                            options={utilitiesOption}
                                                                                            name = {name}
                                                                                            placeholder='Select Utilities Type'
                                                                                            onChange={(e)=>{
                                                                                                onChange(e)
                                                                                                handleCustum(e,'utilities')
                                                                                            }}
                                                                                            closeMenuOnSelect={true}
                                                                                        />
                                                                                    )}
                                                                            />
                                                                                {renderFieldError('utilities') }
                                                                            </div>
                                                                        </div>
                                                                        <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-4">
                                                                            <label>Sewer</label>
                                                                            <div className="form-group">
                                                                                <Controller
                                                                                control={control}
                                                                                name="sewer"
                                                                                rules={{ required: 'Sewer Type is required' }}
                                                                                    render={({ field: { value, onChange, name } }) => (
                                                                                        <Select
                                                                                            options={sewerOption}
                                                                                            name = {name}
                                                                                            placeholder='Select Sewer Type'
                                                                                            onChange={(e)=>{
                                                                                                onChange(e)
                                                                                                handleCustum(e,'sewer')
                                                                                            }}
                                                                                            closeMenuOnSelect={true}
                                                                                        />
                                                                                    )}
                                                                                />
                                                                                {renderFieldError('sewer') }
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            }
                                                            { multiFamilyBuyerSelected && 
                                                                <div className="block-divide">
                                                                    {/* <h5>Multi Family Buyer</h5> */}
                                                                    <div className="row">
                                                                        <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                                            <label>Minimum Units<span>*</span></label>
                                                                            <div className="form-group">
                                                                                <input type="text" name="unit_min" className="form-control" placeholder="Minimum Units" {
                                                                                ...register("unit_min", {
                                                                                    required: "Minimum Units is required",
                                                                                    validate: {
                                                                                        matchPattern: (v) =>
                                                                                        /^[0-9]\d*$/.test(v) ||
                                                                                        "Please enter valid number",
                                                                                        maxLength: (v) =>
                                                                                        v.length <= 10 || "The digit should be less than equal 10",
                                                                                    },
                                                                                })
                                                                            } />
                                                                                {errors.unit_min && <p className="error">{errors.unit_min?.message}</p>}

                                                                                {renderFieldError('unit_min') }
                                                                            </div>
                                                                        </div>
                                                                        <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                                            <label>Maximum Units<span>*</span></label>
                                                                            <div className="form-group">
                                                                                <input type="text" name="unit_max" className="form-control" placeholder="Maximum Units"  {
                                                                                ...register("unit_max", {
                                                                                    required: "Maximum Units is required",
                                                                                    validate: {
                                                                                        matchPattern: (v) =>
                                                                                        /^[0-9]\d*$/.test(v) ||
                                                                                        "Please enter valid number",
                                                                                        maxLength: (v) =>
                                                                                        v.length <= 10 || "The digit should be less than equal 10",
                                                                                    },
                                                                                })
                                                                                } />
                                                                                    {errors.unit_max && <p className="error">{errors.unit_max?.message}</p>}
                                                                                {renderFieldError('unit_max') }
                                                                            </div>
                                                                        </div>
                                                                        <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                                            <label>Building class<span>*</span></label>
                                                                            <div className="form-group">
                                                                                {/* <MultiSelect
                                                                                    name="building_class"
                                                                                    options={buildingClassNamesOption}
                                                                                    placeholder='Select Option'
                                                                                    setMultiselectOption = {setBuildingClassNamesValue}
                                                                                /> */}
                                                                                <Controller
                                                                                    control={control}
                                                                                    name="building_class"
                                                                                    rules={{ required: 'Building class is required' }}
                                                                                    render={({ field: { value, onChange, name } }) => (
                                                                                    <Select
                                                                                        options={buildingClassNamesOption}
                                                                                        name = {name}
                                                                                        placeholder='Select Building class'
                                                                                        onChange={(e)=>{
                                                                                            onChange(e)
                                                                                            handleCustum(e,'building_class')
                                                                                        }}
                                                                                        closeMenuOnSelect={false}
                                                                                        isMulti
                                                                                        />
                                                                                    )}
                                                                                />
                                                                                {errors.building_class && <p className="error">{errors.building_class?.message}</p>}
                                                                                {renderFieldError('building_class') }
                                                                            </div>
                                                                        </div>
                                                                        <div className="col-12 col-md-12 col-lg-3">
                                                                            <label>Value Add</label>
                                                                            <div className="form-group">
                                                                                <div className="radio-block">
                                                                                    <div className="label-container">
                                                                                        <input type="radio" name="value_add" value="0" id="value_add_yes"/>
                                                                                        <label className="mb-0" htmlFor="value_add_yes">Yes</label>
                                                                                    </div>
                                                                                    <div className="label-container">
                                                                                        <input type="radio" name="value_add" value="1" id="value_add_no"/>
                                                                                        <label className="mb-0" htmlFor="value_add_no">No</label>
                                                                                    </div>
                                                                                </div>
                                                                                {renderFieldError('value_add') }
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            }
                                                            <div className="col-12 col-lg-12">
                                                                <label>Purchase Method<span>*</span></label>
                                                                <div className="form-group">
                                                                    {/* <MultiSelect
                                                                        name="purchase_method"
                                                                        options={purchaseMethodsOption}
                                                                        placeholder='Select Purchase Method'
                                                                        setMultiselectOption = {setPurchaseMethodsValue}
                                                                        showCreative = {setShowCreativeFinancing}
                                                                    /> */}

                                                                    <Controller
                                                                        control={control}
                                                                        name="purchase_method"
                                                                        rules={{ required: 'Purchase Method is required' }}
                                                                        render={({ field: { value, onChange, name } }) => (
                                                                        <Select
                                                                            options={purchaseMethodsOption}
                                                                            name = {name}
                                                                            placeholder='Select Purchase Method'
                                                                            setMultiselectOption = {setPurchaseMethodsValue}
                                                                            showCreative={setShowCreativeFinancing}
                                                                            onChange={(e)=>{
                                                                                onChange(e)
                                                                                handleCustum(e,'purchase_method')
                                                                            }}
                                                                            closeMenuOnSelect={false}
                                                                            isMulti
                                                                            />
                                                                        )}
                                                                        />
                                                                        {errors.purchase_method && <p className="error">{errors.purchase_method?.message}</p>}

                                                                    {renderFieldError('purchase_method') }
                                                                </div>
                                                            </div>

                                                            { showCreativeFinancing && 
                                                                <div className="block-divide">
                                                                    <h5>Creative Financing</h5>
                                                                    <div className="row">
                                                                        <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                                            <label>Down Payment (%)</label>
                                                                            <div className="form-group">
                                                                                <input type="number" name="max_down_payment_percentage" className="form-control" placeholder="Down Payment (%)" />
                                                                                {renderFieldError('max_down_payment_percentage') }
                                                                            </div>
                                                                        </div>
                                                                        <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                                            <label>Down Payment ($)</label>
                                                                            <div className="form-group">
                                                                                <input type="number" name="max_down_payment_money" className="form-control" placeholder="Down Payment ($)" />
                                                                                {renderFieldError('max_down_payment_money') }
                                                                            </div>
                                                                        </div>
                                                                        <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                                            <label>Interest Rate (%)</label>
                                                                            <div className="form-group">
                                                                                <input type="number" name="max_interest_rate" className="form-control" placeholder="Interest Rate (%)"  />
                                                                                {renderFieldError('max_interest_rate') }
                                                                            </div>
                                                                        </div>
                                                                        <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                                            <label>Balloon Payment </label>
                                                                            <div className="form-group">
                                                                                <div className="radio-block">
                                                                                    <div className="label-container">
                                                                                        <input type="radio" name="balloon_payment" value="1" id="balloon_payment_yes"/>
                                                                                        <label className="mb-0" htmlFor="balloon_payment_yes">Yes</label>
                                                                                    </div>
                                                                                    <div className="label-container">
                                                                                        <input type="radio" name="balloon_payment" value="0" id="balloon_payment_no"/>
                                                                                        <label className="mb-0" htmlFor="balloon_payment_no">No</label>
                                                                                    </div>
                                                                                </div>
                                                                                {renderFieldError('balloon_payment') }
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            }

                                                            <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                                <label>Bedroom (min)<span>*</span></label>
                                                                <div className="form-group">
                                                                    <input type="text" name="bedroom_min" className="form-control" placeholder="Bedroom (min)"  {
                                                                        ...register("bedroom_min", {
                                                                            required: "Bedroom (min) is required",
                                                                            validate: {
                                                                                matchPattern: (v) =>
                                                                                /^[0-9]\d*$/.test(v) ||
                                                                                "Please enter valid number",
                                                                                maxLength: (v) =>
                                                                                v.length <= 10 || "The digit should be less than equal 10",
                                                                            },
                                                                        })
                                                                        } />
                                                                        {errors.bedroom_min && <p className="error">{errors.bedroom_min?.message}</p>}

                                                                    {renderFieldError('bedroom_min') }
                                                                </div>
                                                            </div>
                                                            <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                                <label>Bedroom (max)<span>*</span></label>
                                                                <div className="form-group">
                                                                    <input type="text" name="bedroom_max" className="form-control" placeholder="Bedroom (max)" 
                                                                    {
                                                                    ...register("bedroom_max", {
                                                                        required: "Bedroom (max) is required",
                                                                        validate: {
                                                                            matchPattern: (v) =>
                                                                            /^[0-9]\d*$/.test(v) ||
                                                                            "Please enter valid number",
                                                                            maxLength: (v) =>
                                                                            v.length <= 10 || "The digit should be less than equal 10",
                                                                        },
                                                                    })
                                                                    } />
                                                                    {errors.bedroom_max && <p className="error">{errors.bedroom_max?.message}</p>}
                                                                    {renderFieldError('bedroom_max') }
                                                                </div>
                                                            </div>
                                                            <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                                <label>Bath (min)<span>*</span></label>
                                                                <div className="form-group">
                                                                    <input type="text" name="bath_min" className="form-control" placeholder="Bath (min)"
                                                                    {
                                                                    ...register("bath_min", {
                                                                        required: "Bath (min) is required",
                                                                        validate: {
                                                                            matchPattern: (v) =>
                                                                            /^[0-9]\d*$/.test(v) ||
                                                                            "Please enter valid number",
                                                                            maxLength: (v) =>
                                                                            v.length <= 10 || "The digit should be less than equal 10",
                                                                        },
                                                                    })
                                                                    } />
                                                                    {errors.bath_min && <p className="error">{errors.bath_min?.message}</p>}
                                                                    {renderFieldError('bath_min') }
                                                                </div>
                                                            </div>
                                                            <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                                <label>Bath (max)<span>*</span></label>
                                                                <div className="form-group">
                                                                    <input type="text" name="bath_max" className="form-control" placeholder="Bath (max)" {
                                                                    ...register("bath_max", {
                                                                        required: "Bath (max) is required",
                                                                        validate: {
                                                                            matchPattern: (v) =>
                                                                            /^[0-9]\d*$/.test(v) ||
                                                                            "Please enter valid number",
                                                                            maxLength: (v) =>
                                                                            v.length <= 10 || "The digit should be less than equal 10",
                                                                        },
                                                                    })
                                                                    } />
                                                                    {errors.bath_max && <p className="error">{errors.bath_max?.message}</p>}
                                                                    
                                                                    {renderFieldError('bath_max') }
                                                                </div>
                                                            </div>
                                                            <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                                <label>Sq Ft Min<span>*</span></label>
                                                                <div className="form-group">
                                                                    <input type="text" name="size_min" className="form-control" placeholder="Sq Ft Min"   {
                                                                    ...register("size_min", {
                                                                        required: "Sq Ft Min is required",
                                                                        validate: {
                                                                            matchPattern: (v) =>
                                                                            /^[0-9]\d*$/.test(v) ||
                                                                            "Please enter valid number",
                                                                            maxLength: (v) =>
                                                                            v.length <= 10 || "The digit should be less than equal 10",
                                                                        },
                                                                    })
                                                                    } />
                                                                    {errors.size_min && <p className="error">{errors.size_min?.message}</p>}

                                                                    {renderFieldError('size_min') }
                                                                </div>
                                                            </div>
                                                            <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                                <label>Sq Ft Max<span>*</span></label>
                                                                <div className="form-group">
                                                                    <input type="text" name="size_max" className="form-control" placeholder="Sq Ft Max"   {
                                                                    ...register("size_max", {
                                                                        required: "Sq Ft Max is required",
                                                                        validate: {
                                                                            matchPattern: (v) =>
                                                                            /^[0-9]\d*$/.test(v) ||
                                                                            "Please enter valid number",
                                                                            maxLength: (v) =>
                                                                            v.length <= 10 || "The digit should be less than equal 10",
                                                                        },
                                                                    })
                                                                    } />
                                                                    {errors.size_max && <p className="error">{errors.size_max?.message}</p>}

                                                                    {renderFieldError('size_max') }
                                                                </div>
                                                            </div>
                                                            <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                                <label>Lot Size Sq Ft (min)<span>*</span></label>
                                                                <div className="form-group">
                                                                    <input type="text" name="lot_size_min" className="form-control" placeholder="Lot Size Sq Ft (min)"   {
                                                                    ...register("lot_size_min", {
                                                                        required: "Lot Size Sq Ft (Min) is required",
                                                                        validate: {
                                                                            matchPattern: (v) =>
                                                                            /^[0-9]\d*$/.test(v) ||
                                                                            "Please enter valid number",
                                                                            maxLength: (v) =>
                                                                            v.length <= 10 || "The digit should be less than equal 10",
                                                                        },
                                                                    })
                                                                    } />
                                                                    {errors.lot_size_min && <p className="error">{errors.lot_size_min?.message}</p>}

                                                                    {renderFieldError('lot_size_min') }
                                                                </div>
                                                            </div>
                                                            <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                                <label>Lot Size Sq Ft (max)<span>*</span></label>
                                                                <div className="form-group">
                                                                    <input type="text" name="lot_size_max" className="form-control" placeholder="Lot Size Sq Ft (max)"    {
                                                                    ...register("lot_size_max", {
                                                                        required: "Lot Size Sq Ft (max) is required",
                                                                        validate: {
                                                                            matchPattern: (v) =>
                                                                            /^[0-9]\d*$/.test(v) ||
                                                                            "Please enter valid number",
                                                                            maxLength: (v) =>
                                                                            v.length <= 10 || "The digit should be less than equal 10",
                                                                        },
                                                                    })
                                                                    } />
                                                                    {errors.lot_size_max && <p className="error">{errors.lot_size_max?.message}</p>}

                                                                    {renderFieldError('lot_size_max') }
                                                                </div>
                                                            </div>
                                                            <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                                <label>Year Built (min)<span>*</span></label>
                                                                <div className="form-group">
                                                                <Controller
                                                                        control={control}
                                                                        name="build_year_min"
                                                                        rules={{ required: 'Year Built (Min) is required' }}
                                                                        render={({ field: { value, onChange, name } }) => (
                                                                            <DatePicker
                                                                                id="DatePicker"
                                                                                type="string"
                                                                                className="text-primary text-center form-control"
                                                                                selected={startDate} 
                                                                                placeholderText="Year Built (Min)"
                                                                                name="build_year_min" 
                                                                                onChange={
                                                                                    (e)=>{
                                                                                        onChange(e)
                                                                                        handleCustum(e,'start_date')
                                                                                    }
                                                                                }
                                                                                showYearPicker
                                                                                dateFormat="yyyy"
                                                                                yearItemNumber={9}
                                                                            />
                                                                        )}
                                                                />
                                                                    {errors.build_year_min && <p className="error">{errors.build_year_min?.message}</p>}

                                                                    {renderFieldError('build_year_min') }
                                                                </div>
                                                            </div>
                                                            <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                                <label>Year Built (max)<span>*</span></label>
                                                                <div className="form-group">
                                                                    <Controller
                                                                            control={control}
                                                                            name="build_year_max"
                                                                            rules={{ required: 'Year Built (Max) is required' }}
                                                                            render={({ field: { value, onChange, name } }) => (
                                                                                <DatePicker
                                                                                    minDate={startDate}
                                                                                    id="DatePicker"
                                                                                    type="string"
                                                                                    className="text-primary text-center form-control"
                                                                                    selected={endDate}
                                                                                    name="build_year_max"
                                                                                    placeholderText="Year Built (Max)" 
                                                                                    minYear={2020}
                                                                                    onChange={
                                                                                        (e)=>{
                                                                                            onChange(e)
                                                                                            handleCustum(e,'end_date')
                                                                                        }
                                                                                    }
                                                                                    showYearPicker
                                                                                    dateFormat="yyyy"
                                                                                    yearItemNumber={9}
                                                                                />
                                                                            )}
                                                                    />
                                                                    {errors.build_year_max && <p className="error">{errors.build_year_max?.message}</p>}
                                                                    {renderFieldError('build_year_max') }
                                                                </div>
                                                            </div>
                                                            <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                                <label>Stories (min)<span>*</span></label>
                                                                <div className="form-group">
                                                                    <input type="text" name="stories_min" className="form-control" placeholder="Stories (min)" 
                                                                    {
                                                                    ...register("stories_min", {
                                                                        required: "Stories (min) is required",
                                                                        validate: {
                                                                            matchPattern: (v) =>
                                                                            /^[0-9]\d*$/.test(v) ||
                                                                            "Please enter valid number",
                                                                            maxLength: (v) =>
                                                                            v > 0 && v < 4 || "The digit should be greater than equal to 1 and less than equal to 3",
                                                                        },
                                                                    })
                                                                    } />
                                                                    {errors.stories_min && <p className="error">{errors.stories_min?.message}</p>}

                                                                    {renderFieldError('stories_min') }
                                                                </div>
                                                            </div>
                                                            <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                                <label>Stories (max)<span>*</span></label>
                                                                <div className="form-group">
                                                                    <input type="text" name="stories_max" className="form-control" placeholder="Stories (max)" {
                                                                    ...register("stories_max", {
                                                                        required: "Stories (max) is required",
                                                                        validate: {
                                                                            matchPattern: (v) =>
                                                                            /^[0-9]\d*$/.test(v) ||
                                                                            "Please enter valid number",
                                                                            maxLength: (v) =>
                                                                            v > 0 && v < 4 || "The digit should be greater than equal to 1 and less than equal to 3",
                                                                        },
                                                                    })
                                                                    } />
                                                                    {errors.stories_max && <p className="error">{errors.stories_max?.message}</p>}

                                                                    {renderFieldError('stories_max') }
                                                                </div>
                                                            </div>
                                                            <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                                <label>Price (min)<span>*</span></label>
                                                                <div className="form-group">
                                                                    <input type="text" name="price_min" className="form-control" placeholder="Price (min)" 
                                                                    {
                                                                    ...register("price_min", {
                                                                        required: "Price (min) is required",
                                                                        validate: {
                                                                            matchPattern: (v) =>
                                                                            /^[0-9]\d*$/.test(v) ||
                                                                            "Please enter valid number",
                                                                            maxLength: (v) =>
                                                                            v.length <= 10 || "The digit should be less than equal 10",
                                                                        },
                                                                    })
                                                                    } />
                                                                    {errors.price_min && <p className="error">{errors.price_min?.message}</p>}

                                                                    {renderFieldError('arv_min') }
                                                                </div>
                                                            </div>
                                                            <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                                <label>Price (max)<span>*</span></label>
                                                                <div className="form-group">
                                                                    <input type="text" name="price_max" className="form-control" placeholder="Price (max)" {
                                                                    ...register("price_max", {
                                                                        required: "Price (max) is required",
                                                                        validate: {
                                                                            matchPattern: (v) =>
                                                                            /^[0-9]\d*$/.test(v) ||
                                                                            "Please enter valid number",
                                                                            maxLength: (v) =>
                                                                            v.length <= 10 || "The digit should be less than equal 10",
                                                                        },
                                                                    })
                                                                    } />
                                                                    {errors.price_max && <p className="error">{errors.price_max?.message}</p>}

                                                                    {renderFieldError('price_max') }
                                                                </div>
                                                            </div>
                                                            <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                                <label>ARV (min)<span>*</span></label>
                                                                <div className="form-group">
                                                                    <input type="text" name="arv_min" className="form-control" placeholder="ARV (min)" 
                                                                    {
                                                                    ...register("arv_min", {
                                                                        required: "ARV (min) is required",
                                                                        validate: {
                                                                            matchPattern: (v) =>
                                                                            /^[0-9]\d*$/.test(v) ||
                                                                            "Please enter valid number",
                                                                            maxLength: (v) =>
                                                                            v.length <= 10 || "The digit should be less than equal 10",
                                                                        },
                                                                    })
                                                                    } />
                                                                    {errors.arv_min && <p className="error">{errors.arv_min?.message}</p>}

                                                                    {renderFieldError('arv_min') }
                                                                </div>
                                                            </div>
                                                            <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                                <label>ARV (max)<span>*</span></label>
                                                                <div className="form-group">
                                                                    <input type="text" name="arv_max" className="form-control" placeholder="ARV (max)" {
                                                                    ...register("arv_max", {
                                                                        required: "ARV (max) is required",
                                                                        validate: {
                                                                            matchPattern: (v) =>
                                                                            /^[0-9]\d*$/.test(v) ||
                                                                            "Please enter valid number",
                                                                            maxLength: (v) =>
                                                                            v.length <= 10 || "The digit should be less than equal 10",
                                                                        },
                                                                    })
                                                                    } />
                                                                    {errors.arv_max && <p className="error">{errors.arv_max?.message}</p>}

                                                                    {renderFieldError('arv_max') }
                                                                </div>
                                                            </div>
                                                            <div className="col-6 col-lg-6">
                                                                <label>Parking<span>*</span></label>
                                                                <div className="form-group">
                                                                    {/* <MultiSelect
                                                                        name="parking"
                                                                        options={parkingOption}
                                                                        placeholder='Select Parking'
                                                                        setMultiselectOption = {setParkingValue}
                                                                    /> */}
                                                                    <Controller
                                                                        control={control}
                                                                        name="parking"
                                                                        rules={{ required: 'Parking is required' }}
                                                                        render={({ field: { value, onChange, name } }) => (
                                                                        <Select
                                                                            options={parkingOption}
                                                                            name = {name}
                                                                            placeholder='Select parking'
                                                                            setMultiselectOption = {setParkingValue}
                                                                            onChange={(e)=>{
                                                                                onChange(e)
                                                                                handleCustum(e,'parking')
                                                                            }}
                                                                            />
                                                                        )}
                                                                        />
                                                                        {errors.parking && <p className="error">{errors.parking?.message}</p>}

                                                                    {renderFieldError('parking') }
                                                                </div>
                                                            </div>
                                                            <div className="col-6 col-lg-6">
                                                                <label>Buyer Type<span>*</span></label>
                                                                <div className="form-group">
                                                                    <Controller
                                                                        control={control}
                                                                        name="buyer_type"
                                                                        rules={{ required: 'Buyer Type is required' }}
                                                                        render={({ field: { value, onChange, name } }) => (
                                                                        <Select
                                                                        options={buyerTypeOption}
                                                                        name = {name}
                                                                            placeholder='Select Buyer Type'
                                                                            setMultiselectOption = {setBuyerTypeValue}
                                                                            onChange={(e)=>{
                                                                                onChange(e)
                                                                                handleCustum(e,'buyer_type')
                                                                            }}
                                                                            />
                                                                        )}
                                                                        />
                                                                        {errors.buyer_type && <p className="error">{errors.buyer_type?.message}</p>}

                                                                    {renderFieldError('buyer_type') }
                                                                </div>
                                                            </div>
                                                            <div className="col-12 col-lg-12">
                                                                <div className="form-group">
                                                                    <label>Property Flaws</label>
                                                                    <div className="form-group">
                                                                        <MultiSelect 
                                                                            name="property_flaw"
                                                                            options={locationFlawsOption} 
                                                                            placeholder='Select Property Flaws'
                                                                            setMultiselectOption = {setLocationFlawsValue}
                                                                        />
                                                                        {renderFieldError('property_flaw') }
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div className="column--grid">
                                                                <div className="grid-template-col">
                                                                    <div className="radio-block-group">
                                                                        <label>Solar</label>
                                                                        <div className="label-container">
                                                                            <input type="radio" name="solar" value="1" id="solar_yes"/>
                                                                            <label className="mb-0" htmlFor="solar_yes">Yes</label>
                                                                        </div>
                                                                        <div className="label-container">
                                                                            <input type="radio" name="solar" value="0" id="solar_no"/>
                                                                            <label className="mb-0" htmlFor="solar_no">No</label>
                                                                        </div>
                                                                    </div>
                                                                    {renderFieldError('solar') }
                                                                </div>
                                                                <div className="grid-template-col">
                                                                    <div className="radio-block-group">
                                                                        <label>Pool</label>
                                                                        <div className="label-container">
                                                                            <input type="radio" name="pool" value="1" id="pool_yes"/>
                                                                            <label className="mb-0" htmlFor="pool_yes">Yes</label>
                                                                        </div>
                                                                        <div className="label-container">
                                                                            <input type="radio" name="pool" value="0" id="pool_no"/>
                                                                            <label className="mb-0" htmlFor="pool_no">No</label>
                                                                        </div>
                                                                    </div>
                                                                    { renderFieldError('pool') }
                                                                </div>
                                                                <div className="grid-template-col">
                                                                    <div className="radio-block-group">
                                                                        <label>Septic</label>
                                                                        <div className="label-container">
                                                                            <input type="radio" name="septic" value="1" id="septic_yes"/>
                                                                            <label className="mb-0" htmlFor="septic_yes">Yes</label>
                                                                        </div>
                                                                        <div className="label-container">
                                                                            <input type="radio" name="septic" value="0" id="septic_no"/>
                                                                            <label className="mb-0" htmlFor="septic_no">No</label>
                                                                        </div>
                                                                    </div>
                                                                    {renderFieldError('septic') }
                                                                </div>
                                                                <div className="grid-template-col">
                                                                    <div className="radio-block-group">
                                                                        <label>Well</label>
                                                                        <div className="label-container">
                                                                            <input type="radio" name="well" value="1" id="well_yes"/>
                                                                            <label className="mb-0" htmlFor="well_yes">Yes</label>
                                                                        </div>
                                                                        <div className="label-container">
                                                                            <input type="radio" name="well" value="0" id="well_no"/>
                                                                            <label className="mb-0" htmlFor="well_no">No</label>
                                                                        </div>
                                                                    </div>
                                                                    {renderFieldError('well') }
                                                                </div>
                                                                <div className="grid-template-col">
                                                                    <div className="radio-block-group">
                                                                        <label>Age restriction</label>
                                                                        <div className="label-container">
                                                                            <input type="radio" name="age_restriction" value="1" id="age_restriction_yes"/>
                                                                            <label className="mb-0" htmlFor="age_restriction_yes">Yes</label>
                                                                        </div>
                                                                        <div className="label-container">
                                                                            <input type="radio" name="age_restriction" value="0" id="age_restriction_no"/>
                                                                            <label className="mb-0" htmlFor="age_restriction_no">No</label>
                                                                        </div>
                                                                    </div>
                                                                    {renderFieldError('age_restriction') }
                                                                </div>
                                                                <div className="grid-template-col">
                                                                    <div className="radio-block-group">
                                                                        <label>Rental Restriction</label>
                                                                        <div className="label-container">
                                                                            <input type="radio" name="rental_restriction" value="1" id="rental_restriction_yes"/>
                                                                            <label className="mb-0" htmlFor="rental_restriction_yes">Yes</label>
                                                                        </div>
                                                                        <div className="label-container">
                                                                            <input type="radio" name="rental_restriction" value="0" id="rental_restriction_no"/>
                                                                            <label className="mb-0" htmlFor="rental_restriction_no">No</label>
                                                                        </div>
                                                                    </div>
                                                                    {renderFieldError('rental_restriction') }
                                                                </div>
                                                                <div className="grid-template-col">
                                                                    <div className="radio-block-group">
                                                                        <label>HOA</label>
                                                                        <div className="label-container">
                                                                            <input type="radio" name="hoa" value="1" id="hoa_yes"/>
                                                                            <label className="mb-0" htmlFor="hoa_yes">Yes</label>
                                                                        </div>
                                                                        <div className="label-container">
                                                                            <input type="radio" name="hoa" value="0" id="hoa_no"/>
                                                                            <label className="mb-0" htmlFor="hoa_no">No</label>
                                                                        </div>
                                                                    </div>
                                                                    {renderFieldError('hoa') }
                                                                </div>
                                                                <div className="grid-template-col">
                                                                    <div className="radio-block-group">
                                                                        <label>Tenant Conveys</label>
                                                                        <div className="label-container">
                                                                            <input type="radio" name="tenant" value="1" id="tenant_yes"/>
                                                                            <label className="mb-0" htmlFor="tenant_yes">Yes</label>
                                                                        </div>
                                                                        <div className="label-container">
                                                                            <input type="radio" name="tenant" value="0" id="tenant_no"/>
                                                                            <label className="mb-0" htmlFor="tenant_no">No</label>
                                                                        </div>
                                                                    </div>
                                                                    {renderFieldError('tenant') }
                                                                </div>
                                                                <div className="grid-template-col">
                                                                    <div className="radio-block-group">
                                                                        <label>Post-Possession</label>
                                                                        <div className="label-container">
                                                                            <input type="radio" name="post_possession" value="1" id="post_possession_yes"/>
                                                                            <label className="mb-0" htmlFor="post_possession_yes">Yes</label>
                                                                        </div>
                                                                        <div className="label-container">
                                                                            <input type="radio" name="post_possession" value="0" id="post_possession_no"/>
                                                                            <label className="mb-0" htmlFor="post_possession_no">No</label>
                                                                        </div>
                                                                    </div>
                                                                    {renderFieldError('post_possession') }
                                                                </div>
                                                                <div className="grid-template-col">
                                                                    <div className="radio-block-group">
                                                                        <label>Building Required</label>
                                                                        <div className="label-container">
                                                                            <input type="radio" name="building_required" value="1" id="building_required_yes"/>
                                                                            <label className="mb-0" htmlFor="building_required_yes">Yes</label>
                                                                        </div>
                                                                        <div className="label-container">
                                                                            <input type="radio" name="building_required" value="0" id="building_required_no"/>
                                                                            <label className="mb-0" htmlFor="building_required_no">No</label>
                                                                        </div>
                                                                    </div>
                                                                    {renderFieldError('building_required') }
                                                                </div>
                                                                <div className="grid-template-col">
                                                                    <div className="radio-block-group">
                                                                        <label>Foundation Issues</label>
                                                                        <div className="label-container">
                                                                            <input type="radio" name="foundation_issues" value="1" id="foundation_issues_yes"/>
                                                                            <label className="mb-0" htmlFor="foundation_issues_yes">Yes</label>
                                                                        </div>
                                                                        <div className="label-container">
                                                                            <input type="radio" name="foundation_issues" value="0" id="foundation_issues_no" />
                                                                            <label className="mb-0" htmlFor="foundation_issues_no">No</label>
                                                                        </div>
                                                                    </div>
                                                                    {renderFieldError('foundation_issues') }
                                                                </div>
                                                                <div className="grid-template-col">
                                                                    <div className="radio-block-group">
                                                                        <label>Mold</label>
                                                                        <div className="label-container">
                                                                            <input type="radio" name="mold" value="1" id="mold_yes" />
                                                                            <label className="mb-0" htmlFor="mold_yes">Yes</label>
                                                                        </div>
                                                                        <div className="label-container">
                                                                            <input type="radio" name="mold" value="0" id="mold_no"/>
                                                                            <label className="mb-0" htmlFor="mold_no">No</label>
                                                                        </div>
                                                                    </div>
                                                                    {renderFieldError('mold') }
                                                                </div>
                                                                <div className="grid-template-col">
                                                                    <div className="radio-block-group">
                                                                        <label>Fire Damaged</label>
                                                                        <div className="label-container">
                                                                            <input type="radio" name="fire_damaged" value="1" id="fire_damaged_yes"/>
                                                                            <label className="mb-0" htmlFor="fire_damaged_yes">Yes</label>
                                                                        </div>
                                                                        <div className="label-container">
                                                                            <input type="radio" name="fire_damaged" value="0" id="fire_damaged_no"/>
                                                                            <label className="mb-0" htmlFor="fire_damaged_no">No</label>
                                                                        </div>
                                                                    </div>
                                                                    {renderFieldError('fire_damaged') }
                                                                </div>
                                                                <div className="grid-template-col">
                                                                    <div className="radio-block-group">
                                                                        <label>Rebuild</label>
                                                                        <div className="label-container">
                                                                            <input type="radio" name="rebuild" value="1" id="rebuild_yes"/>
                                                                            <label className="mb-0" htmlFor="rebuild_yes">Yes</label>
                                                                        </div>
                                                                        <div className="label-container">
                                                                            <input type="radio" name="rebuild" value="0" id="rebuild_no"/>
                                                                            <label className="mb-0" htmlFor="rebuild_no">No</label>
                                                                        </div>
                                                                    </div>
                                                                    {renderFieldError('rebuild') }
                                                                </div>
                                                                <div className="grid-template-col">
                                                                    <div className="radio-block-group">
                                                                        <label>Squatters</label>
                                                                        <div className="label-container">
                                                                            <input type="radio" name="squatters" value="1" id="squatters_yes"/>
                                                                            <label className="mb-0" htmlFor="squatters_yes">Yes</label>
                                                                        </div>
                                                                        <div className="label-container">
                                                                            <input type="radio" name="squatters" value="0" id="squatters_no"/>
                                                                            <label className="mb-0" htmlFor="squatters_no">No</label>
                                                                        </div>
                                                                    </div>
                                                                    {renderFieldError('squatters') }
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div className="submit-btn">
                                                            <button type="submit" className="btn btn-fill" disabled={ loading ? 'disabled' : ''}>Submit Now! { loading ? <MiniLoader/> : ''} </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </section>
           }
        </>
    )
    
}
  
export default CopyAddBuyer;