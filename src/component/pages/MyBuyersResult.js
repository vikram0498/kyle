import React, { useState } from 'react';
import {Link , useNavigate} from "react-router-dom";
import EditRequest from '../partials/Modal/EditRequest';
import SentRequest from '../partials/Modal/SentRequest';
import {useAuth} from "../../hooks/useAuth";
import Swal from 'sweetalert2';
import axios from 'axios';

const MyBuyersResult = ({buyerData}) =>{
    const [editOpen, setEditOpen] = useState(false);
    const [sentOpen, setSentOpen] = useState(false);
    const [buyerId, setBuyerId]   = useState('');
    const {getTokenData} = useAuth();

    const handleClickEditFlag = (data,id) => {
        setBuyerId(id);
        if(data){
            setSentOpen(true);
        }else{
            setEditOpen(true);
        }
    }
    const handleClickConfirmation = (id) => { 
        
        Swal.fire({
          icon: 'warning',
          title: 'Do you want to save the changes?',
          html:'<p class="popup-text-color">It will redeem one point from your account</p>',
          showCancelButton: true,
          confirmButtonText: 'Save',
        }).then((result) => {
          /* Read more about isConfirmed, isDenied below */
          if (result.isConfirmed) {
            unHideBuyer(id);
            Swal.fire('Saved!', '', 'success')
          } else if (result.isDenied) {
            Swal.fire('Changes are not saved', '', 'info')
          }
        })
      }

    async function unHideBuyer(id) {
        try{
            const apiUrl = process.env.REACT_APP_API_URL;
            let headers = { 
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + getTokenData().access_token,
                'auth-token' : getTokenData().access_token,
            };
            const response = await axios.post(apiUrl+"unhide-buyer",{'buyer_id':id},{headers: headers});
            if(response.data.status){
                console.log('response',response.data );
                let data = response.data;
                // let html = `<ul className="list-unstyled mb-0">
                //     <li>
                //         <span className="detail-icon"><img src="/assets/images/user-gradient.svg" className="img-fluid" /></span>
                //         <span className="name-dealer">${data.first_name+' '+ data.last_name}</span>
                //     </li>
                //     <li>
                //         <span className="detail-icon"><img src="/assets/images/phone-gradient.svg" className="img-fluid" /></span>
                //         <a href=${data.phone} className="name-dealer">${data.phone}</a>
                //     </li>
                //     <li>
                //         <span className="detail-icon"><img src="/assets/images/gmail.svg" className="img-fluid" /></span>
                //         <a href=${'mailto:'+data.email} className="name-dealer">${data.email}</a>
                //     </li>
                // </ul>`;
                // const element = document.querySelector(`.unhide-${id}`);
                // element.innerHTML = html;
            }else{
                console.log('response22',response );
            }
        }catch{
            console.log('catch')
        }
    }
    
    async function likeUnlikeBuyer(id,like,unlike) {
        try{
            const apiUrl = process.env.REACT_APP_API_URL;
            let headers = { 
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + getTokenData().access_token,
                'auth-token' : getTokenData().access_token,
            };
            const response = await axios.post(apiUrl+"like-unlike-buyer",{'buyer_id':id,'like':like,unlike:unlike},{headers: headers});
            if(response.data.status){
            }else{
                console.log('response22',response );
            }
        }catch{
            console.log('catch')
        }
    }
 return (
    <>
       <div className="tab-pane fade show active" id="pills-my-buyers" role="tabpanel" aria-labelledby="pills-my-buyers-tab">
            <div className="property-critera">
                <div className="row">
                    { buyerData.map((data) => { 
                        return(<div className="col-12 col-lg-6" key={data.id}>
                            <div className="property-critera-block">
                                <div className="critera-card">
                                    <div className="center-align">
                                        <span className="price-img"><img src="/assets/images/price.svg" className="img-fluid" /></span>
                                        <p>Buyer</p>
                                        <ul className="like-unlike mb-0 list-unstyled">
                                            <li>
                                                <span className="numb">{data.totalBuyerLikes}</span>
                                                
                                                <span className="ico-no ml-min" onClick={()=>{likeUnlikeBuyer(data.id,1,0)}}><img src="/assets/images/like.svg" className="img-fluid" /></span>
                                            </li>
                                            <li>
                                                <span className="ico-no mr-min" onClick={()=>{likeUnlikeBuyer(data.id,0,1)}}><img src="/assets/images/unlike.svg" className="img-fluid" /></span>
                                                <span className="numb text-end">{data.totalBuyerUnlikes}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div className={"property-critera-details unhide-"+data.id}>
                                    <ul className="list-unstyled mb-0">
                                        <li>
                                            <span className="detail-icon"><img src="/assets/images/user-gradient.svg" className="img-fluid" /></span>
                                            <span className="name-dealer">{data.first_name+' '+ data.last_name}</span>
                                        </li>
                                        <li>
                                            <span className="detail-icon"><img src="/assets/images/phone-gradient.svg" className="img-fluid" /></span>
                                            <a href={data.phone} className="name-dealer">{data.phone}</a>
                                        </li>
                                        <li>
                                            <span className="detail-icon"><img src="/assets/images/gmail.svg" className="img-fluid" /></span>
                                            <a href={'mailto:'+data.email} className="name-dealer">{data.email}</a>
                                        </li>
                                    </ul>
                                </div>
                                <div className="cornor-block">
                                    <div className="red-flag" onClick={()=>{handleClickEditFlag(data.redFlag,data.id)}}>
                                        <img src="/assets/images/red-flag.svg" className="img-fluid" />
                                    </div>

                                    <div class="show-hide-data">
                                        <button type="button" class="unhide-btn" onClick={()=>{handleClickConfirmation(data.id)}}>
                                            <span className="icon-unhide">
                                                <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <g clipPath="url(#clip0_677_7400)">
                                                <path d="M1.16699 7.99996C1.16699 7.99996 3.83366 2.66663 8.50033 2.66663C13.167 2.66663 15.8337 7.99996 15.8337 7.99996C15.8337 7.99996 13.167 13.3333 8.50033 13.3333C3.83366 13.3333 1.16699 7.99996 1.16699 7.99996Z" stroke="white" strokeLinecap="round" strokeLinejoin="round"></path>
                                                <path d="M8.5 10C9.60457 10 10.5 9.10457 10.5 8C10.5 6.89543 9.60457 6 8.5 6C7.39543 6 6.5 6.89543 6.5 8C6.5 9.10457 7.39543 10 8.5 10Z" stroke="white" strokeLinecap="round" strokeLinejoin="round"></path>
                                                </g>
                                                <defs>
                                                <clipPath id="clip0_677_7400">
                                                <rect width="16" height="16" fill="white" transform="translate(0.5)"></rect>
                                                </clipPath>
                                                </defs>
                                                </svg>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>)
                    })}
                    <EditRequest setEditOpen={setEditOpen} editOpen={editOpen} buyerId={buyerId}/>
                    <SentRequest setSentOpen={setSentOpen} sentOpen={sentOpen} buyerId={buyerId}/>
                </div>
            </div>
        </div>
    </>
 )
}
export default MyBuyersResult;
