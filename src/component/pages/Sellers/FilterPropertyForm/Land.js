import React from "react";
import Select from "react-select";
import MultiSelect from "../../../partials/Select2/MultiSelect";
// import AutoSuggestionAddress from "./AutoSuggestionAddress";
import GoogleMapAutoAddress from "../../../partials/GoogleMapAutoAddress";
import PropertyAttachments from "../../../partials/PropertyAttachments";

const Land = ({ data }) => {
  return (
    <>
      <div className="row">
        <GoogleMapAutoAddress dataObj={data} />
        {/* <div className="col-12 col-lg-12">
          <AutoSuggestionAddress data={data} />
        </div>
        <div className="col-12 col-lg-12">
          <label>State<span>*</span></label>
          <div className="form-group">
            <Select
              name="state"
              defaultValue=""
              options={data.stateOptions}
              onChange={(item) => data.getCities(item)}
              className="select"
              isClearable={true}
              isSearchable={true}
              isDisabled={false}
              isLoading={false}
              value={data.state}
              isRtl={false}
              placeholder="Select State"
              closeMenuOnSelect={true}
            />
            {data.renderFieldError("state")}
          </div>
        </div>
        <div className="col-12 col-lg-12">
          <label>City<span>*</span></label>
          <div className="form-group">
            <Select
              name="city"
              defaultValue=""
              options={data.cityOptions}
              onChange={(item) => data.setCity(item)}
              className="select"
              isClearable={true}
              isSearchable={true}
              isDisabled={false}
              isLoading={false}
              value={data.city}
              isRtl={false}
              placeholder="Select City"
              closeMenuOnSelect={true}
            />
            {data.renderFieldError("city")}
          </div>
        </div>
        <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-4">
          <label>Zip Code</label>
          <div className="form-group">
            <input
              type="text"
              name="zip_code"
              className="form-control"
              placeholder="Zip Code"
              value={data.zipCode}
              onChange={(e) => data.setZipCode(e.target.value)}
            />
            {data.renderFieldError("zip_code")}
          </div>
        </div> */}
        <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-4">
          <label>Lot Size Sq Ft<span>*</span></label>
          <div className="form-group">
            <input
              type="number"
              name="lot_size"
              className="form-control"
              placeholder="Lot Size Sq Ft"
              value={data.lotSize}
              onChange={(e) => data.setLotSize(e.target.value)}
            />
            {data.renderFieldError("lot_size")}
          </div>
        </div>
        <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-4">
          <label>Price<span>*</span></label>
          <div className="form-group">
            <input
              type="number"
              name="price"
              className="form-control"
              placeholder="Enter Your Price"
              value={data.price}
              onChange={(e) => data.setPrice(e.target.value)}
            />
            {data.renderFieldError("price")}
          </div>
        </div>

        <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-4">
          <label>Zoning<span>*</span></label>
          <div className="form-group">
            <MultiSelect
              name="zoning"
              options={data.zoningOption}
              placeholder="Select Zoning"
              setSelectValues={data.setZoningValue}
              setMultiselectOption={data.setZoning}
            />
            {data.renderFieldError("zoning")}
          </div>
        </div>
        <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-4">
          <label>Utilities<span>*</span></label>
          <div className="form-group">
            <Select
              name="utilities"
              options={data.utilitiesOption}
              placeholder="Select Utilities"
              setMultiselectOption={data.setUtilitiesValue}
              isClearable={true}
            />
            {data.renderFieldError("utilities")}
          </div>
        </div>
        <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-4">
          <label>Sewage<span>*</span></label>
          <div className="form-group">
            <Select
              name="sewer"
              options={data.sewerOption}
              placeholder="Select Sewage"
              setMultiselectOption={data.setSewerValue}
              isClearable={true}
            />
            {data.renderFieldError("sewer")}
          </div>
        </div>
        <div className="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
          <div className="form-group">
            <label>Location Flaws</label>
            <div className="form-group">
              <MultiSelect
                name="property_flaw"
                options={data.locationFlawsOption}
                placeholder="Select Location Flaws"
                setMultiselectOption={data.setLocationFlaw}
                selectValue={data.locationFlawsValue}
                setSelectValues={data.setLocationFlawsValue}
              />
              {data.renderFieldError("property_flaw")}
            </div>
          </div>
        </div>
        <div className="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
          <label>
            Purchase Method<span>*</span>
          </label>
          <div className="form-group">
            <MultiSelect
              name="purchase_method"
              options={data.purchaseMethodsOption}
              placeholder="Select Purchase Method"
              setMultiselectOption={data.setPurchaseMethod}
              showCreative={data.setShowCreativeFinancing}
              selectValue={data.purchaseMethodsValue}
              setSelectValues={data.setPurchaseMethodsValue}
            />
            {data.renderFieldError("purchase_method")}
          </div>
        </div>
        <div className="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
          <label>
            MLS Status<span>*</span>
          </label>
          <div className="form-group">
            <Select
              name="market_preferance"
              defaultValue=""
              onChange={(item) => data.setMarketPreferance(item)}
              options={data.marketPreferanceOption}
              className="select"
              isClearable={true}
              isSearchable={true}
              isDisabled={false}
              isLoading={false}
              value={data.marketPreferance}
              isRtl={false}
              placeholder="Select MLS Status"
              closeMenuOnSelect={true}
            />
            {data.renderFieldError("market_preferance")}
          </div>
        </div>
      </div>
      <PropertyAttachments data={data}/>
      {data.showCreativeFinancing && (
        <div className="block-divide">
          <h5>Creative Financing</h5>
          <div className="row">
            <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
              <label>Down Payment (%)<span>*</span></label>
              <div className="form-group">
                <input
                  type="number"
                  name="max_down_payment_percentage"
                  className="form-control"
                  placeholder="Down Payment (%)"
                  value={data.downPaymentPercentage}
                  onChange={(e) =>
                    data.setDownPaymentPercentage(e.target.value)
                  }
                />
                {data.renderFieldError("max_down_payment_percentage")}
              </div>
            </div>
            <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
              <label>Down Payment ($)<span>*</span></label>
              <div className="form-group">
                <input
                  type="number"
                  name="max_down_payment_money"
                  className="form-control"
                  placeholder="Down Payment ($)"
                  value={data.downPaymentMoney}
                  onChange={(e) => data.setDownPaymentMoney(e.target.value)}
                />
                {data.renderFieldError("max_down_payment_money")}
              </div>
            </div>
            <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
              <label>Interest Rate (%)<span>*</span></label>
              <div className="form-group">
                <input
                  type="number"
                  name="max_interest_rate"
                  className="form-control"
                  placeholder="Interest Rate (%)"
                  value={data.interestRate}
                  onChange={(e) => data.setInterestRate(e.target.value)}
                />
                {data.renderFieldError("max_interest_rate")}
              </div>
            </div>
            <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
              <label>Balloon Payment <span>*</span></label>
              <div className="form-group">
                <div className="radio-block">
                  <div className="label-container">
                    <input
                      type="radio"
                      name="balloon_payment"
                      value="1"
                      id="balloon_payment_yes"
                      checked={data.balloonPayment === '1' ? "checked" : ""}
                      onChange={(e) => data.setBalloonPayment(e.target.value)}
                    />
                    <label className="mb-0" htmlFor="balloon_payment_yes">
                      Yes
                    </label>
                  </div>
                  <div className="label-container">
                    <input
                      type="radio"
                      name="balloon_payment"
                      value="0"
                      id="balloon_payment_no"
                      checked={data.balloonPayment === '0' ? "checked" : ""}
                      onChange={(e) => data.setBalloonPayment(e.target.value)}
                    />
                    <label className="mb-0" htmlFor="balloon_payment_no">
                      No
                    </label>
                  </div>
                </div>
                {data.renderFieldError("balloon_payment")}
              </div>
            </div>
          </div>
        </div>
      )}
    </>
  );
};
export default Land;
