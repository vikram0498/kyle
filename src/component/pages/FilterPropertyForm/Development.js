import axios from 'axios';
import React, { useEffect } from 'react';
import { useState } from 'react';
import Select from 'react-select';
import { useAuth } from './../../../hooks/useAuth';
import MultiSelect from "../../partials/Select2/MultiSelect";
import SingleSelect from "../../partials/Select2/SingleSelect";
import { useForm } from './../../../hooks/useForm';

const Development = ({renderFieldError, setLocationFlawsValue, setPurchaseMethodsValue})=>{
	const {getTokenData} = useAuth();

    // const { renderFieldError } = useForm();

    const [country, setCountry] = useState([]);
    const [state, setState] = useState([]);
    const [city, setCity] = useState([]);

    const [stateOptions,setStateOptions] = useState([]);
    const [cityOptions,setCityOptions] = useState([]);
    const [countryOptions,setCountryOptions] = useState([]);

    const [purchaseMethodsOption, setPurchaseMethodsOption] = useState([])
    const [parkingOption, setParkingOption] = useState([]);
    const [locationFlawsOption,setLocationFlawsOption] = useState([]);

    const [showCreativeFinancing,setShowCreativeFinancing] = useState(false);


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

                setPurchaseMethodsOption(result.purchase_methods);
                setLocationFlawsOption(result.location_flaws);
                setParkingOption(result.parking_values);
                setCountryOptions(result.countries);                
            }
        })
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
 return (
    <>
		<div className="row">
			<div className="col-12 col-lg-12">
				<label>Address</label>
				<div className="form-group">
					<input type="text" name="address" className="form-control" placeholder="Enter Address" />
					{renderFieldError('address') }
				</div>
			</div>
			<div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
				<label>Country</label>
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
				<label>City</label>
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
				<label>Zip Code</label>
				<div className="form-group">
					<input type="text" name="zip_code" className="form-control" placeholder="Zip Code" />
					{renderFieldError('zip_code') }
				</div>
			</div>
			<div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
				<label>Price</label>
				<div className="form-group">
					<input type="number" name="price" className="form-control" placeholder="Enter Your Price"  />
					{renderFieldError('price') }
				</div>
			</div>
			<div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
				<label>Lot Size Sq Ft (min)</label>
				<div className="form-group">
					<input type="number" name="lot_size_min" className="form-control" placeholder="Lot Size Sq Ft (min)"  />
				</div>
			</div>
			<div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
				<label>Lot Size Sq Ft (max)</label>
				<div className="form-group">
					<input type="number" name="lot_size_max" className="form-control" placeholder="Lot Size Sq Ft (max)" />
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
    </>
 )
}
 export default Development;