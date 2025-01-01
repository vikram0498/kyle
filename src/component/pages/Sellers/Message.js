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
import Swal from "sweetalert2";

const Message = () => {
  const { id: chatPartnerId } = useParams();
  const { getTokenData, getLocalStorageUserdata } = useAuth();

  const [userRole, setUserRole] = useState(0);
  const [messages, setMessages] = useState([]);
  const [activeUserData, setActiveUserData] = useState([]);
  const [chatList, setChatList] = useState([]);
  const [message, setMessage] = useState("");
  const [socket, setSocket] = useState(null);
  const [isLoader, setIsLoader] = useState(false);
  const [receiverId, setReceiverId] = useState("");
  const [conversationUuid, setConversationUuid] = useState('');
  const [currentUserId, setCurrentUserId] = useState(0);
  const [isSubmitted, setIsSubmitted] = useState(false);
  const [isBlocked, setIsBlocked] = useState(0);
  const apiUrl = process.env.REACT_APP_API_URL;

  const messagesEndRef = useRef("chat_box");

  const getAuthHeaders = () => ({
    Accept: "application/json",
    Authorization: `Bearer ${getTokenData().access_token}`,
  });

  const scrollToBottom = () => {
    const chatBody = document.querySelector('.whole_messages');
    if (chatBody) {
      chatBody.scrollTop = chatBody.scrollHeight;
    }
  };

  useEffect(() => {
    const userData = getLocalStorageUserdata();
    const socketInstance = io(process.env.REACT_APP_SOCKET_URL, {
      transports: ["websocket", "polling"],
      query: { userId: userData.id },
    });

    setSocket(socketInstance);

    const handleMessage = (newMessage) => {
      console.log(newMessage.sender_user,"newMessage")
      const chatIdValue = sessionStorage.getItem('chatId');
      setMessages((prevMessages) => {
        const updatedMessages = { ...prevMessages };
        if (!updatedMessages.Today) updatedMessages.Today = [];
        if (chatIdValue === newMessage.conversation_uuid) {
          updatedMessages.Today.push(newMessage);
        }
        return updatedMessages;
      });

      setChatList((prevChatList) => {
        const updatedChatList = prevChatList.map((chat) =>
          chat.id === newMessage.sender_user.id
            ? {
                ...chat,
                ...newMessage,
                id: chat.id, // Preserve the original ID
                unread_message_count: chat.unread_message_count + 1, // Increment unread count
                last_message: newMessage, // Update last message
                last_message_at: newMessage.sender_user.last_message_at, // Update timestamp
              }
            : chat
        );
        return updatedChatList; // Return the updated chat list
      });
    };

    socketInstance.on("connect", () => console.log("Connected to Socket.IO server"));
    socketInstance.on("receiveMessage", handleMessage);

    return () => {
      socketInstance.off("receiveMessage", handleMessage);
      socketInstance.disconnect();
    };
  }, []);

  const fetchMessages = async () => {
    if (!receiverId) return;

    try {
      const response = await axios.post(`${apiUrl}chat-messages`, { recipient_id: receiverId }, { headers: getAuthHeaders() });
      setActiveUserData(response.data.data || []);
      setMessages(response.data.message || []);
      setConversationUuid(response.data.data.conversation_uuid);
      setCurrentUserId(response.data.data.id);
      sessionStorage.setItem('chatId', response.data.data.conversation_uuid);
    } catch (error) {
      console.error("Error fetching messages:", error.response?.data?.message || error.message);
    }
  };

  const fetchChatList = async () => {
    try {
      setIsLoader(true);
      const response = await axios.post(`${apiUrl}get-chat-list`, { is_blocked: isBlocked, recipient_id: chatPartnerId || '' }, { headers: getAuthHeaders() });
      setChatList(response.data.data || []);
      if (!receiverId && response.data.data.length) {
        setReceiverId(response.data.data[0].id);
      }
      setIsLoader(false);
    } catch (error) {
      setIsLoader(false);
      console.error("Error fetching chat list:", error.response?.data?.message || error.message);
    }
  };

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
      const updatedMessages = { ...prevMessages };
      if (!updatedMessages.Today) updatedMessages.Today = [];
      updatedMessages.Today.push(senderMessage);
      return updatedMessages;
    });

    setChatList((prevChatList) => {
        const updatedChatList = prevChatList.map(chat => {
            if (chat.id === receiverId) {
              chat.last_message.created_time = created_time;
              chat.last_message.content =  message.trim();
            }
            return chat;
          });
        return updatedChatList;
    });

    const payload = {
      access_token: `Bearer ${getTokenData().access_token}`,
      recipient_id: receiverId,
      content: message.trim(),
      type: "text",
    };

    socket.emit("sendMessage", payload);
    setMessage("");
  };

  const handleBlockUser = async (userId, status) => {
    try {
      const response = await axios.post(`${apiUrl}update-block-status`, { recipient_id: userId, is_block: status }, { headers: getAuthHeaders() });
      Swal.fire({
        icon: "success",
        title: "Success!",
        text: "The user block status has been updated.",
      });
      fetchMessages();
      fetchChatList();
    } catch (error) {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "Failed to update the user block status. Please try again.",
      });
    }
  };

  const markReadNotification = async () => {
    try {
      await axios.post(`${apiUrl}mark-read-message`, { conversationUuid }, { headers: getAuthHeaders() });
    } catch (error) {
      console.log("Error marking as read:", error.response || error.message);
    }
  };

  useEffect(() => {
    scrollToBottom();
  }, [messages]);

  useEffect(() => {
    fetchChatList();
  }, [isBlocked]);

  useEffect(() => {
    if (receiverId) {
        fetchMessages();

        setChatList(chatList.map(chat => {
            if (chat.id === receiverId) {
              return {
                ...chat,
                unread_message_count: 0
              };
            }
            return chat;
          }));
    }
  }, [receiverId]);

  useEffect(() => {
    if (conversationUuid) markReadNotification();
  }, [conversationUuid]);

  useEffect(() => {
    const userData = getLocalStorageUserdata();
    if (getTokenData().access_token && userRole === 0) {
      setUserRole(userData.role);
    }
    setReceiverId(chatPartnerId);
  }, []);

  return (
    <>
      {userRole === 3 && <BuyerHeader />}
      {userRole === 2 && <Header />}
      <section className="main-section position-relative pt-4 pb-120">
        {isLoader ? <div className="loader" style={{ textAlign: "center" }}><img src="/assets/images/loader.svg" /></div> :
          <Container className="position-relative">
            <div className="back-block">
              <div className="row">
                <div className="col-4">
                  <Link to={void(0)} onClick={() => { window.history.back() }} className="back">
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
              {chatList.length > 0 ?
                <Row>
                  <Col lg="4">
                    <ChatSidebar chatList={chatList} setReceiverId={setReceiverId} receiverId={receiverId} handleConfirmBox={handleBlockUser} setIsBlocked={setIsBlocked} isBlocked={isBlocked} />
                  </Col>
                  <Col lg="8">
                    <div className="message-panel">
                      <ChatMessagePanel
                        messages={messages}
                        message={message}
                        setMessage={setMessage}
                        sendMessage={sendMessage}
                        activeUserData={activeUserData}
                        handleConfirmBox={handleBlockUser}
                        conversationUuid={conversationUuid}
                        currentUserId={currentUserId}
                        setIsSubmitted={setIsSubmitted}
                        isSubmitted={isSubmitted}
                      />
                      <div ref={messagesEndRef} />
                    </div>
                  </Col>
                </Row> :
                <Row className="text-center"><p>No chat found</p></Row>
              }
            </div>
          </Container>
        }
      </section>
      <Footer />
    </>
  );
};

export default Message;
