import React, {useState} from 'react'
import Select from "react-select";
function MultiSelect({options,placeholder}) {
    
      const [selectedOptions, setSelectedOptions] = useState([]);
    //   const handleSelect = () => {
    //     console.log(selectedOptions);
    //   };
    const style = {margin:'auto'};
  return (
    <>
    <Select
        defaultValue={[]}
        isMulti
        options={options}
        onChange={(item) => setSelectedOptions(item)}
        className="select"
        isClearable={true}
        isSearchable={true}
        isDisabled={false}
        isLoading={false}
        isRtl={false}
        placeholder={placeholder}
        closeMenuOnSelect={false}
        styles={style}
      />
    </>
  )
}

export default MultiSelect