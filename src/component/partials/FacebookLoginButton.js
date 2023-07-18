import React from 'react'
import FacebookLogin from 'react-facebook-login';


const FacebookLoginButton = () => { 
    const responseFacebook = (response) => {
        console.log('fb response',response);
    }
    const componentClicked = (data) => {
        console.log("fb data",data);
    }
    return(
    <>
        <FacebookLogin
            appId="504416868534509"
            autoLoad={false}
            fields="name,email,picture"
            onClick={componentClicked}
            callback={responseFacebook} 
        />
    </>
    )
}
export default FacebookLoginButton;