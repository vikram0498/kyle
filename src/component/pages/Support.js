import React from 'react';
import Header from '../partials/Layouts/Header';
import Footer from '../partials/Layouts/Footer';
const Support = () =>{
    return (
       <>
           <Header/>
           <section className="main-section position-relative pt-4 pb-120">
                <div className="container position-relative">
                    <div className="card-box mt-0">
                        <form className="support-account">
                            <div className="row">
                                <div className="col-12 col-lg-4">
                                    <img src="/assets/images/help.jpg" className="img-fluid"/>
                                </div>
                                <div className="col-12 col-lg-8">
                                    <div className="card-box-inner">
                                        <div className="row">
                                            <div className="col-12 col-md-12 col-lg-12">
                                                <div className="form-group">
                                                    <label>Name</label>
                                                    <input type="text" name="first_name" className="form-control-form" placeholder="Ennter Your Name" defaultValue=""/>
                                                </div>
                                            </div>
                                            <div className="col-12 col-md-12 col-lg-12">
                                                <div className="form-group">
                                                    <label>Email</label>
                                                    
                                                    <input type="email" name="email" className="form-control-form" placeholder="Enter Your Email" defaultValue=""/>
                                                </div>
                                            </div>
                                            <div className="col-12 col-md-12 col-lg-12">
                                                <div className="form-group">
                                                    <label>Message</label>
                                                    <textarea class="form-control support-textarea" id="exampleFormControlTextarea6" rows="3" placeholder="Write something here..." ></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="save-btn mt-3">
                                            <button className="btn btn-fill">Submit</button>
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
