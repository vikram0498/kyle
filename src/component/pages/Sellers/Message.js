import React, { useState, useEffect } from "react";
import { useParams, Link } from "react-router-dom";
import { io } from "socket.io-client";
import axios from "axios";
import { Container, Row, Col } from "react-bootstrap";
import ChatSidebar from "../../partials/ChatSidebar";
import ChatMessagePanel from "../../partials/ChatMessagePanel";
import { useAuth } from "../../../hooks/useAuth";
import BuyerHeader from "../../partials/Layouts/BuyerHeader";
import Header from "../../partials/Layouts/Header";
import Footer from "../../partials/Layouts/Footer";

const Message = () => {
    const { id: chatPartnerId } = useParams(); 
    const { getTokenData, getLocalStorageUserdata } = useAuth();
    const [userRole, setUserRole] = useState(0);
    const [messages, setMessages] = useState([]);
    const [activeUserData, setActiveUserData] = useState([]);
    const [chatList, setChatList] = useState([]);
    const [message, setMessage] = useState("");
    const [socket, setSocket] = useState(null);
    const [receiverId, setReceiverId] = useState(''); 

    const apiUrl = process.env.REACT_APP_API_URL
    useEffect(() => {
        const userData = getLocalStorageUserdata();
        const socketInstance = io(process.env.REACT_APP_SOCKET_URL, {
            transports: ["websocket", "polling"],
            query: { userId: userData.id }, // Ensure valid user ID is passed
        });

        setSocket(socketInstance);

        socketInstance.on("connect", () => {
            console.log("Connected to Socket.IO server");
        });

        socketInstance.on("receiveMessage", (newMessage) => {
            console.log("New message received:", newMessage);
            setMessages((prevMessages) => [...prevMessages, newMessage]);
        });

        return () => {
            if (socketInstance) socketInstance.disconnect();
        };
    }, []);

    const fetchMessages = async () => {
        const headers = {
            Accept: "application/json",
            Authorization: `Bearer ${getTokenData().access_token}`,
        };

        try {
            const response = await axios.post(
                `${apiUrl}chat-messages`,
                { recipient_id: receiverId },
                { headers }
            );
            setActiveUserData(response.data.data || [])
            setMessages(response.data.message.Today || []);
        } catch (error) {
            console.error("Error fetching messages:", error);
        }
    };

    const fetchChatList = async () => {
        const headers = {
            Accept: "application/json",
            Authorization: `Bearer ${getTokenData().access_token}`,
        };
        try {
            const response = await axios.get(`${apiUrl}get-chat-list/${receiverId}`, { headers });
            setChatList(response.data.data || []);
            if(receiverId == ''){
                setReceiverId(response.data.data[0].id)
            }
            // if(chatPartnerId !== undefined){
            //     setReceiverId(chatPartnerId)
            // }else{
            //     setReceiverId(response.data.data[0].id)
            // }
        } catch (error) {
            console.error("Error fetching chat list:", error);
        }
    };

    const sendMessage = () => {
        if (!socket || !message.trim()) {
            console.log("Message is empty or socket is not connected");
            return; 
        }
        console.log("Sending message:", message);
        const now = new Date();
        const created_time = now.toLocaleTimeString("en-GB", { hour: '2-digit', minute: '2-digit', hour12: true  }); // Format as "HH:MM AM/PM"

        // Create a message object for the sender's message
        const senderMessage = {
            content: message.trim(),
            sender_id: getLocalStorageUserdata().id, // Assuming the user ID is stored in local storage
            recipient_id: receiverId,
            type: "text",
            chat_type: "direct",
            date_time_label:"Today",
            created_time:created_time,
            timestamp: new Date().toISOString(), // Add timestamp if needed
        };

        // Update the local state with the new sender's message
        setMessages((prevMessages) => [...prevMessages, senderMessage]);
        
        const payload = {
            access_token: `Bearer ${getTokenData().access_token}`,
            recipient_id: receiverId,
            content: message.trim(),
            type: "text",
        };
        socket.emit("sendMessage", payload);
        setMessage(""); 
    };

    useEffect(() => {
        console.log(receiverId,"receiverId");
        fetchChatList();
        if(receiverId !== ''){
            fetchMessages();
        }
    }, [receiverId]);

    useEffect(() => {
        const userData = getLocalStorageUserdata();
        if (getTokenData().access_token && userRole === 0) {
            setUserRole(userData.role);
        }
        setReceiverId(chatPartnerId)
    }, []);

    return (
        <>
            {userRole === 3 && <BuyerHeader />}
            {userRole === 2 && <Header />}
            <section className="main-section position-relative pt-4 pb-120">
                <Container className="position-relative">
                    <div className="back-block">
                        <div className="row">
                            <div className="col-4 col-sm-4 col-md-4 col-lg-4">
                                <Link to="/" className="back">
                                    <svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M15 6H1" stroke="#0A2540" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M5.9 11L1 6L5.9 1" stroke="#0A2540" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>Back
                                </Link>
                            </div>
                            <div className="col-7 col-sm-4 col-md-4 col-lg-4 align-self-center">
                                <h6 className="center-head text-center mb-0">Message</h6>
                            </div>
                        </div>
                    </div>
                    <div className="card-box">
                        <Row>
                            <Col lg="4">
                                <ChatSidebar chatList={chatList} setReceiverId={setReceiverId} receiverId={receiverId} />
                            </Col>
                            <Col lg="8">
                                <ChatMessagePanel
                                    messages={messages}
                                    message={message}
                                    setMessage={setMessage}
                                    sendMessage={sendMessage}
                                    activeUserData={activeUserData}
                                />
                            </Col>
                        </Row>
                    </div>
                </Container>
            </section>
            <Footer />
        </>
    );
};

export default Message;
