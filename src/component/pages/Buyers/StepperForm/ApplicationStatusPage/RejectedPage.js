import React from "react";
const RejectedPage = ({setProfileVerificationStatus}) => {
    return (
        <>
          <div className="linkExpire successfull profile-verification-st">
            <div className="container">
              <div className="row align-items-center">
                <div className="col-lg-6 col-md-8 col-12 mx-auto">
                  <div className="cards">
                  <     div className="expireImg mx-auto">
                            <img
                                src="/assets/images/app-close.svg"
                                alt="expire link"
                            />
                        </div>
                        <div className="heading">Rejected!</div>
                        <div className="subheading my-md-3 my-2">
                          Your form has been Rejected.
                        </div>
                        <div className="subheading my-md-3 my-2">
                          Please submit form again
                        </div>
                        <button className="btn btn-fill" onClick={()=>setProfileVerificationStatus('')}>Okay</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </>
    );
}
export default RejectedPage;