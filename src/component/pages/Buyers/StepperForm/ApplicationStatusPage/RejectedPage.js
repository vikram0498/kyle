import React from "react";
const RejectedPage = ({setProfileVerificationStatus}) => {
    return (
        <>
          <div className="linkExpire successfull">
            <div className="container h-100">
              <div className="row align-items-center mx-0">
                <div className="col-lg-10 col-md-11 col-12 mx-auto">
                  <div className="cards p-4">
                    <div className="row cardBody mx-0 py-5">
                      <div className="col-xl-6 col-lg-7 col-md-8 col-9 mx-auto">
                      </div>
                      <div className="col-12 text-center">
                        <div className="heading">Rejected!</div>
                        <div className="subheading my-md-3 my-2">
                          Your form has been Rejected.
                        </div>
                        <div className="subheading my-md-3 my-2">
                          Please submit form again
                        </div>
                        <button className="btn btn-fill" onClick={()=>setProfileVerificationStatus('')}>Ok</button>
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
export default RejectedPage;