import React from 'react';
// import { Link } from 'react-router-dom';

import jwtDecode from "jwt-decode";
import { GoogleLogin, useGoogleLogin } from '@react-oauth/google';

import {useAuth} from "../../../hooks/useAuth";

// import googleImg from './../../assets/images/google.svg';
import axios from 'axios';
import { toast } from 'react-toastify';
import { toHaveAttribute } from '@testing-library/jest-dom/matchers';

const GoogleLoginComponent = ({apiUrl , setLoading, navigate, setErrors, firebaseDeviceToken}) => { 

    const auto_select = false;
    // const login = useGoogleLogin({
    //     onSuccess: tokenResponse => console.log(tokenResponse),
    //     flow:'implicit'
    //   });
    const {setAsLogged} = useAuth();

    const googleLogin = (data) =>{
        data.device_token = firebaseDeviceToken;
        let headers = {
            "Accept": "application/json", 
        }
        axios.post(apiUrl+'handle-google', data, { headers: headers }).then(response => {
            setLoading(toHaveAttribute);
            if(response.data.status) {
                setLoading(false);
                toast.success('Login successfully!', {
                    position: toast.POSITION.TOP_RIGHT
                });
                setAsLogged(response.data.access_token, '', '', response.data.userData);
            }
        }).catch(error => {
            setLoading(false);
            if(error.response) {
                if (error.response.data.validation_errors) {
                    setErrors(error.response.data.validation_errors);
                }
                if (error.response.data.error) {
                    toast.error(error.response.data.error, {position: toast.POSITION.TOP_RIGHT});
                }
            }
        });
    }
    return (
        <div>
            {/* <Link onClick={() => login()}><img src={googleImg} className="img-fluid" /> With Google</Link>  */}
           
            <GoogleLogin
                onSuccess={credentialResponse => {
                    var decoded = jwtDecode(credentialResponse.credential);
                    decoded.isGoogleLogin = true;
                    googleLogin(decoded);
                }}
            />
            
        </div>
    );
}
  
export default GoogleLoginComponent;