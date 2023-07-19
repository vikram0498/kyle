import React from 'react';
// import { Link } from 'react-router-dom';

import jwtDecode from "jwt-decode";
import { GoogleLogin, useGoogleLogin } from '@react-oauth/google';

import {useAuth} from "../../../hooks/useAuth";

// import googleImg from './../../assets/images/google.svg';
import axios from 'axios';
import { toast } from 'react-toastify';

const GoogleLoginComponent = ({apiUrl , setLoading, navigate, setErrors}) => { 

    const auto_select = false;
    // const login = useGoogleLogin({
    //     onSuccess: tokenResponse => console.log(tokenResponse),
    //     flow:'implicit'
    //   });
    const {setAsLogged} = useAuth();

    const googleLogin = (data) =>{
        let headers = {
            "Accept": "application/json", 
        }
        console.log('api data',data);
        axios.post(apiUrl+'handle-google', data, { headers: headers }).then(response => {
            setLoading(false);
            console.log('res ',response.data);
            if(response.data.status) {
                toast.success('Login successfully!', {
                    position: toast.POSITION.TOP_RIGHT
                });
                setAsLogged(response.data.access_token);
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