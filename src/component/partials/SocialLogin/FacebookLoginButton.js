import React from 'react'
import FacebookLogin from 'react-facebook-login';
import {useAuth} from "../../../hooks/useAuth";
import axios from 'axios';
import { toast } from 'react-toastify';

const FacebookLoginButton = ({apiUrl , setLoading, navigate, setErrors}) => { 
    const {setAsLogged} = useAuth();
    const responseFacebook = (response) => {
        console.log('fb response',response);
        const handleFacebookLogin = () =>{
            let headers = {
                "Accept": "application/json", 
            }
            axios.post(apiUrl+'handle-facebook', response, { headers: headers }).then(response => {
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
        handleFacebookLogin();
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