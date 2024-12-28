import React, { useEffect, useState } from 'react';
import { Col, Container, Image, Nav, Row, Tab, Table } from 'react-bootstrap';
import { Link } from 'react-router-dom';
import Header from "../../partials/Layouts/Header";
import Footer from '../../partials/Layouts/Footer';
import { useAuth } from "../../../hooks/useAuth";
import axios from 'axios';
import { useParams } from "react-router-dom";
import Pagination from '../../partials/Pagination';

const PropertyDealDetails = () => {
    const { getTokenData, setLogout } = useAuth();
    const apiUrl = process.env.REACT_APP_API_URL;
    const [dealDetailsData,setDealDetailsData] = useState('');
    const [currentTab, setCurrentTab] = useState('total_buyer');
    const [dealData,setDealData] = useState([]);
    const [page, setPage]= useState(1);
    const [total, setTotal] = useState(0);
    const [limit, setLimit] = useState(0);

    const {id,notificationId =''} = useParams();

    useEffect(()=>{
        let headers = {
            Accept: "application/json",
            Authorization: "Bearer " + getTokenData().access_token,
            "auth-token": getTokenData().access_token,
        };
        const fetchData = async () => {
            let status = '';
            if(currentTab !== 'total_buyer'){
                status = `/${currentTab}`;
            }
            let response = await axios.post(`${apiUrl}deals/show/${id}`,{status: status,notification_id: notificationId},{headers:headers});
            setDealDetailsData(response.data.data);
            setLimit(response.data.data.buyers.per_page);
            setPage(response.data.data.buyers.current_page);
            setTotal(response.data.data.buyers.total);
            setDealData(response.data.data.buyers.data)
        }
        fetchData();
    },[currentTab,page]);

  return (
    <>
        <Header />
            <section className='main-section position-relative pt-4 pb-120'>
                <Container className='position-relative'>
                    <div className="back-block">
                        <div className="row">
                            <div className="col-4 col-sm-4 col-md-4 col-lg-4">
                                <Link to={void(0)} onClick={()=>{window.history.back()}} className="back">
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
                                    Property Deal Detail
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div className='card-box column_bg_space'>
                        <Tab.Container defaultActiveKey="total_buyer">
                            <div className='deal_column radius-b-0'>
                                <div className='deal_left_column border-end-0 detail_deal_column position-relative'>
                                    <div className='detail_deal_left flex_1column'>
                                        <div className='pro_img'>
                                            <Image src={dealDetailsData != '' ? dealDetailsData.property_images[0] : '/assets/images/property-img.png'} alt='' />
                                        </div>
                                        <div className='pro_details'>
                                            <h3>real easte company that prioritizes Property</h3>
                                            <Nav variant="pills">
                                                <Nav.Item onClick={()=>setCurrentTab('total_buyer')}>
                                                    <Nav.Link eventKey="total_buyer">
                                                        <div className='list_icon'>
                                                            <Image src='/assets/images/total-buyer.svg' alt='' />
                                                        </div>
                                                        <div className='list_content'>
                                                            <span>{dealDetailsData.total_buyer}</span>
                                                            <p>Total Buyer</p>
                                                        </div>
                                                    </Nav.Link>
                                                </Nav.Item>
                                                <Nav.Item onClick={()=>setCurrentTab('want_to_buy')}>
                                                    <Nav.Link eventKey="want_to_buy">
                                                        <div className='list_icon'>
                                                            <Image src='/assets/images/home_buy.svg' alt='' />
                                                        </div>
                                                        <div className='list_content'>
                                                            <span>{dealDetailsData.want_to_buy_count}</span>
                                                            <p>Want To Buy</p>
                                                        </div>
                                                    </Nav.Link>
                                                </Nav.Item>
                                                <Nav.Item onClick={()=>setCurrentTab('interested')}>
                                                    <Nav.Link eventKey="interested">
                                                        <div className='list_icon'>
                                                            <Image src='/assets/images/home_check.svg' alt='' />
                                                        </div>
                                                        <div className='list_content'>
                                                            <span>{dealDetailsData.interested_count}</span>
                                                            <p>Interested</p>
                                                        </div>
                                                    </Nav.Link>
                                                </Nav.Item>
                                                <Nav.Item onClick={()=>setCurrentTab('not_interested')}>
                                                    <Nav.Link eventKey="not_interested">
                                                        <div className='list_icon'>
                                                            <Image src='/assets/images/home_close.svg' alt='' />
                                                        </div>
                                                        <div className='list_content'>
                                                            <span>{dealDetailsData.not_interested_count}</span>
                                                            <p>Not Interested</p>
                                                        </div>
                                                    </Nav.Link>
                                                </Nav.Item>
                                            </Nav>
                                        </div>
                                    </div>
                                    <div className='total_notified'>
                                        <p>Total Notified Buyers <span>: 58</span></p>
                                    </div>
                                </div>
                            </div>  
                            <div className='pro_deal_table'>
                                <Tab.Content>
                                    <Tab.Pane eventKey={currentTab}>
                                        <div className='table-responsive' style={{ overflowY: "hidden" }}>
                                            <Table>
                                                <thead>
                                                    <tr>
                                                        <th>BUYER NAME</th>
                                                        <th>PHONE NUMBER</th>
                                                        <th>EMAIL ADDRESS</th>
                                                        {(currentTab =='total_buyer' || currentTab =='want_to_buy') && 
                                                        <th>PROOF OF FUND</th>
                                                        }
                                                        <th>STATUS</th>
                                                        <th>CHAT</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {dealDetailsData && dealDetailsData.buyers.data.length > 0 ? (
                                                        dealDetailsData.buyers.data.map((data, index) => {
                                                            return (
                                                                <React.Fragment key={index}>
                                                                    <tr><td colSpan="6"></td></tr>
                                                                    <tr>
                                                                        <td>
                                                                        <span>
                                                                            <span className='deal_user_img_block'>
                                                                            <Image src={data.profile_image || '/assets/images/user-img1.png'} className='deal_user_img' alt='' />
                                                                            <Image src='/assets/images/pcheck.svg' className='user_verified_check' alt='' />
                                                                            </span>
                                                                            {data.buyer_name}
                                                                        </span>
                                                                        </td>
                                                                        <td>{data.buyer_phone}</td>
                                                                        <td>{data.buyer_email}</td>
                                                                        {(currentTab =='total_buyer' || currentTab =='want_to_buy') && 
                                                                        <td>
                                                                            {(data.want_to_buy_deal_pdf_url !='' && data.want_to_buy_deal_pdf_url !=undefined) &&
                                                                            <a href={data.want_to_buy_deal_pdf_url} download="proof-of-fund">
                                                                                <span>
                                                                                <Image src="/assets/images/folder-zip.svg" alt="" /> {} Documents
                                                                                </span>
                                                                            </a>
                                                                            }
                                                                        </td>
                                                                        }
                                                                        <td>
                                                                            {data.status == 'Interested' && <span className='status interested'>Interested</span>}
                                                                            {data.status == 'Want to Buy' && <span className='status want-by'>Want To Buy</span>}
                                                                            {data.status == 'Not Interested' && <span className='status not-interested'>Not Interested</span>}
                                                                            {data.status == '' && <span className='justify-content-center'>N/A</span>}
                                                                            
                                                                        </td>
                                                                        <td>
                                                                            <Link to={`/message/${data.buyer_user_id}`}>
                                                                                <span>
                                                                                <Image src='/assets/images/chat-icon.svg' alt='' /> Chat With Buyer
                                                                                </span>
                                                                            </Link>
                                                                        </td>
                                                                    </tr>
                                                                </React.Fragment>
                                                            );
                                                        })
                                                    ) : (
                                                        <tr>
                                                        <td colSpan="6" style={{ textAlign: 'center' }}>No data found</td>
                                                        </tr>
                                                    )}
                                                </tbody>
                                            </Table>
                                        </div>
                                    </Tab.Pane>
                                    {/* <Tab.Pane eventKey="want_to_buy">
                                        <Table>
                                            <thead>
                                                <tr>
                                                    <th>Buyer Name</th>
                                                    <th>Phone Number</th>
                                                    <th>Email Address</th>
                                                    <th>documents</th>
                                                    <th>Status</th>
                                                    <th>Chat</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr><td colSpan="5"></td></tr>
                                                <tr>
                                                    <td><span><span className='deal_user_img_block'><Image src='/assets/images/user-img1.png' className='deal_user_img' alt='' /> <Image src='/assets/images/pcheck.svg' className='user_verified_check' alt='' /></span> Brooklyn Simmons</span></td>
                                                    <td>+91123456789</td>
                                                    <td>Devidmiller@gmail.com</td>
                                                    <td><span><Image src='/assets/images/folder-zip.svg' className='' alt='' /> Documents.zip</span></td>
                                                    <td><span className='status want-by'>Want To Buy</span></td>
                                                    <td><Link to='/message'><span><Image src='/assets/images/chat-icon.svg' className='' alt='' /> Chat With Buyer</span></Link></td>
                                                </tr>
                                                <tr>
                                                    <td><span><span className='deal_user_img_block'><Image src='/assets/images/user-img1.png' className='deal_user_img' alt='' /> <Image src='/assets/images/pcheck.svg' className='user_verified_check' alt='' /></span> Brooklyn Simmons</span></td>
                                                    <td>+91123456789</td>
                                                    <td>Devidmiller@gmail.com</td>
                                                    <td><span><Image src='/assets/images/folder-zip.svg' className='' alt='' /> Documents.zip</span></td>
                                                    <td><span className='status want-by'>Want To Buy</span></td>
                                                    <td><Link to='/message'><span><Image src='/assets/images/chat-icon.svg' className='' alt='' /> Chat With Buyer</span></Link></td>
                                                </tr>
                                                <tr>
                                                    <td><span><span className='deal_user_img_block'><Image src='/assets/images/user-img1.png' className='deal_user_img' alt='' /> <Image src='/assets/images/pcheck.svg' className='user_verified_check' alt='' /></span> Brooklyn Simmons</span></td>
                                                    <td>+91123456789</td>
                                                    <td>Devidmiller@gmail.com</td>
                                                    <td><span><Image src='/assets/images/folder-zip.svg' className='' alt='' /> Documents.zip</span></td>
                                                    <td><span className='status want-by'>Want To Buy</span></td>
                                                    <td><Link to='/message'><span><Image src='/assets/images/chat-icon.svg' className='' alt='' /> Chat With Buyer</span></Link></td>
                                                </tr>
                                            </tbody>
                                        </Table>
                                    </Tab.Pane>
                                    <Tab.Pane eventKey="interested">
                                        <Table>
                                            <thead>
                                                <tr>
                                                    <th>Buyer Name</th>
                                                    <th>Phone Number</th>
                                                    <th>Email Address</th>
                                                    <th>documents</th>
                                                    <th>Status</th>
                                                    <th>Chat</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr><td colSpan="5"></td></tr>
                                                <tr>
                                                    <td><span><span className='deal_user_img_block'><Image src='/assets/images/user-img1.png' className='deal_user_img' alt='' /> <Image src='/assets/images/pcheck.svg' className='user_verified_check' alt='' /></span> Brooklyn Simmons</span></td>
                                                    <td>+91123456789</td>
                                                    <td>Devidmiller@gmail.com</td>
                                                    <td><span><Image src='/assets/images/folder-zip.svg' className='' alt='' /> Documents.zip</span></td>
                                                    <td><span className='status want-by'>Want To Buy</span></td>
                                                    <td><Link to='/message'><span><Image src='/assets/images/chat-icon.svg' className='' alt='' /> Chat With Buyer</span></Link></td>
                                                </tr>
                                                <tr>
                                                    <td><span><span className='deal_user_img_block'><Image src='/assets/images/user-img1.png' className='deal_user_img' alt='' /> <Image src='/assets/images/pcheck.svg' className='user_verified_check' alt='' /></span> Brooklyn Simmons</span></td>
                                                    <td>+91123456789</td>
                                                    <td>Devidmiller@gmail.com</td>
                                                    <td><span><Image src='/assets/images/folder-zip.svg' className='' alt='' /> Documents.zip</span></td>
                                                    <td><span className='status want-by'>Want To Buy</span></td>
                                                    <td><Link to='/message'><span><Image src='/assets/images/chat-icon.svg' className='' alt='' /> Chat With Buyer</span></Link></td>
                                                </tr>
                                                <tr>
                                                    <td><span><span className='deal_user_img_block'><Image src='/assets/images/user-img1.png' className='deal_user_img' alt='' /> <Image src='/assets/images/pcheck.svg' className='user_verified_check' alt='' /></span> Brooklyn Simmons</span></td>
                                                    <td>+91123456789</td>
                                                    <td>Devidmiller@gmail.com</td>
                                                    <td><span><Image src='/assets/images/folder-zip.svg' className='' alt='' /> Documents.zip</span></td>
                                                    <td><span className='status want-by'>Want To Buy</span></td>
                                                    <td><Link to='/message'><span><Image src='/assets/images/chat-icon.svg' className='' alt='' /> Chat With Buyer</span></Link></td>
                                                </tr>
                                            </tbody>
                                        </Table>
                                    </Tab.Pane>
                                    <Tab.Pane eventKey="not_interested">
                                        <Table>
                                            <thead>
                                                <tr>
                                                    <th>Buyer Name</th>
                                                    <th>Phone Number</th>
                                                    <th>Email Address</th>
                                                    <th>documents</th>
                                                    <th>Status</th>
                                                    <th>Chat</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr><td colSpan="5"></td></tr>
                                                <tr>
                                                    <td><span><span className='deal_user_img_block'><Image src='/assets/images/user-img1.png' className='deal_user_img' alt='' /> <Image src='/assets/images/pcheck.svg' className='user_verified_check' alt='' /></span> Brooklyn Simmons</span></td>
                                                    <td>+91123456789</td>
                                                    <td>Devidmiller@gmail.com</td>
                                                    <td><span><Image src='/assets/images/folder-zip.svg' className='' alt='' /> Documents.zip</span></td>
                                                    <td><span className='status want-by'>Want To Buy</span></td>
                                                    <td><Link to='/message'><span><Image src='/assets/images/chat-icon.svg' className='' alt='' /> Chat With Buyer</span></Link></td>
                                                </tr>
                                                <tr>
                                                    <td><span><span className='deal_user_img_block'><Image src='/assets/images/user-img1.png' className='deal_user_img' alt='' /> <Image src='/assets/images/pcheck.svg' className='user_verified_check' alt='' /></span> Brooklyn Simmons</span></td>
                                                    <td>+91123456789</td>
                                                    <td>Devidmiller@gmail.com</td>
                                                    <td><span><Image src='/assets/images/folder-zip.svg' className='' alt='' /> Documents.zip</span></td>
                                                    <td><span className='status want-by'>Want To Buy</span></td>
                                                    <td><Link to='/message'><span><Image src='/assets/images/chat-icon.svg' className='' alt='' /> Chat With Buyer</span></Link></td>
                                                </tr>
                                                <tr>
                                                    <td><span><span className='deal_user_img_block'><Image src='/assets/images/user-img1.png' className='deal_user_img' alt='' /> <Image src='/assets/images/pcheck.svg' className='user_verified_check' alt='' /></span> Brooklyn Simmons</span></td>
                                                    <td>+91123456789</td>
                                                    <td>Devidmiller@gmail.com</td>
                                                    <td><span><Image src='/assets/images/folder-zip.svg' className='' alt='' /> Documents.zip</span></td>
                                                    <td><span className='status want-by'>Want To Buy</span></td>
                                                    <td><Link to='/message'><span><Image src='/assets/images/chat-icon.svg' className='' alt='' /> Chat With Buyer</span></Link></td>
                                                </tr>
                                            </tbody>
                                        </Table>
                                    </Tab.Pane> */}
                                </Tab.Content>
                                <Pagination page={page} setPage={setPage} limit={limit} total={total}/>
                            </div>
                        </Tab.Container>
                    </div>
                </Container>
            </section>
        <Footer />
    </>
  );
};
export default PropertyDealDetails;