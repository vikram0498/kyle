import {useContext, useState, useEffect} from "react";
import {Cookies} from "react-cookie";
import {useNavigate} from "react-router-dom";
import AuthContext from "../context/authContext";
import axios from 'axios';

export const useAuth = () => {

    let navigate = useNavigate();
    const [userData, setUserData] = useState(getUserData());
    const {setAuthData} = useContext(AuthContext);
    
    useEffect(() => {
        setAuthData(userData);        
    }, [userData, setAuthData]);

    function getAuthCookieExpiration(){
        let date = new Date();
        date.setTime(date.getTime() + (7 * 24 * 60 * 60 * 1000));  // 7 days
        return date;
    }
    function setAsLogged(user_data, access_token, remember_token='') {
        const cookie = new Cookies();
        cookie.set('is_auth', true, {path: '/', expires: getAuthCookieExpiration(), sameSite: 'lax', httpOnly: false});
        if(remember_token.trim() !== ''){
            cookie.set('remember_me_token', remember_token, {path: '/', expires: getAuthCookieExpiration(), sameSite: 'lax', httpOnly: false});
        }
        // setUserData({signedIn: true, user: user_data, access_token: access_token});

        sessionStorage.setItem('userData', JSON.stringify({signedIn: true, user: user_data, access_token: access_token}));

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

    function setLogout() {
        const cookie = new Cookies();
        cookie.remove('is_auth', {path: '/', expires: getAuthCookieExpiration(), sameSite: 'lax', httpOnly: false});
        cookie.remove('remember_me_token', {path: '/', expires: getAuthCookieExpiration(), sameSite: 'lax', httpOnly: false});
        setUserData({signedIn: false, user: null, access_token: null});
        navigate('/login');
    }

    function loginUserOnStartup(){
        const cookie = new Cookies();
        if(cookie.get('is_auth')) {
            navigate('/');
        } else if (cookie.get('remember_me_token')) {
            
        } else {
            setUserData({signedIn: false, user: null, access_token: null});
            navigate('/login');
        }
    }
    
    return {
        userData,
        setAsLogged,
        setLogout,
        loginUserOnStartup
    }
};