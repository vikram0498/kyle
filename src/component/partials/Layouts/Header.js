import React,{useEffect,useState}  from 'react'
import {useAuth} from "../../../hooks/useAuth";
import {Link , useLocation, useNavigate} from "react-router-dom";
import axios from 'axios';

function Header() {
	const [userDetails, setUserDetails] = useState(null);
	const {setLogout, getTokenData} = useAuth();

	const location = useLocation();
    const isNotSearchPage = location.pathname !== '/sellers-form';

	if(isNotSearchPage){
		localStorage.removeItem('filter_buyer_fields');
		localStorage.removeItem('get_filtered_data');
	}


	useEffect(() => {
		let data = '';
		const apiUrl = process.env.REACT_APP_API_URL;
		if(getTokenData().access_token != null){
			/* let headers = {
				"Accept": "application/json", 
				'Authorization': 'Bearer ' + getTokenData().access_token,
			} */
			if(localStorage.getItem('user_data') !== null){
				let userData = localStorage.getItem('user_data');   
				setUserDetails(JSON.parse(userData));
			}
		}
		
    }, []);
  return (
    <>
		<header className="dashboard-header">
			<div className="container-fluid">
				<div className="row align-items-center">
					<div className="col-6 col-sm-6 col-md-4 col-lg-3">
						<div className="header-logo">
							<Link to='/'>
								<img src="./assets/images/logo.svg" className="img-fluid" />
							</Link>
						</div>
					</div>
					<div className="col-6 col-sm-6 col-md-8 col-lg-9">
						<div className="block-session">
							<div className="upload-buyer">
								<span className="upload-buyer-icon">
									<img src="./assets/images/folder.svg" className="img-fluid" /></span>
								<p>uploaded Buyer Data : <b>0</b></p>
							</div>
							{(userDetails !=null && userDetails.level_type !=1)?
							<div class="upload-buyer">
								<span class="upload-buyer-icon"><img src="./assets/images/wallet.svg" class="img-fluid" /></span>
								<p>Credits Points : <b>40</b></p>
							</div>:''}
							
							<div className="dropdown user-dropdown">
								<button className="btn dropdown-toggle ms-auto" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
									<div className="dropdown-data">
										<div className="img-user"><img src={(userDetails !=null && userDetails.profile_image != '') ? userDetails.profile_image : './assets/images/avtar.png'} className="img-fluid user-profile" alt="" /></div>
										<div className="welcome-user" style={{display:'block'}}>
											<span className="welcome">welcome</span>
											<span className="user-name-title">
												{(userDetails !=null) ? userDetails.first_name + ' ' + userDetails.last_name : ''}
											</span>
										</div>
									</div>
									<span className="arrow-icon">
										<svg width="14" height="8" viewBox="0 0 14 8" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M13.002 7L7.00195 0.999999L1.00195 7" stroke="black" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
										</svg>
									</span>
								</button>
								<div className="dropdown-menu" aria-labelledby="dropdownMenuButton1">
									<ul className="list-unstyled mb-0">
										<li>
											<Link className="dropdown-item" to="/my-profile">
												<img src="./assets/images/user-login.svg" className="img-fluid" />My Profile
											</Link >
										</li>
										<li>
											<Link className="dropdown-item" to="/my-buyers">
												<img src="./assets/images/booksaved.svg" className="img-fluid" />My Buyers Data
											</Link>
										</li>
										<li>
											<a className="dropdown-item" href="/support"><img src="./assets/images/messages.svg" className="img-fluid" />Support
											</a>
										</li>
										<li>
											<a className="dropdown-item" style={{ cursor: "pointer" }} onClick={setLogout}>
												<img src="./assets/images/logoutcurve.svg" className="img-fluid" />Logout
											</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</header>
    </>
  )
}

export default Header