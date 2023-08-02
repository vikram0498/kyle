import React ,{useEffect, useState} from 'react';
import Header from "../partials/Layouts/Header";
import Footer from "../partials/Layouts/Footer";
import {useNavigate , Link} from "react-router-dom";
import {useAuth} from "../../hooks/useAuth";
import {useForm} from "../../hooks/useForm";
import { toast } from "react-toastify";
import axios from 'axios';

 const MyProfile = () => {
    const apiUrl = process.env.REACT_APP_API_URL;
    const {getTokenData} = useAuth();
    const navigate = useNavigate();
    const [userData, setUserData] = useState('');
    const [isProfileUpdate, setIsProfileUpdate] = useState('false');
    const [errorMsg, setErrorMsg] = useState('');
    const [border, setBorder] = useState("1px dashed #677AAB");
    const [loader,setLoader] = useState(true);
    const { setErrors, renderFieldError } = useForm();
    const [previewImageUrl, setPreviewImageUrl] = useState('');
    const [firstName, setFirstName] = useState('');
    const [lastName, setLastName] = useState('');
    // console.log('sdss',getTokenData().access_token);
    let headers = {
        "Accept": "application/json", 
        'Authorization': 'Bearer ' + getTokenData().access_token,
        'auth-token' : getTokenData().access_token,
    }
    useEffect(()=>{
        axios.get(apiUrl+'user-details', { headers: headers }).then(response => {
            let { data } = response.data.data;
            setUserData(response.data.data);
            setFirstName(response.data.data.first_name);
            setLastName(response.data.data.last_name);
            const userData = {
                'first_name':response.data.data.first_name,
                'last_name':response.data.data.last_name,
                'profile_image':response.data.data.profile_image
            };
            localStorage.setItem('user_data', JSON.stringify(userData));
            const profileName = document.querySelector(".user-name-title");
            const profilePic = document.querySelector(".user-profile");
            
            profileName.innerHTML = response.data.data.first_name+" "+response.data.data.last_name;
            if(response.data.data.profile_image !=''){
                profilePic.src = response.data.data.profile_image;
            }
            setLoader(false);
        }).catch(error => { 
            toast.error(error.message, {position: toast.POSITION.TOP_RIGHT});
        })
    },[isProfileUpdate])

    const fetchUserData = () =>{
        axios.get(apiUrl+'user-details', { headers: headers }).then(response => {
            let { data } = response.data.data;
            setUserData(response.data.data);
            const userData = {
                'first_name':response.data.data.first_name,
                'last_name':response.data.data.last_name,
                'profile_image':response.data.data.profile_image
            };
            localStorage.setItem('user_data', JSON.stringify(userData));
        
            setLoader(false);
        }).catch(error => { 
            toast.error(error.message, {position: toast.POSITION.TOP_RIGHT});
        })
    }
    const handleSubmit = (e) => {
        e.preventDefault();
        setIsProfileUpdate('false');
        var data = new FormData(e.target);
        let formData = Object.fromEntries(data.entries());
        let headers = { 
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getTokenData().access_token,
            'auth-token' : getTokenData().access_token,
            "Content-Type": "multipart/form-data"
        };
        const maxFileSize = 2 * 1024 * 1024; // 5MB
        const fileSize = formData.profile_image.size;
        const fileType = formData.profile_image.type;
        const fileName = formData.profile_image.name;

        if(fileName!='' && fileType != 'image/png' && fileType != 'image/jpg' && fileType != 'image/jpeg'){
            setErrorMsg('Please add valid file (jpg,jpeg,png)');
            setBorder('1px dashed #ff0018');
            return false;
        }else if(fileSize > maxFileSize){
            setErrorMsg('File size is too large. Please upload a file that is less than 2MB.');
            setBorder('1px dashed #ff0018');
            return false;
        }else{
            setBorder('1px dashed #677AAB');
            setErrorMsg('');
            setLoader(true);
            setPreviewImageUrl('');
        }
        async function fetchData() {
           try{
            const response = await axios.post(apiUrl+"update-profile",formData,{headers: headers});
            if(response.data.status){
                toast.success(response.data.message, {position: toast.POSITION.TOP_RIGHT});
                
                setIsProfileUpdate('true');
                //await fetchUserData();
            }
           }catch(error){
            setLoader(false);
            if(error.response) {
                if (error.response.data.errors) {
                    setErrors(error.response.data.errors);
                }
                if (error.response.data.error) {
                    toast.error(error.response.data.error, {position: toast.POSITION.TOP_RIGHT});
                }
            }
           }
        }
        fetchData();
    }
    const previewImage = (e) =>{
        if (e.target.files[0]) {
            const reader = new FileReader();
            reader.addEventListener("load", () => {
              setPreviewImageUrl(reader.result);
            });
            reader.readAsDataURL(e.target.files[0]);
          }
    }

    const handleChangeFirstName = (e) => {
        const regex = /^[a-zA-Z]+$/;
        const new_value = e.target.value.replace(/[^a-zA-Z]/g, "");
        if (regex.test(new_value)) {
            setFirstName(new_value);
        }
    }
    const handleChangeLastName = (e) => {
        const regex = /^[a-zA-Z]+$/;
        const new_value = e.target.value.replace(/[^a-zA-Z]/g, "");
        if (regex.test(new_value)) {
            setLastName(new_value);
        }
    }
    return(
        <>
            <Header/>
            <section className="main-section position-relative pt-4 pb-120">
                <div className="container position-relative">
                    <div className="card-box mt-0">
                        {(loader) ? <div className="loader" style={{textAlign:'center'}}><img src="assets/images/loader.svg"/></div>:
                        <form className="profile-account" method="post" onSubmit={handleSubmit}>
                            <div className="row">
                                <div className="col-12 col-lg-8">
                                    <div className="card-box-inner">
                                        <h3>My Profile</h3>
                                        <p>Fill the below form OR send link to the buyer</p>
                                        <div className="row">
                                            <div className="col-12 col-md-6 col-lg-6">
                                                <div className="form-group">
                                                    <label>First Name</label>
                                                    <input type="text" name="first_name" className="form-control-form" placeholder="First Name" 
                                                    onChange={handleChangeFirstName}
                                                    value={firstName}/>
                                                    {renderFieldError('first_name') } 
                                                </div>
                                            </div>
                                            <div className="col-12 col-md-6 col-lg-6">
                                                <div className="form-group">
                                                    <label>Last Name</label>
                                                    <input type="text" name="last_name" className="form-control-form" placeholder="Last Name" 
                                                    value={lastName}
                                                    onChange={handleChangeLastName}/> 
                                                    {renderFieldError('last_name') }
                                                </div>
                                            </div>
                                            <div className="col-12 col-md-6 col-lg-6">
                                                <div className="form-group">
                                                    <label>Email</label>
                                                    {userData.email !='' && userData.email != null ? <p className="form-control-form">{userData.email}</p>:
                                                    <>
                                                    <input type="email" name="email" className="form-control-form" placeholder="Email" defaultValue={userData.email}/>
                                                    {renderFieldError('email') }
                                                    {/* <span>Verify Email</span> */}
                                                    </>
                                                    }
                                                </div>
                                            </div>
                                            <div className="col-12 col-md-6 col-lg-6">
                                                <div className="form-group">
                                                    <label>Phone Number</label>
                                                    <input type="text" name="phone" className="form-control-form" placeholder="Phone Number" defaultValue={userData.phone}/> 
                                                    {renderFieldError('phone') }
                                                </div>
                                            </div>
                                        </div>
                                        <div className="form-title">
                                            <h5>Change Password</h5>
                                        </div>
                                        <div className="row">
                                            <div className="col-12 col-lg-12">
                                                <div className="form-group">
                                                    <label>Old Password</label>
                                                    <input type="password" name="old_password" className="form-control-form" placeholder="Enter your old password"  /> 
                                                </div>
                                            </div>
                                            <div className="col-12 col-lg-12">
                                                <div className="form-group">
                                                    <label>New Password</label>
                                                    <input type="password" name="new_password" className="form-control-form" placeholder="New Password" /> 
                                                </div>
                                            </div>
                                            <div className="col-12 col-lg-12">
                                                <div className="form-group">
                                                    <label>Confirm Password</label>
                                                    <input type="password" name="confirm_password" className="form-control-form" placeholder="Confirm Password" /> 
                                                </div>
                                            </div>
                                        </div>
                                        <div className="save-btn mt-3">
                                            <button className="btn btn-fill">Update</button>
                                        </div>
                                    </div>
                                </div>
                                <div className="col-12 col-lg-4">
                                    <div className="outer-heading text-center">
                                        <h3>Edit Profile Picture </h3>
                                        <p>Lorem Ipsum is simply dummy text of the printing.</p>
                                    </div>
                                    <div className="upload-photo" style={{border:border}}>
                                        <div className="containers">
                                            <div className="imageWrapper" >
                                                {previewImageUrl==''?<img className="image" src={userData.profile_image !='' ? userData.profile_image:'./assets/images/avtar-big.png'}/>:<img className="image" src={previewImageUrl}/>}
                                            </div>
                                        </div>
                                        <button className="file-upload">            
                                            <input type="file" className="file-input" name="profile_image" onChange={previewImage}/>Change Profile Photo
                                        </button>
                                    </div>
                                    <p style={{padding: '6px',textAlign: 'center',fontSize: '13px',color: 'red',fontWeight: '700'}}>{errorMsg}</p>
                                </div>
                            </div>
                         </form>
                        }
                    </div>
                </div>
            </section>
            <Footer/>
        </>
    )
 }
 export default MyProfile;