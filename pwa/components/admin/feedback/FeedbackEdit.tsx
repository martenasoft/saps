import {EditGuesser, InputGuesser} from "@api-platform/admin";

const FeedbackEdit = (props:{props:any}) => (
  <EditGuesser {...props}>
    <InputGuesser source={"fromEmail"} />
    <InputGuesser source={"subject"} />
    <InputGuesser source={"text"} />
    <InputGuesser source={"status"} />
    <InputGuesser source={"createdAt"} />
    <InputGuesser source={"updatedAt"} />
  </EditGuesser>
);

export default FeedbackEdit;
