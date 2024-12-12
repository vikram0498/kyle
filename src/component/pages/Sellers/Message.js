import React, { useState, useEffect, useRef } from "react";
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
    const [receiverId, setReceiverId] = useState("");

    const apiUrl = process.env.REACT_APP_API_URL;

    // Ref for the messages container
    const messagesEndRef = useRef("chat_box");

    // Utility to get authorization headers
    const getAuthHeaders = () => ({
        Accept: "application/json",
        Authorization: `Bearer ${getTokenData().access_token}`,
    });

    // Scroll to the bottom of the message list
    const scrollToBottom = () => {
        const chatBody = document.querySelector('.whole_messages');
        if (chatBody) {
            chatBody.scrollTop = chatBody.scrollHeight;
        }
    };

    // Initialize socket.io and setup listeners
    useEffect(() => {
        const userData = getLocalStorageUserdata();
        const socketInstance = io(process.env.REACT_APP_SOCKET_URL, {
            transports: ["websocket", "polling"],
            query: { userId: userData.id },
        });

        setSocket(socketInstance);

        const handleMessage = (newMessage) => {
            console.log(newMessage,"newMessage",messages)
            setMessages((prevMessages) => {
                // Clone the current messages object to avoid direct mutation
                const updatedMessages = { ...prevMessages };
            
                // Ensure 'Today' exists in the structure
                if (!updatedMessages.Today) {
                    updatedMessages.Today = [];
                }
            
                // Add the new senderMessage to the 'Today' array
                updatedMessages.Today.push(newMessage);
            
                // Return the updated messages object
                return updatedMessages;
            });
            // setMessages((prevMessages) => [...prevMessages, newMessage]);
        };

        socketInstance.on("connect", () => console.log("Connected to Socket.IO server"));
        socketInstance.on("receiveMessage", handleMessage);

        return () => {
            socketInstance.off("receiveMessage", handleMessage);
            socketInstance.disconnect();
        };
    }, []);

    // Fetch chat messages for the selected receiver
    const fetchMessages = async () => {
        if (!receiverId) return;

        try {
            const response = await axios.post(
                `${apiUrl}chat-messages`,
                { recipient_id: receiverId },
                { headers: getAuthHeaders() }
            );
            setActiveUserData(response.data.data || []);
            setMessages(response.data.message || []);
        } catch (error) {
            console.error("Error fetching messages:", error.response?.data?.message || error.message);
        }
    };

    // Fetch the list of chats for the user
    const fetchChatList = async () => {
        try {
            const response = await axios.get(`${apiUrl}get-chat-list/${chatPartnerId || ''}`, {
                headers: getAuthHeaders(),
            });
            setChatList(response.data.data || []);
            if (!receiverId && response.data.data.length) {
                setReceiverId(response.data.data[0].id);
            }
        } catch (error) {
            console.error("Error fetching chat list:", error.response?.data?.message || error.message);
        }
    };

    // Send a message to the selected receiver
    const sendMessage = () => {
        if (!socket?.connected || !message.trim()) {
            console.error("Socket is not connected or message is empty");
            return;
        }

        const now = new Date();
        const created_time = now.toLocaleTimeString("en-GB", { hour: "2-digit", minute: "2-digit", hour12: true });

        const senderMessage = {
            content: message.trim(),
            sender_id: getLocalStorageUserdata().id,
            recipient_id: receiverId,
            type: "text",
            chat_type: "direct",
            date_time_label: "Today",
            created_time,
            timestamp: now.toISOString(),
        };

        setMessages((prevMessages) => {
            // Clone the current messages object to avoid direct mutation
            const updatedMessages = { ...prevMessages };
        
            // Ensure 'Today' exists in the structure
            if (!updatedMessages.Today) {
                updatedMessages.Today = [];
            }
        
            // Add the new senderMessage to the 'Today' array
            updatedMessages.Today.push(senderMessage);
        
            // Return the updated messages object
            return updatedMessages;
        });
        // setMessages((prevMessages) => [...prevMessages, senderMessage]);

        const payload = {
            access_token: `Bearer ${getTokenData().access_token}`,
            recipient_id: receiverId,
            content: message.trim(),
            type: "text",
        };
        socket.emit("sendMessage", payload);
        setMessage("");
    };

    // Scroll to bottom whenever messages change
    useEffect(() => {
        scrollToBottom();
    }, [messages]);

    // Fetch chat list and messages whenever `receiverId` changes
    useEffect(() => {
        fetchChatList();
    }, []);

    useEffect(() => {
        fetchMessages();
    }, [receiverId]);

    // Set user role and initial receiver ID
    useEffect(() => {
        const userData = getLocalStorageUserdata();
        if (getTokenData().access_token && userRole === 0) {
            setUserRole(userData.role);
        }
        setReceiverId(chatPartnerId);
    }, []);
    console.log(messagesEndRef,"messagesEndRef")
    return (
        <>
            {userRole === 3 && <BuyerHeader />}
            {userRole === 2 && <Header />}
            <section className="main-section position-relative pt-4 pb-120">
                <Container className="position-relative">
                    <div className="back-block">
                        <div className="row">
                            <div className="col-4">
                                <Link to="/" className="back">
                                    <svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M15 6H1" stroke="#0A2540" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                                        <path d="M5.9 11L1 6L5.9 1" stroke="#0A2540" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                                    </svg>
                                    Back
                                </Link>
                            </div>
                            <div className="col-7 text-center">
                                <h6 className="center-head mb-0">Message</h6>
                            </div>
                        </div>
                    </div>
                    <div className="card-box">
                        <Row>
                            <Col lg="4">
                                <ChatSidebar chatList={chatList} setReceiverId={setReceiverId} receiverId={receiverId} />
                            </Col>
                            <Col lg="8">
                                <div className="message-panel">
                                    <ChatMessagePanel
                                        messages={messages}
                                        message={message}
                                        setMessage={setMessage}
                                        sendMessage={sendMessage}
                                        activeUserData={activeUserData}
                                    />
                                    <div ref={messagesEndRef} />
                                </div>
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
