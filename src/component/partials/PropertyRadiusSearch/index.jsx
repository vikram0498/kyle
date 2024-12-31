import React, { useState, useRef, useEffect } from 'react';
import { GoogleMap, LoadScript, Circle, Marker, Autocomplete, InfoWindow } from '@react-google-maps/api';

const mapContainerStyle = {
  width: '100%',
  height: '500px',
};

const AddAddressAndRadius = () => {
  const [center, setCenter] = useState(null); // Circle center
  const [radius, setRadius] = useState(5000); // Circle radius in meters
  const [address, setAddress] = useState(''); // Address input value
  const [showInfoWindow, setShowInfoWindow] = useState(false); // Show or hide the InfoWindow
  const autocompleteRef = useRef(null);
  const googleMapsApiKey = process.env.REACT_APP_GOOGLE_MAP_KEY;

  // Fetch the user's current location and reverse geocode the address
  useEffect(() => {
    const fetchCurrentLocation = async () => {
      navigator.geolocation.getCurrentPosition(
        async (position) => {
          const { latitude, longitude } = position.coords;
          setCenter({ lat: latitude, lng: longitude });

          // Reverse geocode to get address
          try {
            const response = await fetch(
              `https://maps.googleapis.com/maps/api/geocode/json?latlng=${latitude},${longitude}&key=${googleMapsApiKey}`
            );
            const data = await response.json();
            if (data.results && data.results[0]) {
              setAddress(data.results[0].formatted_address);
            }
          } catch (error) {
            console.error('Error fetching address:', error);
          }
        },
        (error) => {
          console.error('Error fetching geolocation:', error);
          // Fallback to Sydney if geolocation fails
          setCenter({
            lat: -33.8688,
            lng: 151.2093,
          });
          setAddress('Sydney, Australia');
        }
      );
    };

    fetchCurrentLocation();
  }, [googleMapsApiKey]);


  // Fetch address using Geocoding API
  const fetchAddress = (location) => {
    const geocoder = new window.google.maps.Geocoder();
    geocoder.geocode({ location }, (results, status) => {
      if (status === 'OK' && results[0]) {
        setAddress(results[0].formatted_address);
      } else {
        console.error('Geocode error:', status);
        setAddress('No address available');
      }
    });
  };

  const handlePlaceSelected = () => {
    if (autocompleteRef.current) {
      const place = autocompleteRef.current.getPlace();
      if (place.geometry && place.geometry.location) {
        const lat = place.geometry.location.lat();
        const lng = place.geometry.location.lng();
        const location = { lat, lng };
        setCenter(location);
        setAddress(place.formatted_address || 'No address available');
      }
    }
  };

  const handleCircleDragEnd = (event) => {
    const newLat = event.latLng.lat();
    const newLng = event.latLng.lng();
    const newLocation = { lat: newLat, lng: newLng };
    setCenter(newLocation);
    fetchAddress(newLocation); // Update address on drag end
    setShowInfoWindow(false); // Close InfoWindow
  };

  const handleRadiusChange = (e) => {
    setRadius(Number(e.target.value));
  };

  const handleMarkerClick = () => {
    setShowInfoWindow(true); // Show InfoWindow when marker is clicked
  };

  const handleInfoWindowClose = () => {
    setShowInfoWindow(false); // Hide InfoWindow
  };

  if (!center) {
    // Show a loading message or spinner while geolocation is being fetched
    return <div>Loading map...</div>;
  }

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

            {/* Add Marker */}
            <Marker position={center} onClick={handleMarkerClick} />

            {/* InfoWindow */}
            {showInfoWindow && (
              <InfoWindow position={center} onCloseClick={handleInfoWindowClose}>
                <div>
                  <p><strong>Address:</strong> {address || 'No address available'}</p>
                </div>
              </InfoWindow>
            )}
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
