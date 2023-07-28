import React, {useState} from 'react'
import FacebookLogin from 'react-facebook-login';
import {useAuth} from "../../../hooks/useAuth";
import axios from 'axios';
import { toast } from 'react-toastify';

const FacebookLoginButton = ({apiUrl , setLoading, navigate, setErrors}) => { 
    const {setAsLogged} = useAuth();
    const [fbAutoLoad, setFbAutoLoad] = useState(false);
    const responseFacebook = (response) => {
        if(response.accessToken !='' && response.accessToken != undefined){
            const handleFacebookLogin = () =>{
                let headers = {
                    "Accept": "application/json", 
                }
                axios.post(apiUrl+'handle-facebook', response, { headers: headers }).then(response => {
                    setLoading(false);
                    if(response.data.status) {
                        toast.success('Login successfully!', {
                            position: toast.POSITION.TOP_RIGHT
                        });
                        setAsLogged(response.data.access_token, '', '', response.data.userData);
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
    }
    const componentClicked = (data) => {
        setFbAutoLoad(true);
    }
    return(
    <>
        <FacebookLogin
            appId="504416868534509"
            fields="name,email,picture"
            onClick={componentClicked}
            callback={responseFacebook} 
        />
    </>
    )
}
export default FacebookLoginButton;