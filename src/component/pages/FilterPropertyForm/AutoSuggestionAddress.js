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
    window.selectFunction = function(element) {
        let buyerdata = allAddress[0][`${element.textContent}`];

        console.log(buyerdata,'shshshsh');
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
    };

    const handleSearchKeyup = (e) => {
        let userData = e.target.value;
        let emptyArray = [];
        if(userData){
            let suggestions = allAddress.map(obj => Object.keys(obj));
            suggestions = suggestions[0];
            console.log('case 1',suggestions);

            emptyArray = suggestions.filter((data)=>{
                //filtering array value and user characters to lowercase and return only those words which are start with user enetered chars
                return data.toLocaleLowerCase().startsWith(userData.toLocaleLowerCase());
            });

            emptyArray = emptyArray.map((data)=>{
                // passing return data inside li tag
                return data = `<li data-value="y565" onclick="selectFunction(this)">${data}</li>`;
            });
            console.log(emptyArray,'emptyArray');
            showSuggestions(emptyArray, userData);
        }else{
            console.log('case 1');
            suggBox.innerHTML = '';
        }
    }

    function showSuggestions(list, userValue) {
        let listData;
        if(!list.length){
            listData = `<li data-value="123rr" onclick="selectFunction(this)">${userValue}</li>`;
        }else{
        listData = list.join('');
        }

        console.log(listData,'listData');
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
            console.log(response.data.result,'response12');
            setAllAddress(response.data.result);
        }catch(error){

        }
    }
    useEffect(()=>{
        getAddress();
    },[])
    
    return(
        <>
            <label>Address</label>
            <div className="form-group">
                <input type="text" className="address-box form-control" placeholder="Type to search.." onKeyUp={handleSearchKeyup}/>
                <div className="autocom-box">
                </div>
            </div>
        </>
    );
}
export default AutoSuggestionAddress;