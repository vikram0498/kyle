import React, { useContext, useEffect, useState } from 'react';
import axios from 'axios';

import { Link } from 'react-router-dom';

import Logo from './../../assets/images/logo.svg'
import mailIcon from './../../assets/images/mail.svg'
import passwordIcon from './../../assets/images/password.svg'
import eyeIcon from './../../assets/images/eye.svg'
import facebookImg from './../../assets/images/facebook.svg'
import googleImg from './../../assets/images/google.svg'

import {useForm} from "../../hooks/useForm";
import {useAuth} from "../../hooks/useAuth";
import AuthContext from "../../context/authContext";

import Layout from './Layout';
  
function Login (props){

    const {setAsLogged} = useAuth();
    const {authData} = useContext(AuthContext);
    useEffect(() => {
        if(authData.signedIn) {
            navigate('/');
        }
    }, []);
    const { setErrors, renderFieldError, message, setMessage, navigate } = useForm();

    const [showPassoword, setshowPassoword] = useState(false);
    const togglePasswordVisibility  = () => {
        setshowPassoword(!showPassoword);
    };

    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [remember, setRemember] = useState(false);

    const submitLoginForm = (e) => {

     }

    return (
        <Layout>
            <div className="account-in">
                <div className="center-content">
                    <img src={Logo} className="img-fluid" alt="" />
                    <h2>Welcome Back!</h2>
                </div>
                <form method='post' onSubmit={submitLoginForm}>
                    <div className="row">
                        <div className="col-12 col-lg-12">
                            <div className="form-group">
                                <label htmlFor="email" >Username</label>
                                <div className="form-group-inner">
                                    <span className="form-icon"><img src={mailIcon} className="img-fluid" alt="" /></span>
                                    <input 
                                        type="email" 
                                        id="email"
                                        name="email" 
                                        className="form-control"                                         
                                        placeholder="Enter Your Email" 
                                        value={email} 
                                        onChange={e => setEmail(e.target.value)} 
                                        required autoComplete="email" autoFocus
                                    />
                                </div>
                            </div>
                        </div>
                        <div className="col-12 col-lg-12">
                            <div className="form-group">
                                <label htmlFor='pass_log_id'>password</label>
                                <div className="form-group-inner">
                                    <span className="form-icon"><img src={passwordIcon} className="img-fluid" alt="" /></span>
                                    <input 
                                        id="pass_log_id" 
                                        name="password"
                                        type={showPassoword ? 'text' : 'password'} 
                                        placeholder="Enter Your Password"  className="form-control" 
                                        value={password}
                                        onChange={e=>setPassword(e.target.value)}
                                        required
                                    />
                                    <span onClick={togglePasswordVisibility} className={`form-icon-password toggle-password ${showPassoword ? '' : 'eye-close'}`}><img src={eyeIcon} className="img-fluid" alt="" /></span>
                                </div>
                            </div>
                        </div>
                        <div className="col-12 col-sm-6 col-md-6 col-lg-6">
                            <div className="form-group-switch">
                                <label className="switch">
                                    <input 
                                        type="checkbox"
                                        name="remember"
                                        onChange={e => { setRemember(e.target.checked ? 1 : 0) } }
                                    />
                                    <span className="slider round"></span>
                                </label>
                                <span className="toggle-heading">Remember me</span>
                            </div>
                        </div>
                        <div className="col-12 col-sm-6 col-md-6 col-lg-6">
                            <Link to="/forget-password" className="link-pass">Forgot Password</Link>
                        </div>
                        <div className="col-12 col-lg-12">
                            <div className="form-group-btn">
                                <button type="submit" className="btn btn-fill">Login Now!</button>
                            </div>
                        </div>
                        <div className="col-12 col-lg-12">
                            <p className="account-now">Don’t Have an account?  
                                <Link to="/register"> Sign up Now!</Link>
                            </p>
                            <div className="or"><span>OR</span></div>
                            <ul className="account-with-social list-unstyled mb-0">
                                <li><Link to="https://facebook.com"><img src={facebookImg} className="img-fluid" /> with Facebook</Link></li>
                                <li><Link to="https://google.com"><img src={googleImg} className="img-fluid" /> with Google</Link></li>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
        </Layout>                           
    );
}
  
export default Login;