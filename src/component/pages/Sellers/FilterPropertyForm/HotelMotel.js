import React, { useState } from "react";
import Select from "react-select";
import MultiSelect from "../../../partials/Select2/MultiSelect";
import SingleSelect from "../../../partials/Select2/SingleSelect";
import AutoSuggestionAddress from "./AutoSuggestionAddress";
import "react-datepicker/dist/react-datepicker.css";
import DatePicker from "react-datepicker";
import GoogleMapAutoAddress from "../../../partials/GoogleMapAutoAddress";
import PropertyAttachments from "../../../partials/PropertyAttachments";

const HotelMotel = ({ data }) => {
  const [startDate, setStartDate] = useState("");

  return (
    <>
      <div className="row">
        <GoogleMapAutoAddress dataObj={data} />
        {/* <div className="col-12 col-lg-12">
          <AutoSuggestionAddress data={data} />
        </div>
        */}
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
        <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
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
        </div> 
        
        <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
          <label>Rooms<span>*</span></label>
          <div className="form-group">
            <input
              type="number"
              name="rooms"
              className="form-control"
              placeholder="Rooms"
              value={data.room}
              onChange={(e) => data.setRoom(e.target.value)}
            />
            {data.renderFieldError("rooms")}
          </div>
        </div>
        <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
          <label>Sq Ft<span>*</span></label>
          <div className="form-group">
            <input
              type="number"
              name="size"
              className="form-control"
              placeholder="Sq Ft"
              value={data.size}
              onChange={(e) => data.setSize(e.target.value)}
            />
            {data.renderFieldError("size")}
          </div>
        </div>
        <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
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
        <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
          <label>Year Built<span>*</span></label>
          <div className="form-group">
            <DatePicker
              id="DatePicker"
              type="string"
              maxDate={new Date()}
              className="text-primary text-center form-control"
              selected={startDate}
              name="build_year"
              autoComplete="off"
              showYearPicker
              dateFormat="yyyy"
              yearItemNumber={9}
              placeholderText="Year Built"
              onChange={(e) => {
                setStartDate(e);
              }}
            />
            {data.renderFieldError("build_year")}
          </div>
        </div>

        <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
          <label>Stories<span>*</span></label>
          <div className="form-group">
            <input
              type="number"
              name="stories"
              className="form-control"
              placeholder="Enter Stories"
              value={data.ofStories}
              onChange={(e) => data.setOfStories(e.target.value)}
            />
            {data.renderFieldError("stories")}
          </div>
        </div>

        <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
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

        <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
          <label>Parking<span>*</span></label>
          <div className="form-group">
            <SingleSelect
              name="parking"
              options={data.parkingOption}
              placeholder="Select Parking"
              setValue={data.setParking}
              value={data.parking}
            />
            {data.renderFieldError("parking")}
          </div>
        </div>
        <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-4">
          <label>Total Units<span>*</span></label>
          <div className="form-group">
            <input
              type="number"
              name="total_units"
              className="form-control"
              placeholder="Total Units"
              value={data.totalUnits}
              onChange={(e) => data.setTotalUnits(e.target.value)}
            />
            {data.renderFieldError("total_units")}

          </div>
        </div>
        <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-4">
          <label>Building class<span>*</span></label>
          <div className="form-group">
            <SingleSelect
              name="building_class"
              options={data.buildingClassOption}
              placeholder="Select Option"
              setValue={data.setBuildingClass}
              value={data.buildingClass}
            />
            {data.renderFieldError("building_class")}
          </div>
        </div>
        <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-4">
          <label>Value Add<span>*</span></label>
          <div className="form-group">
            <div className="radio-block">
              <div className="label-container">
                <input
                  type="radio"
                  name="value_add"
                  value="1"
                  id="value_add_yes"
                  checked={data.valueAdd === '1' ? "checked" : ""}
                  onChange={(e) => data.setValueAdd(e.target.value)}
                />
                <label className="mb-0" htmlFor="value_add_yes">
                  Yes
                </label>
              </div>
              <div className="label-container">
                <input
                  type="radio"
                  name="value_add"
                  value="0"
                  id="value_add_no"
                  checked={data.valueAdd === '0' ? "checked" : ""}
                  onChange={(e) => data.setValueAdd(e.target.value)}
                />
                <label className="mb-0" htmlFor="value_add_no">
                  No
                </label>
              </div>
            </div>
            {data.renderFieldError("value_add")}
          </div>
        </div>

        <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
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

        <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
          <label>
            Purchase Method <span>*</span>
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
      </div>
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
      <div className="row">
        <div className="col-12 col-lg-12">
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
      </div>
      <PropertyAttachments data={data}/>
      <div className="column--grid">
        <div className="grid-template-col">
          <div className="radio-block-group">
            <label>Solar<span>*</span></label>
            <div className="label-container">
              <input type="radio" name="solar" value="1" id="solar_yes" checked={data.solar === '1'} onChange={(e)=>data.setSolar(e.target.value)}/>
              <label className="mb-0" htmlFor="solar_yes">
                Yes
              </label>
            </div>
            <div className="label-container">
              <input type="radio" name="solar" value="0" id="solar_no" checked={data.solar === '0'} onChange={(e)=>data.setSolar(e.target.value)}
              />
              <label className="mb-0" htmlFor="solar_no">
                No
              </label>
            </div>
          </div>
          {data.renderFieldError("solar")}
        </div>
        <div className="grid-template-col">
          <div className="radio-block-group">
            <label>Pool<span>*</span></label>
            <div className="label-container">
              <input type="radio" name="pool" value="1" id="pool_yes" checked={data.pool === '1'} onChange={(e)=>data.setPool(e.target.value)}/>
              <label className="mb-0" htmlFor="pool_yes">
                Yes
              </label>
            </div>
            <div className="label-container">
              <input type="radio" name="pool" value="0" id="pool_no" checked={data.pool === '0'} onChange={(e)=>data.setPool(e.target.value)}/>
              <label className="mb-0" htmlFor="pool_no">
                No
              </label>
            </div>
          </div>
          {data.renderFieldError("pool")}
        </div>
        <div className="grid-template-col">
          <div className="radio-block-group">
            <label>Septic<span>*</span></label>
            <div className="label-container">
              <input type="radio" name="septic" value="1" id="septic_yes" checked={data.septic === '1'} onChange={(e)=>data.setSeptic(e.target.value)}/>
              <label className="mb-0" htmlFor="septic_yes">
                Yes
              </label>
            </div>
            <div className="label-container">
              <input type="radio" name="septic" value="0" id="septic_no" checked={data.septic === '0'} onChange={(e)=>data.setSeptic(e.target.value)}/>
              <label className="mb-0" htmlFor="septic_no">
                No
              </label>
            </div>
          </div>
          {data.renderFieldError("septic")}
        </div>
        <div className="grid-template-col">
          <div className="radio-block-group">
            <label>Well<span>*</span></label>
            <div className="label-container">
              <input type="radio" name="well" value="1" id="well_yes" checked={data.well === '1'} onChange={(e)=>data.setWell(e.target.value)}/>
              <label className="mb-0" htmlFor="well_yes">
                Yes
              </label>
            </div>
            <div className="label-container">
              <input type="radio" name="well" value="0" id="well_no" checked={data.well === '0'} onChange={(e)=>data.setWell(e.target.value)}/>
              <label className="mb-0" htmlFor="well_no">
                No
              </label>
            </div>
          </div>
          {data.renderFieldError("well")}
        </div>
        <div className="grid-template-col">
          <div className="radio-block-group">
            <label>HOA<span>*</span></label>
            <div className="label-container">
              <input type="radio" name="hoa" value="1" id="hoa_yes" checked={data.hoa === '1'} onChange={(e)=>data.setHoa(e.target.value)}/>
              <label className="mb-0" htmlFor="hoa_yes">
                Yes
              </label>
            </div>
            <div className="label-container">
              <input type="radio" name="hoa" value="0" id="hoa_no" checked={data.hoa === '0'} onChange={(e)=>data.setHoa(e.target.value)}/>
              <label className="mb-0" htmlFor="hoa_no">
                No
              </label>
            </div>
          </div>
          {data.renderFieldError("hoa")}
        </div>
        <div className="grid-template-col">
          <div className="radio-block-group">
            <label>Age restriction<span>*</span></label>
            <div className="label-container">
              <input
                type="radio"
                name="age_restriction"
                value="1"
                id="age_restriction_yes"
                checked={data.ageRestriction === '1'} onChange={(e)=>data.setAgeRestriction(e.target.value)}
              />
              <label className="mb-0" htmlFor="age_restriction_yes">
                Yes
              </label>
            </div>
            <div className="label-container">
              <input
                type="radio"
                name="age_restriction"
                value="0"
                id="age_restriction_no"
                checked={data.ageRestriction === '0'} onChange={(e)=>data.setAgeRestriction(e.target.value)}
              />
              <label className="mb-0" htmlFor="age_restriction_no">
                No
              </label>
            </div>
          </div>
          {data.renderFieldError("age_restriction")}
        </div>
        <div className="grid-template-col">
          <div className="radio-block-group">
            <label>Rental Restriction<span>*</span></label>
            <div className="label-container">
              <input
                type="radio"
                name="rental_restriction"
                value="1"
                id="rental_restriction_yes"
                checked={data.rentalRestriction === '1'} onChange={(e)=>data.setRentalRestriction(e.target.value)}
              />
              <label className="mb-0" htmlFor="rental_restriction_yes">
                Yes
              </label>
            </div>
            <div className="label-container">
              <input
                type="radio"
                name="rental_restriction"
                value="0"
                id="rental_restriction_no"
                checked={data.rentalRestriction === '0'} onChange={(e)=>data.setRentalRestriction(e.target.value)}
              />
              <label className="mb-0" htmlFor="rental_restriction_no">
                No
              </label>
            </div>
          </div>
          {data.renderFieldError("rental_restriction")}
        </div>
        <div className="grid-template-col">
          <div className="radio-block-group">
            <label>Post-Possession<span>*</span></label>
            <div className="label-container">
              <input
                type="radio"
                name="post_possession"
                value="1"
                id="post_possession_yes"
                checked={data.postPossession === '1'} onChange={(e)=>data.setPostPossession(e.target.value)}
              />
              <label className="mb-0" htmlFor="post_possession_yes">
                Yes
              </label>
            </div>
            <div className="label-container">
              <input
                type="radio"
                name="post_possession"
                value="0"
                id="post_possession_no"
                checked={data.postPossession === '0'} onChange={(e)=>data.setPostPossession(e.target.value)}
              />
              <label className="mb-0" htmlFor="post_possession_no">
                No
              </label>
            </div>
          </div>
          {data.renderFieldError("post_possession")}
        </div>
        <div className="grid-template-col">
          <div className="radio-block-group">
            <label>Tenant Conveys<span>*</span></label>
            <div className="label-container">
              <input type="radio" name="tenant" value="1" id="tenant_yes" checked={data.tenant === '1'} onChange={(e)=>data.setTenant(e.target.value)}
              />
              <label className="mb-0" htmlFor="tenant_yes" >
                Yes
              </label>
            </div>
            <div className="label-container">
              <input type="radio" name="tenant" value="0" id="tenant_no" checked={data.tenant === '0'} onChange={(e)=>data.setTenant(e.target.value)} />
              <label className="mb-0" htmlFor="tenant_no">
                No
              </label>
            </div>
          </div>
          {data.renderFieldError("tenant")}
        </div>
        <div className="grid-template-col">
          <div className="radio-block-group">
            <label>Squatters<span>*</span></label>
            <div className="label-container">
              <input
                type="radio"
                name="squatters"
                value="1"
                id="squatters_yes"
                checked={data.squatters === '1'} 
                onChange={(e)=>data.setSquatters(e.target.value)} 
              />
              <label className="mb-0" htmlFor="squatters_yes">
                Yes
              </label>
            </div>
            <div className="label-container">
              <input
                type="radio"
                name="squatters"
                value="0"
                id="squatters_no"
                checked={data.squatters === '0'} 
                onChange={(e)=>data.setSquatters(e.target.value)}               />
              <label className="mb-0" htmlFor="squatters_no">
                No
              </label>
            </div>
          </div>
          {data.renderFieldError("squatters")}
        </div>
        <div className="grid-template-col">
          <div className="radio-block-group">
            <label>Building Required<span>*</span></label>
            <div className="label-container">
              <input
                type="radio"
                name="building_required"
                value="1"
                id="building_required_yes"
                checked={data.buildingRequired === '1'} 
                onChange={(e)=>data.setBuildingRequired(e.target.value)}
              />
              <label className="mb-0" htmlFor="building_required_yes">
                Yes
              </label>
            </div>
            <div className="label-container">
              <input
                type="radio"
                name="building_required"
                value="0"
                id="building_required_no"
                checked={data.buildingRequired === '0'} 
                onChange={(e)=>data.setBuildingRequired(e.target.value)}
              />
              <label className="mb-0" htmlFor="building_required_no">
                No
              </label>
            </div>
          </div>
          {data.renderFieldError("building_required")}
        </div>
        <div className="grid-template-col">
          <div className="radio-block-group">
            <label>Rebuild<span>*</span></label>
            <div className="label-container">
              <input type="radio" name="rebuild" value="1" id="rebuild_yes" 
              checked={data.rebuild === '1'} 
              onChange={(e)=>data.setRebuild(e.target.value)}
              />
              <label className="mb-0" htmlFor="rebuild_yes">
                Yes
              </label>
            </div>
            <div className="label-container">
              <input type="radio" name="rebuild" value="0" id="rebuild_no" 
              checked={data.rebuild === '0'} 
              onChange={(e)=>data.setRebuild(e.target.value)}
              />
              <label className="mb-0" htmlFor="rebuild_no">
                No
              </label>
            </div>
          </div>
          {data.renderFieldError("rebuild")}
        </div>
        <div className="grid-template-col">
          <div className="radio-block-group">
            <label>Foundation Issues<span>*</span></label>
            <div className="label-container">
              <input
                type="radio"
                name="foundation_issues"
                value="1"
                id="foundation_issues_yes"
                checked={data.foundationIssues === '1'} 
                onChange={(e)=>data.setFoundationIssues(e.target.value)}
              />
              <label className="mb-0" htmlFor="foundation_issues_yes">
                Yes
              </label>
            </div>
            <div className="label-container">
              <input
                type="radio"
                name="foundation_issues"
                value="0"
                id="foundation_issues_no"
                checked={data.foundationIssues === '0'} 
                onChange={(e)=>data.setFoundationIssues(e.target.value)}
              />
              <label className="mb-0" htmlFor="foundation_issues_no">
                No
              </label>
            </div>
          </div>
          {data.renderFieldError("foundation_issues")}
        </div>
        <div className="grid-template-col">
          <div className="radio-block-group">
            <label>Mold<span>*</span></label>
            <div className="label-container">
              <input type="radio" name="mold" value="1" id="mold_yes" 
                checked={data.mold === '1'} 
                onChange={(e)=>data.setMold(e.target.value)}
                />
              <label className="mb-0" htmlFor="mold_yes">
                Yes
              </label>
            </div>
            <div className="label-container">
              <input type="radio" name="mold" value="0" id="mold_no" 
                checked={data.mold === '0'} 
                onChange={(e)=>data.setMold(e.target.value)}
                />
              <label className="mb-0" htmlFor="mold_no">
                No
              </label>
            </div>
          </div>
          {data.renderFieldError("mold")}
        </div>
        <div className="grid-template-col">
          <div className="radio-block-group">
            <label>Fire Damaged<span>*</span></label>
            <div className="label-container">
              <input
                type="radio"
                name="fire_damaged"
                value="1"
                id="fire_damaged_yes"
                checked={data.fireDamaged === '1'} 
                onChange={(e)=>data.setFireDamaged(e.target.value)}
              />
              <label className="mb-0" htmlFor="fire_damaged_yes">
                Yes
              </label>
            </div>
            <div className="label-container">
              <input
                type="radio"
                name="fire_damaged"
                value="0"
                id="fire_damaged_no"
                checked={data.fireDamaged === '0'} 
                onChange={(e)=>data.setFireDamaged(e.target.value)}
              />
              <label className="mb-0" htmlFor="fire_damaged_no">
                No
              </label>
            </div>
          </div>
          {data.renderFieldError("fire_damaged")}
        </div>
      </div>
    </>
  );
};
export default HotelMotel;
