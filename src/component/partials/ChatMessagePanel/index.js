import React from 'react'
import { Dropdown, Figure, Image } from 'react-bootstrap';

const ChatMessagePanel = ({messages,message, setMessage, sendMessage,activeUserData}) => {
   // Handle the Enter key press event to send the message
   const handleKeyDown = (e) => {
    if (e.key === 'Enter' && message.trim()) {
      sendMessage(); // Call sendMessage when Enter is pressed
      e.preventDefault(); // Prevent the default action of the Enter key (new line)
    }
  };

  return (
    <>
        <div className='chat_box'>
          <div className='chat_header d-flex flex-wrap align-items-center'>
            <div className='chat_header_user d-flex flex-wrap align-items-center'>
              <Figure>
                  <Image src={activeUserData.profile_image || '/assets/images/property-img.png'} alt='' />
                  <span className='active_status'></span>
              </Figure>
              <div>
                <span>{activeUserData.name}</span>
                <p>{activeUserData.is_online && "Online" }</p>
              </div>
            </div>
            <div className='chat_header_action'>
              <Dropdown>
                <Dropdown.Toggle>
                  <svg xmlns="http://www.w3.org/2000/svg" width="4" height="20" viewBox="0 0 4 20" fill="none">
                    <path d="M2 4C3.10457 4 4 3.10457 4 2C4 0.895431 3.10457 0 2 0C0.895431 0 0 0.895431 0 2C0 3.10457 0.895431 4 2 4Z" fill="#464B70"/>
                    <path d="M2 11.9999C3.10457 11.9999 4 11.1044 4 9.99988C4 8.89531 3.10457 7.99988 2 7.99988C0.895431 7.99988 0 8.89531 0 9.99988C0 11.1044 0.895431 11.9999 2 11.9999Z" fill="#464B70"/>
                    <path d="M2 19.9998C3.10457 19.9998 4 19.1043 4 17.9998C4 16.8952 3.10457 15.9998 2 15.9998C0.895431 15.9998 0 16.8952 0 17.9998C0 19.1043 0.895431 19.9998 2 19.9998Z" fill="#464B70"/>
                  </svg>
                </Dropdown.Toggle>
                <Dropdown.Menu align="end">
                  <Dropdown.Item href="javascript:void(0)">Delete</Dropdown.Item>
                  <Dropdown.Item href="javascript:void(0)">Block</Dropdown.Item>
                </Dropdown.Menu>
              </Dropdown>
            </div>
          </div>
          <div className='chat_body'>
            <div className='whole_messages scrollbar_design'>
              {messages.map((data,index) => {
                return (
                  <div className={`msg_item ${data.sender_id !== activeUserData.id && 'outgoing_msg'}`}>
                    <div className='msg_content'>{data.content}</div>
                    <p className='msg_time'>{data.date_time_label == 'Today' ? data.created_time : data.created_date}</p>
                  </div>
                )
              })}
              
            </div>
          </div>
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
        </div>
    </>
  );
};

export default ChatMessagePanel;