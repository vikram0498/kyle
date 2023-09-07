import React,{useState} from 'react';

const AutoSuggestionAddress = () =>{
    const suggBox = document.querySelector(".autocom-box");
    const inputBox = document.querySelector(".address-box");
    const suggestedData = document.querySelector(".suggested-data");
    
    const handleSearchKeyup = (e) => {
        let userData = e.target.value;
        let emptyArray = [];
        if(userData){
            console.log('case 1');
            emptyArray = suggestions.filter((data)=>{
                //filtering array value and user characters to lowercase and return only those words which are start with user enetered chars
                return data.toLocaleLowerCase().startsWith(userData.toLocaleLowerCase());
            });

            emptyArray = emptyArray.map((data)=>{
                // passing return data inside li tag
                return data = `<li class="abc" onclick="select()">${data}</li>`;
            });
            console.log(emptyArray,'emptyArray');
            showSuggestions(emptyArray, userData);
        }else{
            console.log('case 1');
            suggBox.innerHTML = '';
        }
    }

    function selectFunction() {
        console.log('hello23');
      }
      
    function showSuggestions(list, userValue, selectFunction) {
            let listData;
            if(!list.length){
                listData = `<li class="abc" onclick="selectFunction()">${userValue}</li>`;
            }else{
            listData = list.join('');
            }
            console.log(listData,'listData');
    
            suggBox.innerHTML = listData;
    }
    let suggestions = [
        "Channel",
        "Mr Code Box",
        "Vs Code",
        "Instagram",
        "YouTube",
        "YouTuber",
        "YouTube Channel",
        "Blogger",
        "Please Like, Share & Subscribe",
        "Bollywood",
        "Vlogger",
        "Vechiles",
        "Facebook",
        "Freelancer",
        "Facebook Page",
        "Designer",
        "Developer",
        "Web Designer",
        "Web Developer",
        "Login Form in HTML & CSS",
        "How to learn HTML & CSS",
        "How to learn JavaScript",
        "How to become Freelancer",
        "How to become Web Designer",
        "How to start Gaming Channel",
        "How to start YouTube Channel",
        "What does HTML stands for?",
        "What does CSS stands for?",
    ];

    return(
        <>
            <label>Address</label>
            <div className="form-group">
                <input type="text" className="address-box" placeholder="Type to search.." onKeyUp={handleSearchKeyup}/>
                <div className="autocom-box">
                </div>
            </div>
        </>
    );
}
export default AutoSuggestionAddress;