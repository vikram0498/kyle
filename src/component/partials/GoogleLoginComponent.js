import React from 'react';
// import { Link } from 'react-router-dom';

import jwtDecode from "jwt-decode";
import { GoogleLogin, useGoogleLogin } from '@react-oauth/google';

// import googleImg from './../../assets/images/google.svg';
import axios from 'axios';
import { toast } from 'react-toastify';

const GoogleLoginComponent = ({apiUrl , setLoading, navigate, setErrors}) => { 

    const auto_select = false;

    // const login = useGoogleLogin({
    //     onSuccess: tokenResponse => console.log(tokenResponse),
    //     flow:'implicit'
    //   });
    const googleLogin = (data) =>{
        console.log("response ", data);
        let headers = {
            "Accept": "application/json", 
        }

        axios.post(apiUrl+'register', data, { headers: headers }).then(response => {
            setLoading(false);
            if(response.data.user_data) {
                toast.success('Registration successful. Please check your email for verification.', {
                    position: toast.POSITION.TOP_RIGHT
                });
                navigate('/login');
            }
        }).catch(error => {
            setLoading(false);
            if(error.response) {
                if (error.response.data.errors) {
                    setErrors(error.response.data.errors);
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