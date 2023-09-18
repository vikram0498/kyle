import React, { useState } from 'react';
import Button from 'react-bootstrap/Button';
import Modal from 'react-bootstrap/Modal';
import { useAuth } from '../../../hooks/useAuth';
import ButtonLoader from '../MiniLoader';
import { toast } from "react-toastify";
import axios from 'axios';

const EditRequest = ({editOpen,setEditOpen,buyerId,buyerType,pageNumber,getFilterResult,buyerData,setBuyerData}) => {
    const [loading, setLoading] = useState(false);
    const [checkboxError, setCheckboxError] = useState(false);

  const handleClose = () => {
    setEditOpen(false);
    setLoading(false);
  };
  const {getTokenData,setLogout} = useAuth();
  const handleSubmit = (e) => {
    e.preventDefault();
    var formData = new FormData(e.target);
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    let Obj = {'name':false,'email':false,'phone':false,'other':false};
    let flag =true;
    for (const checkbox of checkboxes) {
        formData.delete(checkbox.name);
        if (checkbox.checked) {
          Obj[checkbox.name] = true;
          flag = false;
        }
    }
    if(flag){
        setCheckboxError(true);
        return false;
    }
    formData.append('incorrect_info', JSON.stringify(Obj));
    let buyerUserId = formData.get('buyer_id');
    const apiUrl = process.env.REACT_APP_API_URL;
    let headers = { 
        'Accept': 'application/json',
        'Authorization': 'Bearer ' + getTokenData().access_token,
        'auth-token' : getTokenData().access_token,
    };
    setLoading(true);
    setCheckboxError(false);
    async function fetchData() {
        try{
            const response = await axios.post(apiUrl+"red-flag-buyer",formData,{headers: headers});
            if(response.data.status){
                toast.success(response.data.message, {position: toast.POSITION.TOP_RIGHT});
                handleClose();
                const obj = buyerData.find(obj => obj.id == buyerUserId);
                if (obj) {
                    obj.redFlag = true;
                }
                setBuyerData(buyerData);
                //getFilterResult(pageNumber,activeTab,buyerType,);
            }else{
                //console.log('false ',response.data.message);
                toast.error(response.data.message, {position: toast.POSITION.TOP_RIGHT});
            }
        }catch(error){
            if(error.response) {
                if(error.response.status === 401){
					setLogout();
				}
                if (error.response.data.error) {
                    toast.error(error.response.data.error, {position: toast.POSITION.TOP_RIGHT});
                }
            }
        }
    }
    fetchData();
  }

  return (
    <div>
      <Modal show={editOpen} onHide={handleClose} className="modal-form-main">
        <button type="button" className="btn-close" onClick={handleClose}>
            <i className='fa fa-times fa-lg'></i>
        </button>
        <Modal.Body>
            <div className="want-to-edit">
                <div className="popup-heading-block text-center">
                    <span className="popup-icon">
                        <svg width="12" height="53" viewBox="0 0 12 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6.00131 17.847C2.74342 17.847 0.426758 19.2228 0.426758 21.2499V48.8328C0.426758 50.5705 2.74342 52.3077 6.00131 52.3077C9.1144 52.3077 11.648 50.5705 11.648 48.8328V21.2495C11.648 19.2226 9.1144 17.847 6.00131 17.847Z" fill="#3F53FE" fillOpacity="0.23"></path>
                            <path d="M6.0008 0.83374C2.67051 0.83374 0.0644531 3.2228 0.0644531 5.97388C0.0644531 8.72517 2.67073 11.1866 6.0008 11.1866C9.25869 11.1866 11.8652 8.72517 11.8652 5.97388C11.8652 3.2228 9.25848 0.83374 6.0008 0.83374Z" fill="#3F53FE" fillOpacity="0.23"></path>
                        </svg>
                    </span>
                    <h3>Do you want to edit?</h3>
                    <p>Please share the reason with us</p>
                </div>
                <form className="modal-form" method="post" onSubmit={handleSubmit}>
                    <div className="row">
                        <div className="col-12 col-lg-12 mb-4">
                            <div className="row">
                                <label>What Information is incorrect<span className='error'> *</span></label>
                                 <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                    <div className="form-check">
                                        <input className="form-check-input" type="checkbox" name="name" value="1" id="flexCheckChecked"/>
                                        <label className="form-check-label" htmlFor="flexCheckChecked"> Name </label>
                                    </div>
                                 </div>
                                 <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                    <div className="form-check">
                                        <input className="form-check-input" type="checkbox" name="email" value="1" id="flexCheckChecked"/>
                                        <label className="form-check-label" htmlFor="flexCheckChecked"> Email </label>
                                    </div>
                                 </div>
                                 <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                    <div className="form-check">
                                        <input className="form-check-input" type="checkbox" name="phone" value="1" id="flexCheckChecked"/>
                                        <label className="form-check-label" htmlFor="flexCheckChecked">Phone</label>
                                    </div>
                                 </div>
                                 <div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                    <div className="form-check">
                                        <input className="form-check-input" type="checkbox" name="other" value="1" id="flexCheckChecked"/>
                                        <label className="form-check-label" htmlFor="flexCheckChecked">Other</label>
                                    </div>
                                 </div>
                            </div>
                            {(checkboxError)?<p className="error">This field is required </p>:''}
                        </div>
                        <div className="col-12 col-lg-12">
                            <input type="hidden" value={buyerId} name="buyer_id"/>
                            <div className="form-group">
                                <label>message Type here</label>
                                <textarea placeholder="Enter Your Message" name="reason"></textarea>
                            </div>
                        </div>
                        <div className="col-12 col-lg-12">
                            <div className="form-group mb-0">
                                <div className="submit-btn">
                                    <button type="submit" className="btn btn-fill" disabled={ loading ? 'disabled' : ''}>Submit { loading ? <ButtonLoader/> : ''} </button>
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
}
export default EditRequest;