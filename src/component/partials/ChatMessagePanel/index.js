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
  const openSidebar = () => {
    document.body.classList.add("msg-sidebar-open");
  };

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
              { Object.entries(messages).map(([day, dayMessages]) => (
                  <>
                  <div className="day-header line-with-text"><span>{day}</span></div>
                  {dayMessages.map((data,index)=>(
                    <div className={`msg_item ${data.sender_id !== activeUserData.id && 'outgoing_msg'}`}>
                    <div className='msg_content'>{data.content}</div>
                    {/* <p className='msg_time'>{data.date_time_label == 'Today' ? data.created_time : data.created_date}</p> */}
                    <p className='msg_time'>{data.created_time}</p>
                  </div>
                  ))}
                  </>
                ))}
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