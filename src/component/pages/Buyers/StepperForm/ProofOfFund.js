import React, { useState } from "react";

const ProofOfFund = ({ register, errors }) => {
  const [previewBankUrl, setPreviewBankUrl] = useState("");

  const previewImage = (e, type) => {
    setPreviewBankUrl("");
    if (e.target.files[0]) {
      const reader = new FileReader();
      reader.addEventListener("load", () => {
        if (type === "bank_statement_pdf") {
          setPreviewBankUrl(reader.result);
        }
      });
      reader.readAsDataURL(e.target.files[0]);
    }
  };
  const validateFileSize = (file) => {
    let extension = ["application/pdf"];
    if (!extension.includes(file[0].type)) {
      return "Please add valid file (pdf)";
    } else if (file[0].size > 5097152) {
      return "File size is too large. Please upload a file that is less than 5MB.";
    }
    return true;
  };
  return (
    <>
      <fieldset>
        <div className="card-box-blocks main-card-area">
          <div className="row">
            <div className="col-12 col-lg-6">
              <div className="lic-detail-area">
                <label>Bank Statement</label>
                <div className="file-upload-choosen licenses-wrapper">
                  <div className="upload-photo">
                    <div className="containers">
                      <div className="imageWrapper">
                        <div className="imageWrapper">
                          {previewBankUrl == "" ? (
                            <img
                              className="image"
                              src="./assets/images/front-licenses.svg"
                            />
                          ) : (
                            <img
                              className="image upload-img"
                              src={previewBankUrl}
                            />
                          )}
                        </div>
                      </div>
                    </div>
                    <button className="file-upload">
                      <input
                        type="file"
                        className="file-input"
                        name="bank_statement_pdf"
                        {...register("bank_statement_pdf", {
                          required: "This fields is required",
                          validate: {
                            fileSize: validateFileSize,
                          },
                        })}
                        onChange={(e) => {
                          previewImage(e, "bank_statement_pdf");
                        }}
                      />
                      Upload Bank Statement
                    </button>
                  </div>
                  {errors.bank_statement_pdf && (
                    <p className="error">
                      {errors.bank_statement_pdf?.message}
                    </p>
                  )}
                </div>
              </div>
            </div>
            <div className="col-12 col-lg-6">
              <div className="lic-detail-area oth-proof-right">
                <label>Other Proof of fund</label>
                <div className="form-group other-prooff">
                  <input
                    type="email"
                    className="form-control"
                    placeholder="example@gmail.com"
                    name="other_proof_of_fund"
                    {...register("other_proof_of_fund", {
                      required: "This Field is required",
                      validate: {
                        maxLength: (v) =>
                          v.length <= 50 ||
                          "The field should have at most 50 characters",
                        matchPattern: (v) =>
                          /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(
                            v
                          ) || "Email address must be a valid address",
                      },
                    })}
                  />
                  {errors.other_proof_of_fund && (
                    <p className="error">
                      {errors.other_proof_of_fund?.message}
                    </p>
                  )}
                </div>

                <div className="upload-btn">
                  <button type="submit" className="btn btn-fill w-auto">
                    Upload
                  </button>
                </div>
              </div>
              {/* <div className="col-12 col-lg-2">
              <div className="upload-btn">
                <button type="submit" className="btn btn-fill">
                  Upload
                </button>
              </div>
            </div> */}
            </div>
          </div>
        </div>
        {/* <input
                  type="button"
                  name="previous"
                  className="previous action-button-previous btn btn-fill"
                  value="Previous"
                />
                <input
                  type="button"
                  name="next"
                  className="next action-button btn btn-fill"
                  value="Next"
                /> */}
      </fieldset>
    </>
  );
};

export default ProofOfFund;
