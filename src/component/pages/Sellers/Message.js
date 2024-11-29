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

    const apiUrl = process.env.REACT_APP_API_URL || "http://192.168.1.14:8000/api/";

    useEffect(() => {
        const userData = getLocalStorageUserdata();
        const socketInstance = io(process.env.REACT_APP_BACKEND_URL || "http://192.168.1.14:3000", {
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
            setMessages(response.data.messages || []);
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
            const response = await axios.get(`${apiUrl}get-chat-list`, { headers });
            setChatList(response.data.data || []);
            if(chatPartnerId !== undefined){
                setReceiverId(chatPartnerId)
            }else{
                setReceiverId(response.data.data[0].id)
            }
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
        const payload = {
            access_token: `Bearer ${getTokenData().access_token}`,
            recipient_id: receiverId,
            content: message.trim(),
            type: "text",
        };
        console.log("Payload:", payload); 

        socket.emit("sendMessage", payload);
        setMessage(""); 
    };

    useEffect(() => {
        fetchChatList();
        fetchMessages();
    }, [receiverId]);

    useEffect(() => {
        const userData = getLocalStorageUserdata();
        if (getTokenData().access_token && userRole === 0) {
            setUserRole(userData.role);
        }
    }, []);

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
                                    {/* Back SVG */}
                                </Link>
                            </div>
                            <div className="col-7 align-self-center">
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
