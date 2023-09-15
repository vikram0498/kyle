import React,{useState} from 'react';
import axios from 'axios';
import { toast } from "react-toastify";
import {useForm} from "react-hook-form";
import {useAuth} from "../../hooks/useAuth";
import Header from '../partials/Layouts/Header';
import Footer from '../partials/Layouts/Footer';
import {useNavigate , Link} from "react-router-dom";
import { useFormError } from '../../hooks/useFormError';

const Support = () =>{
    const { register, handleSubmit, control , formState: { errors }  } = useForm();
    const {getTokenData, setLogout,setLocalStorageUserdata} = useAuth();
    const navigate = useNavigate();
    const [loading, setLoading] = useState(false);
    const { setErrors, renderFieldError } = useFormError();

    const submitSupportForm = async (data, e) => {
        try{
            console.log("case 1");
            let headers = {
                "Accept": "application/json", 
                'Authorization': 'Bearer ' + getTokenData().access_token,
                'auth-token' : getTokenData().access_token,
            }
            const apiUrl = process.env.REACT_APP_API_URL;
            var data = new FormData(e.target);
            let formObject = Object.fromEntries(data.entries());
            let response  = await axios.post(apiUrl+'support', formObject, {headers: headers});
            if(response){
                setLoading(false);
                if(response.data.status){
                    toast.success(response.data.message, {position: toast.POSITION.TOP_RIGHT});
                    navigate('/')
                }
            }
        }catch(error){
            setLoading(false);
            if(error.response) {
                if(error.response.status === 401){
                    setLogout();
                    navigate('/login');
                }
                if (error.response.data.validation_errors) {
                    setErrors(error.response.data.validation_errors);
                }
                if (error.response.data.errors) {
                    setErrors(error.response.errors);
                }
                if (error.response.data.error) {
                    toast.error(error.response.data.error, {position: toast.POSITION.TOP_RIGHT});
                }
            }
        }
    }
    console.log(errors);

    return (
       <>
           <Header/>
           <section className="main-section position-relative pt-4 pb-0">
                <div className="container position-relative">
                    <div className="card-box mt-0 ">
                        <form method="post" className="support-account" autoComplete="off" onSubmit={handleSubmit(submitSupportForm)}>
                            <div className="row align-items-center">
                                <div className="col-12 col-xxl-5 col-lg-6 col-md-8 mx-auto col-sm-9 col-11 mb-lg-0 mb-5">
                                    <div className='supportImg pe-xxl-0 pe-xl-5'> 
                                    <img src="/assets/images/support.svg" className="img-fluid"/></div>
                                </div>
                                <div className="col-12 ms-xxl-auto col-lg-6">
                                    <div className="card-box-inner card-box-blocks bg-white p-0">
                                        <div className="row">
                                            <div className="col-12 col-sm-6">
                                                <div className="form-group">
                                                    <label>Name<span>*</span></label>
                                                    <input type="text" name="name" className="form-control-form" placeholder="Enter Your Name" autoComplete='off' {...register("name", { required: 'Name is required' , validate: {
                                                            maxLength: (v) =>
                                                            v.length <= 50 || "The Name should have at most 50 characters",
                                                            matchPattern: (v) =>
                                                            /^[a-zA-Z\s]+$/.test(v) ||
                                                            "Name can not include number or special character",
                                                        } })}/>
                                                    {errors.name && <p className="error">{errors.name?.message}</p>}
                                                </div>
                                            </div>
                                            <div className="col-12 col-sm-6">
                                                <div className="form-group">
                                                    <label>Email<span>*</span></label>
                                                    <input type="email" name="email" className="form-control-form" placeholder="Enter Your Email"  autoComplete='off' {
                                                        ...register("email", {
                                                            required: "Email address is required",
                                                            validate: {
                                                                maxLength: (v) =>
                                                                v.length <= 50 || "The Email address should have at most 50 characters",
                                                                matchPattern: (v) =>
                                                                /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
                                                                "Email address must be a valid address",
                                                            },
                                                        })
                                                    }/>
                                                    {errors.email && <p className="error">{errors.email?.message}</p>}
                                                </div>
                                            </div>
                                            <div className="col-12 col-md-12 col-lg-12">
                                                <div className="form-group">
                                                    <label>Message<span>*</span></label>
                                                    <textarea name="message" className="formcontrol form-control-form support-textarea" id="exampleFormControlTextarea6"  autoComplete='off' rows="3" placeholder="Write something here..." {
                                                        ...register("message", {
                                                            required: "Message is required",
                                                        })
                                                    }></textarea>
                                                    {errors.message && <p className="error">{errors.message?.message}</p>}
                                                </div>
                                            </div>
                                        </div>
                                        <div className="save-btn mt-3">
                                            <button type="submit" className="btn btn-fill">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </form>
                    </div>
                </div>
            </section>
           <Footer/>
       </>
    )
   }
export default Support;
