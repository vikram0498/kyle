import React, {useContext, useEffect, useState} from "react";
import Select from "react-select";

const SingleSelect = ({options,placeholder,name}) => {
const [selectedOptions, setSelectedOptions] = useState([]);
 return (
    <>
      <Select
        name={name}
        defaultValue={[]}
        options={options}
        onChange={(item) => setSelectedOptions(item)}
        className="select"
        isClearable={true}
        isSearchable={true}
        isDisabled={false}
        isLoading={false}
        isRtl={false}
        placeholder={placeholder}
        closeMenuOnSelect={true}
      />
    </>
 )
}
export default SingleSelect;