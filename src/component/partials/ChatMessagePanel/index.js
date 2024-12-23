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
                <span>{activeUserData.name}</span>
                <p>{activeUserData.is_online && "Online" }</p>
              </div>
            </div>

            <div className='chat_header_action d-flex'>
              {activeUserData.wishlisted ? <div className='fav-icons-start' onClick={handleRemoveWishList}><img src='./assets/images/vector-yellow.svg'/></div> : <div className='fav-icons-start' onClick={handleAddWishList}><img src='./assets/images/vector.svg'/></div>}
              
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
          {activeUserData.is_block == 1 ? <p>User Blocked </p>:
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
              <Form onSubmit={handleSubmitReport}>
                <Form.Group className="mb-3 text-start">
                  <Form.Label>Reason for Report</Form.Label>
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
                  <Form.Label>Comment</Form.Label>
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
                  <Button variant="primary" type="submit" className="w-100">
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