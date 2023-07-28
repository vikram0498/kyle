import React, {useContext, useEffect, useState} from "react";
import {useNavigate , Link} from "react-router-dom";
import AuthContext from "../../context/authContext";
import {useAuth} from "../../hooks/useAuth";
import {useForm} from "../../hooks/useForm";
import { toast } from "react-toastify";
import axios from 'axios';
const UploadMultipleBuyers = () => {
    const {authData} = useContext(AuthContext);
    const {getTokenData} = useAuth();
    const apiUrl = process.env.REACT_APP_API_URL;
    const navigate = useNavigate();

    /** upload multiple buyer using csv  */
    const [csvFile, setCsvFile] = useState();
    const [border, setBorder] = useState("1px dashed #677AAB");
    const [validCsv, setValidCsv] = useState(true);
    const [errorMsg, setErrorMsg] = useState('');
    const formData = new FormData();
    if (csvFile){
        formData.append('csvFile', csvFile);
    }
    const handleFileChange = (event) => {
        const file = event.target.files[0];
        const maxFileSize = 1 * 1024 * 1024; // 5MB
        const fileSize = file.size;
        const fileType = file.type;
        if(fileType != 'text/csv'){
            setBorder('1px dashed #ff0018');
            setValidCsv(false);
            setErrorMsg('Please add valid file');
        }else if (fileSize > maxFileSize) {
            setBorder('1px dashed #ff0018');
            setValidCsv(false);
            setErrorMsg('File size is too large. Please upload a file that is less than 5MB.');
        }else{
            setErrorMsg('');
            setBorder('1px dashed #677AAB');
            setValidCsv(true);
        }
        console.log(file);
        setCsvFile(file);
    };
    const submitCsvFile = (e) => {
        e.preventDefault();
        let headers = { 
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getTokenData().access_token,
            "Content-Type": "multipart/form-data"
        };
        async function fetchData() {
            const response = await axios.post(apiUrl+"upload-multiple-buyers-csv",formData,{headers: headers});
            if(response.data.status){
                setCsvFile('');
                toast.success(response.data.message, {position: toast.POSITION.TOP_RIGHT});
                navigate('/add-buyer-details');
            }
        }
        if(csvFile !=''){
            fetchData();
        }else{
            toast.error('Please Upload Csv First', {position: toast.POSITION.TOP_RIGHT});
        }
    };

    return(
        <>
        <form className="form-container" method='post' onSubmit={submitCsvFile}>
            <div className="outer-heading text-center">
                <h3>Upload Multiple Buyer </h3>
                <p><a href="/assets/sample/kyle-sample.csv">Download</a> sample CSV </p>
            </div>
            <div className="upload-single-data" style={{border:border}}>
                <div className="upload-files-container">
                    <div className="drag-file-area">
                        <button type="button" className="upload-button">
                            <img src="./assets/images/folder-big.svg" className="img-fluid" alt="" />
                        </button>
                        <label className="label d-block">
                            <span className="browse-files">
                                
                                <input type="file" name="csvFile" accept=".csv" onChange={handleFileChange}className="default-file-input"/> 
                                <span className="d-block upload-file">Upload your .CSV file</span>
                                <span className="browse-files-text">browse Now!</span> 
                            </span> 
                        </label>
                    </div>
                    <span className="cannot-upload-message"> <span className="error">error</span> Please select a file first <span className="cancel-alert-button">cancel</span> </span>
                    <div className="file-block">
                        <div className="file-info"><span className="file-name"> </span> | <span className="file-size">  </span> </div>
                        <span className="remove-file-icon">
                            <img src="./assets/images/remove-file-icon.svg" className="img-fluid" alt="" />
                            {/* <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="20" height="20" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" className=""><g><path d="M424 64h-88V48c0-26.51-21.49-48-48-48h-64c-26.51 0-48 21.49-48 48v16H88c-22.091 0-40 17.909-40 40v32c0 8.837 7.163 16 16 16h384c8.837 0 16-7.163 16-16v-32c0-22.091-17.909-40-40-40zM208 48c0-8.82 7.18-16 16-16h64c8.82 0 16 7.18 16 16v16h-96zM78.364 184a5 5 0 0 0-4.994 5.238l13.2 277.042c1.22 25.64 22.28 45.72 47.94 45.72h242.98c25.66 0 46.72-20.08 47.94-45.72l13.2-277.042a5 5 0 0 0-4.994-5.238zM320 224c0-8.84 7.16-16 16-16s16 7.16 16 16v208c0 8.84-7.16 16-16 16s-16-7.16-16-16zm-80 0c0-8.84 7.16-16 16-16s16 7.16 16 16v208c0 8.84-7.16 16-16 16s-16-7.16-16-16zm-80 0c0-8.84 7.16-16 16-16s16 7.16 16 16v208c0 8.84-7.16 16-16 16s-16-7.16-16-16z" fill="#000000" data-original="#000000" className=""></path></g></svg> */}
                        </span>
                        <div className="progress-bar"> </div>
                    </div>
                </div>
            </div>
            {(validCsv) ?<div className="submit-btn my-30">
                <button className="btn btn-fill">Submit Now!</button> 
            </div>:<p style={{padding: '6px',textAlign: 'center',fontSize: '13px',color: 'red',fontWeight: '700'}}>{errorMsg}</p>}
        </form>
        </>
    );
}
 export default UploadMultipleBuyers;