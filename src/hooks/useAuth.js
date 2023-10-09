import { useContext, useState, useEffect } from "react";
import { Cookies } from "react-cookie";
import { useNavigate } from "react-router-dom";
import AuthContext from "../context/authContext";
import CryptoJS from "crypto-js";
import axios from "axios";

export const useAuth = () => {
  const secretPass = "XkhZG4fW2t2W";
  let navigate = useNavigate();
  const [userData, setUserData] = useState(getUserData());
  const [isLogin, setIsLogin] = useState(getTokenData());
  const { setAuthData } = useContext(AuthContext);

  useEffect(() => {
    setAuthData(isLogin);
  }, [isLogin, setAuthData]);

  function getAuthCookieExpiration() {
    let date = new Date();
    date.setTime(date.getTime() + 7 * 24 * 60 * 60 * 1000); // 7 days
    return date;
  }
  function setAsLogged(
    access_token,
    remember_token = "",
    remember_me_user_data,
    userData
  ) {
    const cookie = new Cookies();
    cookie.set("is_auth", true, {
      path: "/",
      expires: getAuthCookieExpiration(),
      sameSite: "lax",
      httpOnly: false,
    });
    if (remember_token !== "" || remember_token !== null) {
      cookie.set("remember_me_token", remember_token, {
        path: "/",
        expires: getAuthCookieExpiration(),
        sameSite: "lax",
        httpOnly: false,
      });
    }
    // set cookie for remember me
    // Set the cookie to expire in 400 days.
    const expires = new Date(Date.now() + 400 * 24 * 60 * 60 * 1000);
    cookie.set("remember_me_user_data", JSON.stringify(remember_me_user_data), {
      path: "/",
      expires: expires,
      sameSite: "lax",
      httpOnly: false,
    });

    /* token for login */
    const twoHoursLater = new Date(Date.now() + 2 * 60 * 60 * 1000); // 2 hours in milliseconds
    cookie.set("_token", JSON.stringify({ access_token: access_token }), {
      expires: twoHoursLater,
    });
    setLocalStorageUserdata(userData);
    navigate("/");
  }

  function getUserData() {
    if (localStorage.getItem("userData") == null) {
      var deft = { signedIn: false, user: null, access_token: null };
      return deft;
    }
    var storedUserData = JSON.parse(localStorage.getItem("userData"));
    return storedUserData;
  }
  function getTokenData() {
    const cookie = new Cookies();
    let token = cookie.get("_token");
    if (token !== "" && token !== undefined) {
      return token;
    }
    var deft = { signedIn: false, access_token: null };
    return deft;
  }
  function setLogout() {
    const apiUrl = process.env.REACT_APP_API_URL;
    let headers = {
      Accept: "application/json",
      Authorization: "Bearer " + getTokenData().access_token,
      "auth-token": getTokenData().access_token,
    };
    axios
      .post(apiUrl + "logout", {}, { headers: headers })
      .then((response) => {});

    const cookie = new Cookies();
    cookie.remove("is_auth", {
      path: "/",
      expires: getAuthCookieExpiration(),
      sameSite: "lax",
      httpOnly: false,
    });
    cookie.remove("remember_me_token", {
      path: "/",
      expires: getAuthCookieExpiration(),
      sameSite: "lax",
      httpOnly: false,
    });
    setIsLogin({ signedIn: false, access_token: null });
    cookie.remove("_token");

    localStorage.removeItem("user_data");
    localStorage.removeItem("filter_buyer_fields");
    navigate("/");
  }

  function loginUserOnStartup() {
    const cookie = new Cookies();
    if (cookie.get("is_auth")) {
      navigate("/");
    } else if (cookie.get("remember_me_token")) {
    } else {
      setIsLogin({ signedIn: false, user: null, access_token: null });
      navigate("/login");
    }
  }

  function getRememberMeData() {
    const cookie = new Cookies();
    return cookie.get("remember_me_user_data");
  }
  const encryptData = (text) => {
    const data = CryptoJS.AES.encrypt(
      JSON.stringify(text),
      secretPass
    ).toString();
    return data;
  };
  const decryptData = (text) => {
    const bytes = CryptoJS.AES.decrypt(text, secretPass);
    const data = JSON.parse(bytes.toString(CryptoJS.enc.Utf8));
    return data;
  };
  const getLocalStorageUserdata = () => {
    let data = localStorage.getItem("user_data");
    let decryptDatas = "";
    if (data !== null) {
      decryptDatas = decryptData(data);
    }
    return decryptDatas;
  };
  const setLocalStorageUserdata = (data) => {
    let encryptUserData = encryptData(data);
    localStorage.setItem("user_data", encryptUserData);
    return true;
  };
  return {
    userData,
    isLogin,
    getTokenData,
    setAsLogged,
    setLogout,
    getRememberMeData,
    getLocalStorageUserdata,
    setLocalStorageUserdata,
    loginUserOnStartup,
  };
};
