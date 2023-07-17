import React, {useContext, useEffect} from "react";
import {useNavigate} from "react-router-dom";
import AuthContext from "../context/authContext";

function AddBuyerDetails (){
    const {authData} = useContext(AuthContext);
    const navigate = useNavigate();
    
    useEffect(() => {
        if(!authData.signedIn) {
            navigate('/login');
        }
    }, [navigate, authData]);

    return (
        <div>
        <p>Home2</p>
        </div>
    )
    
}
  
export default AddBuyerDetails;