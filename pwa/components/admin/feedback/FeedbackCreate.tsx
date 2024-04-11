import {CreateGuesser, InputGuesser} from "@api-platform/admin";
const FeedbackCreate = (props:{props:any}) => (
  <CreateGuesser {...props}>
    <InputGuesser source={"fromEmail"} />
    <InputGuesser source={"subject"} />
    <InputGuesser source={"text"} />
    <InputGuesser source={"status"} />
    <InputGuesser source={"createdAt"} />
    <InputGuesser source={"updatedAt"} />
  </CreateGuesser>
);

export default FeedbackCreate;
