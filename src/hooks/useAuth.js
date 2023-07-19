import {useContext, useState, useEffect} from "react";
import {Cookies} from "react-cookie";
import {useNavigate} from "react-router-dom";
import AuthContext from "../context/authContext";
import axios from 'axios';

export const useAuth = () => {

    let navigate = useNavigate();
    const [userData, setUserData] = useState(getUserData());
    const [isLogin, setIsLogin] = useState(checkLogin());
    const {setAuthData} = useContext(AuthContext);
    
    useEffect(() => {
        setAuthData(isLogin);   
    }, [isLogin, setAuthData]);

    function getAuthCookieExpiration(){
        let date = new Date();
        date.setTime(date.getTime() + (7 * 24 * 60 * 60 * 1000));  // 7 days
        return date;
    }
    function setAsLogged(access_token, remember_token='',remember_me_user_data) {
        const cookie = new Cookies();
        cookie.set('is_auth', true, {path: '/', expires: getAuthCookieExpiration(), sameSite: 'lax', httpOnly: false});
        if(remember_token.trim() !== ''){
            cookie.set('remember_me_token', remember_token, {path: '/', expires: getAuthCookieExpiration(), sameSite: 'lax', httpOnly: false});
        }
        // set cookie for remember me 
        // Set the cookie to expire in 400 days.
        const expires = new Date(Date.now() + 400 * 24 * 60 * 60 * 1000);
        cookie.set('remember_me_user_data', JSON.stringify(remember_me_user_data), {path: '/', expires: expires, sameSite: 'lax', httpOnly: false});

        sessionStorage.setItem('_token', JSON.stringify({signedIn: true, access_token: access_token}));
        
        navigate('/');
    }

    function getUserData(){
        if(sessionStorage.getItem("userData") == null){
            var deft = {signedIn: false, user: null, access_token: null} ;
            return deft;
        }
        var storedUserData = JSON.parse(sessionStorage.getItem('userData'));
        return storedUserData;
    }
    function checkLogin(){
        if(sessionStorage.getItem("_token") != null ){
            var storedUserData = JSON.parse(sessionStorage.getItem('_token'));
            return storedUserData;
        }
        var deft = {signedIn: false, access_token: null} ;
        return deft;
    }
    function setLogout() {
        const apiUrl = process.env.REACT_APP_API_URL;
        let headers = {
            "Accept": "application/json", 
            'Authorization': 'Bearer ' + checkLogin().access_token
        }
        axios.post(apiUrl+'logout', {}, { headers: headers }).then(response => {
        })
        
        const cookie = new Cookies();
        cookie.remove('is_auth', {path: '/', expires: getAuthCookieExpiration(), sameSite: 'lax', httpOnly: false});
        cookie.remove('remember_me_token', {path: '/', expires: getAuthCookieExpiration(), sameSite: 'lax', httpOnly: false});
        setIsLogin({signedIn: false, access_token: null});
        sessionStorage.removeItem("_token");
    }

    function loginUserOnStartup(){
        const cookie = new Cookies();
        if(cookie.get('is_auth')) {
            navigate('/');
        } else if (cookie.get('remember_me_token')) {
            
        } else {
            setIsLogin({signedIn: false, user: null, access_token: null});
            navigate('/login');
        }
    }
    
    function getRememberMeData(){
        const cookie = new Cookies();
        return cookie.get('remember_me_user_data');
    }
    return {
        userData,
        isLogin,
        setAsLogged,
        setLogout,
        getRememberMeData,
        loginUserOnStartup
    }
};