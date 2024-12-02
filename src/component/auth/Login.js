import React, { useContext, useEffect, useState } from "react";
import axios from "axios";
import { toast } from "react-toastify";

import { Link } from "react-router-dom";
import { useFormError } from "../../hooks/useFormError";
import { useAuth } from "../../hooks/useAuth";

import ButtonLoader from "../partials/MiniLoader";
import GoogleLoginComponent from "../partials/SocialLogin/GoogleLoginComponent";
import { GoogleOAuthProvider } from "@react-oauth/google";
import FacebookLoginButton from "../partials/SocialLogin/FacebookLoginButton";
import Layout from "./Layout";
import { generatedToken,messaging } from "../../notifications/firebase";

function Login(props) {
  const { setAsLogged, getRememberMeData, getTokenData } = useAuth();
  // const { authData } = useContext(AuthContext);
  const { setErrors, renderFieldError, setMessage, navigate } = useFormError();

  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [remember, setRemember] = useState(false);
  const [loading, setLoading] = useState(false);
  const [firebaseDeviceToken, setFirebaseDeviceToken] = useState("");

  useEffect(() => {
    let login = getTokenData();
    let userData = getRememberMeData();
    if (userData != "" && userData != undefined) {
      setEmail(userData.username);
      setPassword(userData.password);
      setRemember(userData.isRemember);
    }
    if (login) {
      navigate("/");
    }
  }, []);

  const [showPassoword, setshowPassoword] = useState(false);
  const togglePasswordVisibility = () => {
    setshowPassoword(!showPassoword);
  };

  const apiUrl = process.env.REACT_APP_API_URL;
  const googleClientId = process.env.REACT_APP_GOOGLE_CLIENT_ID;

  const submitLoginForm = (e) => {
    e.preventDefault();
    setLoading(true);
    setErrors(null);
    setMessage("");
    let payload = { email, password };
    payload.device_token = firebaseDeviceToken;

    if (remember) {
      payload.remember = true;
    }
    let headers = {
      Accept: "application/json",
    };
    axios
      .post(apiUrl + "login", payload, { headers: headers })
      .then((response) => {
        setLoading(false);
        if (response.data.status) {
          // for buyer login
          var access_token = response.data.access_token;
          var remember_me_token = response.data.remember_me_token;
          let remember_me_user_data = {
            username: "",
            password: "",
            isRemember: false,
          };
          if (payload.remember) {
            remember_me_user_data = {
              username: email,
              password: password,
              isRemember: true,
            };
          }
          setAsLogged(
            access_token,
            remember_me_token,
            remember_me_user_data,
            response.data.userData
          );
          toast.success(response.data.message, {
            position: toast.POSITION.TOP_RIGHT,
          });
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
  };
  useState(()=>{
    const getToken = async () => {
      let deviceToken = await generatedToken();
      setFirebaseDeviceToken(deviceToken);
    }
    getToken();
  },[])

  return (
    <Layout>
      <div className="account-in">
        <div className="center-content">
          <img
            src="./assets/images/logo.svg"
            className="img-fluid"
            alt="logo"
          />
          <h2>Welcome Back!</h2>
        </div>
        <form
          className="main-login-form"
          method="post"
          onSubmit={submitLoginForm}
        >
          <div className="row">
            <div className="col-12 col-lg-12">
              <div className="form-group">
                <label htmlFor="email">Email</label>
                <div className="form-group-inner">
                  <span className="form-icon">
                    <img
                      src="./assets/images/mail.svg"
                      className="img-fluid"
                      alt="mail-icon"
                    />
                  </span>
                  <input
                    disabled={loading ? "disabled" : ""}
                    type="email"
                    id="email"
                    name="email"
                    className="form-control"
                    autoComplete="no-email"
                    placeholder="Enter Your Email"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    required
                    autoFocus
                  />
                </div>
                {renderFieldError("email")}
              </div>
            </div>
            <div className="col-12 col-lg-12">
              <div className="form-group">
                <label htmlFor="pass_log_id">password</label>
                <div className="form-group-inner">
                  <span className="form-icon">
                    <img
                      src="./assets/images/password.svg"
                      className="img-fluid"
                      alt="password-icon"
                    />
                  </span>
                  <input
                    disabled={loading ? "disabled" : ""}
                    id="pass_log_id"
                    name="password"
                    type={showPassoword ? "text" : "password"}
                    className="form-control"
                    placeholder="Enter Your Password"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    required
                    autoComplete="no-password"
                  />
                  <span
                    onClick={togglePasswordVisibility}
                    className={`form-icon-password toggle-password ${
                      showPassoword ? "eye-open" : "eye-close"
                    }`}
                  >
                    <img
                      src="./assets/images/eye.svg"
                      className="img-fluid"
                      alt="eye-icon"
                    />
                  </span>
                </div>
                {renderFieldError("password")}
              </div>
            </div>
            <div className="col-12 col-sm-6 col-md-6 col-lg-6">
              <div className="form-group-switch">
                <label className="switch">
                  <input
                    type="checkbox"
                    name="remember"
                    onChange={(e) => {
                      setRemember(e.target.checked ? 1 : 0);
                    }}
                    disabled={loading ? "disabled" : ""}
                    checked={remember ? "checked" : ""}
                  />
                  <span className="slider round"></span>
                </label>
                <span className="toggle-heading">Remember me</span>
              </div>
            </div>
            <div className="col-12 col-sm-6 col-md-6 col-lg-6">
              <Link to="/forget-password" className="link-pass">
                Forgot Password?
              </Link>
            </div>
            <div className="col-12 col-lg-12">
              <div className="form-group-btn mt-30">
                <button
                  type="submit"
                  className="btn btn-fill"
                  disabled={loading ? "disabled" : ""}
                >
                  Login Now! {loading ? <ButtonLoader /> : ""}{" "}
                </button>
              </div>
            </div>
            <div className="col-12 col-lg-12">
              {/* <p className="account-now"> Or Register as:</p> */}
                <div className="line-with-text">Or Register As</div>
                  <br></br>
                    <div className="row">
                       <div className="col-md-6"> 
                          <Link to="/register-buyer">
                            <button className="btn btn-fill btn-white">Buyer</button>
                          </Link>
                        </div>
                       <div className="col-md-6 mt-2 mt-md-0">
                        <Link to={"/register"}>
                          <button className="btn btn-fill">Wholesaler</button>
                        </Link>
                       </div>
                    </div>
              {/* <p className="account-now">
                Register as <Link to="/register">Wholesaler</Link>/<Link to="/register-buyer">Buyer</Link>
              </p> */}

              {/* <p className="account-now">
                <span style={{ marginRight: "2px" }}>
                  Don’t Have an account?{" "}
                </span>
                <Link to="/register"> Sign up now!</Link>
              </p> */}

              <div className="or">
                <span>OR</span>
              </div>
              <ul className="account-with-social social-login-link list-unstyled mb-0">
                <li>
                  <Link to="https://google.com">
                    <img
                      src="./assets/images/google.svg"
                      className="img-fluid"
                      alt="google-icon"
                    />{" "}
                    Login With Google
                  </Link>
                  {/* <Link to="https://google.com"><img src="./assets/images/google.svg" className="img-fluid" alt='google-icon'/> With Google</Link> */}
                  <GoogleOAuthProvider clientId={googleClientId}>
                    <GoogleLoginComponent
                      firebaseDeviceToken={firebaseDeviceToken}
                      apiUrl={apiUrl}
                      setLoading={setLoading}
                      navigate={navigate}
                      setErrors={setErrors}
                    />
                  </GoogleOAuthProvider>
                </li>
                <li>
                  <Link to="https://facebook.com"><img src="./assets/images/facebook.svg" className="img-fluid" alt='fb-icon'/>Login With Facebook</Link>
                    <FacebookLoginButton
                      firebaseDeviceToken={firebaseDeviceToken}
                      apiUrl={apiUrl}
                      setLoading={setLoading}
                      navigate={navigate}
                      setErrors={setErrors}
                    />
                </li>
              </ul>
            </div>
          </div>
        </form>
        <p className="support-text mt-4">
          Want to get in touch? We'd love to hear from you.{" "}
          <Link to="/support" style={{ color: "#3F53FE" }}>
            click here
          </Link>
        </p>
      </div>
    </Layout>
  );
}

export default Login;
