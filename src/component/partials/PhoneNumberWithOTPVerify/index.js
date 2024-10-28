import React, { useState } from 'react'
import SendOTPModal from './SendOTPModal';
import { Modal } from 'react-bootstrap';
const PhoneNumberWithOTPVerify = ({register, errors ='' ,renderFieldError=''}) => {
    const [showModal, setShowModal] = useState(false);
    return(
        <>
            <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                <label>
                    Phone Number<span>*</span>
                    <span onClick={()=>setShowModal(true)} className='send_otp_right'>Send OTP</span>
                </label>
                <div className="form-group position-relative">
                    <input
                        type="text"
                        name="phone"
                        className="form-control"
                        placeholder="Eg. 123-456-789"
                        {...register("phone", {
                        required: "Phone Number is required",
                            validate: {
                                matchPattern: (v) =>
                                /^[0-9-]*$/.test(v) || "Please enter a valid phone number",
                                maxLength: (v) =>
                                (v.length <= 12 && v.length >= 9) || // Adjusted for the formatted length (9 digits + 2 hyphens)
                                "The phone number should be between 9 to 12 characters",
                            },
                        })}
                    />
                    {errors && (
                        <p className="error">
                        {errors?.message}
                        </p>
                    )}
                    {renderFieldError && renderFieldError("phone")}
                    <span className='otp_success_check'>
                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="8" viewBox="0 0 10 8" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9.81632 0.133089C10.0421 0.33068 10.0626 0.674907 9.86176 0.897832L4.20761 7.1736C4.00571 7.39769 3.65904 7.41194 3.43943 7.20522L0.167566 4.12504C-0.0364783 3.93294 -0.0560104 3.61286 0.119053 3.39404C0.312223 3.15257 0.671949 3.11934 0.901844 3.32611L3.44037 5.60947C3.66098 5.80791 4.00062 5.79016 4.19938 5.56984L9.06279 0.177574C9.25956 -0.0406347 9.59519 -0.0604144 9.81632 0.133089Z" fill="#19955A"/>
                        </svg>
                    </span>
                </div>
            </div>
            {/* {showModal && <SendOTPModal setShowModal={setShowModal}/>} */}
            
            <Modal show={showModal} onHide={setShowModal} centered className='radius_30 max-648'>
                <Modal.Header closeButton className='new_modal_close'></Modal.Header>
                <Modal.Body className='space_modal'>
                    <SendOTPModal />
                </Modal.Body>
            </Modal>

        </>
    );
}
export default PhoneNumberWithOTPVerify