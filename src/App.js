import React, { Component } from 'react';
import { BrowserRouter } from 'react-router-dom';

import { ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

import './App.css'

import RoutesList from './RoutesList';

class App extends Component {
  render() {
    return (
      <BrowserRouter>
        <ToastContainer />
        <RoutesList />
      </BrowserRouter>
    );
  }
}

export default App;