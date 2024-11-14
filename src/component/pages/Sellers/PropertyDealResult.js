import React, { useEffect,useState } from 'react';
import { Col, Container, Image, Row } from 'react-bootstrap';
import { Link } from 'react-router-dom';
import Header from "../../partials/Layouts/Header";
import Footer from '../../partials/Layouts/Footer';
import axios from 'axios';
import { useAuth } from "../../../hooks/useAuth";


const PropertyDealResult = () => {
    const { getTokenData, setLogout } = useAuth();
    const apiUrl = process.env.REACT_APP_API_URL;
    const [dealData,setDealData] = useState([]);
    useEffect(()=>{
        let headers = {
            Accept: "application/json",
            Authorization: "Bearer " + getTokenData().access_token,
            "auth-token": getTokenData().access_token,
        };
        const fetchData = async () => {
            let response = await axios.get(`${apiUrl}deals/result-list`,{headers:headers});
            setDealData(response.data.deals.data)
        }
        fetchData();
    },[]);

    console.log(dealData,"dealData")
  return (
    <>
        <Header />
            <section className='main-section position-relative pt-4 pb-120'>
                <Container className='position-relative'>
                    <div className="back-block">
                        <div className="row">
                            <div className="col-4 col-sm-4 col-md-4 col-lg-4">
                                <Link to="/" className="back">
                                    <svg
                                    width="16"
                                    height="12"
                                    viewBox="0 0 16 12"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                    >
                                    <path
                                        d="M15 6H1"
                                        stroke="#0A2540"
                                        strokeWidth="1.5"
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                    />
                                    <path
                                        d="M5.9 11L1 6L5.9 1"
                                        stroke="#0A2540"
                                        strokeWidth="1.5"
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                    />
                                    </svg>
                                    Back
                                </Link>
                            </div>
                            <div className="col-7 col-sm-4 col-md-4 col-lg-4 align-self-center">
                                <h6 className="center-head text-center mb-0">
                                    Property Deal Result List
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div className='card-box column_bg_space'>
                        {dealData.map((data,index)=>{
                            return (
                                <div className='deal_column' key={index}>
                                    <Row className='align-items-center'>
                                        <Col lg={10}>
                                            <div className='deal_left_column'>
                                                <div className='pro_img'>
                                                    <Image src={data.property_images.length > 0 ? data.property_images[0]:'/assets/images/total-buyer.svg' } alt='' />
                                                </div>
                                                <div className='pro_details'>
                                                    <h3>{data.address}</h3>
                                                    <ul className='deal_info_list'>
                                                        <li>
                                                            <div className='list_icon'>
                                                                <Image src='/assets/images/total-buyer.svg' alt='' />
                                                            </div>
                                                            <div className='list_content'>
                                                                <span>69</span>
                                                                <p>Total Buyer</p>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div className='list_icon'>
                                                                <Image src='/assets/images/home_buy.svg' alt='' />
                                                            </div>
                                                            <div className='list_content'>
                                                                <span>{data.want_to_buy_count}</span>
                                                                <p>Want To Buy</p>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div className='list_icon'>
                                                                <Image src='/assets/images/home_check.svg' alt='' />
                                                            </div>
                                                            <div className='list_content'>
                                                                <span>{data.interested_count}</span>
                                                                <p>Interested</p>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div className='list_icon'>
                                                                <Image src='/assets/images/home_close.svg' alt='' />
                                                            </div>
                                                            <div className='list_content'>
                                                                <span>{data.not_interested_count}</span>
                                                                <p>Not Interested</p>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </Col>
                                        <Col lg={2} className='text-center'>
                                            <Link to={`/property-deal-details/${data.id}`} className='btn btn-fill btn-w-icon'>View Details<svg xmlns="http://www.w3.org/2000/svg" width="16" height="13" viewBox="0 0 16 13" fill="none">
                                                <path d="M1 6.5L15 6.5" stroke="white" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
                                                <path d="M10.1 1.5L15 6.5L10.1 11.5" stroke="white" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
                                                </svg>
                                            </Link>
                                        </Col>
                                    </Row>
                                </div>
                            )
                        })}
                    </div>
                </Container>
            </section>
        <Footer />
    </>
  );
};
export default PropertyDealResult;