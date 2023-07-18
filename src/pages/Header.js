import React from 'react'
import {useAuth} from "../hooks/useAuth";
function Header() {
	const {setLogout} = useAuth();
  return (
    <>
    <header className="dashboard-header">
		<div className="container-fluid">
			<div className="row align-items-center">
				<div className="col-6 col-sm-6 col-md-4 col-lg-3">
					<div className="header-logo">
						<a href=""><img src="./assets/images/logo.svg" className="img-fluid" /></a>
					</div>
				</div>
				<div className="col-6 col-sm-6 col-md-8 col-lg-9">
					<div className="block-session">
						<div className="upload-buyer">
							<span className="upload-buyer-icon">
								<img src="./assets/images/folder.svg" className="img-fluid" /></span>
							<p>uploaded Buyer Data : <b>0</b></p>
						</div>
						<div className="dropdown user-dropdown">
							<button className="btn dropdown-toggle ms-auto" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
							    <div className="dropdown-data">
								    <div className="img-user"><img src="./assets/images/avtar.png" className="img-fluid" alt="" /></div>
		                            <div className="welcome-user">
		                                <span className="welcome">welcome</span>
		                                <span className="user-name-title">John Thomsan</span>
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
										<a className="dropdown-item" href="my-profile.html">
											<img src="./assets/images/user-login.svg" className="img-fluid" />My Profile
										</a>
									</li>
	                                <li>
										<a className="dropdown-item" href="#">
											<img src="./assets/images/booksaved.svg" className="img-fluid" />My Buyers Data
										</a>
									</li>
	                                <li>
										<a className="dropdown-item" href="#"><img src="./assets/images/messages.svg" className="img-fluid" />Support
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