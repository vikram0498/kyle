import React, {  useState, useCallback } from "react";
import {useDropzone} from 'react-dropzone';



const PropertyAttachments = () => {
    // Dropzone
    const [selectedImages, setSelectedImages] = useState([]);
    const onDrop = useCallback((acceptedFiles) => {
        const imagePreviews = acceptedFiles.map(file => 
            Object.assign(file, {
            preview: URL.createObjectURL(file)
            })
        );
        setSelectedImages(prev => [...prev, ...imagePreviews]);
    }, []);
    const removeImage = (indexToRemove) => {
        setSelectedImages(prev => prev.filter((_, index) => index !== indexToRemove));
    };
    const { getRootProps, getInputProps, isDragActive } = useDropzone({ 
        onDrop,
        accept: 'image/*',
        multiple: true
    });
    return (
        <>
        <div className="row">
            <div className="col-12 col-lg-12">
                <div className="form-group">
                <label>
                    Url<span>*</span>
                </label>
                <input type="url" placeholder="Url" className="form-control" />
                </div>
            </div>
        </div>

        <div className="row">
            <div className="col-12 col-lg-12">
                <div className="form-group">
                <label>
                    Select Images<span>*</span>
                </label>
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
                    {selectedImages.map((file, index) => (
                        <div key={index} className="image-container">
                        <img
                            src={file.preview}
                            alt={`Preview ${index}`}
                            className="preview-image"
                        />
                        <button 
                            className="remove-button"
                            onClick={() => removeImage(index)}
                        >
                            ✕
                        </button>
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