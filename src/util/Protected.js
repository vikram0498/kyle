import React, {useState} from 'react';
import { useEffect } from 'react';
import {Cookies} from "react-cookie";
import { Link, useNavigate } from 'react-router-dom';
const Protected = (props) =>{
    const {Component} = props;
    const navigate = useNavigate();
    const cookie = new Cookies();
    let login = cookie.get('_token');
    useEffect(()=>{
        if(login === '' || login === undefined){
            navigate('/login');
        }
    })
    return (
        <>
        {(login !== '' && login !== undefined)? <Component/> :''}
        </>
    )
}
export default Protected;