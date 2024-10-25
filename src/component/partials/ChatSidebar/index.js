import React from 'react'
import { Image } from 'react-bootstrap';

const ChatSidebar = () => {
  return (
    <>
        <div className='chat_sidebar'>
            <h6>Messages</h6>
            <ul className='chat_side_list'>
                <li>
                    <div className='chat_pro_area'>
                        <div className='chat_user_img'>
                            <Image src='/assets/images/property-img.png' alt='' />
                        </div>
                        <div className='chat_user_info'>
                            <span>Raghav</span>
                            <p>Dinner?</p>
                        </div>
                    </div>
                    <div className='chat_status_area'>
                        <p className='mb-0'>Today, 8:56pm</p>
                        <div className='seen_status text-end'>
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="27" height="15" viewBox="0 0 27 15" fill="none">
                                    <path d="M13.0745 9.70926L14.8158 11.4712L25.2562 0.907162L27 2.67157L14.8158 15L6.96766 7.0589L8.71143 5.29448L11.332 7.94609L13.0745 9.70801V9.70926ZM13.077 6.18043L19.1839 0L20.9227 1.75942L14.8158 7.93986L13.077 6.18043ZM9.59071 13.2368L7.84818 15L0 7.0589L1.74377 5.29448L3.4863 7.05765L3.48506 7.0589L9.59071 13.2368Z" fill="#3F53FE"/>
                                </svg>
                                {/* <svg xmlns="http://www.w3.org/2000/svg" width="27" height="15" viewBox="0 0 27 15" fill="none">
                                    <path d="M13.0745 9.70926L14.8158 11.4712L25.2562 0.907162L27 2.67157L14.8158 15L6.96766 7.0589L8.71143 5.29448L11.332 7.94609L13.0745 9.70801V9.70926ZM13.077 6.18043L19.1839 0L20.9227 1.75942L14.8158 7.93986L13.077 6.18043ZM9.59071 13.2368L7.84818 15L0 7.0589L1.74377 5.29448L3.4863 7.05765L3.48506 7.0589L9.59071 13.2368Z" fill="#464A6B"/>
                                </svg> */}
                            </span>
                            {/* <span className='msg_left_number'>2</span> */}
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </>
  );
};

export default ChatSidebar;