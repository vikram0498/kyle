import React, {useContext, useEffect} from "react";
import {useNavigate} from "react-router-dom";
import AuthContext from "../context/authContext";

function Home (){
    const {authData} = useContext(AuthContext);
    const navigate = useNavigate();
    useEffect(() => {
        if(!authData.signedIn) {
            navigate('/login');
        }
    }, []);

    return <h1>Home</h1>
}
  
export default Home;