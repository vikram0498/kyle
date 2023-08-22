import React,{useEffect} from "react";
import {useAuth} from "../../hooks/useAuth"; 
import axios from "axios";

import {useNavigate , Link} from "react-router-dom";
function Completion(props) {
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
    }catch(error){
      console.log(error);
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
  