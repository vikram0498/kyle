import React, { useContext, useEffect, useState } from 'react';
import axios from 'axios';
import { toast } from 'react-toastify';

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

import ButtonLoader from '../partials/MiniLoader'

import Layout from './Layout';
  
function Login (props){

    const {setAsLogged} = useAuth();
    const {authData} = useContext(AuthContext);
    
    const { setErrors, renderFieldError, setMessage, navigate } = useForm();

    useEffect(() => {
        if(authData.signedIn) {
            navigate('/');
        }
    }, [authData, navigate]);
    

    const [showPassoword, setshowPassoword] = useState(false);
    const togglePasswordVisibility  = () => {
        setshowPassoword(!showPassoword);
    };

    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [remember, setRemember] = useState(false);
    const [loading, setLoading] = useState(false);

    const submitLoginForm = (e) => {
        e.preventDefault();

        setLoading(true);

        setErrors(null);

        setMessage('');

        const apiUrl = process.env.REACT_APP_API_URL;

        let payload = {
            email,
            password
        }
        if(remember) {
            payload.remember = true;
        }

        let headers = {
            'Accept': 'application/json'
        };

        axios.post(apiUrl+'login', payload, {headers: headers}).then(response => {
            setLoading(false);
            if(response.data.status) {
                toast.success(response.data.message, {position: toast.POSITION.TOP_RIGHT});
                var user_data = response.data.user_data.user;
                var access_token = response.data.user_data.access_token;
                var remember_me_token = response.data.user_data.remember_me_token;

                setAsLogged(user_data, access_token, remember_me_token);
            }
        }).catch(error => {
            setLoading(false);
            if(error.response) {
                if (error.response.data.errors) {
                    setErrors(error.response.data.errors);
                }
                if (error.response.data.errors.error_message) {
                    toast.error(error.response.data.errors.error_message, {position: toast.POSITION.TOP_RIGHT});
                }
            }
        });
    }

    return (
        <Layout>
            <div className="account-in">
                <div className="center-content">
                    <img src={Logo} className="img-fluid" alt="logo" />
                    <h2>Welcome Back!</h2>
                </div>
                <form method='post' onSubmit={submitLoginForm}>
                    <div className="row">
                        <div className="col-12 col-lg-12">
                            <div className="form-group">
                                <label htmlFor="email" >Email</label>
                                <div className="form-group-inner">
                                    <span className="form-icon"><img src={mailIcon} className="img-fluid" alt="mail-icon" /></span>
                                    <input 
                                        type="email" 
                                        id="email"
                                        name="email" 
                                        className="form-control"                                         
                                        placeholder="Enter Your Email" 
                                        value={email} 
                                        onChange={e => setEmail(e.target.value)} 
                                        required autoComplete="email" autoFocus
                                        disabled={ loading ? 'disabled' : ''}
                                    />
                                </div>
                                {renderFieldError('email') }
                            </div>
                        </div>
                        <div className="col-12 col-lg-12">
                            <div className="form-group">
                                <label htmlFor='pass_log_id'>password</label>
                                <div className="form-group-inner">
                                    <span className="form-icon"><img src={passwordIcon} className="img-fluid" alt="password-icon" /></span>
                                    <input 
                                        id="pass_log_id" 
                                        name="password"
                                        type={showPassoword ? 'text' : 'password'} 
                                        className="form-control"
                                        placeholder="Enter Your Password"   
                                        value={password}
                                        onChange={e=>setPassword(e.target.value)}
                                        required
                                        disabled={ loading ? 'disabled' : ''}
                                        autoComplete='off'
                                    />
                                    <span onClick={togglePasswordVisibility} className={`form-icon-password toggle-password ${showPassoword ? '' : 'eye-close'}`}><img src={eyeIcon} className="img-fluid" alt="eye-icon" /></span>
                                </div>
                                {renderFieldError('password') }
                            </div>
                        </div>
                        <div className="col-12 col-sm-6 col-md-6 col-lg-6">
                            <div className="form-group-switch">
                                <label className="switch">
                                    <input 
                                        type="checkbox"
                                        name="remember"
                                        onChange={e => { setRemember(e.target.checked ? 1 : 0) } }
                                        disabled={ loading ? 'disabled' : ''}
                                    />
                                    <span className="slider round"></span>
                                </label>
                                <span className="toggle-heading">Remember me</span>
                            </div>
                        </div>
                        <div className="col-12 col-sm-6 col-md-6 col-lg-6">
                            <Link to="/forget-password" className="link-pass">Forgot Password?</Link>
                        </div>
                        <div className="col-12 col-lg-12">
                            <div className="form-group-btn">
                                <button type="submit" className="btn btn-fill" disabled={ loading ? 'disabled' : ''}>Login Now! { loading ? <ButtonLoader/> : ''} </button>
                            </div>
                        </div>
                        <div className="col-12 col-lg-12">
                            <p className="account-now">Don’t Have an account?  
                                <Link to="/register"> Sign up now!</Link>
                            </p>
                            <div className="or"><span>OR</span></div>
                            <ul className="account-with-social list-unstyled mb-0">
                                <li><Link to="https://facebook.com"><img src={facebookImg} className="img-fluid" alt='fb-icon'/> With Facebook</Link></li>
                                <li><Link to="https://google.com"><img src={googleImg} className="img-fluid" alt='google-icon'/> With Google</Link></li>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
        </Layout>                           
    );
}
  
export default Login;