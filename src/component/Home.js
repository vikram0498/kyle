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
    }, [navigate, authData]);

    return (
        <div>
        Home
        </div>
    )
    
}
  
export default Home;