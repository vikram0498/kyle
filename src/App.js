import React, { Component, useEffect } from "react";
import { BrowserRouter } from "react-router-dom";

import { ToastContainer } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";
import "./App.css";
import Seller from "./Routes/Sellers";
import Buyers from "./Routes/Buyers";

function App(){
  return (
    <BrowserRouter>
      <ToastContainer />
      <Seller />
      <Buyers />
    </BrowserRouter>
  );
}
export default App;
