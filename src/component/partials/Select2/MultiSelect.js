import React, {useState} from 'react'
import Select from "react-select";
function MultiSelect({options,placeholder,name, setMultiselectOption, showCreative='', showmultiFamily=''}) {
    
      // const [selectedOptions, setSelectedOptions] = useState([]);
      const handleSelect = (e) => {
        const selectedValues = Array.isArray(e) ? e.map(x => x.value) : [];
        if(name == 'buyer_type'){
          if (selectedValues.includes(1)) {
            showCreative(true);
          } else {
            showCreative(false);
          }
          if (selectedValues.includes(3)) {
            showmultiFamily(true);
          } else {
            showmultiFamily(false);
          }
        }
        setMultiselectOption(selectedValues);
    };
    const style = {margin:'auto'};
  return (
    <>
    <Select
        name={name}
        defaultValue={[]}
        isMulti
        options={options}
        onChange={handleSelect}
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