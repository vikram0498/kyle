import React,{useState, useEffect} from 'react';
import axios from 'axios';
import { useAuth } from '../../../hooks/useAuth';

const AutoSuggestionAddress = ({data}) =>{
    const {getTokenData,setLogout} = useAuth();
    const [autoAddress, setAutoAddress] = useState('');
    const [autoZipCode, setAutoZipCode] = useState([]);
    const [autoState, setAutoState] = useState([]);
    const [autoCountry, setAutoCountry] = useState([]);
    const [allAddress,setAllAddress] = useState([]);
    const suggBox = document.querySelector(".autocom-box");
    const inputBox = document.querySelector(".address-box");
    const suggestedData = document.querySelector(".suggested-data");
    const resetButton = document.querySelector(".reset-button");
    window.selectFunction = function(element) {
        let buyerdata = allAddress[0][`${element.textContent}`];

        data.setZipCode(buyerdata.zip_code);
        if(buyerdata.state !=''){
            data.setState(buyerdata.state);
        }
        if(buyerdata.city !=''){
            data.setCity(buyerdata.city);
        }
        let selectData = element.textContent;
        inputBox.value = buyerdata.address;
        suggBox.innerHTML = '';
        suggBox.style.display = "none";
        //resetButton.style.display="none";
    };

    const handleSearchKeyup = (e) => {
        let userData = e.target.value;
        let emptyArray = [];
        suggBox.style.display = "block";
        resetButton.style.display="block";

        if(userData){
            let suggestions = allAddress.map(obj => Object.keys(obj));
            suggestions = suggestions[0];

            emptyArray = suggestions.filter((data)=>{
                //filtering array value and user characters to lowercase and return only those words which are start with user enetered chars
                return data.toLocaleLowerCase().startsWith(userData.toLocaleLowerCase());
            });

            emptyArray = emptyArray.map((data)=>{
                // passing return data inside li tag
                return data = `<li onclick="selectFunction(this)">${data}</li>`;
            });
            showSuggestions(emptyArray, userData);
        }else{
            suggBox.innerHTML = '';
            suggBox.style.display = "none";
            resetButton.style.display="none";
            data.setState([]);
            data.setCity([]);
            data.setZipCode('');
        }
    }

    function showSuggestions(list, userValue) {
        let listData;
        if(!list.length){
            suggBox.style.display = "none";
            listData = `<li onclick="selectFunction(this)">${userValue}</li>`;
        }else{
        listData = list.join('');
        }
        suggBox.innerHTML = listData;
    }

    const getAddress = async () => {
        try{
            const apiUrl = process.env.REACT_APP_API_URL;
            let headers = { 
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + getTokenData().access_token,
                'auth-token' : getTokenData().access_token,
            };
            let response = await axios.get(apiUrl+'search-address', { headers: headers })
            setAllAddress(response.data.result);
        }catch(error){

        }
    }
    const handleResetSearchBox = () => {
        suggBox.style.display = "none";
        resetButton.style.display="none";
        data.setState([]);
        data.setCity([]);
        data.setZipCode('');
        inputBox.value = '';
    }
    useEffect(()=>{
        getAddress();
    },[])
    
    return(
        <>
            <label>Address</label>
            <div className="form-group address-selectbox">
                <input type="text" className="address-box form-control" placeholder="Type to search.." name="address" onKeyUp={handleSearchKeyup}/>
                <input type="reset" className="reset-button" value="" alt="Clear the search form" onClick={handleResetSearchBox} style={{display:'none'}}/>
                <div className="autocom-box" style={{display:'none'}}>
                </div>
            </div>
        </>
    );
}
export default AutoSuggestionAddress;