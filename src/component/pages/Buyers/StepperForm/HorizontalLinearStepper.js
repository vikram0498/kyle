import * as React from "react";
import Box from "@mui/material/Box";
import Stepper from "@mui/material/Stepper";
import Step from "@mui/material/Step";
import StepLabel from "@mui/material/StepLabel";
import Button from "@mui/material/Button";
import Typography from "@mui/material/Typography";
import PhoneVerification from "./PhoneVerification";
import DriverLicense from "./DriverLicense";
import LLCVerification from "./LLCVerification";
import ProofOfFund from "./ProofOfFund";
import ApplicationProcess from "./ApplicationProcess";
import SuccessfullySubmiitedPage from "../../SuccessfullySubmiitedPage";
import { useForm, Controller } from "react-hook-form";
import { useAuth } from "../../../../hooks/useAuth";
import { useFormError } from "../../../../hooks/useFormError";
import axios from "axios";
import { toast } from "react-toastify";

const steps = [
  "Phone Verification",
  "ID/Driver’s License",
  "Proof of Funds",
  "LLC Verification",
  "Application Process",
];

const HorizontalLinearStepper = () => {
  const [miniLoader, setMiniLoader] = React.useState(false);
  const [activeStep, setActiveStep] = React.useState(0);
  const [skipped, setSkipped] = React.useState(new Set());
  const [isOtpSent, setIsOtpSent] = React.useState(false);
  const [isOtpVerify, setIsOtpVerify] = React.useState(false);
  const [phoneNumber, setphoneNumber] = React.useState("");
  const { getTokenData, setLogout } = useAuth();
  const { setErrors, renderFieldError } = useFormError();

  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm();

  const isStepOptional = (step) => {
    return step === 10;
  };

  const isStepSkipped = (step) => {
    return skipped.has(step);
  };

  const handleNext = () => {
    let newSkipped = skipped;
    if (isStepSkipped(activeStep)) {
      newSkipped = new Set(newSkipped.values());
      newSkipped.delete(activeStep);
    }

    setActiveStep((prevActiveStep) => prevActiveStep + 1);
    setSkipped(newSkipped);
  };

  const handleBack = () => {
    setActiveStep((prevActiveStep) => prevActiveStep - 1);
  };

  const handleSkip = () => {
    if (!isStepOptional(activeStep)) {
      // You probably want to guard against something like this,
      // it should never occur unless someone's actively trying to break something.
      throw new Error("You can't skip a step that isn't optional.");
    }

    setActiveStep((prevActiveStep) => prevActiveStep + 1);
    setSkipped((prevSkipped) => {
      const newSkipped = new Set(prevSkipped.values());
      newSkipped.add(activeStep);
      return newSkipped;
    });
  };

  const handleReset = () => {
    setActiveStep(0);
  };
  const stepperFormSubmit = async (data, e) => {
    e.preventDefault();
    try {
      var data = new FormData(e.target);
      let formObject = Object.fromEntries(data.entries());
      formObject.step = parseInt(activeStep) + 1;
      await stepperForm(formObject);
    } catch (error) {
      if (error.response) {
        if (error.response.status === 401) {
          setLogout();
        }
        if (error.response.validation_errors) {
          setErrors(error.response.data.validation_errors);
        }
        if (error.response.data.errors) {
          setErrors(error.response.data.errors);
        }
        if (error.response.data.error) {
          toast.error(error.response.data.error, {
            position: toast.POSITION.TOP_RIGHT,
          });
        }
      }
    }
    //handleNext();
  };

  // send otp on phone number
  const sendOtp = async () => {
    try {
      setMiniLoader(true);
      setIsOtpSent(false);
      const apiUrl = process.env.REACT_APP_API_URL;
      let headers = {
        Accept: "application/json",
        Authorization: "Bearer " + getTokenData().access_token,
        "auth-token": getTokenData().access_token,
      };
      let response = await axios.post(
        apiUrl + "send-sms",
        { phone: phoneNumber },
        {
          headers: headers,
        }
      );
      if (response.data.status) {
        setMiniLoader(false);
        setIsOtpSent(true);
        toast.success(response.data.message, {
          position: toast.POSITION.TOP_RIGHT,
        });
      }
    } catch (error) {
      setMiniLoader(false);
      if (error.response) {
        if (error.response.status === 401) {
          setLogout();
        }
        if (error.response.validation_errors) {
          setErrors(error.response.data.validation_errors);
        }
        if (error.response.data.errors) {
          setErrors(error.response.data.errors);
        }
        if (error.response.error) {
          toast.error(error.response.error, {
            position: toast.POSITION.TOP_RIGHT,
          });
        }
      }
    }
  };
  const stepperForm = async (formObject) => {
    const apiUrl = process.env.REACT_APP_API_URL;
    let headers = {
      Accept: "application/json",
      Authorization: "Bearer " + getTokenData().access_token,
      "auth-token": getTokenData().access_token,
      "Content-Type": "multipart/form-data",
    };
    let response = await axios.post(
      apiUrl + "buyer-profile-verification",
      formObject,
      {
        headers: headers,
      }
    );

    if (response.data.status) {
      setIsOtpVerify(true);
      setActiveStep(parseInt(response.data.current_step));
      toast.success(response.data.message, {
        position: toast.POSITION.TOP_RIGHT,
      });
    }
  };

  const getLastFormStep = async () => {
    try {
      const apiUrl = process.env.REACT_APP_API_URL;
      let headers = {
        Accept: "application/json",
        Authorization: "Bearer " + getTokenData().access_token,
        "auth-token": getTokenData().access_token,
      };
      let response = await axios.get(apiUrl + "last-form-step", {
        headers: headers,
      });
      setActiveStep(response.data.lastStepForm);
    } catch (error) {
      if (error.response) {
        if (error.response.status === 401) {
          setLogout();
        }
        if (error.response.validation_errors) {
          setErrors(error.response.data.validation_errors);
        }
        if (error.response.data.errors) {
          setErrors(error.response.data.errors);
        }
        if (error.response.error) {
          toast.error(error.response.error, {
            position: toast.POSITION.TOP_RIGHT,
          });
        }
      }
    }
  };
  React.useEffect(() => {
    getLastFormStep();
  }, []);
  return (
    <Box sx={{ width: "100%" }}>
      <Stepper activeStep={activeStep}>
        {steps.map((label, index) => {
          const stepProps = {};
          const labelProps = {};
          if (isStepOptional(index)) {
            labelProps.optional = (
              <Typography variant="caption">Optional</Typography>
            );
          }
          if (isStepSkipped(index)) {
            stepProps.completed = false;
          }
          return (
            <Step key={label} {...stepProps}>
              <StepLabel {...labelProps}>{label}</StepLabel>
            </Step>
          );
        })}
      </Stepper>
      {activeStep === steps.length ? (
        <React.Fragment>
          <Typography sx={{ mt: 2, mb: 1 }}>
            <SuccessfullySubmiitedPage />
            {/* All steps completed - you&apos;re finished */}
          </Typography>
          {/* <Box sx={{ display: "flex", flexDirection: "row", pt: 2 }}>
            <Box sx={{ flex: "1 1 auto" }} />
            <Button onClick={handleReset}>Reset</Button>
          </Box> */}
        </React.Fragment>
      ) : (
        <React.Fragment>
          {/* <Typography sx={{ mt: 2, mb: 1 }}>Step {activeStep + 1}</Typography> */}
          <form method="post" onSubmit={handleSubmit(stepperFormSubmit)}>
            <div className="profile-varification">
              {activeStep === 0 && (
                <PhoneVerification
                  register={register}
                  errors={errors}
                  renderFieldError={renderFieldError}
                  isOtpSent={isOtpSent}
                  sendOtp={sendOtp}
                  setphoneNumber={setphoneNumber}
                  phoneNumber={phoneNumber}
                  handleSubmit={handleSubmit}
                  isOtpVerify={isOtpVerify}
                  miniLoader={miniLoader}
                />
              )}
              {activeStep === 1 && (
                <DriverLicense register={register} errors={errors} />
              )}
              {activeStep === 2 && (
                <ProofOfFund register={register} errors={errors} />
              )}
              {activeStep === 3 && (
                <LLCVerification register={register} errors={errors} />
              )}
              {activeStep === 4 && <ApplicationProcess />}
            </div>
            <Box sx={{ display: "flex", flexDirection: "row", pt: 2 }}>
              {/* <Button
                className="back-btn-stepper"
                color="inherit"
                disabled={activeStep === 0}
                onClick={handleBack}
                sx={{ mr: 1 }}
              >
                Back
              </Button> */}
              <Box sx={{ flex: "1 1 auto" }} />
              {/* {isStepOptional(activeStep) && (
                <Button color="inherit" onClick={handleSkip} sx={{ mr: 1 }}>
                  Skip
                </Button>
              )} */}

              <Button
                className="next-btn-stepper"
                type="button"
                onClick={handleSubmit(handleNext)}
              >
                {activeStep === steps.length - 1 ? "Finish" : "Next"}
              </Button>
            </Box>
          </form>
        </React.Fragment>
      )}
    </Box>
  );
};
export default HorizontalLinearStepper;
