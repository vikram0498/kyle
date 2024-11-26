import React, { useState, useCallback } from "react";
import { useDropzone } from "react-dropzone";

const PropertyAttachments = ({ data }) => {
    const [maxImagesWarning, setMaxImagesWarning] = useState("");

    const onDrop = useCallback(
        (acceptedFiles) => {
            const currentAttachmentsCount = data.attachments.length;

            if (currentAttachmentsCount >= 3) {
                setMaxImagesWarning("You can only upload a maximum of 3 images.");
                return;
            }

            const remainingSlots = 3 - currentAttachmentsCount;

            // Filter and limit files to the remaining slots
            const imagePreviews = acceptedFiles.slice(0, remainingSlots).map((file) =>
                Object.assign(file, {
                    preview: URL.createObjectURL(file),
                })
            );

            data.setAttachments((prev) => [...prev, ...imagePreviews]);
            setMaxImagesWarning(""); // Clear warning if upload is successful
        },
        [data.attachments, data.setAttachments]
    );

    const removeImage = (indexToRemove) => {
        data.setAttachments((prev) => prev.filter((_, index) => index !== indexToRemove));
        setMaxImagesWarning(""); // Clear warning when an image is removed
    };

    const { getRootProps, getInputProps, isDragActive } = useDropzone({
        onDrop,
        accept: "jpeg", // Allowed file types
        multiple: true, // Allow multiple files
    });

    return (
        <>
            <div className="row">
                <div className="col-12 col-lg-12">
                    <div className="form-group">
                        <label>
                            URL<span>*</span>
                        </label>
                        <input
                            type="url"
                            placeholder="Enter URL"
                            className="form-control"
                            value={data.url}
                            name="picture_link"
                            onChange={(e) => data.setUrl(e.target.value)}
                        />
                        {data.renderFieldError("picture_link")}
                    </div>
                </div>

                <div className="col-12 col-lg-12">
                    <div className="form-group">
                        <label>
                            Select Images<span>*</span> <span className="max-image">(Max 3 images allowed)</span>
                        </label>
                        <div className="multiple_files">
                            <div {...getRootProps()} className="dropzone">
                                <input {...getInputProps()} />
                                <p>{isDragActive ? "Drop files here..." : "Drag and drop or click to upload images"}</p>
                            </div>

                            {/* Warning for max image limit */}
                            {maxImagesWarning && (
                                <p className="warning-text" style={{ color: "red" }}>
                                    {maxImagesWarning}
                                </p>
                            )}

                            {/* Image previews */}
                            <div className="image-preview">
                                {data.attachments.map((file, index) => (
                                    <div key={index} className="image-container">
                                        <img src={file.preview} alt={`Preview ${index + 1}`} className="preview-image"/>
                                        <button type="button" className="remove-button" onClick={() => removeImage(index)}>✕</button>
                                    </div>
                                ))}
                            </div>
                        </div>
                        {data.renderFieldError("attachments")}
                    </div>
                </div>
            </div>
        </>
    );
};

export default PropertyAttachments;
