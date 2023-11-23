import React from "react";

const PendingPage = () => {
    return (
        <>
          <div className="linkExpire successfull profile-verification-st">
            <div className="container">
              <div className="row align-items-center">
                <div className="col-lg-6 col-md-8 col-12 mx-auto">
                  <div className="cards st-bg-blue">
                        <div className="expireImg mx-auto">
                            <img
                                src="/assets/images/pending.svg"
                                className="w-100"
                                alt="expire link"
                            />
                        </div>
                        <div className="heading">Pending Status!</div>
                        <div className="subheading my-md-3 my-2">
                          Your form has been in Pending status.
                        </div>
                        <div className="subheading my-md-3 my-2">
                          Please wait for approval
                        </div>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </>
    );
}

export default PendingPage;