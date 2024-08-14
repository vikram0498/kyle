import React from "react";
import ButtonLoader from "../../../partials/MiniLoader";

const ApplicationProcess = ({ miniLoader }) => {
  console.log(miniLoader, "miniLoader");
  return (
    <>
      <fieldset>
        <div className="card-box-blocks">
          <div className="application-process">
            <div className="pricehard">$100</div>
            <h3>Please Submit Your Application Fee</h3>
            <p className="mb-0">
              You will be redirected to schedule your virtual verification meeting. Please be on-time for your meeting as the application fee is non-refundable.
            </p>
            <div className="process-payment-btn">
              <button type="submit" className="btn btn-fill">
                Process Payment {miniLoader ? <ButtonLoader /> : ""}
              </button>
            </div>
          </div>
        </div>
        {/* <input
          type="button"
          name="previous"
          className="previous action-button-previous btn btn-fill"
          value="Previous"
        />
        <input
          type="submit"
          name="submit"
          className="submit action-button btn btn-fill"
          value="Submit"
        /> */}
      </fieldset>
    </>
  );
};

export default ApplicationProcess;
