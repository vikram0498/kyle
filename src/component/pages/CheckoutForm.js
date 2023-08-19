import { PaymentElement } from "@stripe/react-stripe-js";
import { useState } from "react";
import { useStripe, useElements,AddressElement } from "@stripe/react-stripe-js";

export default function CheckoutForm() {
  const stripe = useStripe();
  const elements = useElements();

  const [address, setAddress] = useState({
    addressLine1: "",
    addressLine2: "",
    city: "",
    state: "",
    country: "",
    zip: "",
  });

  const handleAddressChange = (event) => {
      setAddress({
        ...address,
        [event.target.name]: event.target.value,
      });
  };

  const [message, setMessage] = useState(null);
  const [isProcessing, setIsProcessing] = useState(false);
  const apiUrl = process.env.REACT_APP_API_URL;

  const handleSubmit = async (e) => {
    e.preventDefault();

    if (!stripe || !elements) {
      // Stripe.js has not yet loaded.
      // Make sure to disable form submission until Stripe.js has loaded.
      return;
    }

    setIsProcessing(true);
    const baseUrl = window.location.origin;
    console.log(`${baseUrl}/completion`,'ssss');
    const { error, payment  } = await stripe.confirmPayment({
      elements,
      confirmParams: {
        // Make sure to change this to your payment completion page
        return_url: `${baseUrl}/completion`,
      },
    });
    if (!error) {
      // The payment was successful.
      console.log(`Payment successful! Amount: ${payment}`);
    } 
    if (error.type === "card_error" || error.type === "validation_error") {
      setMessage(error.message);
    } else {
      setMessage("An unexpected error occured.");
    }

    setIsProcessing(false);
  };

  return (
    <form id="payment-form" onSubmit={handleSubmit}>
      <PaymentElement id="payment-element" />
      {/* <AddressElement
        addressMode="billing"
        value={address}
        onChange={handleAddressChange}
      /> */}
      <button disabled={isProcessing || !stripe || !elements} id="submit">
        <span id="button-text">
          {isProcessing ? "Processing ... " : "Pay now"}
        </span>
      </button>
      {/* Show any error or success messages */}
      {message && <div id="payment-message">{message}</div>}
    </form>
  );
}