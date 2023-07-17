import React from 'react';
import { Link, useLocation } from 'react-router-dom';

function Head({ children }) {
    const location = useLocation();
    const isNotLogin = location.pathname !== '/login';
    return (
        <head>
            <meta charSet="utf-8"/>
            <meta name="viewport" content="width=device-width, initial-scale=1"/>
            <title>KYLE - Buyers Form</title>
        
            <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
        
            <link rel="stylesheet" type="text/css" href="css/main.css"/>
        
            <link rel="stylesheet" type="text/css" href="css/responsive.css"/>
        
            <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700;800&family=Nunito+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
        </head>
    );
}
export default Head