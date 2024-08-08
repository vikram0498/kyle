import React, { useState } from "react";
import Button from "react-bootstrap/Button";
import Modal from "react-bootstrap/Modal";
import { useAuth } from "../../../hooks/useAuth";
import ButtonLoader from "../MiniLoader";
import { toast } from "react-toastify";
import axios from "axios";

const EditRequest = ({
  editOpen,
  setEditOpen,
  buyerId,
  buyerType,
  pageNumber,
  getFilterResult,
  buyerData,
  setBuyerData,
}) => {
  const [loading, setLoading] = useState(false);
  const [checkboxError, setCheckboxError] = useState(false);
  const [checkMessage, setCheckMessage] = useState("");
  const [checkMessageError, setCheckMessageError] = useState(false);

  const handleClose = () => {
    setEditOpen(false);
    setLoading(false);
    setCheckboxError(false);
    setCheckMessageError(false);
  };
  const { getTokenData, setLogout } = useAuth();
  const handleSubmit = (e) => {
    e.preventDefault();
    var formData = new FormData(e.target);
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    let Obj = { name: false, email: false, phone: false, other: false };
    let flag = true;
    for (const checkbox of checkboxes) {
      formData.delete(checkbox.name);
      if (checkbox.checked) {
        Obj[checkbox.name] = true;
        flag = false;
      }
    }
    if (flag) {
      setCheckboxError(true);
      if (checkMessage.trim() === "") {
        setCheckMessageError(true);
      } else {
        setCheckMessageError(false);
      }
      return false;
    } else {
      setCheckboxError(false);
    }
    if (checkMessage == "") {
      setCheckMessageError(true);
      return false;
    }
    formData.append("incorrect_info", JSON.stringify(Obj));
    let buyerUserId = formData.get("buyer_id");
    const apiUrl = process.env.REACT_APP_API_URL;
    let headers = {
      Accept: "application/json",
      Authorization: "Bearer " + getTokenData().access_token,
      "auth-token": getTokenData().access_token,
    };
    setLoading(true);
    setCheckboxError(false);
    async function fetchData() {
      try {
        const response = await axios.post(apiUrl + "red-flag-buyer", formData, {
          headers: headers,
        });
        if (response.data.status) {
          toast.success(response.data.message, {
            position: toast.POSITION.TOP_RIGHT,
          });
          handleClose();
          const obj = buyerData.find((obj) => obj.id == buyerUserId);
          if (obj) {
            obj.redFlag = true;
          }
          setBuyerData(buyerData);
          //getFilterResult(pageNumber,activeTab,buyerType,);
        } else {
          //console.log('false ',response.data.message);
          toast.error(response.data.message, {
            position: toast.POSITION.TOP_RIGHT,
          });
        }
      } catch (error) {
        setLoading(false);
        if (error.response) {
          if (error.response.status === 401) {
            setLogout();
          }
          if (error.response.data.error) {
            toast.error(error.response.data.error, {
              position: toast.POSITION.TOP_RIGHT,
            });
          }
        }
      }
    }
    fetchData();
  };
  const handleMessage = (e) => {
    setCheckMessage(e.target.value.trim());
  };
  return (
    <div>
      <Modal show={editOpen} onHide={handleClose} className="modal-form-main">
        <button type="button" className="btn-close" onClick={handleClose}>
          <i className="fa fa-times fa-lg"></i>
        </button>
        <Modal.Body>
          <div className="want-to-edit">
            <div className="popup-heading-block text-center">
              <img src="/assets/images/red-flag-bg.svg" class="img-fluid w-25" alt=""/>
              <h3>Flag Data</h3>
              <p>Please report the incorrect information</p>
            </div>
            <form className="modal-form" method="post" onSubmit={handleSubmit}>
              <div className="row">
                <div className="col-12 col-lg-12 mb-4">
                  <div className="row">
                    <label>
                      What Information is incorrect
                      <span className="error"> *</span>
                    </label>
                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                      <div className="form-check">
                        <input
                          className="form-check-input"
                          type="checkbox"
                          name="name"
                          value="1"
                          id="flexCheckChecked"
                        />
                        <label
                          className="form-check-label"
                          htmlFor="flexCheckChecked"
                        >
                          Name
                        </label>
                      </div>
                    </div>
                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                      <div className="form-check">
                        <input
                          className="form-check-input"
                          type="checkbox"
                          name="email"
                          value="1"
                          id="flexCheckChecked"
                        />
                        <label
                          className="form-check-label"
                          htmlFor="flexCheckChecked"
                        >
                          {" "}
                          Email{" "}
                        </label>
                      </div>
                    </div>
                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                      <div className="form-check">
                        <input
                          className="form-check-input"
                          type="checkbox"
                          name="phone"
                          value="1"
                          id="flexCheckChecked"
                        />
                        <label
                          className="form-check-label"
                          htmlFor="flexCheckChecked"
                        >
                          Phone
                        </label>
                      </div>
                    </div>
                    <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                      <div className="form-check">
                        <input
                          className="form-check-input"
                          type="checkbox"
                          name="other"
                          value="1"
                          id="flexCheckChecked"
                        />
                        <label
                          className="form-check-label"
                          htmlFor="flexCheckChecked"
                        >
                          Other
                        </label>
                      </div>
                    </div>
                  </div>
                  {checkboxError ? (
                    <p className="error">This field is required </p>
                  ) : (
                    ""
                  )}
                </div>
                <div className="col-12 col-lg-12">
                  <input type="hidden" value={buyerId} name="buyer_id" />
                  <div className="form-group">
                    <label>
                      message Type here <span className="error"> *</span>
                    </label>
                    <textarea
                      placeholder="Enter Your Message"
                      name="reason"
                      onChange={handleMessage}
                    ></textarea>
                  </div>
                  {checkMessageError ? (
                    <p className="error">This field is required </p>
                  ) : (
                    ""
                  )}
                </div>
                <div className="col-12 col-lg-12">
                  <div className="form-group mb-0">
                    <div className="submit-btn">
                      <button
                        type="submit"
                        className="btn btn-fill"
                        disabled={loading ? "disabled" : ""}
                      >
                        Submit {loading ? <ButtonLoader /> : ""}{" "}
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </Modal.Body>
      </Modal>
    </div>
  );
};
export default EditRequest;
