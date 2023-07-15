import React from 'react';


// import { Link } from 'react-router-dom';
import jwtDecode from "jwt-decode";

import { GoogleLogin, useGoogleLogin } from '@react-oauth/google';
// import googleImg from './../../assets/images/google.svg';



const GoogleLoginComponent = () => { 

    const auto_select = false;

    // const login = useGoogleLogin({
    //     onSuccess: tokenResponse => console.log(tokenResponse),
    //     flow:'implicit'
    //   });
    
    return (
        <div>

            {/* <Link onClick={() => login()}><img src={googleImg} className="img-fluid" /> With Google</Link>  */}
            
            <GoogleLogin
                onSuccess={credentialResponse => {
                    var decoded = jwtDecode(credentialResponse.credential);
                    console.log(decoded);
                }}
            />
            
        </div>
    );
}
  
export default GoogleLoginComponent;