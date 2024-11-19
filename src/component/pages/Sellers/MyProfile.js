import React, { useEffect, useState } from "react";
import Header from "../../partials/Layouts/Header";
import Footer from "../../partials/Layouts/Footer";
import { useNavigate, Link } from "react-router-dom";
import { useAuth } from "../../../hooks/useAuth";
//import {useForm} from "../../hooks/useForm";
import { useFormError } from "../../../hooks/useFormError";
import { toast } from "react-toastify";
import { useForm } from "react-hook-form";
import axios from "axios";

const MyProfile = () => {
  const apiUrl = process.env.REACT_APP_API_URL;
  const countryCode = process.env.REACT_APP_COUNTRY_CODE;

  const { getTokenData, setLogout, getLocalStorageUserdata, setLocalStorageUserdata } = useAuth();
  const navigate = useNavigate();
  const [userData, setUserData] = useState("");
  const [oldPassword, setOldPassword] = useState("");
  const [newPassword, setNewPassword] = useState("");
  const [confirmPassword, setConfirmPassword] = useState("");
  const [isProfileUpdate, setIsProfileUpdate] = useState("false");
  const [errorMsg, setErrorMsg] = useState("");
  const [border, setBorder] = useState("1px dashed #677AAB");
  const [loader, setLoader] = useState(true);
  const { setErrors, renderFieldError } = useFormError();
  const [previewImageUrl, setPreviewImageUrl] = useState("");
  const [firstName, setFirstName] = useState("");
  const [showOldPassoword, setShowOldPassoword] = useState(false);
  const [showNewPassoword, setShowNewPassoword] = useState(false);
  const [showConfirmPassoword, setShowConfirmPassoword] = useState(false);
  const [lastName, setLastName] = useState("");
  const [phoneNumber, setPhoneNumber] = useState('');
  const {
    register,
    handleSubmit,
    setValue,
    watch,
    formState: { errors },
  } = useForm();

  let phoneValue = watch("phone", '');

  // toast.success("sssssssss",{ autoClose: 1500000000 }, {position: toast.POSITION.TOP_RIGHT});
  let headers = {
    Accept: "application/json",
    Authorization: "Bearer " + getTokenData().access_token,
    "auth-token": getTokenData().access_token,
  };
  useEffect(() => {
    fetchUserData();
  }, [isProfileUpdate]);

  const fetchUserData = async () => {
    try {
      let response = await axios.get(apiUrl + "user-details", {
        headers: headers,
      });
      if (response) {
        let { data } = response.data.data;
        setUserData(response.data.data);
        setFirstName(response.data.data.first_name);
        setLastName(response.data.data.last_name);
       
        let userData = getLocalStorageUserdata();
        userData.first_name = response.data.data.first_name;
        userData.last_name = response.data.data.last_name;
        userData.profile_image =response.data.data.profile_image;
        userData.level_type = response.data.data.level_type;
        userData.role = response.data.data.role;
        userData.total_buyer_uploaded = response.data.data.total_buyer_uploaded;
        setPhoneNumber(response.data.data.phone);
        setLocalStorageUserdata(userData);        
        const profileName = document.querySelector(".user-name-title");
        const profilePic = document.querySelector(".user-profile");

        profileName.innerHTML =
          response.data.data.first_name + " " + response.data.data.last_name;
        if (response.data.data.profile_image != "") {
          profilePic.src = response.data.data.profile_image;
        }
      }
      setLoader(false);
    } catch (error) {
      toast.error(error.message, { position: toast.POSITION.TOP_RIGHT });
    }
  };
  const handleFormSubmit = (data, e) => {
    e.preventDefault();
    setIsProfileUpdate("false");
    setErrors("");
    var data = new FormData(e.target);
    let formData = Object.fromEntries(data.entries());

    if (formData.hasOwnProperty("phone")) {
      if(formData.phone != ''){
        let convertedNumber = formData.phone.replace(/\D/g, "")
        formData.phone = convertedNumber;
      }
    }

    let headers = {
      Accept: "application/json",
      Authorization: "Bearer " + getTokenData().access_token,
      "auth-token": getTokenData().access_token,
      "Content-Type": "multipart/form-data",
    };
    const maxFileSize = 2 * 1024 * 1024; // 5MB
    const fileSize = formData.profile_image.size;
    const fileType = formData.profile_image.type;
    const fileName = formData.profile_image.name;

    if (
      fileName != "" &&
      fileType != "image/png" &&
      fileType != "image/jpg" &&
      fileType != "image/jpeg"
    ) {
      setErrorMsg("Please add valid file (jpg,jpeg,png)");
      setBorder("1px dashed #ff0018");
      return false;
    } else if (fileSize > maxFileSize) {
      setErrorMsg(
        "File size is too large. Please upload a file that is less than 2MB."
      );
      setBorder("1px dashed #ff0018");
      return false;
    } else {
      setBorder("1px dashed #677AAB");
      setErrorMsg("");
      setLoader(true);
      setPreviewImageUrl("");
    }

    async function fetchData() {
      try {
        const response = await axios.post(apiUrl + "update-profile", formData, {
          headers: headers,
        });

        if (response.data.status) {
          toast.success(response.data.message, {
            position: toast.POSITION.TOP_RIGHT,
          });
          setIsProfileUpdate("true");
          //await fetchUserData();
        }
      } catch (error) {
        setLoader(false);
        if (error.response) {
          if (error.response.status === 401) {
            setLogout();
          }
          if (error.response.data.errors) {
            setErrors(error.response.data.errors);
          }
          if (error.response.data.error) {
            toast.error(error.response.data.error, {
              position: toast.POSITION.TOP_RIGHT,
            });
          }
        }
      }
    }
    fetchData();
  };
  const previewImage = (e) => {
    if (e.target.files[0]) {
      const reader = new FileReader();
      reader.addEventListener("load", () => {
        setPreviewImageUrl(reader.result);
      });
      reader.readAsDataURL(e.target.files[0]);
    }
  };

  const handleOldPassword = (e) => {
    setOldPassword(e.target.value);
  };
  const handleNewPassword = (e) => {
    setNewPassword(e.target.value);
  };
  const handleConfirmPassword = (e) => {
    setConfirmPassword(e.target.value);
  };

  const toggleOldPasswordVisibility = () => {
    setShowOldPassoword(!showOldPassoword);
  };

  const toggleNewPasswordVisibility = () => {
    setShowNewPassoword(!showNewPassoword);
  };
  const toggleConfirmPasswordVisibility = () => {
    setShowConfirmPassoword(!showConfirmPassoword);
  };
  // const handleChangeLastName = (e) => {
  //     const regex = /^[a-zA-Z\s]+$/;
  //     const new_value = e.target.value.replace(/[^a-zA-Z\s]/g, "");
  //     if (regex.test(new_value)) {
  //         setLastName(new_value);
  //     }
  //     if(new_value ==''){
  //         setLastName('');
  //     }
  // }
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

  // Update the form value whenever the phoneValue changes
  useEffect(() => {
    const formattedValue = formatInput(phoneNumber);
    setValue("phone", formattedValue); // Update the field with the formatted phone number
  }, [phoneNumber, setValue]);

  return (
    <>
      <Header />
      <section className="main-section position-relative pt-4 pb-120">
        {loader ? (
          <div className="loader" style={{ textAlign: "center" }}>
            <img src="assets/images/loader.svg" />
          </div>
        ) : (
          <div className="container position-relative pat-40">
            <div className="back-block">
              <div className="row align-items-center">
                <div className="col-12 col-sm-4 col-md-4 col-lg-4">
                  <Link to="/" className="back">
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
                      />
                      <path
                        d="M5.9 11L1 6L5.9 1"
                        stroke="#0A2540"
                        strokeWidth="1.5"
                        strokeLinecap="round"
                        strokeLinejoin="round"
                      />
                    </svg>
                    Back
                  </Link>
                </div>
              </div>
            </div>
            <div className="card-box">
              <form
                className="profile-account"
                method="post"
                onSubmit={handleSubmit(handleFormSubmit)}
              >
                <div className="row">
                  <div className="col-12 col-lg-8">
                    <div className="card-box-inner">
                      <h3 className="my-profile-title">My Profile</h3>
                      {/* <p>Fill the below form OR send link to the buyer</p> */}
                      <div className="row">
                        <div className="col-12 col-md-6 col-lg-6">
                          <div className="form-group">
                            <label>
                              First Name <span className="error">*</span>
                            </label>
                            <input
                              type="text"
                              name="first_name"
                              className="form-control-form"
                              placeholder="First Name"
                              defaultValue={userData.first_name}
                              {...register("first_name", {
                                required: "First Name is required",
                                validate: {
                                  maxLength: (v) =>
                                    v.length <= 50 ||
                                    "The First Name should have at most 50 characters",
                                  matchPattern: (v) =>
                                    /^[a-zA-Z\s]+$/.test(v) ||
                                    "First Name can not include number or special character",
                                },
                              })}
                            />

                            {errors.first_name && (
                              <p className="error">
                                {errors.first_name?.message}
                              </p>
                            )}

                            {renderFieldError("first_name")}
                          </div>
                        </div>
                        <div className="col-12 col-md-6 col-lg-6">
                          <div className="form-group">
                            <label>
                              Last Name <span className="error">*</span>
                            </label>
                            <input
                              type="text"
                              defaultValue={userData.last_name}
                              name="last_name"
                              className="form-control-form"
                              placeholder="Last Name"
                              {...register("last_name", {
                                required: "Last Name is required",
                                validate: {
                                  maxLength: (v) =>
                                    v.length <= 50 ||
                                    "The Last Name should have at most 50 characters",
                                  matchPattern: (v) =>
                                    /^[a-zA-Z\s]+$/.test(v) ||
                                    "Last Name can not include number or special character",
                                },
                              })}
                            />
                            {errors.last_name && (
                              <p className="error">
                                {errors.last_name?.message}
                              </p>
                            )}
                            {renderFieldError("last_name")}
                          </div>
                        </div>
                        <div className="col-12 col-md-6 col-lg-6">
                          <div className="form-group">
                            <label>
                              Email <span className="error">*</span>
                            </label>
                            {userData.email != "" && userData.email != null ? (
                              <p
                                className="form-control-form"
                                style={{ background: "#e8f0fe" }}
                              >
                                {userData.email}
                              </p>
                            ) : (
                              <>
                                <input
                                  type="email"
                                  name="email"
                                  className="form-control-form"
                                  placeholder="Email"
                                  defaultValue={userData.email}
                                  {...register("email", {
                                    required: "Email is required",
                                    validate: {
                                      maxLength: (v) =>
                                        v.length <= 50 ||
                                        "The email should have at most 50 characters",
                                      matchPattern: (v) =>
                                        /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(
                                          v
                                        ) ||
                                        "Email address must be a valid address",
                                    },
                                  })}
                                />

                                {errors.email && (
                                  <p className="error">
                                    {errors.email?.message}
                                  </p>
                                )}

                                {renderFieldError("email")}
                                {/* <span>Verify Email</span> */}
                              </>
                            )}
                          </div>
                        </div>
                        <div className="col-12 col-md-6 col-lg-6">
                          <input type="hidden" name="country_code" value={countryCode}/>
                          <div className="form-group">
                            <label> Phone Number <span className="error">*</span></label>
                            <input
                              type="text"
                              name="phone"
                              className="form-control-form"
                              placeholder="Phone Number"
                              defaultValue={formatInput(userData.phone)}
                              autoComplete="no-phone"
                              onKeyUp={(e)=>{setPhoneNumber(e.target.value)}}
                              {...register("phone", {
                                required: "Phone is required",
                                validate: {
                                  matchPattern: (v) =>
                                    /^[0-9-]*$/.test(v) || "Please enter a valid phone number",
                                  maxLength: (v) =>
                                    (v.length <= 13 && v.length >= 1) || // Adjusted for the formatted length (9 digits + 2 hyphens)
                                    "The phone number should be between 1 to 10 characters",
                                },
                              })}
                              disabled={true}
                            />
                            {errors.phone && (
                              <p className="error">{errors.phone?.message}</p>
                            )}

                            {renderFieldError("phone")}
                          </div>
                        </div>
                      </div>
                      <div className="form-title mt-3">
                        <h5>
                          Change Password <br/>
                          <span className="msg-passwrd">
                            (Leave blank if you don’t want to update password)
                          </span>
                        </h5>
                      </div>
                      <div className="row">
                        <div className="col-12 col-lg-12">
                          <div className="form-group">
                            <label>Old Password</label>
                            <div className=" password-check">
                              <input
                                type={showOldPassoword ? "text" : "password"}
                                name="old_password"
                                className="form-control-form"
                                placeholder="Old Password"
                                autoComplete="new-password"
                                value={oldPassword}
                                onChange={handleOldPassword}
                              />
                              <span
                                onClick={toggleOldPasswordVisibility}
                                className={`form-icon-password toggle-password ${
                                  showOldPassoword ? "eye-open" : "eye-close"
                                }`}
                              >
                                <img
                                  src="./assets/images/eye.svg"
                                  className="img-fluid"
                                  alt="eye-icon"
                                />
                              </span>
                            </div>
                            {renderFieldError("old_password")}
                          </div>
                        </div>
                        <div className="col-12 col-lg-12">
                          <div className="form-group">
                            <label>New Password</label>
                            <div className=" password-check">
                              <input
                                type={showNewPassoword ? "text" : "password"}
                                name="new_password"
                                className="form-control-form"
                                placeholder="New Password"
                              />
                              <span
                                onClick={toggleNewPasswordVisibility}
                                className={`form-icon-password toggle-password ${
                                  showNewPassoword ? "eye-open" : "eye-close"
                                }`}
                              >
                                <img
                                  src="./assets/images/eye.svg"
                                  className="img-fluid"
                                  alt="eye-icon"
                                />
                              </span>
                            </div>
                            {renderFieldError("new_password")}
                          </div>
                        </div>
                        <div className="col-12 col-lg-12">
                          <div className="form-group">
                            <label>Confirm Password</label>
                            <div className=" password-check">
                              <input
                                type={
                                  showConfirmPassoword ? "text" : "password"
                                }
                                name="confirm_password"
                                className="form-control-form"
                                placeholder="Confirm Password"
                              />

                              <span
                                onClick={toggleConfirmPasswordVisibility}
                                className={`form-icon-password toggle-password ${
                                  showConfirmPassoword
                                    ? "eye-open"
                                    : "eye-close"
                                }`}
                              >
                                <img
                                  src="./assets/images/eye.svg"
                                  className="img-fluid"
                                  alt="eye-icon"
                                />
                              </span>
                            </div>
                            {renderFieldError("confirm_password")}
                          </div>
                        </div>
                      </div>
                      <div className="save-btn my-3">
                        <button className="btn btn-fill">Update</button>
                      </div>
                    </div>
                  </div>
                  <div className="col-12 col-lg-4 mt-lg-0 mt-sm-3 mt-2 pt-lg-0 pt-1">
                    <div className="outer-heading text-center">
                      <h3 className="editprofilePic">Edit Profile Picture </h3>
                      <p>Lorem Ipsum is simply dummy text of the printing.</p>
                    </div>
                    <div className="upload-photo" style={{ border: border }}>
                      <div className="containers">
                        <div className="imageWrapper">
                          {previewImageUrl == "" ? (
                            <img
                              className="image"
                              src={
                                userData.profile_image != ""
                                  ? userData.profile_image
                                  : "./assets/images/avtar-big.png"
                              }
                            />
                          ) : (
                            <img className="image" src={previewImageUrl} />
                          )}
                        </div>
                      </div>
                      <button className="file-upload">
                        <input
                          type="file"
                          className="file-input"
                          name="profile_image"
                          accept="image/gif, image/jpeg, image/png" 
                          onChange={previewImage}
                        />
                        Change Profile Photo
                      </button>
                    </div>
                    <p
                      style={{
                        padding: "6px",
                        textAlign: "center",
                        fontSize: "13px",
                        color: "red",
                        fontWeight: "700",
                      }}
                    >
                      {errorMsg}
                    </p>
                  </div>
                </div>
              </form>
            </div>
          </div>
        )}
      </section>
      <Footer />
    </>
  );
};
export default MyProfile;
