import React from 'react';

const SendOTPModal = ({setShowModal}) => {
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
    return (
        <div
        className="modal modal-form-main"
        id="exampleModal"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
        style={{ display: "block" }}
      >
        <div className="modal-dialog modal-dialog-centered">
            <div className="modal-content">
                <button
                    type="button"
                    className="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                    onClick={()=>setShowModal(false)}
                >
                    <img
                    alt="close"
                    src="/assets/images/close.svg"
                    className="img-fluid"
                    />
                </button>
                <div className="modal-body">
                    <div className="want-to-edit">
                    <div className="popup-heading-block text-center">
                        <span className="popup-icon">
                        <svg
                            width="12"
                            height="53"
                            viewBox="0 0 12 53"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                            d="M6.00131 17.847C2.74342 17.847 0.426758 19.2228 0.426758 21.2499V48.8328C0.426758 50.5705 2.74342 52.3077 6.00131 52.3077C9.1144 52.3077 11.648 50.5705 11.648 48.8328V21.2495C11.648 19.2226 9.1144 17.847 6.00131 17.847Z"
                            fill="#3F53FE"
                            fill-opacity="0.23"
                            ></path>
                            <path
                            d="M6.0008 0.83374C2.67051 0.83374 0.0644531 3.2228 0.0644531 5.97388C0.0644531 8.72517 2.67073 11.1866 6.0008 11.1866C9.25869 11.1866 11.8652 8.72517 11.8652 5.97388C11.8652 3.2228 9.25848 0.83374 6.0008 0.83374Z"
                            fill="#3F53FE"
                            fill-opacity="0.23"
                            ></path>
                        </svg>
                        </span>
                        <h3>Otp is sent on this </h3>
                        <p>Please verify the phone number</p>
                    </div>
                    <form className="modal-form">
                        <div className="row">
                            <div className="col-12 col-lg-12">
                                <div className="lic-detail-area">
                                    <div className="row align-items-end">
                                        <div className="col-12 col-lg-3">
                                            <input type="hidden" name="otp" id="otp_value" value="" />
                                            <label>Enter OTP</label>
                                            <div className="otp-digit">

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
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div className="col-12 col-lg-12">
                                <div className="form-group mb-0">
                                    <div className="submit-btn">
                                    <button type="submit" className="btn btn-fill">
                                        Submit
                                    </button>
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
    );
}

export default SendOTPModal;