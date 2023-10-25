import React from "react";
import BuyerHeader from "../../partials/Layouts/BuyerHeader";
import Footer from "../../partials/Layouts/Footer";
import Select from "react-select";

const EditBuyerProfile = () => {
  return (
    <>
      <BuyerHeader />
      <section className="main-section position-relative pt-4 pb-120">
        <div className="container position-relative">
          <div className="back-block">
            <div className="row">
              <div className="col-12 col-sm-4 col-md-4 col-lg-4">
                <a href="#" className="back">
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
                    ></path>
                    <path
                      d="M5.9 11L1 6L5.9 1"
                      stroke="#0A2540"
                      strokeWidth="1.5"
                      strokeLinecap="round"
                      strokeLinejoin="round"
                    ></path>
                  </svg>
                  Back
                </a>
              </div>
              <div className="col-12 col-sm-4 col-md-4 col-lg-4">
                <h6 className="center-head text-center mb-0">Edit Profile</h6>
              </div>
            </div>
          </div>
          <div className="card-box">
            <div className="row">
              <div className="col-12 col-lg-4">
                <form className="form-container">
                  <div className="outer-heading text-center">
                    <h3 className="mb-0">Edit Profile Picture </h3>
                  </div>
                  <div className="upload-photo">
                    <div className="containers">
                      <div className="imageWrapper">
                        <img
                          className="image img-fluid"
                          src="assets/images/avtar-big.png"
                        />
                      </div>
                    </div>
                    <button className="file-upload">
                      <input type="file" className="file-input" />
                      upload Your Image
                    </button>
                  </div>
                </form>
              </div>
              <div className="col-12 col-lg-8">
                <div className="card-box-inner">
                  <h3>Edit Profile</h3>
                  <p>Lorem Ipsum is simply dummy text of the</p>
                  <form>
                    <div className="card-box-blocks">
                      <div className="row">
                        <div className="col-12 col-sm-6 col-md-6 col-lg-6">
                          <label>
                            First Name<span>*</span>
                          </label>
                          <div className="form-group">
                            <input
                              type="text"
                              name=""
                              className="form-control"
                              placeholder="First Name"
                              value="Amit"
                            />
                          </div>
                        </div>
                        <div className="col-12 col-sm-6 col-md-6 col-lg-6">
                          <label>
                            last Name<span>*</span>
                          </label>
                          <div className="form-group">
                            <input
                              type="text"
                              name=""
                              className="form-control"
                              placeholder="last Name"
                              value="Kr"
                            />
                          </div>
                        </div>
                        <div className="col-12 col-sm-6 col-md-6 col-lg-6">
                          <label>
                            Email<span>*</span>
                          </label>
                          <div className="form-group">
                            <input
                              type="email"
                              name=""
                              className="form-control"
                              placeholder="Email"
                              value="seller1548@gmail.com"
                            />
                          </div>
                        </div>
                        <div className="col-12 col-sm-6 col-md-6 col-lg-6">
                          <label>
                            Phone Number<span>*</span>
                          </label>
                          <div className="form-group">
                            <input
                              type="text"
                              name=""
                              className="form-control"
                              placeholder="Phone Number"
                              value="+91 123456789055"
                            />
                          </div>
                        </div>
                        <div className="col-12 col-sm-6 col-md-6 col-lg-6">
                          <label>
                            State<span>*</span>
                          </label>
                          <div className="form-group">
                            <Select
                              name="property_type"
                              defaultValue=""
                              className="select"
                              isClearable={true}
                              isSearchable={true}
                              isDisabled={false}
                              isLoading={false}
                              isRtl={false}
                              placeholder="Select State"
                              closeMenuOnSelect={true}
                            />
                          </div>
                        </div>
                        <div className="col-12 col-sm-6 col-md-6 col-lg-6">
                          <label>
                            City<span>*</span>
                          </label>
                          <div className="form-group">
                            <Select
                              name="city"
                              defaultValue=""
                              className="select"
                              isClearable={true}
                              isSearchable={true}
                              isDisabled={false}
                              isLoading={false}
                              isRtl={false}
                              placeholder="Select City"
                              closeMenuOnSelect={true}
                            />
                          </div>
                        </div>
                        <div className="col-12 col-sm-6 col-md-6 col-lg-4">
                          <label>Company/LLC</label>
                          <div className="form-group">
                            <input
                              type="text"
                              name=""
                              className="form-control"
                              placeholder="Enter Company/LLC"
                            />
                          </div>
                        </div>
                        <div className="col-12 col-sm-6 col-md-6 col-lg-4">
                          <label>MLS Status</label>
                          <div className="form-group">
                            <Select
                              name="mls_status"
                              defaultValue=""
                              className="select"
                              isClearable={true}
                              isSearchable={true}
                              isDisabled={false}
                              isLoading={false}
                              isRtl={false}
                              placeholder="Select City"
                              closeMenuOnSelect={true}
                            />
                          </div>
                        </div>
                        <div className="col-12 col-sm-6 col-md-6 col-lg-4">
                          <label>Contact Preference</label>
                          <div className="form-group">
                            <Select
                              name="contact_preference"
                              defaultValue=""
                              className="select"
                              isClearable={true}
                              isSearchable={true}
                              isDisabled={false}
                              isLoading={false}
                              isRtl={false}
                              placeholder="Select Contact Preference"
                              closeMenuOnSelect={true}
                            />
                          </div>
                        </div>
                        <div className="col-12 col-lg-6">
                          <div className="form-group">
                            <label>
                              Property Type<span>*</span>
                            </label>
                            <div className="form-group">
                              <Select
                                name="property_type"
                                defaultValue=""
                                className="select"
                                isClearable={true}
                                isSearchable={true}
                                isDisabled={false}
                                isLoading={false}
                                isRtl={false}
                                placeholder="Select Property Type"
                                closeMenuOnSelect={true}
                              />
                            </div>
                          </div>
                        </div>

                        <div className="col-12 col-lg-6">
                          <label>
                            Purchase Method<span>*</span>
                          </label>
                          <div className="form-group">
                            <Select
                              name="purchase_method"
                              defaultValue=""
                              className="select"
                              isClearable={true}
                              isSearchable={true}
                              isDisabled={false}
                              isLoading={false}
                              isRtl={false}
                              placeholder="Select Purchase Method"
                              closeMenuOnSelect={true}
                            />
                          </div>
                        </div>
                        <div className="col-12 col-sm-6 col-md-6 col-lg-4">
                          <label>
                            Bedroom (min)<span>*</span>
                          </label>
                          <div className="form-group">
                            <input
                              type="text"
                              name=""
                              className="form-control"
                              placeholder="Bedroom (min)"
                              value="02"
                            />
                          </div>
                        </div>
                        <div className="col-12 col-sm-6 col-md-6 col-lg-4">
                          <label>
                            Bedroom (max)<span>*</span>
                          </label>
                          <div className="form-group">
                            <input
                              type="text"
                              name=""
                              className="form-control"
                              placeholder="Bedroom (max)"
                              value="03"
                            />
                          </div>
                        </div>
                        <div className="col-12 col-sm-6 col-md-6 col-lg-4">
                          <label>
                            Bath (min)<span>*</span>
                          </label>
                          <div className="form-group">
                            <input
                              type="text"
                              name=""
                              className="form-control"
                              placeholder="Bath (min)"
                              value="02"
                            />
                          </div>
                        </div>
                        <div className="col-12 col-sm-6 col-md-6 col-lg-4">
                          <label>
                            Bath (max)<span>*</span>
                          </label>
                          <div className="form-group">
                            <input
                              type="text"
                              name=""
                              className="form-control"
                              placeholder="Bath (max)"
                              value="03"
                            />
                          </div>
                        </div>
                        <div className="col-12 col-sm-6 col-md-6 col-lg-4">
                          <label>
                            Sq Ft Min<span>*</span>
                          </label>
                          <div className="form-group">
                            <input
                              type="text"
                              name=""
                              className="form-control"
                              placeholder="Sq Ft Min"
                              value="02"
                            />
                          </div>
                        </div>
                        <div className="col-12 col-sm-6 col-md-6 col-lg-4">
                          <label>
                            Sq Ft Max<span>*</span>
                          </label>
                          <div className="form-group">
                            <input
                              type="text"
                              name=""
                              className="form-control"
                              placeholder="Sq Ft Max"
                              value="03"
                            />
                          </div>
                        </div>
                        <div className="col-12 col-sm-6 col-md-6 col-lg-4">
                          <label>
                            Lot Size Sq Ft (min)<span>*</span>
                          </label>
                          <div className="form-group">
                            <input
                              type="text"
                              name=""
                              className="form-control"
                              placeholder="Lot Size Sq Ft (min)"
                              value="02"
                            />
                          </div>
                        </div>
                        <div className="col-12 col-sm-6 col-md-6 col-lg-4">
                          <label>
                            Lot Size Sq Ft (max)<span>*</span>
                          </label>
                          <div className="form-group">
                            <input
                              type="text"
                              name=""
                              className="form-control"
                              placeholder="Lot Size Sq Ft (max)"
                              value="03"
                            />
                          </div>
                        </div>
                        <div className="col-12 col-sm-6 col-md-6 col-lg-4">
                          <label>
                            Year Built (min)<span>*</span>
                          </label>
                          <div className="form-group">
                            <input
                              type="text"
                              name=""
                              className="form-control"
                              placeholder="Year Built (min)"
                              value="2023"
                            />
                          </div>
                        </div>
                        <div className="col-12 col-sm-6 col-md-6 col-lg-4">
                          <label>
                            Year Built (max)<span>*</span>
                          </label>
                          <div className="form-group">
                            <input
                              type="text"
                              name=""
                              className="form-control"
                              placeholder="Year Built (max)"
                              value="2024"
                            />
                          </div>
                        </div>
                        <div className="col-12 col-sm-6 col-md-6 col-lg-4">
                          <label>
                            Stories (Min)<span>*</span>
                          </label>
                          <div className="form-group">
                            <input
                              type="text"
                              name=""
                              className="form-control"
                              placeholder="Enter Stories (Min)"
                            />
                          </div>
                        </div>
                        <div className="col-12 col-sm-6 col-md-6 col-lg-4">
                          <label>
                            Stories (Max)<span>*</span>
                          </label>
                          <div className="form-group">
                            <input
                              type="text"
                              name=""
                              className="form-control"
                              placeholder="Enter Stories (Max)"
                            />
                          </div>
                        </div>
                        <div className="col-12 col-sm-6 col-md-6 col-lg-6">
                          <label>
                            Price (Min)<span>*</span>
                          </label>
                          <div className="form-group">
                            <input
                              type="text"
                              name=""
                              className="form-control"
                              placeholder="Price (Min)"
                            />
                          </div>
                        </div>
                        <div className="col-12 col-sm-6 col-md-6 col-lg-6">
                          <label>
                            Price (Max)<span>*</span>
                          </label>
                          <div className="form-group">
                            <input
                              type="text"
                              name=""
                              className="form-control"
                              placeholder="Price (Max)"
                            />
                          </div>
                        </div>
                        <div className="col-12 col-lg-6">
                          <label>
                            Parking<span>*</span>
                          </label>
                          <div className="form-group">
                            <Select
                              name="parking"
                              defaultValue=""
                              className="select"
                              isClearable={true}
                              isSearchable={true}
                              isDisabled={false}
                              isLoading={false}
                              isRtl={false}
                              placeholder="Select Parking"
                              closeMenuOnSelect={true}
                            />
                          </div>
                        </div>
                        <div className="col-12 col-lg-6">
                          <label>
                            Buyer Type<span>*</span>
                          </label>
                          <div className="form-group">
                            <Select
                              name="buyer_type"
                              defaultValue=""
                              className="select"
                              isClearable={true}
                              isSearchable={true}
                              isDisabled={false}
                              isLoading={false}
                              isRtl={false}
                              placeholder="Select Buyer"
                              closeMenuOnSelect={true}
                            />
                          </div>
                        </div>
                        <div className="col-12 col-lg-12">
                          <div className="form-group">
                            <label>Location Flaws</label>
                            <div className="form-group">
                              <Select
                                name="location_flaws"
                                defaultValue=""
                                className="select"
                                isClearable={true}
                                isSearchable={true}
                                isDisabled={false}
                                isLoading={false}
                                isRtl={false}
                                placeholder="Select Location"
                                closeMenuOnSelect={true}
                              />
                            </div>
                          </div>
                        </div>
                      </div>
                      <div className="column--grid">
                        <div className="grid-template-col">
                          <div className="radio-block-group">
                            <label>Solar</label>
                            <div className="label-container">
                              <input type="radio" name="Solar" checked />
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
                              <input type="radio" name="Pool" checked />
                              <span>No</span>
                            </div>
                          </div>
                        </div>
                        <div className="grid-template-col">
                          <div className="radio-block-group">
                            <label>Septic</label>
                            <div className="label-container">
                              <input type="radio" name="Septic" checked />
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
                              <input type="radio" name="Well" checked />
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
                            <label>HOA</label>
                            <div className="label-container">
                              <input type="radio" name="HOA" />
                              <span>Yes</span>
                            </div>
                            <div className="label-container">
                              <input type="radio" name="HOA" checked />
                              <span>No</span>
                            </div>
                          </div>
                        </div>
                        <div className="grid-template-col">
                          <div className="radio-block-group">
                            <label>Age restriction</label>
                            <div className="label-container">
                              <input
                                type="radio"
                                name="Age restriction"
                                checked
                              />
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
                              <input
                                type="radio"
                                name="Rental Restriction"
                                checked
                              />
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
                            <label>Post-Possession</label>
                            <div className="label-container">
                              <input
                                type="radio"
                                name="Post-Possession"
                                checked
                              />
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
                            <label>Tenant Conveys</label>
                            <div className="label-container">
                              <input type="radio" name="Tenant Conveys" />
                              <span>Yes</span>
                            </div>
                            <div className="label-container">
                              <input
                                type="radio"
                                name="Tenant Conveys"
                                checked
                              />
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
                              <input type="radio" name="Squatters" checked />
                              <span>No</span>
                            </div>
                          </div>
                        </div>
                        <div className="grid-template-col">
                          <div className="radio-block-group">
                            <label>Building Required</label>
                            <div className="label-container">
                              <input
                                type="radio"
                                name="Building Required"
                                checked
                              />
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
                            <label>Rebuild</label>
                            <div className="label-container">
                              <input type="radio" name="Rebuild" />
                              <span>Yes</span>
                            </div>
                            <div className="label-container">
                              <input type="radio" name="Rebuild" checked />
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
                              <input
                                type="radio"
                                name="Foundation Issues"
                                checked
                              />
                              <span>No</span>
                            </div>
                          </div>
                        </div>
                        <div className="grid-template-col">
                          <div className="radio-block-group">
                            <label>Mold</label>
                            <div className="label-container">
                              <input type="radio" name="Mold" checked />
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
                              <input type="radio" name="Fire Damaged" checked />
                              <span>Yes</span>
                            </div>
                            <div className="label-container">
                              <input type="radio" name="Fire Damaged" />
                              <span>No</span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div className="submit-btn">
                        <a href="" className="btn btn-fill">
                          Submit Now!
                        </a>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <Footer />
    </>
  );
};
export default EditBuyerProfile;
