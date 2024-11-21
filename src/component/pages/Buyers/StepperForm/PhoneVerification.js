import React from "react";
import ButtonLoader from "../../../partials/MiniLoader";
const PhoneVerification = ({
  register,
  errors,
  renderFieldError,
  isOtpSent,
  handleSubmit,
  sendOtp,
  isOtpVerify,
  countryCode,
  setphoneNumber,
  phoneNumber,
  miniLoader,
}) => {
  const inputfocus = (elmnt) => {
    const elements = document.getElementsByClassName("otpnumb");
    const inputField = document.getElementById("otp_value");

    const values = [];
    for (const element of elements) {
      values.push(element.value);
    }
    let inputValue = values.join("");
    inputField.value = inputValue;
    if (elmnt.key === "Delete" || elmnt.key === "Backspace") {
      const next = elmnt.target.tabIndex - 2;
      if (next > -1) {
        elmnt.target.form.elements[next].focus();
        let tabIndex = elmnt.target.tabIndex - 1;
        const element = document.querySelector(`[tabIndex="${tabIndex}"]`);
        element.focus();
      }
    } else {
      const next = elmnt.target.tabIndex;
      if (next < 4) {
        let tabIndex = next + 1;
        const element = document.querySelector(`[tabIndex="${tabIndex}"]`);
        element.focus();
      }
    }
  };

  const formatInput = (input) => {
    // Remove all non-digit characters
    let cleaned = input.replace(/\D/g, "");

    // Format the input as 123-456-7890 (up to 10 digits)
    return cleaned
        .substring(0, 10) // Limit the length to 10 digits
        .replace(/(\d{3})(\d{0,3})(\d{0,4})/, (_, g1, g2, g3) =>
            [g1, g2, g3].filter(Boolean).join("-")
        );
  };

  return (
    <>
      <div className="card-box-blocks">
        <div className="lic-detail-area">
          <div className="row align-items-end">
            <div className="col-12 col-md-8">
              <label>Phone Number</label>
              <div className="form-group mb-0">
                <input type="hidden" value={countryCode} name="country_code"/>
                <input
                  autoComplete="off"
                  type="text"
                  name="phone"
                  value={formatInput(phoneNumber)}
                  className="form-control"
                  placeholder="Enter Phone Number"
                  {...register("phone", {
                    onChange: (e) => {
                      setphoneNumber(e.target.value);
                    },
                    required: "Phone Number is required",
                    validate: {
                      maxLength: (v) =>
                        (v.length <= 13) ||
                        "The phone number should be less than equal 10",
                    },
                  })}
                />
              </div>
            </div>
            <div className="col-12 col-sm-4 mt-2 mt-md-0 col-md-4">
              <div className={isOtpSent ? "sent-btn" : "send-btn"}>
                {/* <button type="submit" className="btn btn-fill">
                  Send
                </button> */}
                <button
                  onClick={handleSubmit(sendOtp)}
                  type="button"
                  className="btn btn-fill"
                  disabled={isOtpSent ? "disabled" : ""}
                >
                  {isOtpSent ? (
                    "Sent ✓"
                  ) : miniLoader ? (
                    <ButtonLoader />
                  ) : (
                    "Send"
                  )}
                </button>
              </div>
            </div>
            {isOtpSent ? (
              <div className="col-12 col-sm- col-md-6 col-lg-2 mt-2">
                <div className="resend-btn">
                  <button
                    className="btn btn-outline"
                    type="button"
                    onClick={handleSubmit(sendOtp)}
                  >
                    {miniLoader ? <ButtonLoader /> : "Resend"}
                  </button>
                </div>
              </div>
            ) : (
              ""
            )}
          </div>
          {errors.phone && <p className="error">{errors.phone?.message}</p>}
          {renderFieldError("phone")}
        </div>
      </div>

      <div
        className="card-box-blocks enter-otp-section"
        style={{ display: isOtpSent ? "block" : "none" }}
      >
        <fieldset disabled={isOtpSent ? "" : "disabled"}>
          <div className="lic-detail-area">
            <div className="row align-items-end">
              <div className="col-12 col-lg-3">
                <input type="hidden" name="otp" id="otp_value" value="" />
                <label>Enter OTP</label>
                <div className="otp-digit">
                  {/* <span className="otpnumb">4</span>
                <span className="otpnumb">2</span>
                <span className="otpnumb">8</span>
                <span className="otpnumb">5</span> */}
                  <input
                    name="otp1"
                    type="text"
                    autoComplete="off"
                    className="otpnumb"
                    tabIndex="1"
                    maxLength="1"
                    onKeyUp={(e) => inputfocus(e)}
                  />
                  <input
                    name="otp2"
                    type="text"
                    autoComplete="off"
                    className="otpnumb"
                    tabIndex="2"
                    maxLength="1"
                    onKeyUp={(e) => inputfocus(e)}
                  />
                  <input
                    name="otp3"
                    type="text"
                    autoComplete="off"
                    className="otpnumb"
                    tabIndex="3"
                    maxLength="1"
                    onKeyUp={(e) => inputfocus(e)}
                  />
                  <input
                    name="otp4"
                    type="text"
                    autoComplete="off"
                    className="otpnumb"
                    tabIndex="4"
                    maxLength="1"
                    onKeyUp={(e) => inputfocus(e)}
                  />
                </div>
                {renderFieldError("otp")}
              </div>
              <div className="col-12 col-sm- col-md-6 col-lg-4">
                <div className="send-btn text-start">
                  {isOtpVerify ? (
                    <button
                      type="submit"
                      className="btn btn-fill w-auto"
                      style={{
                        color: "green",
                        border: "unset",
                        background: "none",
                      }}
                    >
                      ✓ verified
                    </button>
                  ) : (
                    <button type="submit" className="btn btn-fill w-auto">
                      verify & next
                    </button>
                  )}
                </div>
              </div>
            </div>
          </div>
        </fieldset>
      </div>
    </>
  );
};

export default PhoneVerification;
