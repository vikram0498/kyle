import React from "react";
const DarkMode = () => {
  const handleChange = (e) => {
    if (e.target.checked) {
      localStorage.setItem("darkMode", true);
    } else {
      localStorage.removeItem("darkMode");
    }
    document.body.classList.toggle("dark");
  };
  const myValue = localStorage.getItem("darkMode");

  return (
    <>
      <div className="mode_type">
        <input
          type="checkbox"
          className="checkbox"
          id="checkbox"
          onChange={handleChange}
          defaultChecked={myValue === "true" ? "checked" : ""}
        />
        <label htmlFor="checkbox" className="">
            <span className="lightmode txtmode">Light Mode</span>
            <span className="darkmode txtmode">Dark Mode</span>
        </label>
      </div>
    </>
  );
};

export default DarkMode;
