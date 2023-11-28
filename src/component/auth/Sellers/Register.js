import React, { useState, useEffect } from "react";
import axios from "axios";
import { toast } from "react-toastify";
import { Link } from "react-router-dom";
import ButtonLoader from "../../partials/MiniLoader";
import Layout from "../Layout";
import ReCAPTCHA from "react-google-recaptcha";
import { useFormError } from "../../../hooks/useFormError";
import GoogleLoginComponent from "../../partials/SocialLogin/GoogleLoginComponent";
import { GoogleOAuthProvider } from "@react-oauth/google";
import FacebookLoginButton from "../../partials/SocialLogin/FacebookLoginButton";
import { useForm } from "react-hook-form";
import { useAuth } from "../../../hooks/useAuth";
const Register = () => {
  const {getTokenData } = useAuth();

  const {
    register,
    handleSubmit,
    watch,
    formState: { errors },
  } = useForm();

  useEffect(() => {
    let login = getTokenData();
    if (login) {
      navigate("/");
    }
  });

  const apiUrl = process.env.REACT_APP_API_URL;
  const capchaSiteKey = process.env.REACT_APP_RECAPTCHA_SITE_KEY;
  const capchaSecretKey = process.env.REACT_APP_RECAPTCHA_SECRET_KEY;
  const [showPassoword, setshowPassoword] = useState(false);
  const togglePasswordVisibility = () => {
    setshowPassoword(!showPassoword);
  };

  const [showConfirmPassword, setshowConfirmPassword] = useState(false);
  const toggleConfirmPasswordVisibility = () => {
    setshowConfirmPassword(!showConfirmPassword);
  };

  const [first_name, setFirstName] = useState("");
  const [last_name, setLastName] = useState("");
  const [email, setEmail] = useState("");
  const [phone, setPhone] = useState("");
  const [company_name, setCompanyName] = useState("");
  const [password, setPassword] = useState("");
  const [password_confirmation, setPasswordConfirmation] = useState("");

  const [captchaVerified, setCaptchaVerified] = useState(false);
  const [recaptchaError, setRecaptchaError] = useState("");

  const { setErrors, renderFieldError, navigate } = useFormError();

  const [loading, setLoading] = useState(false);

  function onCaptchaChange(value) {
    if (value) {
      setCaptchaVerified(true);
    } else {
      setCaptchaVerified(false);
    }
  }

  const submitRegisterForm = (data, e) => {
    e.preventDefault();
    setErrors(null);

    setRecaptchaError("");

    setLoading(true);

    if (captchaVerified) {
      // let payload = {
      //     first_name,
      //     last_name,
      //     email,
      //     phone,
      //     company_name,
      //     password,
      //     password_confirmation,
      // };
      var data = new FormData(e.target);
      let payload = Object.fromEntries(data.entries());
      //console.log(payload,'formData');
      let headers = {
        Accept: "application/json",
      };

      axios
        .post(apiUrl + "register", payload, { headers: headers })
        .then((response) => {
          setLoading(false);
          if (response.data.status) {
            toast.success(
              "Registration successful. Please check your email for verification.",
              {
                position: toast.POSITION.TOP_RIGHT,
              }
            );
            navigate("/login");
          }
        })
        .catch((error) => {
          setLoading(false);
          if (error.response) {
            if (error.response.data.validation_errors) {
              setErrors(error.response.data.validation_errors);
            }
            if (error.response.data.error) {
              toast.error(error.response.data.error, {
                position: toast.POSITION.TOP_RIGHT,
              });
            }
          }
        });
    } else {
      setLoading(false);
      setRecaptchaError("Please complete reCAPTCHA verification.");
    }
  };
  const handleChangeFirstName = (e) => {
    const regex = /^[a-zA-Z]+$/;
    const new_value = e.target.value.replace(/[^a-zA-Z]/g, "");
    //console.log(new_value,'new_value ');
    if (regex.test(new_value)) {
      setFirstName(new_value);
    } else {
      setFirstName("");
    }
  };
  const handleChangeLastName = (e) => {
    const regex = /^[a-zA-Z]+$/;
    const new_value = e.target.value.replace(/[^a-zA-Z]/g, "");
    if (regex.test(new_value)) {
      //console.log(new_value);
      setLastName(new_value);
    } else {
      setLastName("");
    }
  };
  return (
    <Layout>
      <div className="account-in">
        <div className="center-content">
          <img src="./assets/images/logo.svg" className="img-fluid" alt="" />
          <h2>Welcome to Inucation!</h2>
        </div>
        <form
          method="POST"
          action="#"
          onSubmit={handleSubmit(submitRegisterForm)}
        >
          <div className="row main-login-form">
            <div className="col-12 col-sm-6 col-md-6 col-lg-6">
              <div className="form-group">
                <label htmlFor="first_name">
                  First Name <span style={{ color: "red" }}>*</span>
                </label>
                <div className="form-group-inner">
                  <span className="form-icon">
                    <img
                      src="./assets/images/user.svg"
                      className="img-fluid"
                      alt=""
                    />
                  </span>
                  <input
                    type="text"
                    name="first_name"
                    id="first_name"
                    className="form-control"
                    autoComplete="off"
                    //value={first_name}
                    //onChange={handleChangeFirstName}
                    placeholder="First Name"
                    disabled={loading ? "disabled" : ""}
                    {...register("first_name", {
                      required: "First Name is required",
                      validate: {
                        maxLength: (v) =>
                          v.length <= 50 ||
                          "The first name should have at most 50 characters",
                        matchPattern: (v) =>
                          /^[a-zA-Z\s]+$/.test(v) ||
                          "First Name can not include number or special character",
                      },
                    })}
                  />
                </div>

                {errors.first_name && (
                  <p className="error">{errors.first_name?.message}</p>
                )}
                {renderFieldError("first_name")}
              </div>
            </div>
            <div className="col-12 col-sm-6 col-md-6 col-lg-6">
              <div className="form-group">
                <label htmlFor="last_name">
                  Last Name <span style={{ color: "red" }}>*</span>
                </label>
                <div className="form-group-inner">
                  <span className="form-icon">
                    <img
                      src="./assets/images/user.svg"
                      className="img-fluid"
                      alt=""
                    />
                  </span>
                  <input
                    type="text"
                    name="last_name"
                    id="last_name"
                    className="form-control"
                    autoComplete="off"
                    //value={last_name}
                    //onChange={handleChangeLastName}
                    placeholder="Last Name"
                    disabled={loading ? "disabled" : ""}
                    {...register("last_name", {
                      required: "Last Name is required",
                      validate: {
                        maxLength: (v) =>
                          v.length <= 50 ||
                          "The last name should have at most 50 characters",
                        matchPattern: (v) =>
                          /^[a-zA-Z\s]+$/.test(v) ||
                          "Last Name can not include number or special character",
                      },
                    })}
                  />
                </div>
                {errors.last_name && (
                  <p className="error">{errors.last_name?.message}</p>
                )}
                {renderFieldError("last_name")}
              </div>
            </div>
            <div className="col-12 col-sm-6 col-md-6 col-lg-6">
              <div className="form-group">
                <label htmlFor="email">
                  Email <span style={{ color: "red" }}>*</span>
                </label>
                <div className="form-group-inner">
                  <span className="form-icon">
                    <img
                      src="./assets/images/mail.svg"
                      className="img-fluid"
                      alt=""
                    />
                  </span>
                  <input
                    type="email"
                    name="email"
                    id="email"
                    className="form-control"
                    autoComplete="no-email"
                    //onChange={e => setEmail(e.target.value)}
                    placeholder="Enter Your Email"
                    disabled={loading ? "disabled" : ""}
                    {...register("email", {
                      required: "Email is required",
                      validate: {
                        maxLength: (v) =>
                          v.length <= 50 ||
                          "The email should have at most 50 characters",
                        matchPattern: (v) =>
                          /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(
                            v
                          ) || "Email address must be a valid address",
                      },
                    })}
                  />
                </div>
                {errors.email && (
                  <p className="error">{errors.email?.message}</p>
                )}
                {renderFieldError("email")}
              </div>
            </div>
            <div className="col-12 col-sm-6 col-md-6 col-lg-6">
              <div className="form-group">
                <label htmlFor="phone">
                  Mobile Number <span style={{ color: "red" }}>*</span>
                </label>
                <div className="form-group-inner">
                  <span className="form-icon">
                    <img
                      src="./assets/images/phone.svg"
                      className="img-fluid"
                      alt=""
                    />
                  </span>
                  <input
                    type="number"
                    name="phone"
                    id="phone"
                    className="form-control"
                    //value={phone}
                    //onChange={e => setPhone(e.target.value)}
                    placeholder="1234567890"
                    autoComplete="off"
                    disabled={loading ? "disabled" : ""}
                    {...register("phone", {
                      required: "Phone is required",
                      validate: {
                        matchPattern: (v) =>
                          /^[0-9]\d*$/.test(v) ||
                          "Please enter valid phone number",
                        maxLength: (v) =>
                          (v.length <= 15 && v.length >= 5) ||
                          "The phone number should be more than 4 digit and less than equal 15",
                      },
                    })}
                  />
                </div>
                {errors.phone && (
                  <p className="error">{errors.phone?.message}</p>
                )}
                {renderFieldError("phone")}
              </div>
            </div>
            <div className="col-12 col-lg-12">
              <div className="form-group">
                <label htmlFor="company_name">
                  Company Name <span style={{ color: "red" }}>*</span>
                </label>
                <div className="form-group-inner">
                  <span className="form-icon">
                    <img
                      src="./assets/images/map-pin.svg"
                      className="img-fluid"
                      alt=""
                    />
                  </span>
                  <input
                    type="text"
                    name="company_name"
                    id="company_name"
                    className="form-control"
                    autoComplete="off"
                    //value={company_name}
                    //onChange={e => setCompanyName(e.target.value)}
                    placeholder="Enter Company Name"
                    disabled={loading ? "disabled" : ""}
                    {...register("company_name", {
                      required: "Company Name is required",
                    })}
                  />
                </div>
                {errors.company_name && (
                  <p className="error">{errors.company_name?.message}</p>
                )}
                {renderFieldError("company_name")}
              </div>
            </div>
            <div className="col-12 col-lg-12">
              <div className="form-group">
                <label htmlFor="pass_log_id">
                  Password <span style={{ color: "red" }}>*</span>
                </label>
                <div className="form-group-inner">
                  <span className="form-icon">
                    <img
                      src="./assets/images/password.svg"
                      className="img-fluid"
                      alt=""
                    />
                  </span>
                  <input
                    type={showPassoword ? "text" : "password"}
                    name="password"
                    id="pass_log_id"
                    className="form-control"
                    //value={password}
                    //onChange={e => setPassword(e.target.value)}
                    placeholder="Enter Your Password"
                    autoComplete="off"
                    disabled={loading ? "disabled" : ""}
                    {...register("password", {
                      required: "Password is required",
                    })}
                  />
                  <span
                    onClick={togglePasswordVisibility}
                    className={`form-icon-password ${
                      showPassoword ? "eye-open" : "eye-close"
                    }`}
                  >
                    <img
                      src="./assets/images/eye.svg"
                      className="img-fluid"
                      alt=""
                    />
                  </span>
                </div>
                {errors.password && (
                  <p className="error">{errors.password?.message}</p>
                )}
                {renderFieldError("password")}
              </div>
            </div>
            <div className="col-12 col-lg-12">
              <div className="form-group">
                <label htmlFor="conpass_log_id">
                  Confirm password <span style={{ color: "red" }}>*</span>
                </label>
                <div className="form-group-inner">
                  <span className="form-icon">
                    <img
                      src="./assets/images/password.svg"
                      className="img-fluid"
                      alt=""
                    />
                  </span>
                  <input
                    type={showConfirmPassword ? "text" : "password"}
                    name="password_confirmation"
                    id="conpass_log_id"
                    className="form-control"
                    autoComplete="off"
                    //value={password_confirmation}
                    //onChange={e => setPasswordConfirmation(e.target.value)}
                    placeholder="Enter Your Confirm Password"
                    disabled={loading ? "disabled" : ""}
                    {...register("password_confirmation", {
                      required: "Confirm password is required",
                    })}
                  />
                  <span
                    onClick={toggleConfirmPasswordVisibility}
                    className={`form-icon-password toggle-password ${
                      showConfirmPassword ? "eye-open" : "eye-close"
                    }`}
                  >
                    <img
                      src="./assets/images/eye.svg"
                      className="img-fluid"
                      alt=""
                    />
                  </span>
                </div>
                {errors.password_confirmation && (
                  <p className="error">
                    {errors.password_confirmation?.message}
                  </p>
                )}
                {renderFieldError("password_confirmation")}
              </div>
            </div>
            <div className="col-12 col-lg-12">
              <div className="form-group mb-0">
                <ReCAPTCHA sitekey={capchaSiteKey} onChange={onCaptchaChange} />
                {recaptchaError && (
                  <span className="invalid-feedback" role="alert">
                    <strong>{recaptchaError}</strong>
                  </span>
                )}
              </div>
            </div>
            <div className="col-12 col-lg-12">
              <div className="form-group-btn">
                <button
                  type="submit"
                  className="btn btn-fill"
                  disabled={loading ? "disabled" : ""}
                >
                  Register Now!
                  {loading ? <ButtonLoader /> : ""}
                </button>
              </div>
            </div>
            <div className="col-12 col-lg-12">
              <p className="account-now">
                Already Have an account? <Link to="/login">Login Now!</Link>
              </p>
              <div className="or">
                <span>OR</span>
              </div>
              <ul className="account-with-social list-unstyled mb-0 social-login-link">
                {/* <li>
                                    <Link to="https://facebook.com"><img src="./assets/images/facebook.svg" className="img-fluid" /> With Facebook</Link>
                                    <FacebookLoginButton
                                    apiUrl={apiUrl}
                                    setLoading={setLoading}
                                    navigate={navigate}
                                    setErrors={setErrors}
                                    />
                                </li> */}
                <li>
                  <Link to="https://google.com">
                    <img
                      src="./assets/images/google.svg"
                      className="img-fluid"
                      alt="google-icon"
                    />{" "}
                    With Google
                  </Link>
                  <GoogleOAuthProvider clientId="228707625591-afemor5re8dlrdjfvb0fht68g0apfjuv.apps.googleusercontent.com">
                    <GoogleLoginComponent
                      apiUrl={apiUrl}
                      setLoading={setLoading}
                      navigate={navigate}
                      setErrors={setErrors}
                    />
                  </GoogleOAuthProvider>
                </li>
              </ul>
            </div>
          </div>
        </form>
      </div>
    </Layout>
  );
};

export default Register;
