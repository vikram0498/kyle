import React, {useEffect, useState} from "react";
import {useNavigate} from "react-router-dom";
import MultiSelect from "../partials/Select2/MultiSelect";
import Select from "react-select";
import {useForm} from "../../hooks/useForm";
import axios from 'axios';
import MiniLoader from "../partials/MiniLoader";
import { toast } from "react-toastify";

function CopyAddBuyer (){
    const navigate = useNavigate();
    const [isLoader, setIsLoader] = useState(true);

    const { setErrors, renderFieldError } = useForm();
    
    const [country, setCountry] = useState([]);
    const [state, setState] = useState([]);
    const [city, setCity] = useState([]);

    const [purchaseMethodsOption, setPurchaseMethodsOption] = useState([])
    const [buildingClassNamesOption, setBuildingClassNamesOption] = useState([])
    const [propertyTypeOption, setPropertyTypeOption] = useState([]);
    const [parkingOption, setParkingOption] = useState([]);
    const [locationFlawsOption,setLocationFlawsOption] = useState([]);
    // const [buyerTypeOption,setbuyerTypeOption] = useState([]);

    const [countryOptions,setCountryOptions] = useState([]);
    const [stateOptions,setStateOptions] = useState([]);
    const [cityOptions,setCityOptions] = useState([]);
    
    const [showCreativeFinancing,setShowCreativeFinancing] = useState(false);
    const [multiFamilyBuyerSelected,setMultiFamilyBuyerSelected] = useState(false);


    const [parkingValue, setParkingValue] = useState([]);
    const [propertyTypeValue, setPropertyTypeValue] = useState([]);
    const [locationFlawsValue,setLocationFlawsValue] = useState([]);
    // const [buyerTypeValue,setBuyerTypeValue] = useState([]);
    const [purchaseMethodsValue, setPurchaseMethodsValue] = useState([]);
    const [buildingClassNamesValue, setBuildingClassNamesValue] = useState([]);

    const [loading, setLoading] = useState(false);

    useEffect(() => {
        getOptionsValues();
    }, [navigate]);
    
    
    const apiUrl = process.env.REACT_APP_API_URL;
    
    let headers = { 
        'Accept': 'application/json',
    };
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
                    setCountryOptions(result.countries);
                    // setbuyerTypeOption(result.buyer_types);
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

    const submitSingleBuyerForm = (e) => {
        e.preventDefault();

        setErrors(null);

        setLoading(true);

        var data = new FormData(e.target);
        let formObject = Object.fromEntries(data.entries());

        formObject.parking          =  parkingValue;        
        formObject.property_type    =  propertyTypeValue;
        formObject.property_flaw    =  locationFlawsValue;
        // formObject.buyer_type       =  buyerTypeValue;
        formObject.purchase_method  =  purchaseMethodsValue;

        if (formObject.hasOwnProperty('building_class')) {
            formObject.building_class =  buildingClassNamesValue;
        }
        axios.post(apiUrl+'upload-single-buyer-details', formObject, {headers: headers}).then(response => {
            setLoading(false);
            if(response.data.status){
                toast.success(response.data.message, {position: toast.POSITION.TOP_RIGHT});
                navigate('/my-buyers')
            }
            
        }).catch(error => {
            setLoading(false);
            if(error.response) {
                if (error.response.data.validation_errors) {
                    setErrors(error.response.data.validation_errors);
                }
                if (error.response.data.error) {
                    toast.error(error.response.data.error, {position: toast.POSITION.TOP_RIGHT});
                }
            }
        });
    }

    return (
        <>
           { (isLoader)?<div className="loader" style={{textAlign:'center'}}><img src="assets/images/loader.svg"/></div>:
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

                                        <form method='post' onSubmit={submitSingleBuyerForm}>
                                            <div className="card-box-blocks">
                                                <div className="row">
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>First Name<span>*</span></label>
                                                        <div className="form-group">
                                                            <input type="text" name="first_name" className="form-control" placeholder="First Name" />
                                                            {renderFieldError('first_name') }
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>Last Name<span>*</span></label>
                                                        <div className="form-group">
                                                            <input type="text" name="last_name" className="form-control" placeholder="Last Name" />
                                                            {renderFieldError('last_name') }
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>Email Address<span>*</span></label>
                                                        <div className="form-group">
                                                            <input type="email" name="email" className="form-control" placeholder="Email Address" />
                                                            {renderFieldError('email') }
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>Phone Number<span>*</span></label>
                                                        <div className="form-group">
                                                            <input type="text" name="phone" className="form-control" placeholder="(123) 456-7890" />
                                                            {renderFieldError('phone') }
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>Address<span>*</span></label>
                                                        <div className="form-group">
                                                            <input type="text" name="address" className="form-control" placeholder="Enter Address" />
                                                            {renderFieldError('address') }
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>Country<span>*</span></label>
                                                        <div className="form-group">
                                                            <Select
                                                                name="country"
                                                                defaultValue=''
                                                                options={countryOptions}
                                                                onChange={(item) => getStates(item)}
                                                                className="select"
                                                                isClearable={true}
                                                                isSearchable={true}
                                                                isDisabled={false}
                                                                isLoading={false}
                                                                isRtl={false}
                                                                placeholder= "Select Country"
                                                                closeMenuOnSelect={true}
                                                            />
                                                            {renderFieldError('country') }
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>State<span>*</span></label>
                                                        <div className="form-group">
                                                            <Select
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
                                                            />
                                                            {renderFieldError('state') }
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>City<span>*</span></label>
                                                        <div className="form-group">
                                                            <Select
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
                                                            />
                                                            {renderFieldError('city') }
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>Zip<span>*</span></label>
                                                        <div className="form-group">
                                                            <input type="text" name="zip_code" className="form-control" placeholder="Zip Code" />
                                                            {renderFieldError('zip_code') }
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>Company/LLC<span>*</span></label>
                                                        <div className="form-group">
                                                            <input type="text" className="form-control" name="company_name" placeholder="Company LLC"/>
                                                            {renderFieldError('company_name') }
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-lg-12">
                                                        <div className="form-group">
                                                            <label>Property Type<span>*</span></label>
                                                            <div className="form-group">
                                                                <MultiSelect
                                                                    name="property_type" 
                                                                    options={propertyTypeOption} 
                                                                    placeholder='Select Property Type'
                                                                    setMultiselectOption = {setPropertyTypeValue}
                                                                    showCreative={setMultiFamilyBuyerSelected}
                                                                />
                                                                {renderFieldError('property_type') }
                                                            </div>
                                                        </div>
                                                    </div>
                                                    { multiFamilyBuyerSelected && 
                                                        <div className="block-divide">
                                                            {/* <h5>Multi Family Buyer</h5> */}
                                                            <div className="row">
                                                                <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                                    <label>Minimum Units<span>*</span></label>
                                                                    <div className="form-group">
                                                                        <input type="number" name="unit_min" className="form-control" placeholder="Minimum Units" />
                                                                        {renderFieldError('unit_min') }
                                                                    </div>
                                                                </div>
                                                                <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                                    <label>Maximum Units<span>*</span></label>
                                                                    <div className="form-group">
                                                                        <input type="number" name="unit_max" className="form-control" placeholder="Maximum Units" />
                                                                        {renderFieldError('unit_max') }
                                                                    </div>
                                                                </div>
                                                                <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                                    <label>Building class<span>*</span></label>
                                                                    <div className="form-group">
                                                                        <MultiSelect
                                                                            name="building_class"
                                                                            options={buildingClassNamesOption}
                                                                            placeholder='Select Option'
                                                                            setMultiselectOption = {setBuildingClassNamesValue}
                                                                        />
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
                                                            <MultiSelect
                                                                name="purchase_method"
                                                                options={purchaseMethodsOption}
                                                                placeholder='Select Purchase Method'
                                                                setMultiselectOption = {setPurchaseMethodsValue}
                                                                showCreative = {setShowCreativeFinancing}
                                                            />
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
                                                            <input type="number" name="bedroom_min" className="form-control" placeholder="Bedroom (min)"  />
                                                            {renderFieldError('bedroom_min') }
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>Bedroom (max)<span>*</span></label>
                                                        <div className="form-group">
                                                            <input type="number" name="bedroom_max" className="form-control" placeholder="Bedroom (max)" />
                                                            {renderFieldError('bedroom_max') }
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>Bath (min)<span>*</span></label>
                                                        <div className="form-group">
                                                            <input type="number" name="bath_min" className="form-control" placeholder="Bath (min)" />
                                                            {renderFieldError('bath_min') }
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>Bath (max)<span>*</span></label>
                                                        <div className="form-group">
                                                            <input type="number" name="bath_max" className="form-control" placeholder="Bath (max)" />
                                                            {renderFieldError('bath_max') }
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>Sq Ft Min<span>*</span></label>
                                                        <div className="form-group">
                                                            <input type="number" name="size_min" className="form-control" placeholder="Sq Ft Min"  />
                                                            {renderFieldError('size_min') }
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>Sq Ft Max<span>*</span></label>
                                                        <div className="form-group">
                                                            <input type="number" name="size_max" className="form-control" placeholder="Sq Ft Max"  />
                                                            {renderFieldError('size_max') }
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>Lot Size Sq Ft (min)<span>*</span></label>
                                                        <div className="form-group">
                                                            <input type="number" name="lot_size_min" className="form-control" placeholder="Lot Size Sq Ft (min)"  />
                                                            {renderFieldError('lot_size_min') }
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>Lot Size Sq Ft (max)<span>*</span></label>
                                                        <div className="form-group">
                                                            <input type="number" name="lot_size_max" className="form-control" placeholder="Lot Size Sq Ft (max)" />
                                                            {renderFieldError('lot_size_max') }
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>Year Built (min)<span>*</span></label>
                                                        <div className="form-group">
                                                            <input type="number" name="build_year_min" className="form-control" placeholder="Year Built (min)"/>
                                                            {renderFieldError('build_year_min') }
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>Year Built (max)<span>*</span></label>
                                                        <div className="form-group">
                                                            <input type="number" name="build_year_max" className="form-control" placeholder="Year Built (max)"/>
                                                            {renderFieldError('build_year_max') }
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>ARV (min)<span>*</span></label>
                                                        <div className="form-group">
                                                            <input type="number" name="arv_min" className="form-control" placeholder="ARV (min)" />
                                                            {renderFieldError('arv_min') }
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>ARV (max)<span>*</span></label>
                                                        <div className="form-group">
                                                            <input type="number" name="arv_max" className="form-control" placeholder="ARV (max)" />
                                                            {renderFieldError('arv_max') }
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-lg-12">
                                                        <label>Parking<span>*</span></label>
                                                        <div className="form-group">
                                                            <MultiSelect
                                                                name="parking"
                                                                options={parkingOption}
                                                                placeholder='Select Parking'
                                                                setMultiselectOption = {setParkingValue}
                                                            />
                                                            {renderFieldError('parking') }
                                                        </div>
                                                    </div>
                                                    
                                                    <div className="col-12 col-lg-12">
                                                        <div className="form-group">
                                                            <label>Location Flaws</label>
                                                            <div className="form-group">
                                                                <MultiSelect 
                                                                    name="property_flaw"
                                                                    options={locationFlawsOption} 
                                                                    placeholder='Select Location Flaws'
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
                                                    {/* <div className="col-12 col-lg-12">
                                                        <label>Buyer Type<span>*</span></label>
                                                        <div className="form-group">     
                                                            <MultiSelect
                                                                name="buyer_type"
                                                                options={buyerTypeOption}
                                                                placeholder='Select Buyer Type'
                                                                setMultiselectOption = {setBuyerTypeValue}
                                                                showCreative={setCreativeBuyerSelected}
                                                                showmultiFamily={setMultiFamilyBuyerSelected}
                                                            />
                                                            {renderFieldError('buyer_type') }
                                                        </div>
                                                    </div> */}
                                                    
                                                    
                                                    
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