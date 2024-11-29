import React, {
  createContext,
  useContext,
  useState,
  useEffect,
  useRef,
  useCallback,
} from "react";

import { io } from "socket.io-client";
import axios from "axios";
import { useUserContext } from "./user-context";

const ChatContext = createContext();
export const ChatProvider = ({ children }) => {
  const { user } = useUserContext(); // Fetch user from UserContext
  const [chatUsers, setChatUsers] = useState([]);
  const [messages, setMessages] = useState([]);
  const [socket, setSocket] = useState(null);
  const [activeChat, setActiveChat] = useState({
    chatId: null,
    senderId: null,
    receiverId: null,
  });
  const [activeUser, setActiveUser] = useState(null);
  const [messageNotifications, setMessageNotifications] = useState([]);

  const activeChatRef = useRef(activeChat);

  const fetchMessages = useCallback(async () => {
    try {
      const response = await axios.get(
        `${process.env.NEXT_PUBLIC_BACKEND_API}chats/messages/${activeChat.chatId}/${activeChat?.receiverId}/${activeChat?.senderId}`
      );
      if (response.data.success) {
        setMessages(response.data.data);
      }
    } catch (error) {
      console.error("Error fetching messages:", error);
    }
  }, [activeChat]);



  const fetchChatUsers = useCallback(async () => {
    var project_id = "All";
    if(chat_project_id){
      var project_id = chat_project_id;
    }

    try {
      const response = await axios.get(
        `${process.env.NEXT_PUBLIC_BACKEND_API}chats/users/${user?.role}/${user?.id}/${project_id}`
      );
      setChatUsers(response.data.data);
    } catch (error) {
      console.error("Error fetching chat users:", error);
    }
  }, [user?.role, user?.id]);

  useEffect(() => {
    activeChatRef.current = activeChat;
    if (activeChat?.chatId && activeChat?.receiverId) {
      fetchMessages();
      fetchNotifications();
    }
    if (activeChat.chatId && chatUsers) {
      const currentActiveUser = chatUsers.find(
        (chatUser) => chatUser.id === activeChat.chatId
      );
      setActiveUser(currentActiveUser?.users?.[0]);
    }
  }, [activeChat, chatUsers, fetchMessages, fetchNotifications]);

  useEffect(() => {
    if (user) {
      fetchChatUsers();
      fetchNotifications();
    }
  }, [user, fetchChatUsers, fetchNotifications]);

  useEffect(() => {
    if (user) {

      // Initialize Socket
      const socketInstance = io(process.env.NEXT_PUBLIC_BACKEND_API, {
        transports: ["websocket", "polling"],
        query: { userId: user?.id },
      });

      setSocket(socketInstance);

      // Listen for incoming messages
      socketInstance.on("receiveMessage", (message) => {
        const currentActiveChat = activeChatRef.current;

        if (message?.chat_id === currentActiveChat.chatId) {
          setMessages((prev) => [...prev, message]);
        }
      });

      socketInstance.on("newNotification", (message) => {
        setMessageNotifications((prev) => [...prev, message]);
      });

      socketInstance.on("receiveAttachment", (message) => {
        const currentActiveChat = activeChatRef.current;

        if (message?.chat_id == currentActiveChat.chatId) {
          setMessages((prev) => [...prev, message]);
        }
      });

      // Clean up the socket connection on unmount or when user changes
      return () => {
        socketInstance.disconnect();
      };
    }
  }, [user]);

  const createChatIfNotExists = async (activeChat) => {
    const { receiverId, senderId } = activeChat;
    let customer_id = receiverId,
      provider_id = user?.id;

    if (user?.role !== "provider") {
      customer_id = user?.id;
      provider_id = receiverId;
    }

    if (!customer_id || !provider_id || !senderId) {
      console.error("Invalid parameters for creating chat:", {
        customer_id,
        provider_id,
        senderId,
      });
      return null;
    }
    try {
      const response = await axios.post(
        `${process.env.NEXT_PUBLIC_BACKEND_API}chats/create`,
        { customer_id, provider_id, project_id: senderId }
      );
      setActiveChat((prev) => ({ ...prev, chatId: response.data.id }));
      return response.data.id;
    } catch (error) {
      console.error("Error creating chat:", error);
    }
  };


  const sendMessage = async (messageContent) => {
    const { chatId, senderId, receiverId, message_type = "text" } = activeChat;
    if (!socket || !messageContent.trim()) return;

    if (!chatId) {
      await createChatIfNotExists(activeChat);
    }

    const messageData = {
      chat_id: chatId,
      sender_id: senderId,
      receiver_id: receiverId,
      content: messageContent,
      message_type,
      sender: {},
    };

    socket.emit("sendMessage", messageData);

    setMessages((prevMessages) => [
      ...prevMessages,
      { ...messageData, createdAt: new Date(), sender: user },
    ]);

  };

  const markAsRead = async (notificationId) => {
    try {
      const response = await axios.put(
        `${process.env.NEXT_PUBLIC_BACKEND_API}notifications/${notificationId}/read`
      );
      if (response.data.success) {
        setMessageNotifications((prev) =>
          prev.map((notification) =>
            notification.id === notificationId
              ? { ...notification, is_read: true }
              : notification
          )
        );
      }
    } catch (error) {
      console.error("Error marking notification as read:", error);
    }
  };

  return (
    <ChatContext.Provider
      value={{
        socket,
        chatUsers,
        messages,
        sendMessage,
        activeUser,
        setActiveChat,
        fetchChatUsers,
        createChatIfNotExists,
        messageNotifications,
        setMessageNotifications,
        fetchNotifications,
        markAsRead,
      }}
    >
      {children}
    </ChatContext.Provider>
  );
};

export const useChatContext = () => useContext(ChatContext);
