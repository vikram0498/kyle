import React, { useEffect, useState } from 'react'
import { Dropdown, Figure, Image, Modal, Form,FloatingLabel, Button} from 'react-bootstrap';
import Swal from "sweetalert2";
import axios from "axios";
import { useAuth } from "../../../hooks/useAuth";
import { toast } from 'react-toastify';

const ChatMessagePanel = ({messages,message, setMessage, sendMessage,activeUserData, handleConfirmBox, conversationUuid, currentUserId, setIsSubmitted, isSubmitted}) => {
   const { getTokenData } = useAuth();
   const [isShowReportModal, setIsShowReportModal] = useState(false);
   const [reason, setReason] = useState("");
   const [comment, setComment] = useState("");
   const [error, setError] = useState("");
   const [reportReasons, setReportReasons] = useState([]);
   const apiUrl = process.env.REACT_APP_API_URL;
   // Handle the Enter key press event to send the message

   const handleKeyDown = (e) => {
    if (e.key === 'Enter' && message.trim()) {
      sendMessage(); // Call sendMessage when Enter is pressed
      e.preventDefault(); // Prevent the default action of the Enter key (new line)
    }
  };
  const openSidebar = () => {
    document.body.classList.add("msg-sidebar-open");
  };

  const getAuthHeaders = () => ({
    Accept: "application/json",
    Authorization: `Bearer ${getTokenData().access_token}`,
  });

  const handleAddWishList = (status) => {
    const addToWishList = async () => {
      try {
        const response = await axios.post(`${apiUrl}wishlist/add`,{wishlist_user_id: currentUserId},{ headers: getAuthHeaders() });
        toast.success(response.data.message, {
          position: toast.POSITION.TOP_RIGHT,
        });
        setIsSubmitted(!isSubmitted);
      } catch (error) {
          console.error("Error fetching messages:", error.response?.data?.message || error.message);
      }
    }
    addToWishList();
      // Swal.fire({
      //     icon: "success",
      //     title: "Success!",
      //     text: "User Added to favorite list",
      // });
  }

  const handleRemoveWishList = (status) => {
    const addToWishList = async () => {
         try {
           const response = await axios.post(`${apiUrl}wishlist/remove`,{wishlist_user_id: currentUserId},{ headers: getAuthHeaders() });
            toast.success(response.data.message, {
              position: toast.POSITION.TOP_RIGHT,
            });
           setIsSubmitted(!isSubmitted);
       } catch (error) {
           console.error("Error fetching messages:", error.response?.data?.message || error.message);
       }
    }
    addToWishList();
  //  Swal.fire({
  //      icon: "success",
  //      title: "Success!",
  //      text: "User Added to favorite list",
  //  });
 }

  const handleSubmitReport = (e) => {
    e.preventDefault();

    if (!reason) {
      setError("Reason for Report is required.");
      return;
    }
    setError(""); // Clear error if validation passes

    const formData = {
      reason,
      comment,
    };

    const addToWishList = async () => {
      try {
        const response = await axios.post(`${apiUrl}conversations/${conversationUuid}/add-to-report`,formData,{ headers: getAuthHeaders() });
        if(response.data.status){
          setIsShowReportModal(false);
        }
    } catch (error) {
        console.error("Error fetching messages:", error.response?.data?.message || error.message);
        setError(error.response?.data?.message)
    }
 }
 addToWishList();
    console.log("Form Submitted:", formData);
  };

  const handleChangeReason = (e) => {
    const reason = reportReasons.find(reason => reason.id == e.target.value);
    let selectedComment = reason ? reason.description : "";
    setReason(e.target.value);
    setComment(selectedComment);
    setError("");
  }
  
  useEffect(()=>{
    const fetchReportReason = async () => {
      try {
        const response = await axios.get(`${apiUrl}get-reasons`,{ headers: getAuthHeaders() });
        setReportReasons(response.data.data);
      } catch (error) {
        
      }
    }
    fetchReportReason();
  },[]);

  return (
    <>
        <div className='chat_box'>
          <div className='chat_header d-flex flex-wrap align-items-center'>
            <div className='chat_header_user d-flex flex-wrap align-items-center'>
              <div className='mobile_hamburger d-lg-none' onClick={openSidebar}>
                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="29" viewBox="0 0 19 29" fill="none">
                  <path d="M0.448867 9.64138C0.360065 9.55261 0.299584 9.4395 0.275075 9.31635C0.250566 9.1932 0.263129 9.06555 0.311177 8.94955C0.359224 8.83354 0.440596 8.73439 0.545001 8.66464C0.649406 8.59489 0.772152 8.55766 0.897714 8.55768L17.4871 8.55768C17.6555 8.55768 17.817 8.62457 17.936 8.74362C18.0551 8.86268 18.122 9.02416 18.122 9.19253C18.122 9.3609 18.0551 9.52238 17.936 9.64144C17.817 9.7605 17.6555 9.82738 17.4871 9.82738L0.897714 9.82738C0.814336 9.82747 0.731761 9.81108 0.654734 9.77916C0.577707 9.74724 0.507745 9.70041 0.448867 9.64138Z" fill="#121639"/>
                  <path d="M17.936 14.6412C17.8772 14.7003 17.8072 14.7471 17.7302 14.779C17.6532 14.8109 17.5706 14.8273 17.4872 14.8272L0.897813 14.8272C0.72944 14.8272 0.567964 14.7603 0.448906 14.6413C0.329849 14.5222 0.262963 14.3608 0.262963 14.1924C0.262963 14.024 0.329849 13.8625 0.448906 13.7435C0.567964 13.6244 0.72944 13.5575 0.897813 13.5575L17.4872 13.5575C17.6128 13.5575 17.7355 13.5947 17.8399 13.6645C17.9443 13.7342 18.0257 13.8334 18.0737 13.9494C18.1218 14.0654 18.1343 14.1931 18.1098 14.3162C18.0853 14.4393 18.0249 14.5525 17.936 14.6412Z" fill="#121639"/>
                  <path d="M17.936 19.6412C17.8772 19.7003 17.8072 19.7471 17.7302 19.779C17.6532 19.8109 17.5706 19.8273 17.4872 19.8272L0.897813 19.8272C0.72944 19.8272 0.567964 19.7603 0.448906 19.6413C0.329849 19.5222 0.262963 19.3608 0.262963 19.1924C0.262963 19.024 0.329849 18.8625 0.448906 18.7435C0.567964 18.6244 0.72944 18.5575 0.897813 18.5575L17.4872 18.5575C17.6128 18.5575 17.7355 18.5947 17.8399 18.6645C17.9443 18.7342 18.0257 18.8334 18.0737 18.9494C18.1218 19.0654 18.1343 19.1931 18.1098 19.3162C18.0853 19.4393 18.0249 19.5525 17.936 19.6412Z" fill="#121639"/>
                </svg>
              </div>
              <Figure>
                  <Image src={activeUserData.profile_image || '/assets/images/property-img.png'} alt='' />
                  <span className={activeUserData.is_online && "active_status" }></span>
              </Figure>
              <div>
                <div className='d-flex chat_user_name_area'><span>{activeUserData.name}</span><span>Level {activeUserData.level_type}</span></div>
                <div className='d-flex align-items-center chat_user_name_below gap-2'>
                  <p>{activeUserData.is_online && "Online" }</p>
                  {activeUserData.profile_tag_image && 
                    <p className='d-flex gap-1'>
                      <span><Image src={activeUserData.profile_tag_image} alt='' /></span>
                      {activeUserData.profile_tag_name}
                    </p>
                  }
                </div>
              </div>
            </div>

            <div className='chat_header_action d-flex'>
              {activeUserData.wishlisted ? <div className='fav-icons-start' onClick={handleRemoveWishList}><img src='/assets/images/vector-yellow.svg'/></div> : <div className='fav-icons-start' onClick={handleAddWishList}><img src='/assets/images/vector.svg'/></div>}
              
              <Dropdown>
                <Dropdown.Toggle>
                  <svg xmlns="http://www.w3.org/2000/svg" width="4" height="20" viewBox="0 0 4 20" fill="none">
                    <path d="M2 4C3.10457 4 4 3.10457 4 2C4 0.895431 3.10457 0 2 0C0.895431 0 0 0.895431 0 2C0 3.10457 0.895431 4 2 4Z" fill="#464B70"/>
                    <path d="M2 11.9999C3.10457 11.9999 4 11.1044 4 9.99988C4 8.89531 3.10457 7.99988 2 7.99988C0.895431 7.99988 0 8.89531 0 9.99988C0 11.1044 0.895431 11.9999 2 11.9999Z" fill="#464B70"/>
                    <path d="M2 19.9998C3.10457 19.9998 4 19.1043 4 17.9998C4 16.8952 3.10457 15.9998 2 15.9998C0.895431 15.9998 0 16.8952 0 17.9998C0 19.1043 0.895431 19.9998 2 19.9998Z" fill="#464B70"/>
                  </svg>
                </Dropdown.Toggle>
                <Dropdown.Menu align="end">
                  {activeUserData.is_block ?
                  <Dropdown.Item href="javascript:void(0)" onClick={()=>handleConfirmBox(activeUserData.id, 0)}>Unblock</Dropdown.Item>
                  :
                  <Dropdown.Item href="javascript:void(0)" onClick={()=>handleConfirmBox(activeUserData.id, 1)}>Block</Dropdown.Item>
                  }
                  <Dropdown.Item href="javascript:void(0)" className='text-danger' onClick={() => {setIsShowReportModal(true)}}><strong>Report</strong></Dropdown.Item>
                </Dropdown.Menu>
              </Dropdown>
            </div>
          </div>
          <div className='chat_body'>
            <div className='whole_messages scrollbar_design'>
              { Object.entries(messages).map(([day, dayMessages],index) => (
                  <div key={index}>
                    <div className="day-header line-with-text"><span>{day}</span></div>
                    {dayMessages.map((data,index)=>(
                      <div className={`msg_item ${data.sender_id !== activeUserData.id && 'outgoing_msg'}`} key={index}>
                        <div className='msg_content'>{data.content}</div>
                        {/* <p className='msg_time'>{data.date_time_label == 'Today' ? data.created_time : data.created_date}</p> */}
                        <p className='msg_time'>{data.created_time}</p>
                      </div>
                    ))}
                  </div>
                ))}
            </div>
          </div>
          {activeUserData.is_block == 1 ? <p className='user_block_content'>This user is blocked.<button className='unlock_btn' onClick={()=>handleConfirmBox(activeUserData.id, 0)}>Tap to unblock</button></p>:
            <div className='chat_footer'>
              <form className='msg_send_footer'>
                <input type='text' placeholder='Message Here...' value={message}  onChange={(e) => setMessage(e.target.value)}  onKeyDown={handleKeyDown}/>
                <button type='button' className='msg_send_btn' onClick={sendMessage}>
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M22 2L11 13" stroke="white" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
                    <path d="M22 2L15 22L11 13L2 9L22 2Z" stroke="white" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
                  </svg>
                </button>
              </form>
            </div>
          }
        </div>

        <Modal
          show={isShowReportModal}
          onHide={() => setIsShowReportModal(false)}
          centered
          className="radius_30 max-648"
        >
          <Modal.Header closeButton className="new_modal_close"></Modal.Header>
          <Modal.Body className="space_modal">
            <div className="modal_inner_content">
              <div className='icon_top_circle'>
                <svg xmlns="http://www.w3.org/2000/svg" width="45" height="40" viewBox="0 0 45 40" fill="none">
                  <path d="M18.7931 1.88097L0.504374 33.5556C0.174015 34.1276 6.11423e-05 34.7765 1.6115e-08 35.4371C-6.11101e-05 36.0976 0.173773 36.7465 0.504026 37.3186C0.834278 37.8907 1.30931 38.3657 1.88137 38.696C2.45342 39.0262 3.10234 39.2001 3.76289 39.2H40.3373C40.9979 39.2001 41.6468 39.0262 42.2189 38.696C42.7909 38.3657 43.2659 37.8907 43.5962 37.3186C43.9265 36.7465 44.1003 36.0976 44.1002 35.4371C44.1002 34.7765 43.9262 34.1276 43.5959 33.5556L25.3091 1.88097C24.9789 1.30908 24.504 0.834182 23.9321 0.504003C23.3602 0.173824 22.7115 0 22.0511 0C21.3907 0 20.742 0.173824 20.1701 0.504003C19.5982 0.834182 19.1233 1.30908 18.7931 1.88097Z" fill="#EE404C"/>
                  <path d="M22.2874 11.6514H21.8103C20.6357 11.6514 19.6836 12.6035 19.6836 13.7781V23.9433C19.6836 25.1179 20.6357 26.07 21.8103 26.07H22.2874C23.4619 26.07 24.4141 25.1179 24.4141 23.9433V13.7781C24.4141 12.6035 23.4619 11.6514 22.2874 11.6514Z" fill="#FFF7ED"/>
                  <path d="M22.0488 34.1309C23.3551 34.1309 24.4141 33.0719 24.4141 31.7656C24.4141 30.4593 23.3551 29.4004 22.0488 29.4004C20.7425 29.4004 19.6836 30.4593 19.6836 31.7656C19.6836 33.0719 20.7425 34.1309 22.0488 34.1309Z" fill="#FFF7ED"/>
                </svg>
              </div>
              <div className='modal_top_title'>
                <h2>Report This buyer</h2>
                <p>Addressing Buyer Misconduct for Resolution</p>
              </div>
              <Form onSubmit={handleSubmitReport} className='modal_inner_form'>
                <Form.Group className="mb-3 text-start">
                  <Form.Label className='offer_label'>Reason For Report</Form.Label>
                  <Form.Select
                    aria-label="Select a reason"
                    value={reason}
                    onChange={handleChangeReason}
                  >
                    <option value="">Select a reason</option>
                    {reportReasons.map((data,index)=>{
                      return (
                        <option value={data.id} key={index}>{data.name}</option>
                      )
                    })}
                    {/* <option value="">Select a reason</option>
                    <option value="1">Inappropriate Content</option>
                    <option value="2">Spam</option>
                    <option value="3">Harassment</option>
                    <option value="4">Other</option> */}
                  </Form.Select>
                  {error && <div className="text-danger mt-1">{error}</div>}
                </Form.Group>
                <Form.Group className="mb-3 text-start">
                  <Form.Label className='offer_label'>Comment</Form.Label>
                  <FloatingLabel controlId="floatingTextarea2">
                    <Form.Control
                      as="textarea"
                      placeholder="Write your comment here"
                      style={{ height: "100px" }}
                      value={comment}
                      onChange={(e) => setComment(e.target.value)}
                    />
                  </FloatingLabel>
                </Form.Group>
                <Form.Group className="d-flex justify-content-end">
                  <Button variant="primary" type="submit" className="w-100 btn-fill">
                    Submit
                  </Button>
                </Form.Group>
              </Form>
            </div>
          </Modal.Body>
        </Modal>
    </>
  );
};
export default ChatMessagePanel;