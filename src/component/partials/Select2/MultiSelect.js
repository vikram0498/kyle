import React, {useState} from 'react'
import Select from "react-select";
function MultiSelect({options,placeholder,name,setMultiSelectedOptions}) {
    //   const handleSelect = () => {
    //     console.log(selectedOptions);
    //   };
    const style = {margin:'auto'};
  return (
    <>
    <Select
        name={name}
        defaultValue={[]}
        isMulti
        options={options}
        onChange={(item) => setMultiSelectedOptions(item)}
        className="multi-select"
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