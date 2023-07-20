import React, {useContext, useEffect, useState} from "react";
import Select from "react-select";

const SingleSelect = ({options,placeholder,value,selectedOption}) => {
 return (
    <>
    <Select
    options={options}
    placeholder={placeholder}
    value={selectedOption}
    />
    </>
 )
}
export default SingleSelect;