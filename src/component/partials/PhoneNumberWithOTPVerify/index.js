import React, { useState } from 'react'
import SendOTPModal from './SendOTPModal';
import { Modal } from 'react-bootstrap';
import axios from "axios";
import { toast } from "react-toastify";

const PhoneNumberWithOTPVerify = ({register, errors ='', getValues ,renderFieldError='', setIsVerifiedOTP='', isVerifiedOTP}) => {
    const [showModal, setShowModal] = useState(false);
    const [otpValue, setOtpValue] = useState(0);
    const apiUrl = process.env.REACT_APP_API_URL;
    const countryCode = process.env.REACT_APP_COUNTRY_CODE;
    const handleSendOtp = async () => {
        try {
            const phoneNumber = getValues('phone'); 
            const formattedPhoneNumber = phoneNumber.replace(/-/g, '');
            let payload = {
                country_code:countryCode,
                phone:formattedPhoneNumber
            }
            let headers = {
                Accept: "application/json",
              };
            let response = await axios.post(`${apiUrl}send-otp` , payload,{ headers: headers });
            if(response.data.status){
                toast.success(response.data.message, {
                    position: toast.POSITION.TOP_RIGHT,
                });
            }
            clearInputs();
            setShowModal(true)
        } catch (error) {
            toast.error(error.response.data.message, {
                position: toast.POSITION.TOP_RIGHT,
            });       
        }
    }
    const handleSubmitOtp = async () => {
        try {
            const phoneNumber = getValues('phone'); 
            const formattedPhoneNumber = phoneNumber.replace(/-/g, '');
            const payload = {
                country_code:countryCode,
                phone:formattedPhoneNumber,
                otp:otpValue
            }
            let headers = {
                Accept: "application/json",
              };
            let response = await axios.post(`${apiUrl}verify-otp` , payload,{ headers: headers });
                if(response.data.status){
                    toast.success(response.data.message, {
                        position: toast.POSITION.TOP_RIGHT,
                    });
                    if(setIsVerifiedOTP !==''){
                        setIsVerifiedOTP(true);
                        setShowModal(false)
                    }
                }else{

                    toast.error(response.data.message, {
                        position: toast.POSITION.TOP_RIGHT,
                    });
                }
        } catch (error) {
            toast.error(error.response.data.message, {
                position: toast.POSITION.TOP_RIGHT,
            });      
        }
    }
    const clearInputs = () => {
        const elements = document.getElementsByClassName("otpnumb");
        const inputField = document.getElementById("otp_value");

        if (!elements || !inputField) return; // Ensure elements exist

        for (const el of elements) {
            el.value = ""; // Clear each input field
        }
        inputField.value = ""; // Clear hidden input
        setOtpValue(""); // Clear OTP value in state
    };
    return(
        <>
            <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                <label> Phone Number<span>*</span>
                    {getValues('phone') !='' && 
                        <span onClick={handleSendOtp} className='send_otp_right'>Send OTP</span>
                    }
                </label>
                <div className="form-group position-relative">
                    <input type="hidden" name="country_code" value={countryCode}/>
                    <input
                        type="text"
                        name="phone"
                        className="form-control"
                        placeholder="Eg. 123-456-7890"
                        {...register("phone", {
                        required: "Phone Number is required",
                            validate: {
                                matchPattern: (v) =>
                                /^[0-9-]*$/.test(v) || "Please enter a valid phone number",
                                maxLength: (v) =>
                                (v.length <= 13 && v.length >= 1) || // Adjusted for the formatted length (9 digits + 2 hyphens)
                                "The phone number should be between 1 to 10 characters",
                            },
                        })}
                    />
                    {errors && (
                        <p className="error">
                        {errors?.message}
                        </p>
                    )}
                    {renderFieldError && renderFieldError("phone")}
                    {isVerifiedOTP  && 
                        <span className='otp_success_check'>
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="8" viewBox="0 0 10 8" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M9.81632 0.133089C10.0421 0.33068 10.0626 0.674907 9.86176 0.897832L4.20761 7.1736C4.00571 7.39769 3.65904 7.41194 3.43943 7.20522L0.167566 4.12504C-0.0364783 3.93294 -0.0560104 3.61286 0.119053 3.39404C0.312223 3.15257 0.671949 3.11934 0.901844 3.32611L3.44037 5.60947C3.66098 5.80791 4.00062 5.79016 4.19938 5.56984L9.06279 0.177574C9.25956 -0.0406347 9.59519 -0.0604144 9.81632 0.133089Z" fill="#19955A"/>
                            </svg>
                        </span>
                    }
                </div>
            </div>
            {/* {showModal && <SendOTPModal setShowModal={setShowModal}/>} */}
            
            <Modal show={showModal} onHide={setShowModal} centered className='radius_30 max-648'>
                <Modal.Header closeButton className='new_modal_close'></Modal.Header>
                <Modal.Body className='space_modal'>
                    <SendOTPModal handleSendOtp={handleSendOtp} handleSubmitOtp={handleSubmitOtp} setOtpValue={setOtpValue}/>
                </Modal.Body>
            </Modal>

        </>
    );
}
export default PhoneNumberWithOTPVerify