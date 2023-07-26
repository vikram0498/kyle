import React from 'react'
import Header from "../partials/Layouts/Header";
import Footer from "../partials/Layouts/Footer";
const Development = ()=>{
 return (
    <>
    <Header/>
    <section className="main-section position-relative pt-4 pb-120">
		<div className="container position-relative">
			<div className="back-block">
				<a href="#" className="back">
					<svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M15 6H1" stroke="#0A2540" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
						<path d="M5.9 11L1 6L5.9 1" stroke="#0A2540" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
					</svg>
					Back
				</a>
			</div>
			<div className="card-box">
				<div className="row">
					<div className="col-12 col-lg-8 w-70">
						<div className="card-box-inner">
							<h3>Search Property Details</h3>
							<p>Fill the below form OR send link to the buyer</p>
							<form>
								<div className="card-box-blocks">
									<div className="row">
										<div className="col-12 col-lg-12">
											<div className="form-group">
												<label>Property Type<span>*</span></label>
												<div className="form-group">
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
											</div>
										</div>
										<div className="col-12 col-lg-12">
											<label>Address<span>*</span></label>
											<div className="form-group">
												<input type="text" name="" className="form-control" placeholder="Address/Parcel#"/>
											</div>
										</div>
										<div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
											<label>City<span>*</span></label>
											<div className="form-group">
												<select id="city" data-minimum-results-for-search="Infinity">
													<option>Choose City</option>
												</select>
											</div>
										</div>
										<div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
											<label>State<span>*</span></label>
											<div className="form-group">
												<select id="state" className="" data-minimum-results-for-search="Infinity">
													<option>Choose State</option>
												</select>
											</div>
										</div>
										<div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
											<label>Zip<span>*</span></label>
											<div className="form-group">
												<input type="text" name="" className="form-control" placeholder="enter Your Zip" value="302020"/>
											</div>
										</div>
										<div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
											<label>Price<span>*</span></label>
											<div className="form-group">
												<input type="text" name="" className="form-control" placeholder="enter Your Price" value="$20,0000"/>
											</div>
										</div>
										<div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
											<label>Lot Size Sq Ft (min)<span>*</span></label>
											<div className="form-group">
												<input type="text" name="" className="form-control" placeholder="Lot Size Sq Ft (min)" value="02"/>
											</div>
										</div>
										<div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
											<label>Lot Size Sq Ft (max)<span>*</span></label>
											<div className="form-group">
												<input type="text" name="" className="form-control" placeholder="Lot Size Sq Ft (max)" value="03"/>
											</div>
										</div>
										<div className="col-12 col-lg-6">
											<div className="form-group">
												<label>Location Flaws</label>
												<div className="form-group">
													<select id="location-flaws" data-minimum-results-for-search="Infinity">
														<option>Boarders non-residential</option>
														<option>Boarders non-residential</option>
														<option>Boarders non-residential</option>
														<option>Boarders non-residential</option>
													</select>
												</div>
											</div>
										</div>
										<div className="col-12 col-lg-6">
											<label>Parking<span>*</span></label>
											<div className="form-group">
												<select id="parking" data-minimum-results-for-search="Infinity">
													<option>Choose Parking</option>
												</select>
											</div>
										</div>
										<div className="col-12 col-lg-6">
											<label>Purchase Method<span>*</span></label>
											<div className="form-group">
												<select id="financing" data-minimum-results-for-search="Infinity">
													<option>Creative Financing</option>
												</select>
											</div>
										</div>
									</div>
									<div className="block-divide">
										<h5>Creative Financing</h5>
										<div className="row">
											<div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
												<label>Down Payment (%)</label>
												<div className="form-group">
													<input type="text" name="" className="form-control" placeholder="Down Payment (%)" value="10%"/>
												</div>
											</div>
											<div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
												<label>Down Payment ($)</label>
												<div className="form-group">
													<input type="text" name="" className="form-control" placeholder="Down Payment ($)" value="$2000"/>
												</div>
											</div>
											<div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
												<label>Interest Rate (%)</label>
												<div className="form-group">
													<input type="text" name="" className="form-control" placeholder="Interest Rate (%)" value="02"/>
												</div>
											</div>
											<div className="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
												<label>Balloon Payment</label>
												<div className="form-group">
													<div className="radio-block">
														<div className="label-container">
															<input type="radio" name="payment" checked/>
															<span>Yes</span>
														</div>
														<div className="label-container">
															<input type="radio" name="payment"/>
															<span>No</span>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div className="submit-btn mt-3">
										<a href="" className="btn btn-fill">Submit Now!</a>
									</div>
								</div>
							</form>
						</div>
					</div>
					<div className="col-12 col-lg-4 w-30">
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
										<span className="remove-file-icon">
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
									<path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="#121639" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
									<path d="M10 8L16 12L10 16V8Z" stroke="#121639" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
								</svg>
								Watch the Video!
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
    <Footer/>
    </>
 )
}
 export default Development;