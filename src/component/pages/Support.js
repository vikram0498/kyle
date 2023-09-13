import React from 'react';
import {useForm} from "react-hook-form";
import Header from '../partials/Layouts/Header';
import Footer from '../partials/Layouts/Footer';

const Support = () =>{
    const { register, handleSubmit, control , formState: { errors }  } = useForm();
    const submitSupportForm = () => {
    }
    console.log(errors);

    return (
       <>
           <Header/>
           <section className="main-section position-relative pt-4 pb-0">
                <div className="container position-relative">
                    <div className="card-box mt-0 ">
                        <form method="post" className="support-account" onSubmit={handleSubmit(submitSupportForm)}>
                            <div className="row align-items-center">
                                <div className="col-12 col-md-6 colmd-5 d-md-block d-none">
                                    <div className='supportImg'> 
                                    <img src="/assets/images/support.svg" className="img-fluid"/></div>
                                </div>
                                <div className="col-12 col-md-6 colmd-7">
                                    <div className="card-box-inner card-box-blocks p-0">
                                        <div className="row">
                                            <div className="col-12 col-sm-6">
                                                <div className="form-group">
                                                    <label>Name<span>*</span></label>
                                                    <input type="text" name="name" className="form-control-form" placeholder="Enter Your Name" {...register("name", { required: 'Name is required' , validate: {
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
                                                    <input type="email" name="email" className="form-control-form" placeholder="Enter Your Email" {
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
                                                    <textarea name="message" class="formcontrol form-control-form support-textarea" id="exampleFormControlTextarea6" rows="3" placeholder="Write something here..." {
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
