import React, {  useState, useCallback } from "react";
import {useDropzone} from 'react-dropzone';



const PropertyAttachments = ({data}) => {
    // Dropzone
    const [selectedImages, setSelectedImages] = useState([]);
    const onDrop = useCallback((acceptedFiles) => {
        const imagePreviews = acceptedFiles.map(file => 
            Object.assign(file, {
            preview: URL.createObjectURL(file)
            })
        );
        // setSelectedImages(prev => [...prev, ...imagePreviews]);
        data.setAttachments(prev => [...prev, ...imagePreviews])
    }, []);
    const removeImage = (indexToRemove) => {
        data.setAttachments(prev => prev.filter((_, index) => index !== indexToRemove));
    };
    const { getRootProps, getInputProps, isDragActive } = useDropzone({ 
        onDrop,
        accept: 'image/jpeg, image/png, image/jpg, image/svg+xml',
        multiple: true,
        name:"attachments"
    });
    console.log('selectedImages', data.attachments);
    console.log('url', data.url);

    return (
        <>
        <div className="row">
            <div className="col-12 col-lg-12">
                <div className="form-group">
                    <label>
                        Url<span>*</span>
                    </label>
                    <input type="url" placeholder="Url" className="form-control" value={data.url} name="picture_link" onChange={(e)=>{data.setUrl(e.target.value)}}/>
                </div>
                {data.renderFieldError("picture_link")}
            </div>
            <div className="col-12 col-lg-12">
                <div className="form-group">
                    <label>Select Images<span>*</span></label>
                    <div className="multiple_files">
                        <div {...getRootProps()} className="dropzone">
                            <input {...getInputProps()} />
                            {isDragActive ? (
                                <p>Drop files here or click to upload.</p>
                            ) : (
                                <p>Drop files here or click to upload.</p>
                            )}
                        </div>
                        <div className="image-preview">
                            {data.attachments.map((file, index) => (
                                <div key={index} className="image-container">
                                    <img src={file.preview} alt={`Preview ${index}`} className="preview-image"/>
                                    <button type="button" className="remove-button" onClick={() => removeImage(index)}> ✕</button>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </>
  );
};

export default PropertyAttachments;