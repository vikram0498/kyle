import { useEffect, useState } from "react";

import { Elements } from "@stripe/react-stripe-js";
import CheckoutForm from "./CheckoutForm";
import { loadStripe } from "@stripe/stripe-js";
import { useAuth } from "../../../hooks/useAuth";
import axios from "axios";

function Payment({ clientSecret, setClientSecret }) {
  const [stripePromise, setStripePromise] = useState(null);
  const { getTokenData } = useAuth();
  const apiUrl = process.env.REACT_APP_API_URL;

  useEffect(() => {
    getPublishableKey();
    //getPaymentIntent();
    // fetch("/config").then(async (r) => {
    //   const { publishableKey } = await r.json();
    //   setStripePromise(loadStripe(publishableKey));
    // });
  }, []);

  const getPublishableKey = async () => {
    try {
      let headers = {
        Accept: "application/json",
        Authorization: "Bearer " + getTokenData().access_token,
        "auth-token": getTokenData().access_token,
      };
      const response = await axios.get(`${apiUrl}config`, { headers: headers });
      setStripePromise(loadStripe(response.data.key));
    } catch (error) {
      console.log(error);
    }
  };

  // const getPaymentIntent = async () => {
  //   try{
  //     let headers = {
  //       "Accept": "application/json",
  //       'Authorization': 'Bearer ' + getTokenData().access_token,
  //       'auth-token' : getTokenData().access_token,
  //     }
  //     const response = await axios.get(`${apiUrl}create-payment-intent`, { headers: headers });
  //     console.log(response);
  //     setClientSecret(response.data);
  //   }catch(error){
  //     console.log(error);
  //   }
  // }
  // useEffect(() => {
  //   fetch("/create-payment-intent", {
  //     method: "POST",
  //     body: JSON.stringify({}),
  //   }).then(async (result) => {
  //     var { clientSecret } = await result.json();
  //     setClientSecret(clientSecret);
  //   });
  // }, []);

  return (
    <>
      <section className="main-section position-relative pt-4 pb-120">
        <div className="container position-relative">
          <div className="card-box mt-0">
            <h3>Please Complete Your Payment</h3>
            {clientSecret && stripePromise && (
              <Elements
                stripe={stripePromise}
                options={{ clientSecret }}
                disableIframe={true}
              >
                <CheckoutForm setClientSecret={setClientSecret} />
              </Elements>
            )}
          </div>
        </div>
      </section>
    </>
  );
}

export default Payment;
