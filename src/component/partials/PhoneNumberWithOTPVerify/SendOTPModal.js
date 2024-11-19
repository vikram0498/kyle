import React from 'react';
import { Link } from 'react-router-dom';

const SendOTPModal = ({ handleSendOtp, handleSubmitOtp, setOtpValue }) => {
    const inputFocus = (event) => {
        const elements = document.getElementsByClassName("otpnumb");
        const inputField = document.getElementById("otp_value");

        const values = [];
        for (const el of elements) {
            values.push(el.value); // Renamed variable to `el` to avoid conflict
        }

        let inputValue = values.join("");
        inputField.value = inputValue;
        setOtpValue(inputValue);
        if (event.key === "Delete" || event.key === "Backspace") {
            if (event.target.value === "") {
                const next = event.target.tabIndex - 2;
                if (next > -1) {
                    const previousElement = document.querySelector(`[tabIndex="${next + 1}"]`);
                    previousElement?.focus();
                }
            }
        } else if (event.key === "ArrowLeft" || event.key === "ArrowRight") {
            // Handle navigation with arrow keys (optional)
        } else {
            const next = event.target.tabIndex;
            if (next < 4 && event.target.value !== "") {
                const nextElement = document.querySelector(`[tabIndex="${next + 1}"]`);
                nextElement?.focus();
            }
        }
    };

    return (
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
                            fillOpacity="0.23"
                        ></path>
                        <path
                            d="M6.0008 0.83374C2.67051 0.83374 0.0644531 3.2228 0.0644531 5.97388C0.0644531 8.72517 2.67073 11.1866 6.0008 11.1866C9.25869 11.1866 11.8652 8.72517 11.8652 5.97388C11.8652 3.2228 9.25848 0.83374 6.0008 0.83374Z"
                            fill="#3F53FE"
                            fillOpacity="0.23"
                        ></path>
                    </svg>
                </span>
                <h3>Otp is sent on this</h3>
                <p>Please verify the phone number</p>
            </div>
            <form className="modal-form">
                <div className="otp_block">
                    <input type="hidden" name="otp" id="otp_value" value="" />
                    <div className="otp-digit">
                        <input
                            name="otp1"
                            type="text"
                            autoComplete="off"
                            className="otpnumb"
                            tabIndex="1"
                            maxLength="1"
                            onKeyUp={(e) => inputFocus(e)}
                        />
                        <input
                            name="otp2"
                            type="text"
                            autoComplete="off"
                            className="otpnumb"
                            tabIndex="2"
                            maxLength="1"
                            onKeyUp={(e) => inputFocus(e)}
                        />
                        <input
                            name="otp3"
                            type="text"
                            autoComplete="off"
                            className="otpnumb"
                            tabIndex="3"
                            maxLength="1"
                            onKeyUp={(e) => inputFocus(e)}
                        />
                        <input
                            name="otp4"
                            type="text"
                            autoComplete="off"
                            className="otpnumb"
                            tabIndex="4"
                            maxLength="1"
                            onKeyUp={(e) => inputFocus(e)}
                        />
                    </div>
                    <div className="text-center otp_resend_code">
                        <p>Didn't Get OTP Code?</p>
                        <Link onClick={handleSendOtp}>Resend Code</Link>
                    </div>
                </div>
                <div className="form-group mb-0">
                    <div className="submit-btn">
                        <button type="button" className="btn btn-fill" onClick={handleSubmitOtp}>
                            Submit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    );
};

export default SendOTPModal;
