import React, { useState, useRef } from 'react';
import { GoogleMap, LoadScript, Circle, Autocomplete } from '@react-google-maps/api';

const mapContainerStyle = {
  width: '100%',
  height: '500px',
};

const defaultCenter = {
  lat: -33.8688, // Default: Sydney latitude
  lng: 151.2093, // Default: Sydney longitude
};

const AddAddressAndRadius = () => {
  const [center, setCenter] = useState(defaultCenter); // Circle center
  const [radius, setRadius] = useState(5000); // Circle radius in meters
  const [address, setAddress] = useState(''); // Address input value
  const autocompleteRef = useRef(null);
  const googleMapsApiKey = process.env.REACT_APP_GOOGLE_MAP_KEY;

  const handlePlaceSelected = () => {
    if (autocompleteRef.current) {
      const place = autocompleteRef.current.getPlace();
      if (place.geometry && place.geometry.location) {
        const lat = place.geometry.location.lat();
        const lng = place.geometry.location.lng();
        setCenter({ lat, lng });
        setAddress(place.formatted_address || '');
      }
    }
  };

  const handleCircleDragEnd = (event) => {
    const newLat = event.latLng.lat();
    const newLng = event.latLng.lng();
    setCenter({ lat: newLat, lng: newLng });
  };

  const handleRadiusChange = (e) => {
    setRadius(Number(e.target.value));
  };

  return (
    <section className='main-section position-relative pt-4 pb-120'>
      <LoadScript
        googleMapsApiKey={googleMapsApiKey} // API key from environment
        libraries={['places', 'geometry']} // Load Places and Geometry libraries
      >
        {/* Google Map */}
        <div className='map-section'>
          <GoogleMap
            mapContainerStyle={mapContainerStyle}
            center={center}
            zoom={12}
          >
            {/* Draw Circle */}
            <Circle
              center={center}
              radius={radius}
              options={{
                fillColor: '#0000FF33',
                strokeColor: '#0000FF',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                draggable: true, // Enable dragging
              }}
              onDragEnd={handleCircleDragEnd} // Update center on drag end
            />
          </GoogleMap>
        </div>
        
        {/* Address and Radius Input */}
        <div style={{ marginTop: '10px' }}>
          <label> <strong>Address:</strong></label>
          <Autocomplete
            onLoad={(autocomplete) => (autocompleteRef.current = autocomplete)}
            onPlaceChanged={handlePlaceSelected}
          >
            <input
              type="text"
              placeholder="Enter address"
              value={address}
              onChange={(e) => setAddress(e.target.value)}
              style={{ width: '300px', marginLeft: '10px', padding: '5px' }}
            />
          </Autocomplete>
        </div>
        
        <div style={{ marginTop: '10px' }}>
          <label><strong>Radius (meters):</strong></label>
          <input
            type="number"
            value={radius}
            onChange={(e) => setRadius(Number(e.target.value))}
            style={{ marginLeft: '10px', width: '100px' }}
          />
        </div>

        {/* Radius Slider */}
        <div style={{ marginTop: '10px' }}>
          <label><strong>Radius (meters):</strong></label>
          <input
            type="range"
            min="1000"
            max="10000"
            value={radius}
            onChange={handleRadiusChange}
            style={{ marginLeft: '10px', width: '300px' }}
          />
          <span style={{ marginLeft: '10px' }}>{radius} m</span>
        </div>
      </LoadScript>
    </section>
  );
};

export default AddAddressAndRadius;
