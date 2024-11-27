import { GoogleOAuthProvider } from '@react-oauth/google';
import React, { useState } from 'react'
import GoogleLoginComponent from './SocialLogin/GoogleLoginComponent';
import { Link } from "react-router-dom";
import { useFormError } from '../../hooks/useFormError';
import FacebookLoginButton from './SocialLogin/FacebookLoginButton';
const GoogleFacebookLogin = () => {
    const apiUrl = process.env.REACT_APP_API_URL;
    const googleClientId = process.env.REACT_APP_GOOGLE_CLIENT_ID;
    const { setErrors, renderFieldError, navigate } = useFormError();

    const [loading, setLoading] = useState(false);

  return (
    <ul className="account-with-social social-login-link list-unstyled mb-0 justify-content-start justify-content-lg-end mt-2 mt-lg-0">
        <li>
            <Link to="https://google.com">
            <img
                src="/assets/images/google.svg"
                className="img-fluid"
                alt="google-icon"
            />{" "}
            Register With Google
            </Link>
            <GoogleOAuthProvider clientId={googleClientId}>
            <GoogleLoginComponent
                apiUrl={apiUrl}
                setLoading={setLoading}
                navigate={navigate}
                setErrors={setErrors}
            />
            </GoogleOAuthProvider>
        </li>
        <li>
            <Link to="https://facebook.com"><img src="/assets/images/facebook.svg" className="img-fluid" alt='fb-icon'/> Register With Facebook</Link>
            <FacebookLoginButton
                apiUrl={apiUrl}
                setLoading={setLoading}
                navigate={navigate}
                setErrors={setErrors}
            />
        </li> 
    </ul>
  )
}

export default GoogleFacebookLogin