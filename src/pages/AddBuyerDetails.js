import React, {useContext, useEffect} from "react";
import {useNavigate} from "react-router-dom";
import AuthContext from "../context/authContext";
import Head from "./Head";
function AddBuyerDetails (){
    const {authData} = useContext(AuthContext);
    const navigate = useNavigate();
    
    useEffect(() => {
        if(!authData.signedIn) {
            navigate('/login');
        }
    }, [navigate, authData]);


    function handleChange(event) {
        // Update the state of your application accordingly.
    }
    return (
        <>
           <Head/>
           <section className="main-section position-relative pt-4 pb-120">
                <div className="container position-relative">
                    <div className="back-block">
                        <a href="#" className="back">
                            <svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15 6H1" stroke="#0A2540" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
                                <path d="M5.9 11L1 6L5.9 1" stroke="#0A2540" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
                            </svg>
                            Back
                        </a>
                    </div>
                    <div className="card-box">
                        <div className="row">
                            <div className="col-12 col-lg-8 w-70">
                                <div className="card-box-inner">
                                    <div className="row">
                                        <div className="col-12 col-sm-7 col-md-6 col-lg-6">
                                            <h3>Upload Single Buyer Detail</h3>
                                            <p>Fill the below form OR send link to the buyer</p>
                                        </div>
                                        <div className="col-12 col-sm-5 col-md-6 col-lg-6">
                                            <button type="button" className="copy-link">
                                                <span className="link-icon">
                                                    <svg width="18" height="17" viewBox="0 0 18 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <g clipPath="url(#clip0_270_17734)">
                                                        <path d="M7.5 9.20823C7.82209 9.6149 8.23302 9.9514 8.70491 10.1949C9.17681 10.4384 9.69863 10.5832 10.235 10.6195C10.7713 10.6557 11.3097 10.5827 11.8135 10.4052C12.3173 10.2277 12.7748 9.9499 13.155 9.59073L15.405 7.46573C16.0881 6.79776 16.4661 5.90313 16.4575 4.97451C16.449 4.0459 16.0546 3.15761 15.3593 2.50095C14.664 1.8443 13.7235 1.47183 12.7403 1.46376C11.757 1.45569 10.8098 1.81267 10.1025 2.45781L8.8125 3.66906" stroke="#121639" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
                                                        <path d="M10.5001 7.79162C10.1781 7.38495 9.76713 7.04845 9.29524 6.80496C8.82334 6.56146 8.30152 6.41667 7.76516 6.38039C7.2288 6.34411 6.69046 6.4172 6.18664 6.59469C5.68282 6.77219 5.22531 7.04995 4.84515 7.40912L2.59515 9.53412C1.91206 10.2021 1.53408 11.0967 1.54262 12.0253C1.55117 12.9539 1.94555 13.8422 2.64083 14.4989C3.33611 15.1556 4.27666 15.528 5.2599 15.5361C6.24313 15.5442 7.19039 15.1872 7.89765 14.542L9.18015 13.3308" stroke="#121639" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
                                                        </g>
                                                        <defs>
                                                        <clipPath id="clip0_270_17734">
                                                        <rect width="18" height="17" fill="white"/>
                                                        </clipPath>
                                                        </defs>
                                                    </svg>
                                                </span>
                                                Copy Form Link
                                            </button>
                                        </div>
                                    </div>
                                    <form>
                                        <div className="card-box-blocks">
                                            <div className="row">
                                                <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                    <label>First Name<span>*</span></label>
                                                    <div className="form-group">
                                                        <input type="text" name="" className="form-control" placeholder="First Name" />
                                                    </div>
                                                </div>
                                                <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                    <label>Last Name<span>*</span></label>
                                                    <div className="form-group">
                                                        <input type="text" name="" className="form-control" placeholder="Last Name" />
                                                    </div>
                                                </div>
                                                <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                    <label>Email Address<span>*</span></label>
                                                    <div className="form-group">
                                                        <input type="email" name="" className="form-control" placeholder="Email Address" />
                                                    </div>
                                                </div>
                                                <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                    <label>Phone Number<span>*</span></label>
                                                    <div className="form-group">
                                                        <input type="text" name="" className="form-control" placeholder="(123) 456-7890" />
                                                    </div>
                                                </div>
                                                <div className="col-12 col-md-4 col-lg-4">
                                                    <label>City<span>*</span></label>
                                                    <div className="form-group">
                                                        <select id="city" data-minimum-results-for-search="Infinity">
                                                            <option>Choose City</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div className="col-12 col-md-4 col-lg-4">
                                                    <label>State<span>*</span></label>
                                                    <div className="form-group">
                                                        <select id="state" className="" data-minimum-results-for-search="Infinity">
                                                            <option>Choose State</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div className="col-12 col-md-4 col-lg-4">
                                                    <label>Company/LLC<span>*</span></label>
                                                    <div className="form-group">
                                                        <select className="form-control">
                                                            <option>Company/LLC*</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div className="col-12 col-lg-12">
                                                    <div className="form-group">
                                                        <label>Property Type<span>*</span></label>
                                                        <div className="form-group">
                                                            <select id="single" data-minimum-results-for-search="Infinity" multiple="multiple">
                                                                <option>Select Property Type</option>
                                                                <option>Attached</option>
                                                                <option>Apartment Buildings</option>
                                                                <option>Commercial - Retail</option>
                                                                <option>Condo</option>
                                                                <option>Detached</option>
                                                                <option>Development</option>
                                                                <option>Land</option>
                                                                <option>Manufactured</option>
                                                                <option>Mobile Home</option>
                                                                <option>Multi-Family - Commercial</option>
                                                                <option>Multi-Family - Residential</option>
                                                                <option>Single Family</option>
                                                                <option>Townhouse</option>
                                                                <option>Mobile Home Park</option>
                                                                <option>Hotel/Motel</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                    <label>Additional Fields<span>*</span></label>
                                                    <div className="form-group">
                                                        <input type="text" name="" className="form-control" placeholder="Additional Fields" />
                                                    </div>
                                                </div>
                                                <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                    <label>Minimum Units<span>*</span></label>
                                                    <div className="form-group">
                                                        <input type="text" name="" className="form-control" placeholder="Minimum Units" />
                                                    </div>
                                                </div>
                                                <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                    <label>Maximum Units<span>*</span></label>
                                                    <div className="form-group">
                                                        <input type="text" name="" className="form-control" placeholder="Maximum Units" />
                                                    </div>
                                                </div>
                                                <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                    <label>Building className<span>*</span></label>
                                                    <div className="form-group">
                                                        <select className="form-control">
                                                            <option>A</option>
                                                            <option>A</option>
                                                            <option>A</option>
                                                            <option>A</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div className="col-12 col-md-12 col-lg-3">
                                                    <label>Value Add</label>
                                                    <div className="form-group">
                                                        <div className="radio-block">
                                                            <div className="label-container">
                                                                <input type="radio" name="valueadd" />
                                                                <span>Yes</span>
                                                            </div>
                                                            <div className="label-container">
                                                                <input type="radio" name="valueadd" checked onChange={handleChange} />
                                                                <span>No</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="col-12 col-lg-9">
                                                    <label>Purchase Method<span>*</span></label>
                                                    <div className="form-group">
                                                        <select id="financing" data-minimum-results-for-search="Infinity">
                                                            <option>Creative Financing</option>
                                                            <option>Creative Financing</option>
                                                            <option>Creative Financing</option>
                                                            <option>Creative Financing</option>
                                                            <option>Creative Financing</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div className="block-divide">
                                                <h5>Creative Financing</h5>
                                                <div className="row">
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>Down Payment (%)</label>
                                                        <div className="form-group">
                                                            <input type="text" name="" className="form-control" placeholder="Down Payment (%)" />
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>Down Payment ($)</label>
                                                        <div className="form-group">
                                                            <input type="text" name="" className="form-control" placeholder="Down Payment ($)" />
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>Interest Rate (%)</label>
                                                        <div className="form-group">
                                                            <input type="text" name="" className="form-control" placeholder="Interest Rate (%)"  />
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>Balloon Payment</label>
                                                        <div className="form-group">
                                                            <div className="radio-block">
                                                                <div className="label-container">
                                                                    <input type="radio" name="payment" checked onChange={handleChange} />
                                                                    <span>Yes</span>
                                                                </div>
                                                                <div className="label-container">
                                                                    <input type="radio" name="payment" />
                                                                    <span>No</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>Bedroom (min)<span>*</span></label>
                                                        <div className="form-group">
                                                            <input type="text" name="" className="form-control" placeholder="Bedroom (min)"  />
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>Bedroom (max)<span>*</span></label>
                                                        <div className="form-group">
                                                            <input type="text" name="" className="form-control" placeholder="Bedroom (max)" />
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>Bath (min)<span>*</span></label>
                                                        <div className="form-group">
                                                            <input type="text" name="" className="form-control" placeholder="Bath (min)" />
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>Bath (max)<span>*</span></label>
                                                        <div className="form-group">
                                                            <input type="text" name="" className="form-control" placeholder="Bath (max)" />
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>Sq Ft Min<span>*</span></label>
                                                        <div className="form-group">
                                                            <input type="text" name="" className="form-control" placeholder="Sq Ft Min"  />
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>Sq Ft Max<span>*</span></label>
                                                        <div className="form-group">
                                                            <input type="text" name="" className="form-control" placeholder="Sq Ft Max"  />
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>Lot Size Sq Ft (min)<span>*</span></label>
                                                        <div className="form-group">
                                                            <input type="text" name="" className="form-control" placeholder="Lot Size Sq Ft (min)"  />
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>Lot Size Sq Ft (max)<span>*</span></label>
                                                        <div className="form-group">
                                                            <input type="text" name="" className="form-control" placeholder="Lot Size Sq Ft (max)" />
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>Year Built (min)<span>*</span></label>
                                                        <div className="form-group">
                                                            <input type="text" name="" className="form-control" placeholder="Year Built (min)"/>
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>Year Built (max)<span>*</span></label>
                                                        <div className="form-group">
                                                            <input type="text" name="" className="form-control" placeholder="Year Built (max)"/>
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>ARV (min)<span>*</span></label>
                                                        <div className="form-group">
                                                            <input type="text" name="" className="form-control" placeholder="ARV (min)" />
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                        <label>ARV (max)<span>*</span></label>
                                                        <div className="form-group">
                                                            <input type="text" name="" className="form-control" placeholder="ARV (max)" />
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-lg-12">
                                                        <label>Parking<span>*</span></label>
                                                        <div className="form-group">
                                                            <select id="parking" data-minimum-results-for-search="Infinity">
                                                                <option>Choose Parking</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div className="col-12 col-lg-12">
                                                        <div className="form-group">
                                                            <label>Location Flaws</label>
                                                            <div className="form-group">
                                                                <select id="location-flaws" multiple="multiple" data-minimum-results-for-search="Infinity">
                                                                    <option>Assigned</option>
                                                                    <option>Carport</option>
                                                                    <option>Driveway</option>
                                                                    <option>Boarders non-residential</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div className="column--grid">
                                                <div className="grid-template-col">
                                                    <div className="radio-block-group">
                                                        <label>Solar</label>
                                                        <div className="label-container">
                                                            <input type="radio" name="Solar" checked onChange={handleChange} />
                                                            <span>Yes</span>
                                                        </div>
                                                        <div className="label-container">
                                                            <input type="radio" name="Solar" />
                                                            <span>No</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="grid-template-col">
                                                    <div className="radio-block-group">
                                                        <label>Pool</label>
                                                        <div className="label-container">
                                                            <input type="radio" name="Pool" />
                                                            <span>Yes</span>
                                                        </div>
                                                        <div className="label-container">
                                                            <input type="radio" name="Pool" checked onChange={handleChange} />
                                                            <span>No</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="grid-template-col">
                                                    <div className="radio-block-group">
                                                        <label>Septic</label>
                                                        <div className="label-container">
                                                            <input type="radio" name="Septic" checked onChange={handleChange} />
                                                            <span>Yes</span>
                                                        </div>
                                                        <div className="label-container">
                                                            <input type="radio" name="Septic" />
                                                            <span>No</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="grid-template-col">
                                                    <div className="radio-block-group">
                                                        <label>Well</label>
                                                        <div className="label-container">
                                                            <input type="radio" name="Well" checked onChange={handleChange} />
                                                            <span>Yes</span>
                                                        </div>
                                                        <div className="label-container">
                                                            <input type="radio" name="Well" />
                                                            <span>No</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="grid-template-col">
                                                    <div className="radio-block-group">
                                                        <label>Age restriction</label>
                                                        <div className="label-container">
                                                            <input type="radio" name="Age restriction" checked onChange={handleChange} />
                                                            <span>Yes</span>
                                                        </div>
                                                        <div className="label-container">
                                                            <input type="radio" name="Age restriction" />
                                                            <span>No</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="grid-template-col">
                                                    <div className="radio-block-group">
                                                        <label>Rental Restriction</label>
                                                        <div className="label-container">
                                                            <input type="radio" name="Rental Restriction" checked onChange={handleChange} />
                                                            <span>Yes</span>
                                                        </div>
                                                        <div className="label-container">
                                                            <input type="radio" name="Rental Restriction" />
                                                            <span>No</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="grid-template-col">
                                                    <div className="radio-block-group">
                                                        <label>HOA</label>
                                                        <div className="label-container">
                                                            <input type="radio" name="HOA" />
                                                            <span>Yes</span>
                                                        </div>
                                                        <div className="label-container">
                                                            <input type="radio" name="HOA" checked onChange={handleChange} />
                                                            <span>No</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="grid-template-col">
                                                    <div className="radio-block-group">
                                                        <label>Tenant Conveys</label>
                                                        <div className="label-container">
                                                            <input type="radio" name="Tenant Conveys" />
                                                            <span>Yes</span>
                                                        </div>
                                                        <div className="label-container">
                                                            <input type="radio" name="Tenant Conveys" checked onChange={handleChange} />
                                                            <span>No</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="grid-template-col">
                                                    <div className="radio-block-group">
                                                        <label>Post-Possession</label>
                                                        <div className="label-container">
                                                            <input type="radio" name="Post-Possession" checked onChange={handleChange} />
                                                            <span>Yes</span>
                                                        </div>
                                                        <div className="label-container">
                                                            <input type="radio" name="Post-Possession" />
                                                            <span>No</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="grid-template-col">
                                                    <div className="radio-block-group">
                                                        <label>Building Required</label>
                                                        <div className="label-container">
                                                            <input type="radio" name="Building Required" checked onChange={handleChange} />
                                                            <span>Yes</span>
                                                        </div>
                                                        <div className="label-container">
                                                            <input type="radio" name="Building Required" />
                                                            <span>No</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="grid-template-col">
                                                    <div className="radio-block-group">
                                                        <label>Foundation Issues</label>
                                                        <div className="label-container">
                                                            <input type="radio" name="Foundation Issues" />
                                                            <span>Yes</span>
                                                        </div>
                                                        <div className="label-container">
                                                            <input type="radio" name="Foundation Issues" checked onChange={handleChange} />
                                                            <span>No</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="grid-template-col">
                                                    <div className="radio-block-group">
                                                        <label>Mold</label>
                                                        <div className="label-container">
                                                            <input type="radio" name="Mold" checked onChange={handleChange} />
                                                            <span>Yes</span>
                                                        </div>
                                                        <div className="label-container">
                                                            <input type="radio" name="Mold" />
                                                            <span>No</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="grid-template-col">
                                                    <div className="radio-block-group">
                                                        <label>Fire Damaged</label>
                                                        <div className="label-container">
                                                            <input type="radio" name="Fire Damaged" checked onChange={handleChange} />
                                                            <span>Yes</span>
                                                        </div>
                                                        <div className="label-container">
                                                            <input type="radio" name="Fire Damaged" />
                                                            <span>No</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="grid-template-col">
                                                    <div className="radio-block-group">
                                                        <label>Rebuild</label>
                                                        <div className="label-container">
                                                            <input type="radio" name="Rebuild" />
                                                            <span>Yes</span>
                                                        </div>
                                                        <div className="label-container">
                                                            <input type="radio" name="Rebuild" checked onChange={handleChange} />
                                                            <span>No</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="grid-template-col">
                                                    <div className="radio-block-group">
                                                        <label>Squatters</label>
                                                        <div className="label-container">
                                                            <input type="radio" name="Squatters" />
                                                            <span>Yes</span>
                                                        </div>
                                                        <div className="label-container">
                                                            <input type="radio" name="Squatters" checked onChange={handleChange} />
                                                            <span>No</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div className="submit-btn">
                                                <a href="" className="btn btn-fill">Submit Now!</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div className="col-12 col-lg-4 w-30">
                                <form className="form-container">
                                    <div className="outer-heading text-center">
                                        <h3>Upload Multiple Buyer </h3>
                                        <p>Lorem Ipsum is simply dummy text of the printing.</p>
                                    </div>
                                    <div className="upload-single-data">
                                        <div className="upload-files-container">
                                            <div className="drag-file-area">
                                                <button type="button" className="upload-button">
                                                    <img src="images/folder-big.svg" className="img-fluid" alt="" />
                                                </button>
                                                <label className="label d-block">
                                                    <span className="browse-files">
                                                        <input type="file" className="default-file-input"/> 
                                                        <span className="d-block upload-file">Upload your .CSV file</span>
                                                        <span className="browse-files-text">browse Now!</span> 
                                                    </span> 
                                                </label>
                                            </div>
                                            <span className="cannot-upload-message"> <span className="error">error</span> Please select a file first <span className="cancel-alert-button">cancel</span> </span>
                                            <div className="file-block">
                                                <div className="file-info"><span className="file-name"> </span> | <span className="file-size">  </span> </div>
                                                <span className="remove-file-icon">
                                                    {/* <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="20" height="20" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" className=""><g><path d="M424 64h-88V48c0-26.51-21.49-48-48-48h-64c-26.51 0-48 21.49-48 48v16H88c-22.091 0-40 17.909-40 40v32c0 8.837 7.163 16 16 16h384c8.837 0 16-7.163 16-16v-32c0-22.091-17.909-40-40-40zM208 48c0-8.82 7.18-16 16-16h64c8.82 0 16 7.18 16 16v16h-96zM78.364 184a5 5 0 0 0-4.994 5.238l13.2 277.042c1.22 25.64 22.28 45.72 47.94 45.72h242.98c25.66 0 46.72-20.08 47.94-45.72l13.2-277.042a5 5 0 0 0-4.994-5.238zM320 224c0-8.84 7.16-16 16-16s16 7.16 16 16v208c0 8.84-7.16 16-16 16s-16-7.16-16-16zm-80 0c0-8.84 7.16-16 16-16s16 7.16 16 16v208c0 8.84-7.16 16-16 16s-16-7.16-16-16zm-80 0c0-8.84 7.16-16 16-16s16 7.16 16 16v208c0 8.84-7.16 16-16 16s-16-7.16-16-16z" fill="#000000" data-original="#000000" className=""></path></g></svg> */}
                                                </span>
                                                <div className="progress-bar"> </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="submit-btn my-30">
                                        <a href="" className="btn btn-fill">Submit Now!</a>
                                    </div>
                                </form>
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

        </>
    )
    
}
  
export default AddBuyerDetails;