import React from "react";
import axios from "axios";
import {useNavigate , Link} from "react-router-dom";
const Cancel = () => {
    return (
        <div className="payment-bg">
          <div className="card payment-card">
            <span className="payment-card__danger"><i className="fa fa-times" aria-hidden="true"></i>
            </span>
            <h1 className="payment-card__msg">Sorry ! Payment Not Completed</h1>
            <h2 className="payment-card__submsg">Please try again</h2>
            <div className="payment-card__tags">
                <Link to="/">
                  <span className="payment-card__tag">Back to home</span>
                </Link>
            </div>
          </div>
        </div>
    )
}
export default Cancel;