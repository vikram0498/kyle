import React ,{useEffect, useState} from 'react';
import Header from "../partials/Layouts/Header";
import Footer from "../partials/Layouts/Footer";
import axios from 'axios';
import {useAuth} from "../../hooks/useAuth";

 const MyProfile = () => {
    const apiUrl = process.env.REACT_APP_API_URL;
    const {getTokenData} = useAuth();
    const [userData, setUserData] = useState(null);
    let headers = {
        "Accept": "application/json", 
        'Authorization': 'Bearer ' + getTokenData().access_token,
    }
    const getUsers = async () => {
        let res = await axios.get(apiUrl+'user-details', { headers: headers });
        let { data } = res.data.data;
        setUserData(res.data.data)
    };
    useEffect(()=>{
        getUsers();
    },[])
    const handleSubmit = (e) => {
        e.preventDefault();
        var data = new FormData(e.target);
        let formData = Object.fromEntries(data.entries());
        console.log(formData,'formObject');
        let headers = { 
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getTokenData().access_token,
            "Content-Type": "multipart/form-data"
        };
        async function fetchData() {
            const response = await axios.post(apiUrl+"update-profile",formData,{headers: headers});
            console.log(response,'update-profile');
        }
        fetchData();
    }
    return(
        <>
            <Header/>
            <section className="main-section position-relative pt-4 pb-120">
                <div className="container position-relative">
                    <div className="card-box mt-0">
                        {(!userData) ? <div className="loader" style={{textAlign:'center'}}><img src="assets/images/loader.svg"/></div>:
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
                                                        <input type="text" name="first_name" className="form-control-form" placeholder="First Name" /> 
                                                    </div>
                                                </div>
                                                <div className="col-12 col-md-6 col-lg-6">
                                                    <div className="form-group">
                                                        <label>Last Name</label>
                                                        <input type="text" name="last_name" className="form-control-form" placeholder="Last Name" /> 
                                                    </div>
                                                </div>
                                                <div className="col-12 col-md-6 col-lg-6">
                                                    <div className="form-group">
                                                        <label>Email</label>
                                                        <input type="email" name="email" className="form-control-form" placeholder="Email" /> 
                                                    </div>
                                                </div>
                                                <div className="col-12 col-md-6 col-lg-6">
                                                    <div className="form-group">
                                                        <label>Phone Number</label>
                                                        <input type="text" name="phone" className="form-control-form" placeholder="Phone Number"/> 
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
                                                <button class="btn btn-fill">Update</button>
                                            </div>
                                    </div>
                                </div>
                                <div className="col-12 col-lg-4">
                                        <div className="outer-heading text-center">
                                            <h3>Edit Profile Picture </h3>
                                            <p>Lorem Ipsum is simply dummy text of the printing.</p>
                                        </div>
                                        <div className="upload-photo">
                                            <div className="containers">
                                                <div className="imageWrapper">
                                                    <img className="image" src="./assets/images/avtar-big.png"/>
                                                </div>
                                            </div>
                                            <button className="file-upload">            
                                                <input type="file" className="file-input" name="profile_image"/>Change Profile Photo
                                            </button>
                                        </div>
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