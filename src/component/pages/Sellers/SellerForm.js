import React, { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import Header from "../../partials/Layouts/Header";
import Footer from "../../partials/Layouts/Footer";
import { useAuth } from "../../../hooks/useAuth";
import Select from "react-select";
import { useFormError } from "../../../hooks/useFormError";
import axios from "axios";
import MiniLoader from "../../partials/MiniLoader";
import { toast } from "react-toastify";

import ResultPage from "./ResultPage";
import CommercialRetail from "./FilterPropertyForm/CommercialRetail";
import Condo from "./FilterPropertyForm/Condo";
import Land from "./FilterPropertyForm/Land";
import Manufactured from "./FilterPropertyForm/Manufactured";
import MultiFamilyCommercial from "./FilterPropertyForm/MultiFamilyCommercial";
import MultiFamilyResidential from "./FilterPropertyForm/MultiFamilyResidential";
import SingleFamily from "./FilterPropertyForm/SingleFamily";
import TownHouse from "./FilterPropertyForm/TownHouse";
import MobileHomePark from "./FilterPropertyForm/MobileHomePark";
import HotelMotel from "./FilterPropertyForm/HotelMotel";
import GoogleReCaptcha from "../../partials/SocialLogin/GoogleReCaptcha";
import { Image } from "react-bootstrap";
import AddAddressAndRadius from "../../partials/PropertyRadiusSearch";


const SellerForm = () => {
  const { getTokenData, setLogout } = useAuth();
  const [isLoader, setIsLoader] = useState(true);
  const [advertisementData, setAdvertisementData] = useState([]);
  const [videoUrl, setVideoUrl] = useState("");

  const [captchaVerified, setCaptchaVerified] = useState(false);
  const [recaptchaError, setRecaptchaError] = useState("");

  const [isFiltered, setIsFiltered] = useState(false);
  const [lastSearchedLogId, setLastSearchedLogId] = useState('');
  const [isSearchForm, setIsSearchForm] = useState("");

  const { setErrors, renderFieldError } = useFormError();

  const [address, setAddress] = useState("");
  const [country, setCountry] = useState("");
  const [state, setState] = useState("");
  const [city, setCity] = useState("");
  const [zipCode, setZipCode] = useState("");

  const [price, setPrice] = useState("");
  const [ofStories, setOfStories] = useState("");
  const [bedroom, setBedroom] = useState("");
  const [room, setRoom] = useState("");
  const [bath, setBath] = useState("");
  const [size, setSize] = useState("");
  const [lotSize, setLotSize] = useState("");
  const [yearBuild, setYearBuild] = useState("");

  const [arv, setArv] = useState("");

  const [parking, setParking] = useState([]);
  const [park, setPark] = useState([]);
  const [locationFlaw, setLocationFlaw] = useState([]);
  const [purchaseMethod, setPurchaseMethod] = useState([]);
  const [downPaymentPercentage, setDownPaymentPercentage] = useState("");
  const [downPaymentMoney, setDownPaymentMoney] = useState("");
  const [interestRate, setInterestRate] = useState("");
  const [balloonPayment, setBalloonPayment] = useState("0");
  const [zoning, setZoning] = useState([]);
  const [utilities, setUtilities] = useState([]);
  const [sewer, setSewer] = useState([]);
  const [marketPreferance, setMarketPreferance] = useState([]);
  const [contactPreferance, setContactPreferance] = useState([]);
  const [attachments,setAttachments] = useState([]);
  const [url,setUrl] = useState("");


  const [solar, setSolar] = useState('0');
  const [pool, setPool] = useState('0');
  const [septic, setSeptic] = useState('0');
  const [well, setWell] = useState('0');
  const [hoa, setHoa] = useState('0');
  const [ageRestriction, setAgeRestriction] = useState('0');
  const [rentalRestriction, setRentalRestriction] = useState('0');
  const [postPossession, setPostPossession] = useState('0');
  const [tenant, setTenant] = useState('0');
  const [squatters, setSquatters] = useState('0');
  const [buildingRequired, setBuildingRequired] = useState('0');
  const [rebuild, setRebuild] = useState('0');
  const [foundationIssues, setFoundationIssues] = useState('0');
  const [mold, setMold] = useState('0');
  const [fireDamaged, setFireDamaged] = useState('0');
  const [permanentlyAffixed, setPermanentlyAffixed] = useState('0');

  const [totalUnits, setTotalUnits] = useState("");
  const [buildingClass, setBuildingClass] = useState("");
  const [valueAdd, setValueAdd] = useState('0');

  const [stateOptions, setStateOptions] = useState([]);
  const [cityOptions, setCityOptions] = useState([]);

  const [purchaseMethodsOption, setPurchaseMethodsOption] = useState([]);
  const [parkingOption, setParkingOption] = useState([]);
  const [parkOption, setParkOption] = useState([]);
  const [locationFlawsOption, setLocationFlawsOption] = useState([]);
  const [propertyTypeOption, setPropertyTypeOption] = useState([]);
  const [buildingClassOption, setBuildingClassOption] = useState([]);
  const [marketPreferanceOption, setMarketPreferanceOption] = useState([]);
  const [contactPreferanceOption, setContactPreferanceOption] = useState([]);
  const [zoningOption, setZoningOption] = useState([]);
  const [sewerOption, setSewerOption] = useState([]);
  const [utilitiesOption, setUtilitiesOption] = useState([]);

  const [showCreativeFinancing, setShowCreativeFinancing] = useState(false);

  const [zoningValue, setZoningValue] = useState([]);
  const [utilitiesValue, setUtilitiesValue] = useState([]);
  const [sewerValue, setSewerValue] = useState([]);

  const [propertyTypeValue, setPropertyTypeValue] = useState([]);
  const [locationFlawsValue, setLocationFlawsValue] = useState([]);
  const [purchaseMethodsValue, setPurchaseMethodsValue] = useState([]);

  const [loading, setLoading] = useState(false);
  const [filterFormData, setFilterFormData] = useState([]);
  useEffect(() => {
    // redirect from payment completed page
    const paramsObject = decodeURI(window.location.search).replace("?", "");
    if (paramsObject === "search") {
      getLastSearch();
    }
    getOptionsValues();
    getVideoUrl();
  }, []);

  const apiUrl = process.env.REACT_APP_API_URL;

  let headers = {
    Accept: "application/json",
    Authorization: "Bearer " + getTokenData().access_token,
    "auth-token": getTokenData().access_token,
  };
  const getOptionsValues = async () => {
    try {
      let response = await axios.get(apiUrl + "search-buyer-form-details", {
        headers: headers,
      });
      if (response.data.status) {
        let result = response.data.result;
        setPurchaseMethodsOption(result.purchase_methods);
        setLocationFlawsOption(result.location_flaws);
        setParkingOption(result.parking_values);
        setParkOption(result.park);
        setStateOptions(result.states);
        setPropertyTypeOption(result.property_types);
        setBuildingClassOption(result.building_class_values);
        setMarketPreferanceOption(result.market_preferances);
        setContactPreferanceOption(result.contact_preferances);
        setZoningOption(result.zonings);
        setSewerOption(result.sewers);
        setUtilitiesOption(result.utilities);
        setIsLoader(false);
      }
    } catch (error) {
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

  const getVideoUrl = () => {
    try {
      const apiUrl = process.env.REACT_APP_API_URL;
      let headers = {
        Accept: "application/json",
        Authorization: "Bearer " + getTokenData().access_token,
        "auth-token": getTokenData().access_token,
      };
      axios
        .get(apiUrl + "getVideo/upload_buyer_video", { headers: headers })
        .then((response) => {
          let videoLink = response.data.videoDetails.video.video_link;
          setVideoUrl(videoLink);
        });
    } catch (error) {
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

  const getStates = (country_id) => {
    if (country_id === null) {
      setCountry([]);
      setState([]);
      setCity([]);
      setStateOptions([]);
      setCityOptions([]);
    } else {
      axios
        .post(
          apiUrl + "getStates",
          { country_id: country_id },
          { headers: headers }
        )
        .then((response) => {
          let result = response.data.options;

          setCountry([]);
          setState([]);
          setCity([]);
          setCityOptions([]);
          setCountry(country_id);
          setStateOptions(result);
        });
    }
  };

  const getCities = (state_id) => {
    if (state_id === null) {
      setState([]);
      setCity([]);

      setCityOptions([]);
    } else {
      let country_id = { country };
      axios
        .post(
          apiUrl + "getCities",
          { state_id: state_id, country_id: country_id },
          { headers: headers }
        )
        .then((response) => {
          let result = response.data.options;

          setState([]);
          setCity([]);

          setState(state_id);
          setCityOptions(result);
        });
    }
  };
  const makeStateBlank = () => {
    setAddress("");
    setCountry("");
    setState("");
    setCity("");
    setZipCode("");

    setPrice("");
    setOfStories("");
    setBedroom("");
    setRoom("");
    setBath("");
    setSize("");
    setLotSize("");
    setYearBuild("");
    setArv("");
    setParking([]);
    setPark([]);
    setTotalUnits("");
    setBuildingClass("");
    setValueAdd('0');

    setPurchaseMethod([]);

    setDownPaymentPercentage("");
    setDownPaymentMoney("");
    setInterestRate("");
    setBalloonPayment("0");
    setZoning([]);
    setUtilities([]);
    setSewer([]);
    setMarketPreferance([]);
    setContactPreferance([]);

    setLocationFlaw([]);
    setAttachments([]);
    setUrl("");

    setSolar('1');
    setPool('1');
    setSeptic('1');
    setWell('1');
    setHoa('1');
    setAgeRestriction('1');
    setRentalRestriction('1');
    setPostPossession('1');
    setTenant('1');
    setSquatters('1');
    setBuildingRequired('1');
    setRebuild('1');
    setFoundationIssues('1');
    setMold('1');
    setFireDamaged('1');
    setPermanentlyAffixed('1');

    setBalloonPayment('1');
    setShowCreativeFinancing(false);
    setLocationFlawsValue([]);

    setPurchaseMethodsValue([]);

    setCityOptions([]);
  };

  const handlePropertyTypeChange = (value) => {
    makeStateBlank();
    setErrors(null);
    if (value === null) {
      setPropertyTypeValue("");
      setIsSearchForm("");
    } else {
      let propValue = value.value;
      setPropertyTypeValue(value);
      setIsSearchForm(propValue);
    }
  };
  const submitSearchBuyerForm = (e) => {
    e.preventDefault();

    if (!captchaVerified) {
        setLoading(false);
        setRecaptchaError("Please complete reCAPTCHA verification.");
        return false;
    }

    setErrors(null);
    setLoading(true);

    const data = new FormData(e.target);

    // Add arrays to FormData
    locationFlaw.forEach((item) => data.append("property_flaw[]", item));
    purchaseMethod.forEach((item) => data.append("purchase_method[]", item));
    if (Array.isArray(zoning)) zoning.forEach((item) => data.append("zoning[]", item));
    
    // Append other fields
    data.append("filterType", "search_page");
    data.append("activeTab", "my_buyers");
    // data.append("state[]", state.value);

    // // Append `city` values correctly
    // city.forEach((item) => {
    //     data.append("city[]", item.value); // Using "[]" to denote array
    // });

    // Add attachments
    attachments.forEach((file) => data.append("attachments[]", file));
    // Submit FormData
    buyBoxSearch(data);
    setFilterFormData(data);

};


  const dataObj = {
    address,
    setAddress,
    country,
    setCountry,
    state,
    setState,
    city,
    setCity,
    zipCode,
    setZipCode,
    price,
    setPrice,
    ofStories,
    setOfStories,
    bedroom,
    setBedroom,
    room,
    setRoom,
    bath,
    setBath,
    size,
    setSize,
    lotSize,
    setLotSize,
    yearBuild,
    setYearBuild,
    parking,
    setParking,
    park,
    setPark,
    totalUnits,
    setTotalUnits,
    buildingClass,
    setBuildingClass,
    valueAdd,
    setValueAdd,
    arv,
    setArv,
    purchaseMethod,
    setPurchaseMethod,

    downPaymentPercentage,
    setDownPaymentPercentage,
    downPaymentMoney,
    setDownPaymentMoney,
    interestRate,
    setInterestRate,
    balloonPayment,
    setBalloonPayment,
    zoning,
    setZoning,
    utilities,
    setUtilities,
    sewer,
    setSewer,
    marketPreferance,
    setMarketPreferance,
    contactPreferance,
    setContactPreferance,
    locationFlaw,
    setLocationFlaw,
    attachments,
    setAttachments,
    url,
    setUrl,

    solar,
    setSolar,
    pool,
    setPool,
    septic,
    setSeptic,
    well,
    setWell,
    ageRestriction,
    setAgeRestriction,
    rentalRestriction,
    setRentalRestriction,
    hoa,
    setHoa,
    tenant,
    setTenant,
    postPossession,
    setPostPossession,
    buildingRequired,
    setBuildingRequired,
    foundationIssues,
    setFoundationIssues,
    mold,
    setMold,
    fireDamaged,
    setFireDamaged,
    permanentlyAffixed,
    setPermanentlyAffixed,
    rebuild,
    setRebuild,
    squatters,
    setSquatters,

    renderFieldError,

    stateOptions,
    cityOptions,

    getStates,
    getCities,

    locationFlawsOption,
    purchaseMethodsOption,
    parkingOption,
    parkOption,
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
  };
  async function buyBoxSearch(formObject) {
    try {
      let response = await axios.post(apiUrl + "buy-box-search", formObject, {
        headers: headers,
        "Content-Type": "multipart/form-data",
      });
      if (response) {
        setLoading(false);
        if (response.data.status) {
          setLastSearchedLogId(response.data.search_log_id);
          localStorage.setItem(
            "filter_buyer_fields",
            JSON.stringify(formObject)
          );
          localStorage.setItem(
            "get_filtered_data",
            JSON.stringify(response.data)
          );
          setIsFiltered(true);
        }
      }
      
    } catch (error) {
      setLoading(false);
      if (error.response) {
        if (error.response.status === 401) {
          setLogout();
        }
        if (error.response.data.errors) {
          setErrors(error.response.data.errors);
        }
        if (error.response.data.error) {
          toast.error(error.response.data.error, {
            position: toast.POSITION.TOP_RIGHT,
          });
        }
      }
    }
  }
  const getLastSearch = async () => {
    try {
      setIsLoader(true);
      let response = await axios.get(apiUrl + "get-last-search", {
        headers: headers,
      });
      if (response.data.status) {
        let result = response.data.data.searchLog;
        buyBoxSearch(result);
        setIsLoader(false);
      }
    } catch (error) {
      setIsLoader(true);
      if (error.response) {
        if (error.response.status === 401) {
          setLogout();
        }
        if (error.response.data.errors) {
          setErrors(error.response.data.errors);
        }
        if (error.response.data.error) {
          toast.error(error.response.data.error, {
            position: toast.POSITION.TOP_RIGHT,
          });
        }
      }
    }
  };

  useEffect(()=>{
    const fetchAdvertisementBannerData = async () => {
      try {
        let headers = {
          Accept: "application/json",
          Authorization: "Bearer " + getTokenData().access_token,
          "auth-token": getTokenData().access_token,
        };
  
        let response = await axios.post(apiUrl + `banner/search-buybox`, {}, {
          headers: headers,
        });
        setAdvertisementData(response.data.data);
      } catch (error) {
          console.log(error)
      }
    } 
    fetchAdvertisementBannerData();
  },[])
  
  return (
    <>
      <Header />
      <section className="main-section position-relative pt-4 pb-120">
        {isLoader ? (
          <div className="loader" style={{ textAlign: "center" }}>
            <img alt="" src="assets/images/loader.svg" />
          </div>
        ) : isFiltered ? (
          <ResultPage setIsFiltered={setIsFiltered} filterFormData={filterFormData} lastSearchedLogId={lastSearchedLogId} address={address} attachments={attachments} size={size} lotSize={lotSize} bath={bath} bedroom={bedroom}/>
        ) : (
          <>
            <div className="container position-relative pat-40">
              <div className="back-block">
                <Link to="/" className="back">
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
                    />
                    <path
                      d="M5.9 11L1 6L5.9 1"
                      stroke="#0A2540"
                      strokeWidth="1.5"
                      strokeLinecap="round"
                      strokeLinejoin="round"
                    />
                  </svg>
                  Back
                </Link>
              </div>
              <div className="card-box">
                <div className="row">
                  <div className="col-12 col-lg-12">
                    <div className="card-box-inner">
                      <h3>Buybox Search</h3>
                      <form method="post" onSubmit={submitSearchBuyerForm} >
                        <div className="card-box-blocks">
                          <div className="row">
                            <div className="col-12 col-lg-8 w-70">
                              <div className="row">
                                <div className="col-12 col-lg-12">
                                  <div className="form-group">
                                    <label>
                                      Property Type<span>*</span>
                                    </label>
                                    <Select
                                      name="property_type"
                                      defaultValue=""
                                      options={propertyTypeOption}
                                      onChange={(item) =>
                                        handlePropertyTypeChange(item)
                                      }
                                      className="select"
                                      isClearable={true}
                                      isSearchable={true}
                                      isDisabled={false}
                                      isLoading={false}
                                      value={propertyTypeValue}
                                      isRtl={false}
                                      placeholder="Select Property Type"
                                      closeMenuOnSelect={true}
                                      //menuIsOpen={true}
                                    />
                                    {renderFieldError("property_type")}
                                  </div>
                                </div>
                              </div>
                              {isSearchForm === 3 && (
                                <CommercialRetail data={dataObj} />
                              )}
                              {isSearchForm === 4 && <Condo data={dataObj} />}
                              {isSearchForm === 7 && <Land data={dataObj} />}
                              {isSearchForm === 8 && (
                                <Manufactured data={dataObj} />
                              )}
                              {isSearchForm === 10 && (
                                <MultiFamilyCommercial data={dataObj} />
                              )}
                              {isSearchForm === 11 && (
                                <MultiFamilyResidential data={dataObj} />
                              )}
                              {isSearchForm === 12 && (
                                <SingleFamily data={dataObj} />
                              )}
                              
                              {isSearchForm === 13 && <TownHouse data={dataObj} />}
                              {isSearchForm === 14 && (
                                <MobileHomePark data={dataObj} />
                              )}
                              {isSearchForm === 15 && <HotelMotel data={dataObj} />}

                              <div className="row mb-2">
                                <GoogleReCaptcha setCaptchaVerified={setCaptchaVerified} recaptchaError={recaptchaError}/>
                              </div>

                              <div className="submit-btn">
                                <button
                                  type="submit"
                                  className="btn btn-fill"
                                  disabled={loading ? "disabled" : ""}
                                >
                                  Submit Now! {loading ? <MiniLoader /> : ""}{" "}
                                </button>
                              </div>
                            </div>
                            <div className="col-12 col-lg-4 w-30">
                              <div className="buybox_search_right">
                                <h6>map Preview</h6>
                                <AddAddressAndRadius />
                                {!advertisementData.is_expired &&  
                                  <div className="advertisement">
                                    <Link>
                                      <Image src={advertisementData.is_expired ? './assets/images/add-1.svg' :  advertisementData.image}/>
                                    </Link>
                                  </div>
                                }
                              </div>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            {/* modal box for video */}
            <div
              className="modal fade"
              id="exampleModal"
              tabIndex="-1"
              aria-labelledby="exampleModalLabel"
              aria-hidden="true"
            >
              <div className="modal-dialog" role="document">
                <div className="modal-content">
                  <div className="modal-header">
                    <h5 className="modal-title" id="exampleModalLabel">
                      Watch The Video
                    </h5>
                    <button
                      type="button"
                      className="btn-close"
                      data-bs-dismiss="modal"
                      aria-label="Close"
                    ></button>
                  </div>
                  <div className="modal-body">
                    {isLoader ? (
                      <div className="video-loader">
                        {" "}
                        <img alt="" src="/assets/images/data-loader.svg" />
                      </div>
                    ) : (
                      <div className="video">
                        <video
                          width="460"
                          height="240"
                          src={videoUrl}
                          loop
                          autoPlay
                          muted
                          controls
                        />
                      </div>
                    )}
                  </div>
                </div>
              </div>
            </div>
          </>
        )}
      </section>

      <Footer />
    </>
  );
};
export default SellerForm;
