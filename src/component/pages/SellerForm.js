import React from 'react';
import {Link , useNavigate} from "react-router-dom";
import Header from "../partials/Layouts/Header";
import Footer from "../partials/Layouts/Footer";

const SellerForm = () =>{
 return (
    <>
     <Header/>
    <section className="main-section position-relative pt-4 pb-120">
		<div className="container position-relative">
			<div className="back-block">
                <Link to="/" className="back">
					<svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M15 6H1" stroke="#0A2540" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						<path d="M5.9 11L1 6L5.9 1" stroke="#0A2540" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
					Back
				</Link>
			</div>
			<div className="card-box">
				<div className="row">
					<div className="col-12 col-lg-8">
						<div className="card-box-inner">
							<h3>Search Property Details</h3>
							<p>Fill the below form OR send link to the buyer</p>
							<form>
								<div className="card-box-blocks">
									<div className="form-group">
										<label>Property Type<span>*</span></label>
										<select id="single" data-minimum-results-for-search="Infinity">
											<option>Select Property Type</option>
											<option>Attached</option>
											<option>Apartment Buildings</option>
											<option>Commercial - Retail</option>
											<option>Condo</option>
											<option>Detached</option>
											<option>Development</option>
											<option>Land</option>
											<option>Manufactured</option>
											<option>Mobile Home</option>
											<option>Multi-Family - Commercial</option>
											<option>Multi-Family - Residential</option>
											<option>Single Family</option>
											<option>Townhouse</option>
											<option>Mobile Home Park</option>
											<option>Hotel/Motel</option>
										</select>
									</div>
									<div className="submit-btn">
										<a href="" className="btn btn-fill btn-light">Submit Now!</a>
									</div>
								</div>
							</form>
						</div>
					</div>
					<div className="col-12 col-lg-4">
						<form className="form-container">
							<div className="outer-heading text-center">
								<h3>Upload Multiple Buyer </h3>
								<p>Lorem Ipsum is simply dummy text of the printing.</p>
							</div>
							<div className="upload-single-data">
								<div className="upload-files-container">
									<div className="drag-file-area">
										<button type="button" className="upload-button">
											<img src="./assets/images/folder-big.svg" className="img-fluid" alt="" />
										</button>
										<label className="label d-block">
											<span className="browse-files">
												<input type="file" className="default-file-input"/> 
												<span className="d-block upload-file">Upload your .CSV file</span>
												<span className="browse-files-text">browse Now!</span> 
											</span> 
										</label>
									</div>
									<span className="cannot-upload-message"> <span className="error">error</span> Please select a file first <span className="cancel-alert-button">cancel</span> </span>
									<div className="file-block">
										<div className="file-info"><span className="file-name"> </span> | <span className="file-size">  </span> </div>
										<span className="remove-file-icon">.
                                        <img src="./assets/images/remove-file-icon.svg" className="img-fluid" alt="" />
											{/* <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="20" height="20" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" className=""><g><path d="M424 64h-88V48c0-26.51-21.49-48-48-48h-64c-26.51 0-48 21.49-48 48v16H88c-22.091 0-40 17.909-40 40v32c0 8.837 7.163 16 16 16h384c8.837 0 16-7.163 16-16v-32c0-22.091-17.909-40-40-40zM208 48c0-8.82 7.18-16 16-16h64c8.82 0 16 7.18 16 16v16h-96zM78.364 184a5 5 0 0 0-4.994 5.238l13.2 277.042c1.22 25.64 22.28 45.72 47.94 45.72h242.98c25.66 0 46.72-20.08 47.94-45.72l13.2-277.042a5 5 0 0 0-4.994-5.238zM320 224c0-8.84 7.16-16 16-16s16 7.16 16 16v208c0 8.84-7.16 16-16 16s-16-7.16-16-16zm-80 0c0-8.84 7.16-16 16-16s16 7.16 16 16v208c0 8.84-7.16 16-16 16s-16-7.16-16-16zm-80 0c0-8.84 7.16-16 16-16s16 7.16 16 16v208c0 8.84-7.16 16-16 16s-16-7.16-16-16z" fill="#000000" data-original="#000000" className=""></path></g></svg> */}
										</span>
										<div className="progress-bar"> </div>
									</div>
								</div>
							</div>
							<div className="submit-btn my-30">
								<a href="" className="btn btn-fill">Submit Now!</a>
							</div>
						</form>
						<div className="watch-video">
							<p>Don’t Know How to Upload</p>
							<a href="" className="title">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="#121639" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
									<path d="M10 8L16 12L10 16V8Z" stroke="#121639" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
								Watch the Video!
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
    </>
 )
}
export default SellerForm;
