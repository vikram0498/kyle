import React, { useState } from 'react'
import SendOTPModal from './SendOTPModal';
const PhoneNumberWithOTPVerify = ({register, errors ='' ,renderFieldError=''}) => {
    const [showModal, setShowModal] = useState(false);
    return(
        <>
            <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                <label>
                Phone Number<span>*</span>
                <span onClick={()=>setShowModal(true)}> &nbsp; &nbsp;&nbsp;&nbsp;send Otp </span>
                </label>
                <div className="form-group">
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
                </div>
                {showModal && <SendOTPModal setShowModal={setShowModal}/>}
            </div>
        </>
    );
}
export default PhoneNumberWithOTPVerify