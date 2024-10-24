import React, { useState } from "react";
import usePlacesAutocomplete, { getGeocode, getLatLng } from "use-places-autocomplete";
import { useLoadScript } from "@react-google-maps/api";

const libraries = ["places"];

const LocationAutoSuggest = () => {
  const { isLoaded } = useLoadScript({
    googleMapsApiKey: process.env.REACT_APP_GOOGLE_MAP_KEY, // Replace with your API key
    libraries,
  });

  if (!isLoaded) return <div>Loading...</div>;
  return <AutocompleteInput />;
};

const AutocompleteInput = () => {
  const [city, setCity] = useState("");
  const [state, setState] = useState("");
  const [pinCode, setPinCode] = useState("");

  const {
    ready,
    value,
    suggestions: { status, data },
    setValue,
    clearSuggestions,
  } = usePlacesAutocomplete({
    requestOptions: {
      types: ["address"], // Broaden search to include full addresses
      // Uncomment this line to restrict to India
      // componentRestrictions: { country: "IN" },
    },
    debounce: 300,
  });

  const handleSelect = async (address) => {
    setValue(address, false);
    clearSuggestions();

    try {
      const results = await getGeocode({ address });
      const { lat, lng } = await getLatLng(results[0]);
      console.log("Coordinates:", { lat, lng });

      const addressComponents = results[0].address_components;

      const cityComponent = addressComponents.find((component) =>
        component.types.includes("locality") || component.types.includes("administrative_area_level_2")
      );
      const stateComponent = addressComponents.find((component) =>
        component.types.includes("administrative_area_level_1")
      );
      const pinComponent = addressComponents.find((component) =>
        component.types.includes("postal_code")
      );

      setCity(cityComponent?.long_name || "");
      setState(stateComponent?.long_name || "");
      setPinCode(pinComponent?.long_name || "");
    } catch (error) {
      console.error("Error getting geocode data:", error);
    }
  };

  return (
    <section className="main-section position-relative pt-4 pb-120">
      <div className="container position-relative">
        <div className="back-block">
          <div className="row">
            <label>Location:</label>
            <input
              type="text"
              value={value}
              onChange={(e) => setValue(e.target.value)}
              disabled={!ready}
              placeholder="Type your location"
              className="form-control"
            />
            {/* Render suggestions */}
            {status === "OK" && (
              <ul>
                {data.map(({ place_id, description }) => (
                  <li key={place_id} onClick={() => handleSelect(description)}>
                    {description}
                  </li>
                ))}
              </ul>
            )}

            <div className="form-group">
              <label>City:</label>
              <input type="text" value={city} readOnly className="form-control" />
            </div>

            <div className="form-group">
              <label>State:</label>
              <input type="text" value={state} readOnly className="form-control" />
            </div>

            <div className="form-group">
              <label>Pin Code:</label>
              <input type="text" value={pinCode} readOnly className="form-control" />
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};

export default LocationAutoSuggest;
