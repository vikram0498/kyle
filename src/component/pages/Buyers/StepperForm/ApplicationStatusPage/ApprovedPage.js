import React from "react";

const ApprorvedPage = ({handleNext}) => {
    return (
        <>
          <div className="linkExpire successfull">
            <div className="container h-100">
              <div className="row align-items-center mx-0">
                <div className="col-lg-10 col-md-11 col-12 mx-auto">
                  <div className="cards p-4">
                    <div className="row cardBody mx-0 py-5">
                      <div className="col-xl-6 col-lg-7 col-md-8 col-9 mx-auto">
                        <div className="expireImg mx-auto">
                            <img
                                src="/assets/images/congrats.svg"
                                className="w-100"
                                alt="expire link"
                            />
                        </div>
                      </div>
                      <div className="col-12 text-center">
                        <div className="heading">Congratulations !</div>
                        <div className="subheading my-md-3 my-2">
                          Your form request has been Approved.
                        </div>
                        <button className="btn btn-fill" onClick={handleNext}>Ok</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </>
    );
}

export default ApprorvedPage;