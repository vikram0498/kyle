import React from 'react'
import { Figure, Image } from 'react-bootstrap';

const ChatSidebar = ({chatList,setReceiverId, receiverId}) => {
    const closeSidebar = () => {
        document.body.classList.remove("msg-sidebar-open");
      };
  return (
    <>
        <div className='chat_sidebar'>
            <h6>Messages 
                <span className='sidebar_mob_close d-lg-none' onClick={closeSidebar}>
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none">
                        <path d="M9.28265 7.5L14.6306 2.15205C15.7766 1.00606 13.9939 -0.776597 12.848 0.369393L7.5 5.71735L2.15205 0.369393C1.00606 -0.776597 -0.776597 1.00606 0.369393 2.15205L5.71735 7.5L0.369393 12.848C-0.776597 13.9939 1.00606 15.7766 2.15205 14.6306L7.5 9.28265L12.848 14.6306C13.9939 15.7766 15.7766 13.9939 14.6306 12.848L9.28265 7.5Z" fill="#5c5c5c"/>
                    </svg>
                </span>
            </h6>
            <ul className='chat_side_list scrollbar_design'>
                {chatList.map((data,index)=>{
                    return(
                        <li onClick={() => {
                            setReceiverId(data.id);
                            closeSidebar();
                          }} key={index} className={data.is_block == 1 ? 'blocked' : data.id == receiverId && 'active-user'}>
                            <div className='chat_user_img'>
                                <Figure>
                                    <Image src={data.profile_image || '/assets/images/property-img.png'} alt='' />
                                </Figure>
                            </div>
                            <div className='chat_pro_area'>
                                <div className='chat_user_info'>
                                    <span>{data.name}</span>
                                    {/* <p>{data?.last_message?.content}</p> */}
                                </div>
                                <div className='chat_status_area'>
                                    <p className='mb-0'>{data.last_message_at}</p>
                                    {data.unread_message_count > 0 && data.id != receiverId? 
                                    <div className='seen_status text-end'>
                                        <span className='msg_left_number'>{data.unread_message_count}</span>
                                    </div>:
                                    <div className='seen_status text-end'>
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="27" height="15" viewBox="0 0 27 15" fill="none">
                                                <path d="M13.0745 9.70926L14.8158 11.4712L25.2562 0.907162L27 2.67157L14.8158 15L6.96766 7.0589L8.71143 5.29448L11.332 7.94609L13.0745 9.70801V9.70926ZM13.077 6.18043L19.1839 0L20.9227 1.75942L14.8158 7.93986L13.077 6.18043ZM9.59071 13.2368L7.84818 15L0 7.0589L1.74377 5.29448L3.4863 7.05765L3.48506 7.0589L9.59071 13.2368Z" fill="#3F53FE"/>
                                            </svg>
                                        </span>
                                    </div>
                                    }
                                </div>
                            </div>
                        </li>
                    )
                })}
                
                {/* <li>
                    <div className='chat_user_img'>
                        <Figure>
                            <Image src='/assets/images/property-img.png' alt='' />
                        </Figure>
                    </div>
                    <div className='chat_pro_area'>
                        <div className='chat_user_info'>
                            <span>Raghav</span>
                            <p>Dinner?</p>
                        </div>
                        <div className='chat_status_area'>
                            <p className='mb-0'>Today, 8:56pm</p>
                            <div className='seen_status text-end'>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="27" height="15" viewBox="0 0 27 15" fill="none">
                                        <path d="M13.0745 9.70926L14.8158 11.4712L25.2562 0.907162L27 2.67157L14.8158 15L6.96766 7.0589L8.71143 5.29448L11.332 7.94609L13.0745 9.70801V9.70926ZM13.077 6.18043L19.1839 0L20.9227 1.75942L14.8158 7.93986L13.077 6.18043ZM9.59071 13.2368L7.84818 15L0 7.0589L1.74377 5.29448L3.4863 7.05765L3.48506 7.0589L9.59071 13.2368Z" fill="#464A6B"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div className='chat_user_img'>
                        <Figure>
                            <Image src='/assets/images/property-img.png' alt='' />
                        </Figure>
                    </div>
                    <div className='chat_pro_area'>
                        <div className='chat_user_info'>
                            <span>Raghav</span>
                            <p>Dinner?</p>
                        </div>
                        <div className='chat_status_area'>
                            <p className='mb-0'>Today, 8:56pm</p>
                            <div className='seen_status text-end'>
                                <span className='msg_left_number'>2</span>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div className='chat_user_img'>
                        <Figure>
                            <Image src='/assets/images/property-img.png' alt='' />
                        </Figure>
                    </div>
                    <div className='chat_pro_area'>
                        <div className='chat_user_info'>
                            <span>Raghav</span>
                            <p>Dinner?</p>
                        </div>
                        <div className='chat_status_area'>
                            <p className='mb-0'>Today, 8:56pm</p>
                            <div className='seen_status text-end'>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="27" height="15" viewBox="0 0 27 15" fill="none">
                                        <path d="M13.0745 9.70926L14.8158 11.4712L25.2562 0.907162L27 2.67157L14.8158 15L6.96766 7.0589L8.71143 5.29448L11.332 7.94609L13.0745 9.70801V9.70926ZM13.077 6.18043L19.1839 0L20.9227 1.75942L14.8158 7.93986L13.077 6.18043ZM9.59071 13.2368L7.84818 15L0 7.0589L1.74377 5.29448L3.4863 7.05765L3.48506 7.0589L9.59071 13.2368Z" fill="#3F53FE"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div className='chat_user_img'>
                        <Figure>
                            <Image src='/assets/images/property-img.png' alt='' />
                        </Figure>
                    </div>
                    <div className='chat_pro_area'>
                        <div className='chat_user_info'>
                            <span>Raghav</span>
                            <p>Dinner?</p>
                        </div>
                        <div className='chat_status_area'>
                            <p className='mb-0'>Today, 8:56pm</p>
                            <div className='seen_status text-end'>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="27" height="15" viewBox="0 0 27 15" fill="none">
                                        <path d="M13.0745 9.70926L14.8158 11.4712L25.2562 0.907162L27 2.67157L14.8158 15L6.96766 7.0589L8.71143 5.29448L11.332 7.94609L13.0745 9.70801V9.70926ZM13.077 6.18043L19.1839 0L20.9227 1.75942L14.8158 7.93986L13.077 6.18043ZM9.59071 13.2368L7.84818 15L0 7.0589L1.74377 5.29448L3.4863 7.05765L3.48506 7.0589L9.59071 13.2368Z" fill="#464A6B"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div className='chat_user_img'>
                        <Figure>
                            <Image src='/assets/images/property-img.png' alt='' />
                        </Figure>
                    </div>
                    <div className='chat_pro_area'>
                        <div className='chat_user_info'>
                            <span>Raghav</span>
                            <p>Dinner?</p>
                        </div>
                        <div className='chat_status_area'>
                            <p className='mb-0'>Today, 8:56pm</p>
                            <div className='seen_status text-end'>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="27" height="15" viewBox="0 0 27 15" fill="none">
                                        <path d="M13.0745 9.70926L14.8158 11.4712L25.2562 0.907162L27 2.67157L14.8158 15L6.96766 7.0589L8.71143 5.29448L11.332 7.94609L13.0745 9.70801V9.70926ZM13.077 6.18043L19.1839 0L20.9227 1.75942L14.8158 7.93986L13.077 6.18043ZM9.59071 13.2368L7.84818 15L0 7.0589L1.74377 5.29448L3.4863 7.05765L3.48506 7.0589L9.59071 13.2368Z" fill="#464A6B"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                </li> */}
            </ul>
        </div>
        <div className='mobile_overlay d-lg-none' onClick={closeSidebar}></div>
    </>
  );
};

export default ChatSidebar;