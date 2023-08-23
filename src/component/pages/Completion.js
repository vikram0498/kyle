import React,{ useEffect, useState } from "react";
import {useAuth} from "../../hooks/useAuth"; 
import { toast } from "react-toastify";
import { useFormError } from '../../hooks/useFormError';
import axios from "axios";
import {useNavigate , Link} from "react-router-dom";

function Completion(props) {
  const { setErrors, renderFieldError } = useFormError();
  const body = document.querySelector('body');
  body.classList.remove('bg-img');
  const navigate = useNavigate();
  const {getTokenData} = useAuth();
  const apiUrl = process.env.REACT_APP_API_URL;

  const paramsObject = decodeURI(window.location.search)
  .replace('?', '')
  .split('&')
  .map(param => param.split('='))
  .reduce((values, [ key, value ]) => {
    values[ key ] = value
    return values
  }, {});
  const sendPaymentDetails = async () => {
    try{
      let headers = {
        "Accept": "application/json", 
        'Authorization': 'Bearer ' + getTokenData().access_token,
        'auth-token' : getTokenData().access_token,
      }
      const response = await axios.post(`${apiUrl}subscribe`,paramsObject, { headers: headers });
      if(response.data.status){
        const existingUser = JSON.parse(localStorage.getItem('user_data'));
        existingUser.level_type = 2;
        localStorage.setItem('user_data', JSON.stringify(existingUser));
        setTimeout(() => {
          navigate("/sellers-form?search")
        }, 2000);
      }
    }catch(error){
      if(error.response) {
        if (error.response.errors) {
            setErrors(error.response.errors);
        }
        if (error.response.error) {
            toast.error(error.response.error, {position: toast.POSITION.TOP_RIGHT});
        }
    }
    }
  }
  
  useEffect(()=>{
    sendPaymentDetails();
  },[]);

    return (
      <div className="payment-bg">
        <div className="card payment-card">
          <span className="payment-card__success"><i className="fa fa-check" aria-hidden="true"></i>
          </span>
          <h1 className="payment-card__msg">Payment Complete</h1>
          <h2 className="payment-card__submsg">Thank you for your transfer</h2>
          <div className="payment-card__tags">
              <Link to="/">
                <span className="payment-card__tag">Back to home</span>
              </Link>
          </div>
        </div>
      </div>
    )
  }
  
  export default Completion;
  